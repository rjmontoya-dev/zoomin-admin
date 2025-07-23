<script setup lang="ts">
import AppMainContent from '@/components/AppMainContent.vue';
import LoadingPage from '@/components/shared/LoadingPage.vue';
import { Button } from '@/components/ui/button';
import { Toaster } from '@/components/ui/sonner';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';
import 'vue-sonner/style.css';
import FormSection from './components/FormSection.vue';
import { toast } from 'vue-sonner';

const props = defineProps({
  item: {
    type: Object,
    required: true,
  },
});

const form = useForm({
    message: props.item.message ?? null,
    image: props.item.image ?? null,
    status: props.item.status ?? null,
    start_at: props.item.start_at ?? null,
    end_at: props.item.end_at ?? null,
    maintenance_type: props.item.start_at ? 'scheduled' : 'immediate',
});

const breadcrumbs = [
    {
        title: 'Maintenance',
        href: '#',
    },
];

const isLoading = ref(false);
const submit = () => {
    if (form.start_at) {
        form.start_at = new Date(form.start_at).toLocaleDateString('en-CA');
    }

    if (form.end_at) {
        form.end_at = new Date(form.end_at).toLocaleDateString('en-CA');
    }

    isLoading.value = true;
    form.post(route('admin.maintenance.update'), {
        onSuccess: () => {
            isLoading.value = false;
            toast.success('Maintenance successfully updated.');
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
        <AppMainContent :breadcrumbs="breadcrumbs" title="Maintenance">
            <div class="flex-1 overflow-y-auto p-6">
                <div class="flex justify-center">
                    <div class="w-full max-w-2xl rounded border bg-white p-6 shadow">
                        <FormSection
                            :form="form"
                            button-title="Update"
                            @update:start_at="newDate => (form.start_at = newDate)"
                            @update:end_at="newDate => (form.end_at = newDate)"
                            @submit="submit"
                        />
                        <!-- Sticky button container -->
                        <div class="sticky bottom-0 mt-6 flex justify-end space-x-3 border-t bg-white p-4">
                            <Button variant="default" @click="submit()"> Submit </Button>
                        </div>
                    </div>
                </div>
            </div>
        </AppMainContent>
    </AppLayout>
</template>
