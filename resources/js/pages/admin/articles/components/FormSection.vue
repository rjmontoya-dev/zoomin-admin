<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import RichTextEditor from '@/components/shared/RichTextEditor.vue';
import Input from '@/components/ui/input/Input.vue';

defineProps({
    buttonTitle: String,
    form: {
        type: Object,
        required: true
    },
    statuses: Object,
})

const emit = defineEmits(['submit','cancel']);


</script>
<template>
    <div class="flex-1 space-y-6 ">
        <!-- Form -->
        <form @submit.prevent>
            <!-- Client -->
            <div class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Title</label>
                <Input
                    v-model="form.title"
                    :error="form.errors.title"
                    type="text"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    placeholder="Enter article title"
                />
            </div>

            <!-- Position -->
            <div class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Position</label>
                <Input
                    v-model="form.author"
                    :error="form.errors.author"
                    type="text"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    placeholder="Enter author"
                />
            </div>
            <!-- Status (Select Dropdown) -->
            <div class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Status</label>
                <select v-model="form.status" place-holder="Select" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                    <option disabled value="">Select status</option>
                    <option v-for="status in statuses" :key="status.id" :value="status.id">
                        {{ status.label }}
                    </option>
                </select>
                <span class="text-sm text-red-500 italic" v-if="form.errors.status"> {{  form?.errors?.status  }}</span>
            </div>

            <!-- Content -->
            <div class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Body</label>
                <RichTextEditor
                    v-model="form.body"
                />
                <span class="text-sm text-red-500 italic" v-if="form.errors.body"> {{  form?.errors?.body  }}</span>
            </div>
        </form>
    </div>
</template>
