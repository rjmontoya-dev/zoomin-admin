<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import AppMainContent from '@/components/AppMainContent.vue';
import { router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { MoreHorizontal, PlusCircle, Edit, Trash2, ArchiveRestore } from 'lucide-vue-next'
import BaseNavTabs from '@/components/base/BaseNavTabs.vue'
import { Toaster, toast } from 'vue-sonner'
import 'vue-sonner/style.css'
import LoadingPage from '@/components/shared/LoadingPage.vue'
import BaseTable from '@/components/base/BaseTable.vue'
import { Badge } from '@/components/ui/badge';

interface Status {
    label: string;
    value: number;
}

const props = defineProps<{
    data: Object,
    tabCounts: Record<string, number>;
    meta: Object;
    additionalProps: {
        status: Status[];
    };
    filters: Record<string, any>;
}>()

const page = usePage()
const activeTab = ref<string>(page.props.tab as string || 'all')
const isLoading = ref<boolean>(false)

const breadcrumbs = [
    {
        title: 'Projects',
        href: route('admin.projects.index'),
    },
];

const COLUMNS = [
    { key: 'project_code', label: 'Code' },
    { key: 'client_name', label: 'Client' },
    { key: 'agent', label: 'Agent' },
    { key: 'amount', label: 'Amount' },
    { key: 'status', label: 'Status' },
    { key: 'contact_number', label: 'Contact #' },
    { key: 'start_date', label: 'Start Date' },
    { key: 'created_at', label: 'Date Created' },
    { key: 'actions', label: 'Actions' },
]

watch(() => (page.props as any).flash.success, (newValue: string) => {
  if (newValue) {
    toast.success(newValue)
  }
})

const TABS = computed(() => {
  const tabs = [{ label: 'All', value: 'all', tabCount: props.tabCounts.all }]
  props.additionalProps.status.forEach((status: Status) => {
    tabs.push({
      label: status.label,
      value: status.id,
      tabCount: props.tabCounts[status.value] || 0,
    })
  })
  tabs.push({ label: 'Archived', value: 'archived', tabCount: props.tabCounts.archived })
  return tabs
})

const create = () => {
  isLoading.value = true
  router.get(route('admin.projects.create'))
}

const archive = (id: number) => {
  isLoading.value = true
  router.delete(route('admin.projects.destroy', id), {
    preserveScroll: true,
    onSuccess: () => {
      isLoading.value = false
    },
    onError: () => {
      isLoading.value = false
    },
  })
}

const restore = (id: number) => {
  isLoading.value = true
  router.post(route('admin.projects.restore', id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      isLoading.value = false
    },
    onError: () => {
      isLoading.value = false
    },
  })
}
</script>

<template>
  <Toaster :expand="true" />
  <LoadingPage :is-loading="isLoading" />
  <AppLayout title="Projects">
    <AppMainContent :breadcrumbs="breadcrumbs" title="Project Management">
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
            :data="data"
            :columns="COLUMNS"
            :meta="meta"
            :tabs="TABS"
            :filters="filters"
            :route="route('admin.projects.index')"
            :active-tab="activeTab"
        >
            <template #cell-agent="{ item }">
                {{ item.agent }}
            </template>
            <template #cell-status="{ item }">
                <Badge
                    :class="`border-${item.status.color}-500 bg-${item.status.color}-50 text-${item.status.color}-700`"
                    variant="outline"
                    >
                    {{ item.status.title }}
                </Badge>
            </template>
            <template #cell-business_type="{ item }">
                {{ item.business_type.title }}
            </template>
            <template #cell-actions="{ item }">
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="ghost" class="h-8 w-8 p-0">
                            <span class="sr-only">Open menu</span>
                            <MoreHorizontal class="h-4 w-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <template v-if="activeTab !== 'archived'">
                            <DropdownMenuItem @click="router.get(route('admin.projects.edit', item.id))">
                                <Edit class="mr-2 h-4 w-4" />
                                Edit
                            </DropdownMenuItem>
                            <DropdownMenuItem @click="archive(item.id)">
                                <Trash2 class="mr-2 h-4 w-4" />
                                Archive
                            </DropdownMenuItem>
                        </template>
                        <template v-else>
                            <DropdownMenuItem @click="restore(item.id)">
                                <ArchiveRestore class="mr-2 h-4 w-4" />
                                Restore
                            </DropdownMenuItem>
                        </template>
                    </DropdownMenuContent>
                </DropdownMenu>
            </template>
        </BaseTable>
    </div>
    </AppMainContent>
  </AppLayout>
</template>
