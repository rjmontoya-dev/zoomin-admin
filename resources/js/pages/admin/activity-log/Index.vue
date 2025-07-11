<script setup lang="ts">
import AppMainContent from '@/components/AppMainContent.vue';
import BaseTable from '@/components/base/BaseTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, defineProps } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useDateFormat } from '@/composables/useDateFormat';

interface ActivityLog {
    id: number;
    subject_name: string;
    event: string;
    description: string;
    causer_name: string;
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

const { formatDate } = useDateFormat();

const props = defineProps<{
  data: {
    data: ActivityLog[];
  },
  // Using any for meta to bypass type checking issues with BaseTable component
  meta: any,
}>()

const indexRoute = ref(route('admin.activity-log.index'));

const COLUMNS = [
    { key: 'id', label: 'ID' },
    { key: 'subject_name', label: 'Subject Name' },
    { key: 'event', label: 'Event' },
    { key: 'description', label: 'Description' },
    { key: 'causer_name', label: 'Causer' },
    { key: 'created_at', label: 'Date Created' },
];

const breadcrumbs = [
    {
        title: 'Activity Log',
        href: route('admin.activity-log.index'),
    },
];

</script>
<template>
    <AppLayout>
        <AppMainContent :breadcrumbs="breadcrumbs" title="Activity Log">
            <div class=" h-full w-full  p-8 overflow-y-auto">
                <BaseTable
                    :route="indexRoute"
                    :data="data.data"
                    :columns="COLUMNS"
                    :initial-sort="{ value: 'created_at', direction: 'desc' }"
                    :initial-per-page="meta.per_page"
                    :meta="meta"
                >
                    <!-- Custom cell renderers -->
                    <template #cell-created_at="{ item }">
                        <span
                            class="inline-flex items-center justify-center rounded-full "
                        >
                            {{ formatDate(item.created_at).formatted }}
                        </span>
                    </template>
                </BaseTable>
            </div>
        </AppMainContent>
    </AppLayout>
</template>
