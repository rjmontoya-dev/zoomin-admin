<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { router } from '@inertiajs/vue3';
import { Eye } from 'lucide-vue-next';

// Define the Page type for better type safety
interface Page {
    id: number;
    slug: string;
    label: string;
    show_metadata: number;
    content: string | null;
    title: string | null;
    description: string | null;
    keywords: string | null;
    robots_follow: string | null;
    robots_index: string | null;
    og_image_path: string | null;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    items: Page[];
}>();


// Format the date to match the design in the image
const formatDate = (dateString: string) => {
    if (!dateString) return { formatted: '', time: '' };

      const date = DateTime.fromISO(dateString);
    return {
        formatted: date.toFormat('MMM dd, yyyy'),
        time: date.toFormat('hh:mm a'),
    };
};

// Get display name for the content column
const getDisplayName = (page: Page) => {
    // Extract the label without any prefixes (Website:, Web App:, General:)
    const label = page.label;

    if (label.includes(':')) {
        return label.split(':')[1].trim();
    }

    return label;
};

// Method to handle view button click
const handleView = (pageId: number) => {
    router.visit(route('admin.cms.edit', pageId));
};

const pageTitle: string = 'Pages';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pages',
        href: '/pages',
    },
];
</script>

<template>
     <AppLayout>
        <div class="p-8">
            <header class="mb-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <!-- Title and Description -->
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Content Management</h1>
                        <p class="text-md mt-1 text-gray-600 dark:text-gray-400">Manages all contents in the website</p>
                    </div>
                </div>
            </header>

            <div class="flex flex-1 flex-col gap-4 border border-gray-100 dark:border-zinc-800 rounded-lg overflow-hidden">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[60%]">Content</TableHead>
                            <TableHead class="w-[30%]">Last Updated</TableHead>
                            <TableHead class="w-[10%]"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="page in items" :key="page.id" class="border-b">
                            <TableCell>{{ getDisplayName(page) }}</TableCell>
                            <TableCell>
                                <div class="flex flex-col">
                                    <span>{{ formatDate(page.updated_at).formatted }}</span>
                                    <span class="text-sm text-muted-foreground">{{ formatDate(page.updated_at).time }}</span>
                                </div>
                            </TableCell>
                            <TableCell class="text-right">
                                <Button variant="ghost" size="icon" @click="handleView(page.id)">
                                    <Eye class="h-5 w-5" />
                                </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
