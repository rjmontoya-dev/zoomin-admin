<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import axios from 'axios'
import { ref } from 'vue';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

 const stats = ref({ total_visits: 0, todays_visits: 0, distinct_users: 0  });

onMounted(async() => {
    const response = await axios.get(route('admin.dashboard.visits.stats'));
  stats.value = response.data;
})

const totalVisits = computed(() => stats.value.total_visits);
const todaysVisits = computed(() => stats.value.todays_visits);
const distinct_users = computed(() => stats.value.distinct_users);
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <h2 class="text-xl font-bold">Visits</h2>
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-medium">Total</span>
                                <span class="text-green-500 text-lg font-bold">{{ totalVisits }}</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-medium">Today</span>
                                <span class="text-orange-500 text-lg font-bold">{{ todaysVisits }}</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-medium">Users</span>
                                <span class="text-blue-500 text-lg font-bold">{{ distinct_users }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
