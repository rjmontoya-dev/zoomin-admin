<script setup lang="ts">
import AppMainContent from '@/components/AppMainContent.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';

interface Role {
    id: number;
    name: string;
}

const props = defineProps<{
  roles: Role[];
}>()

const breadcrumbs = [
    {
        title: 'Roles & Permissions',
        href: route('admin.roles-permissions.index'),
    },
];

</script>
<template>
    <AppLayout>
        <AppMainContent :breadcrumbs="breadcrumbs" title="Roles & Permissions">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                <Link v-for="role in props.roles" :key="role.id" :href="route('admin.roles-permissions.show', role.id)">
                    <Card class="hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <CardHeader>
                            <CardTitle>{{ role.name }}</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p class="text-sm text-gray-500">
                                View permissions and members assigned to this role.
                            </p>
                        </CardContent>
                    </Card>
                </Link>
            </div>
        </AppMainContent>
    </AppLayout>
</template>
