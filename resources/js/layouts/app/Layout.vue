<script setup lang="ts">
import AppSidebarLayout from './AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppSidebarLayout :breadcrumbs="props.breadcrumbs">
        <slot />
    </AppSidebarLayout>
</template>
