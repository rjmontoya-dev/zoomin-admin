<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import Input from '@/components/ui/input/Input.vue';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'


defineProps({
    buttonTitle: String,
    form: {
        type: Object,
        required: true
    },
    roles: {
        type: Array,
        required: false
    },
    isCreating: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['submit','cancel']);


</script>
<template>
    <div class="flex-1 space-y-6 ">
        <!-- Form -->
        <form @submit.prevent>
            <!-- Name -->
            <div class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Name</label>
                <Input
                    v-model="form.name"
                    :error="form.errors.name"
                    type="text"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    placeholder="Enter user name"
                />
            </div>

            <!-- Email -->
            <div class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                <Input
                    v-model="form.email"
                    :error="form.errors.email"
                    type="email"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    placeholder="Enter user email"
                />
            </div>

            <!-- Password -->
            <div v-if="isCreating" class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <Input
                    v-model="form.password"
                    :error="form.errors.password"
                    type="password"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    placeholder="Enter user password"
                />
            </div>

             <!-- Roles -->
            <div class="space-y-2 pb-4 w-full">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Role</label>
                <Select v-model="form.role_id" class="w-full">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Select a role" />
                    </SelectTrigger>
                    <SelectContent class="w-full">
                        <SelectGroup>
                            <SelectLabel>Roles</SelectLabel>
                            <SelectItem
                                v-for="role in roles"
                                :key="role.id"
                                :value="role.id"
                                class="w-full"
                            >
                                {{ role.name }}
                            </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </div>

        </form>
    </div>
</template>
