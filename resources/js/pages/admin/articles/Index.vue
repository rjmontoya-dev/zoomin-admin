<script setup lang="ts">
import AppMainContent from '@/components/AppMainContent.vue';
import BaseTable from '@/components/base/BaseTable.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Edit, PlusCircle, Trash2, ArchiveRestore } from 'lucide-vue-next';
import { ref, defineProps, computed, watch } from 'vue';
import BaseNavTabs from '@/components/base/BaseNavTabs.vue';
import {  router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { Toaster, toast } from 'vue-sonner'
import 'vue-sonner/style.css'
import LoadingPage from '@/components/shared/LoadingPage.vue';
import { useDateFormat } from '@/composables/useDateFormat';

interface Article {
    id: number;
    title: string;
    author: string;
    status: string;
    created_at: string;
}

interface Meta {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    has_more_pages: boolean;
}

interface TabCounts {
    all: number;
    archived: number;
}

interface PageProps {
    [key: string]: any; // Add index signature
    flash: {
        success?: string;
        error?: string;
    };
}

const page = usePage<PageProps>();
const { formatDate } = useDateFormat();

watch(() => page.props.flash.success, (newValue: string | undefined) => {
  if (newValue) {
    toast.success(newValue);
  }
});

interface Tab {
    label: string;
    value: string;
    tabCount?: number;
}

const props = defineProps<{
  data: {
    data: Article[];
  },
  tabCounts: TabCounts,
  meta: Meta,
}>()

const indexRoute = ref(route('admin.articles.index'));

const counts = computed(() => props.tabCounts);

const TABS = computed(() => [
  { label: 'All', value: 'all', tabCount: counts.value.all },
  { label: 'Archived', value: 'archived', tabCount: counts.value.archived },
]);

const COLUMNS = [
    { key: 'title', label: 'Title' },
    { key: 'author', label: 'Author' },
    { key: 'status', label: 'Status' },
    { key: 'created_at', label: 'Date Created' },
    { key: 'actions', label: 'Actions' },
];

const filters = {
    status: null,
};

const activeTab = ref(usePage().props.tab || 'all');

const breadcrumbs = [
    {
        title: 'Articles',
        href: route('admin.articles.index'),
    },
];


const isLoading = ref(false);
const create = () => {
    isLoading.value = true;
    router.get(route('admin.articles.create'));
}

const archive = (id: number) => {
    isLoading.value = true;
    router.delete(route('admin.articles.archive', id), {
        preserveScroll: true,
        onSuccess: () => {
            isLoading.value = false;
        },
        onError: () => {
            isLoading.value = false;
        },
    });
};

const restore = (id: number) => {
    isLoading.value = true;
    router.post(route('admin.articles.restore', id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            isLoading.value = false;
        },
        onError: () => {
            isLoading.value = false;
        },
    });
};

</script>
<template>
    <Toaster :expand="true"  />
    <LoadingPage :isLoading="isLoading" />
    <AppLayout>
        <AppMainContent :breadcrumbs="breadcrumbs" title="Articles">
            <template #button>
                <div class="flex space-x-2 self-start">
                    <Button variant="outline" @click="create()">
                        <PlusCircle class="mr-2 h-4 w-4" />
                        Create
                    </Button>
                </div>
            </template>
            <BaseNavTabs v-if="TABS && TABS.length > 0" :tabs="TABS" v-model="activeTab" />
            <div class=" h-full w-full  p-8 overflow-y-auto">
                <BaseTable
                    :route="indexRoute"
                    :data="data"
                    :columns="COLUMNS"
                    :tabs="TABS"
                    :active-tab="activeTab"
                    :initial-sort="{ value: 'created_at', direction: 'desc' }"
                    :initial-per-page="meta.per_page"
                    :filters="filters"
                    :meta="meta"
                >
                    <!-- Custom filter content in drawer -->
                    <template #drawer-filters="{ filters }">
                        <!-- Add any additional custom filters here -->
                        <div class="space-y-4">
                            <!-- Example: Status filter -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <select v-model="filters.status" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                                    <option value="">All Statuses</option>
                                    <option value="publish">Published</option>
                                    <option value="draft">Draft</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>
                    </template>

                    <!-- Custom cell renderers -->
                    <template #cell-candidates="{ item }">
                        <span
                            class="inline-flex items-center justify-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800"
                        >
                            {{ item.candidates }}
                        </span>
                    </template>

                    <template #cell-status="{ item }">
                        <Badge
                            :class="{
                                'border-green-500 bg-green-50 text-green-700': item.status === 'Active',
                                'border-yellow-500 bg-yellow-50 text-yellow-700': item.status === 'On Hold',
                                'border-gray-500 bg-gray-50 text-gray-700': item.status === 'Closed',
                            }"
                            variant="outline"
                        >
                            {{ item.status }}
                        </Badge>
                    </template>
                    <template #cell-created_at="{ item }">
                        <span
                            class="inline-flex items-center justify-center rounded-full "
                        >
                            {{ formatDate(item.created_at).formatted }}
                        </span>
                    </template>
                    <template #cell-actions="{ item }">
                        <div class="flex space-x-2">
                            <template v-if="activeTab !== 'archived'">
                                <button @click="router.get(route('admin.articles.edit', item.id))" class="auto-focus p-2 text-gray-600 hover:text-gray-800" title="Edit">
                                    <Edit class="h-4 w-4" />
                                </button>

                                <button @click="archive(item.id)" class="p-2 text-red-600 hover:text-red-800" title="Delete">
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </template>
                            <template v-else>
                                <button @click="restore(item.id)" class="p-2 text-green-600 hover:text-green-800" title="Restore">
                                    <ArchiveRestore class="h-4 w-4" />
                                </button>
                            </template>
                        </div>
                    </template>
                </BaseTable>
            </div>
        </AppMainContent>
    </AppLayout>
</template>
