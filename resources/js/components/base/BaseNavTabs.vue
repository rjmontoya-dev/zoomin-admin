<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';

interface Tab {
  label: string;
  value: string;
  tabRoute?: string;
  tabCount?: number;
}

defineProps<{
  tabs: Tab[];
  modelValue: string;
}>();

const emit = defineEmits(['update:modelValue']);

const handleTabClick = (value: string) => {
  emit('update:modelValue', value);
};
</script>

<template>
  <div class="border-b border-gray-200 dark:border-gray-700">
    <nav class="flex  ">
      <button
        v-for="tab in tabs"
        :key="tab.value"
        type="button"
        class="table-row-tab border-b-2 px-1 ml-4 py-4 text-sm font-medium transition-colors duration-200 flex items-center gap-2"
        :class="{
          'border-blue-500 text-blue-600': modelValue === (tab.value || tab.id),
          'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700': modelValue !== (tab.value || tab.id),
        }"
        @click="handleTabClick(tab.value || tab.id)"
      >
        <span>{{ tab.label }}</span>
        <span
          v-if="tab.tabCount"
          class="inline-flex items-center justify-center rounded-full bg-gray-200 px-2 py-0.5 text-xs font-semibold text-gray-700"
        >
          {{ tab.tabCount }}
        </span>
        <span v-else>

        </span>
      </button>
    </nav>
  </div>
</template>
