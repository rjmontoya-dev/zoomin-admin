<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Drawer, DrawerContent, DrawerHeader, DrawerTitle, DrawerTrigger } from '@/components/ui/drawer';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { debounce } from 'lodash';
import { CalendarDays, Filter, Logs, Search, SortAsc, SortDesc } from 'lucide-vue-next';
import { computed, defineEmits, defineProps, reactive, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

import BaseDataTable from './BaseDataTable.vue';
import BasePaginator from './BasePaginator.vue';
// Interfaces
interface Tab {
    label: string;
    value: string;
    tabCount?: number;
}
interface Column {
    key: string;
    label: string;
}
interface Sorter {
    label: string;
    value: string;
}

interface Filters {
    label: string;
    value: string;
}

interface PaginationMeta {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
}
interface QueryOptions {
    page: number;
    perPage: number;
    tab: string | null;
    sort: string;
    direction: 'asc' | 'desc';
    search: string | null;
    filters: Record<string, any>;
}

// Props
const props = defineProps<{
    route: string;
    data: any[];
    columns: Column[];
    tabs?: Tab[];
    sorters?: Sorter[];
    filters?: object;
    activeTab?: string;
    initialSort?: { value: string; direction: 'asc' | 'desc' };
    initialPerPage?: number;
    meta: PaginationMeta[];
}>();

// Emits
const emit = defineEmits(['row:click']);

// --- INTERNAL STATE MANAGEMENT ---
const loading = ref(false);
const items = computed(() => props.data);


// Active filters state
const activeFilters = reactive({
    dateRange: null,
    ...(props.filters || {}),
});

//DEFAULTS
const perPageOptions = [5, 10, 20];

const columns = computed(() => {
    return props?.columns?.length
        ? props.columns
        : [
              { key: 'id', label: 'ID' },
              { key: 'created_at', label: 'Date Created' },
          ];
});

const sorters = computed(() => [{ label: 'Date Created', value: 'created_at' }, ...(props?.sorters ?? [])]);

// Query options - centralized state
const queryOptions = reactive<QueryOptions>({
    page: 1,
    perPage: props.initialPerPage || 10,
    tab: computed(() => props.activeTab || null),
    sort: props.initialSort?.value || props.sorters?.[0]?.value || 'created_at',
    direction: props.initialSort?.direction || 'desc',
    search: null,
    filters: activeFilters,
});

// UI State
const isFilterDrawerOpen = ref(false);
const searchQuery = ref('');
const activeSort = ref({
    value: queryOptions.sort,
    direction: queryOptions.direction,
});
const selectedDate = ref(null);

// --- COMPUTED ---
const currentSortLabel = computed(() => {
    return sorters?.value.find((s) => s.value === activeSort.value.value)?.label || 'Sort';
});

// --- GENERAL METHODS (moved from implementation) ---

const handlePageUpdate = (page: number) => {
    queryOptions.page = page;
};

const handlePerPageUpdate = (perPage: number) => {
    queryOptions.page = 1;
    queryOptions.perPage = perPage;
};

const handleSortUpdate = (sort: { value: string; direction: 'asc' | 'desc' }) => {
    queryOptions.sort = sort.value;
    queryOptions.direction = sort.direction;
    activeSort.value = sort;
};

const handleResetFilters = () => {
    activeFilters.dateRange = null;
    selectedDate.value = null;
    if (props.filters) {
        Object.keys(props.filters).forEach((key) => {
            activeFilters[key] = null;
        });
    }
    queryOptions.page = 1; // Reset to page 1
    isFilterDrawerOpen.value = false;
};

// --- UI EVENT HANDLERS ---
const handleRowClick = (item: any) => emit('row:click', item);

const handleSearch = () => {
    queryOptions.search = searchQuery.value;
    queryOptions.page = 1; // Reset to first page on search
};

const handleSortDirectionToggle = () => {
    const newDirection = activeSort.value.direction === 'asc' ? 'desc' : 'asc';
    activeSort.value.direction = newDirection;
    handleSortUpdate(activeSort.value);
};

const handleSortBy = (value: string) => {
    activeSort.value.value = value;
    handleSortUpdate(activeSort.value);
};

// Date formatting utility
const formatDate = (date: any) => {
    if (!date) return '';
    if (date.start && date.end) {
        return `${date.start.toLocaleDateString()} - ${date.end.toLocaleDateString()}`;
    }
    return date.toLocaleDateString();
};

const setSelectedDate = (date: any) => {
    selectedDate.value = date;
};

const applyFilters = () => {
    activeFilters.dateRange = selectedDate.value;
    queryOptions.page = 1;
    isFilterDrawerOpen.value = false;
};

// --- WATCHERS ---

watch(
  queryOptions,
  debounce((newQuery) => {
    const params = {
      ...newQuery,
      filters: Object.fromEntries(
        Object.entries(newQuery.filters || {}).filter(([_, v]) => v != null && v !== '')
      ),
    };

    // Use router.get instead of window.history to preserve Inertia behavior
    router.get(props.route, params, {
      preserveState: true,
      replace: true,
      onStart: () => (loading.value = true),
      onFinish: () => (loading.value = false),
    });
  }, 300),
  { deep: true }
);
</script>

<template>
    <div class="  relative flex-1 space-y-4 rounded-md">
        <div class="mr-6 ml-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div class="relative max-w-full flex-1">
                <Search class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-gray-400" />
                <Input v-model="searchQuery" placeholder="Search..." class="pl-10" @keyup.enter="handleSearch" />
            </div>

            <div class="flex items-center gap-2">
                <template v-if="sorters && sorters.length > 0">
                    <Button variant="outline" class="gap-2" @click="handleSortDirectionToggle">
                        <SortAsc class="h-4 w-4" v-if="activeSort.direction === 'asc'" />
                        <SortDesc class="h-4 w-4" v-else />
                    </Button>

                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button variant="outline" class="gap-2">
                                <Logs class="h-4 w-4" />
                                {{ currentSortLabel }}
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-48">
                            <DropdownMenuItem v-for="sorter in sorters" :key="sorter.value" @click="handleSortBy(sorter.value)">
                                {{ sorter.label }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </template>
                <Drawer v-model:open="isFilterDrawerOpen" direction="right">
                    <DrawerTrigger as-child>
                        <Button variant="outline" class="gap-2">
                            <Filter class="h-4 w-4" />
                            Filter
                        </Button>
                    </DrawerTrigger>

                    <DrawerContent class="fixed inset-y-0 right-0 z-50 w-full max-w-md bg-white shadow-xl dark:bg-gray-950">
                        <div class="flex h-full flex-col overflow-y-auto">
                            <!-- Header -->
                            <DrawerHeader class="flex items-start justify-start border-b px-6 py-4">
                                <DrawerTitle class="text-lg font-semibold text-gray-900 dark:text-white">Filters</DrawerTitle>
                            </DrawerHeader>

                            <div class="flex-1 space-y-6 p-6">
                                <!-- Date Range Filter -->
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-900 dark:text-white">Date Range</label>
                                    <Popover>
                                        <PopoverTrigger as-child>
                                            <Button variant="outline" class="w-full justify-start text-left font-normal">
                                                <span v-if="selectedDate">
                                                    {{ formatDate(selectedDate) }}
                                                </span>
                                                <span v-else class="text-muted-foreground">Pick a date</span>
                                                <CalendarDays class="ml-auto h-4 w-4 opacity-50" />
                                            </Button>
                                        </PopoverTrigger>

                                        <PopoverContent class="w-auto p-0" align="start">
                                            <Calendar
                                                initialFocus
                                                mode="range"
                                                :selected="selectedDate"
                                                @select="setSelectedDate"
                                                :defaultMonth="selectedDate || new Date()"
                                                :numberOfMonths="2"
                                            />
                                        </PopoverContent>
                                    </Popover>
                                </div>

                                <!-- Custom Filters Slot -->
                                <slot name="drawer-filters" :filters="activeFilters"></slot>
                            </div>

                            <!-- Footer Actions -->
                            <div class="border-t px-6 py-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex gap-3">
                                        <Button @click="applyFilters" class="min-w-[100px]"> Apply Filters </Button>
                                        <Button variant="outline" @click="handleResetFilters"> Reset </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </DrawerContent>
                </Drawer>
            </div>
        </div>

        <div class="mr-6 ml-6">
            <BaseDataTable :items="items" :columns="columns" :loading="loading" @row:click="handleRowClick">
                <!-- Empty state slot -->
                <template #empty>
                    <div v-if="items.length < 1" class="py-8 text-center">
                        <h3 class="text-lg font-semibold">No Data Found</h3>
                    </div>
                </template>

                <!-- Forward any custom slots passed into this component -->
                <template v-for="(_, slotName) in $slots" v-slot:[slotName]="slotProps">
                    <slot :name="slotName" v-bind="slotProps" />
                </template>
            </BaseDataTable>
        </div>
        <BasePaginator :meta="meta" :per-page-options="perPageOptions" @update:page="handlePageUpdate" @update:perPage="handlePerPageUpdate" />
    </div>
</template>
