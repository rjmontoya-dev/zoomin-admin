<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/app/Layout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile',
        href: '/settings',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profile" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Profile" description="This is the profile page." />
                <!-- Profile content goes here -->
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
