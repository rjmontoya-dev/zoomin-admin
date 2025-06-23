<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

interface Column {
  key: string;
  label: string;
}

const props = defineProps<{
  items: any[];
  columns: Column[];
  loading?: boolean;
}>();

const emit = defineEmits(['row:click']);

const getCellValue = (item: any, key: string) => {
  return key.split('.').reduce((o, i) => (o ? o[i] : undefined), item);
};

const handleRowClick = (item: any) => {
  emit('row:click', item);
};
</script>

<template>
  <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-950">
    <Table>
      <TableHeader>
        <TableRow class="bg-gray-50 dark:bg-gray-900/50">
          <TableHead
            v-for="column in columns"
            :key="column.key"
            class="font-semibold text-gray-900 dark:text-gray-100"
          >
            {{ column.label }}
          </TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <template v-if="loading">
          <TableRow>
            <TableCell :colspan="columns.length" class="text-center py-4 text-gray-500">
              Loading...
            </TableCell>
          </TableRow>
        </template>
        <template v-else-if="items?.length === 0">
          <TableRow>
            <TableCell :colspan="columns.length" class="text-center py-4 text-gray-500">
              <slot name="empty">No Data</slot>
            </TableCell>
          </TableRow>
        </template>
        <template v-else>
          <TableRow
            v-for="(item, index) in items"
            :key="index"
            class="table-row hover:bg-gray-50 dark:hover:bg-gray-900/50 cursor-pointer"
            @click="handleRowClick(item)"
          >
            <TableCell v-for="column in columns" :key="column.key" class="text-gray-700 dark:text-gray-300">
              <slot :name="`cell-${column.key}`" :item="item" :value="getCellValue(item, column.key)">
                {{ getCellValue(item, column.key) }}
              </slot>
            </TableCell>
          </TableRow>
        </template>
      </TableBody>
    </Table>
  </div>
</template>
