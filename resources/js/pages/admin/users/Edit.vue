<script setup lang="ts">
import AppMainContent from '@/components/AppMainContent.vue';
import BaseNavTabs from '@/components/base/BaseNavTabs.vue';
import LoadingPage from '@/components/shared/LoadingPage.vue';
import { Button } from '@/components/ui/button';
import { Toaster } from '@/components/ui/sonner';
import AppLayout from '@/layouts/AppLayout.vue';
import { router, useForm } from '@inertiajs/vue3';
import { defineModel, defineProps, ref, watch } from 'vue';
import 'vue-sonner/style.css';
import FormSection from './components/FormSection.vue';
import { toast } from 'vue-sonner'

interface Tab {
    label: string;
    value: string;
    tabCount?: number;
}
const props = defineProps({
  title: String,
  buttonTitle: String,
  item: {
    type: Object,
    required: true,
  },
})
console.log(props.item);
const isShow = defineModel('isEditShow');

const form = useForm({
    id: null,
    name: null,
    email: null,
})

const breadcrumbs = [
    {
        title: 'Users',
        href: route('admin.users.index'),
    },
    {
        title: 'Edit',
        href: '#',
    },
];

const TABS: Tab[] = [{ label: 'Information', value: 'all' }];

const activeTab = ref(TABS[0].value);

const cancel = () => {
    router.get(route('admin.users.index'));
};

// Watch for changes in props.data and populate the form
watch(
  () => props.item.data,
  (newData:any) => {
    if (newData) {
        form.id = newData.id ?? ''
        form.name = newData.name ?? ''
        form.email = newData.email ?? ''
    }
  },
  { immediate: true }
)


const isLoading = ref(false);
const submit = () => {
    isLoading.value = true;
    form.post(route('admin.users.update',{ id: form.id }), {
        onSuccess: () => {
            isLoading.value = false;
            toast.success('User successfully updated.')
        },
        onError: () => {
            isLoading.value = false;
             toast.error('Something went wrong!');
        },
    });
}

</script>

<template>
    <Toaster :expand="true" richColors  />
    <LoadingPage :isLoading="isLoading" />
    <AppLayout>
        <AppMainContent :breadcrumbs="breadcrumbs" title="Edit User">
            <BaseNavTabs v-if="TABS && TABS.length > 0" :tabs="TABS" v-model="activeTab" @update:model-value="(value) => (activeTab = value)" />
            <div class="flex-1 overflow-y-auto p-6">
                <div class="flex justify-center">
                    <div class="w-full max-w-2xl rounded border bg-white p-6 shadow">
                        <FormSection :form="form" :buttonTitle="buttonTitle" />

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
