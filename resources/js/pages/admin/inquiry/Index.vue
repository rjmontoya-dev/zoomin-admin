<script setup lang="ts">
import AppMainContent from '@/components/AppMainContent.vue';
import BaseTable from '@/components/base/BaseTable.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Eye, Trash2, ArchiveRestore } from 'lucide-vue-next';
import { ref, defineProps, computed, watch } from 'vue';
import BaseNavTabs from '@/components/base/BaseNavTabs.vue';
import { router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { Toaster, toast } from 'vue-sonner'
import 'vue-sonner/style.css'
import LoadingPage from '@/components/shared/LoadingPage.vue';
import { useDateFormat } from '@/composables/useDateFormat';
import { useClipboardItems } from '@vueuse/core';

interface Inquiry {
    id: number;
    name: string;
    email: string;
    company: string;
    service: string;
    budget: string;
    message: string;
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
  data: Object,
  tabCounts: TabCounts,
  meta: Meta,
  additionalProps: Object,
}>()

const indexRoute = ref(route('admin.inquiry.index'));

const counts = computed(() => props.tabCounts);

const TABS = computed(() => [
  { label: 'All', value: 'all', tabCount: counts.value.all },
  { label: 'Archived', value: 'archived', tabCount: counts.value.archived },
]);

const COLUMNS = [
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'company', label: 'Company' },
    { key: 'service', label: 'Service' },
    { key: 'budget', label: 'Budget' },
    { key: 'created_at', label: 'Date Created' },
    { key: 'actions', label: 'Actions' },
];

const filters = {
    service: null,
    budget: null,
};

const activeTab = ref(usePage().props.tab || 'all');

const breadcrumbs = [
    {
        title: 'Inquiries',
        href: route('admin.inquiry.index'),
    },
];

const isLoading = ref(false);

const archive = (id: number) => {
    isLoading.value = true;
    router.delete(route('admin.inquiry.archive', id), {
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
    router.post(route('admin.inquiry.restore', id), {}, {
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
    <Toaster :expand="true" richColors />
    <LoadingPage :isLoading="isLoading" />
    <AppLayout>
        <AppMainContent :breadcrumbs="breadcrumbs" title="Inquiries">
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
                        <div class="space-y-4">
                            <!-- Service filter -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-900 dark:text-white">Service</label>
                                <select v-model="filters.service" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                                    <option value="">All Services</option>
                                    <option value="web-development">Web Development</option>
                                    <option value="mobile-app">Mobile App</option>
                                    <option value="consulting">Consulting</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <!-- Budget filter -->
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-gray-900 dark:text-white">Budget</label>
                                <select v-model="filters.budget" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                                    <option value="">All Budgets</option>
                                    <option value="under-5k">Under $5,000</option>
                                    <option value="5k-10k">$5,000 - $10,000</option>
                                    <option value="10k-25k">$10,000 - $25,000</option>
                                    <option value="over-25k">Over $25,000</option>
                                </select>
                            </div>
                        </div>
                    </template>

                    <!-- Custom cell renderers -->
                    <template #cell-name="{ item }">
                        <div class="font-medium text-gray-900">
                            {{ item.name }}
                        </div>
                    </template>

                    <template #cell-email="{ item }">
                        <a :href="`mailto:${item.email}`" class="text-blue-600 hover:text-blue-800">
                            {{ item.email }}
                        </a>
                    </template>

                    <template #cell-company="{ item }">
                        <span class="text-gray-700">
                            {{ item.company }}
                        </span>
                    </template>

                    <template #cell-service="{ item }">
                        <Badge v-if="item.service" variant="outline" class="bg-blue-50 text-blue-700 border-blue-200">
                            {{ item.service }}
                        </Badge>
                    </template>

                    <template #cell-budget="{ item }">
                        <Badge v-if="item.service" variant="outline" class="bg-green-50 text-green-700 border-green-200">
                            {{ item.budget }}
                        </Badge>
                    </template>

                    <template #cell-created_at="{ item }">
                        <span class="text-gray-600">
                            {{ formatDate(item.created_at).formatted }}
                        </span>
                    </template>

                    <template #cell-actions="{ item }">
                        <div class="flex space-x-2">
                            <template v-if="activeTab !== 'archived'">
                                <button @click="router.get(route('admin.inquiry.show', item.id))" class="p-2 text-blue-600 hover:text-blue-800" title="View">
                                    <Eye class="h-4 w-4" />
                                </button>

                                <button @click="archive(item.id)" class="p-2 text-red-600 hover:text-red-800" title="Archive">
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
