<script setup lang="ts">
import AppMainContent from '@/components/AppMainContent.vue';
import BaseNavTabs from '@/components/base/BaseNavTabs.vue';
import LoadingPage from '@/components/shared/LoadingPage.vue';
import { Button } from '@/components/ui/button';
import { Toaster } from '@/components/ui/sonner';
import AppLayout from '@/layouts/AppLayout.vue';
import { router, useForm } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';
import 'vue-sonner/style.css';
import FormSection from './components/FormSection.vue';
import { toast } from 'vue-sonner'

interface Tab {
    label: string;
    value: string;
    tabCount?: number;
}

interface Project {
    id: number;
    name: string;
}

const props = defineProps({
    project: Object as () => Project,
    status: Object,
});

const breadcrumbs = [
    {
        title: 'Projects',
        href: route('admin.projects.index'),
    },
    {
        title: props.project.name,
        href: route('admin.projects.edit', props.project.id),
    },
    {
        title: 'Repair History',
        href: route('admin.projects.repairs.index', { project: props.project.id }),
    },
    {
        title: 'Create',
        href: route('admin.projects.repairs.create', { project: props.project.id }),
    },
];

const TABS: Tab[] = [{ label: 'Information', value: 'all' }];

const activeTab = ref(TABS[0].value);

const cancel = () => {
    router.get(route('admin.projects.repairs.index', { project: props.project.id }));
};

const form = useForm({
    reported_issue: null,
    date_reported: null,
    status: null,
    remarks: null,
    repair_date: null,
    amount: null,
    date_finished: null,
});


const isLoading = ref(false);
const submit = () => {
    isLoading.value = true;
    form.post(route('admin.projects.repairs.store', { project: props.project.id }), {
        onSuccess: () => {
            isLoading.value = false;
            toast.success('Repair successfully created.')
        },
        onError: () => {
            isLoading.value = false;
             toast.error('Something went wrong!');
        },
    });
};
</script>

<template>
    <Toaster :expand="true" richColors  />
    <LoadingPage :isLoading="isLoading" />
    <AppLayout>
        <AppMainContent :breadcrumbs="breadcrumbs" title="Create Repair">
            <BaseNavTabs v-if="TABS && TABS.length > 0" :tabs="TABS" v-model="activeTab" @update:model-value="(value) => (activeTab = value)" />
            <div class="flex-1 overflow-y-auto p-6">
                <div class="flex justify-center">
                    <div class="w-full max-w-2xl rounded border bg-white p-6 shadow">
                        <FormSection :form="form" :statuses="status" />

                        <!-- Sticky button container -->
                        <div class="sticky bottom-0 mt-6 flex justify-end space-x-3 border-t bg-white p-4">
                            <Button variant="outline" @click="cancel()"> Cancel </Button>
                            <Button variant="default" @click="submit()"> Submit </Button>
                        </div>
                    </div>
                </div>
            </div>
        </AppMainContent>
    </AppLayout>
</template>