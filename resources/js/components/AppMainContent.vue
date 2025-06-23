<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';

interface Props {
    breadcrumbs?: Object;
    class?: string;
    title?: string;
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <div class="h-[calc(100vh-80px)] flex flex-col">
        <!-- Header section - fixed height -->
        <div class="flex items-center justify-between py-8 px-6 border-b border-gray-100 dark:border-zinc-800 shrink-0">
            <div class="space-y-3">
                <template v-if="breadcrumbs.length > 0">
                    <Breadcrumbs :breadcrumbs="breadcrumbs" />
                </template>
                <template v-if="$slots.title">
                    <slot name="title" />
                </template>
                <template v-else-if="props.title">
                    <h1 class="text-3xl font-black truncate text-black dark:text-white">
                        {{ props.title }}
                    </h1>
                </template>
            </div>
            <slot name="button" />
        </div>

        <!-- Content section - flexible height -->
        <div class="flex-1 flex flex-col min-h-0" :class="props.class">
            <slot />
        </div>
    </div>
</template>
