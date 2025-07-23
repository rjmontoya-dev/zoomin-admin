<script setup lang="ts">
import { computed, defineProps, defineEmits, ref, watch } from 'vue';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

const props = defineProps<{
  meta: {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
  };
  perPageOptions?: number[];
}>();

const emit = defineEmits(['update:page', 'update:perPage']);

const internalPerPage = ref(props.meta.per_page);
const perPageValues = computed(() => props.perPageOptions || [10, 25, 50]);

watch(internalPerPage, (newValue) => {
  emit('update:perPage', newValue);
});

const visiblePages = computed(() => {
  const pages = [];
  const total = props.meta.last_page;
  const current = props.meta.current_page;

  if (total <= 7) {
    for (let i = 1; i <= total; i++) pages.push(i);
  } else if (current <= 4) {
    for (let i = 1; i <= 5; i++) pages.push(i);
  } else if (current >= total - 3) {
    for (let i = total - 4; i <= total; i++) pages.push(i);
  } else {
    for (let i = current - 2; i <= current + 2; i++) pages.push(i);
  }
  return pages;
});

const showLeftEllipsis = computed(() => props.meta.last_page > 7 && visiblePages.value[0] > 2);
const showRightEllipsis = computed(() => props.meta.last_page > 7 && visiblePages.value.at(-1)! < props.meta.last_page - 1);
const showFirstPage = computed(() => props.meta.last_page > 7 && visiblePages.value[0] > 1);
const showLastPage = computed(() => props.meta.last_page > 7 && visiblePages.value.at(-1)! < props.meta.last_page);

const from = computed(() => (props.meta.current_page - 1) * props.meta.per_page + 1);
const to = computed(() => Math.min(props.meta.current_page * props.meta.per_page, props.meta.total));
</script>

<template>
  <div class="m-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-4">
      <Select v-model="internalPerPage">
        <SelectTrigger class="w-20 table-row-select">
          <SelectValue :placeholder="internalPerPage" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem v-for="option in perPageValues" :key="option" :value="option">
            {{ option }}
          </SelectItem>
        </SelectContent>
      </Select>
      <div class="flex flex-row items-center gap-x-1 text-sm text-gray-600 dark:text-gray-400">
        <span>Showing</span>
        <span>{{ from }}</span>
        <span>to</span>
        <span>{{ to }}</span>
        <span>of</span>
        <span>{{ meta.total }}</span>
        <span>entries</span>
      </div>
    </div>

    <div class="flex justify-end">
      <Pagination>
        <PaginationContent class="flex items-center gap-x-2">
          <PaginationItem>
            <PaginationPrevious
              href="#"
              :class="{ 'pointer-events-none opacity-50': meta.current_page === 1 }"
              class="table-row-prev inline-flex h-9 items-center justify-center rounded-md border bg-white px-3 text-sm hover:bg-gray-100 dark:bg-gray-950 dark:border-gray-800 dark:hover:bg-gray-800"
              @click.prevent="$emit('update:page', meta.current_page - 1)"
            />
          </PaginationItem>

          <PaginationItem v-if="showFirstPage">
            <a href="#" class="table-row-page inline-flex h-9 w-9 items-center justify-center rounded-md text-sm hover:bg-gray-100 dark:hover:bg-gray-800" @click.prevent="$emit('update:page', 1)">1</a>
          </PaginationItem>

          <PaginationItem v-if="showLeftEllipsis">
            <PaginationEllipsis />
          </PaginationItem>

          <PaginationItem v-for="page in visiblePages" :key="page">
            <a
              href="#"
              class="table-row-page inline-flex h-9 w-9 items-center justify-center rounded-md text-sm transition-colors"
              :class="{
                'bg-gray-600 text-white hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600': meta.current_page === page,
                'text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800': meta.current_page !== page,
              }"
              @click.prevent="$emit('update:page', page)"
            >
              {{ page }}
            </a>
          </PaginationItem>

          <PaginationItem v-if="showRightEllipsis">
            <PaginationEllipsis />
          </PaginationItem>

          <PaginationItem v-if="showLastPage">
            <a
              href="#"
              class="table-row-page inline-flex h-9 w-9 items-center justify-center text-sm hover:bg-gray-100 dark:hover:bg-gray-800"
              @click.prevent="$emit('update:page', meta.last_page)"
            >
              {{ meta.last_page }}
            </a>
          </PaginationItem>

          <PaginationItem>
            <PaginationNext
              href="#"
              :class="{ 'pointer-events-none opacity-50': meta.current_page === meta.last_page }"
              class="table-row-next inline-flex h-9 items-center justify-center rounded-md border bg-white px-3 text-sm hover:bg-gray-100 dark:bg-gray-950 dark:border-gray-800 dark:hover:bg-gray-800"
              @click.prevent="$emit('update:page', meta.current_page + 1)"
            />
          </PaginationItem>
        </PaginationContent>
      </Pagination>
    </div>
  </div>
</template>
