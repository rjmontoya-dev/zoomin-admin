<script setup lang="ts">
import { computed } from 'vue';

const patternId = computed(() => `pattern-${Math.random().toString(36).substring(2, 9)}`);
defineProps({
    text: {
        type: String,
        default: '',
    },
    color: {
        type: String,
        default: 'neutral',
    },
});
</script>

<template>
    <div class="absolute inset-0 flex items-center justify-center size-full">
        <span :class="`text-2xl font-bold text-${color}-500`">{{ text }}</span>
    </div>
    <svg class="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" fill="none">
        <defs>
            <pattern :id="patternId" x="0" y="0" width="8" height="8" patternUnits="userSpaceOnUse">
                <path d="M-1 5L5 -1M3 9L8.5 3.5" stroke-width="0.5"></path>
            </pattern>
        </defs>
        <rect stroke="none" :fill="`url(#${patternId})`" width="100%" height="100%"></rect>
    </svg>
</template>
