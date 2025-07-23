<script setup lang="ts">
import ImageUploader from '@/components/shared/ImageUploader.vue';
import RichTextEditor from '@/components/shared/RichTextEditor.vue';
import { Calendar } from '@/components/ui/calendar';
import { Label } from '@/components/ui/label';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Switch } from '@/components/ui/switch';
import { useDateFormat } from '@/composables/useDateFormat';
import { cn } from '@/lib/utils';
import { Calendar as CalendarIcon } from 'lucide-vue-next';
import { defineEmits, defineProps, watch } from 'vue';

const { formatDate } = useDateFormat();
const props = defineProps({
    buttonTitle: String,
    form: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['submit', 'cancel']);

watch(
    () => props.form.maintenance_type,
    (newType: 'immediate' | 'scheduled') => {
        if (newType === 'immediate') {
            props.form.start_at = null;
            props.form.end_at = null;
        } else if (newType === 'scheduled') {
            props.form.status = false;
        }
    },
);
</script>
<template>
    <div class="flex-1 space-y-6">
        <!-- Form -->
        <form @submit.prevent>
            <!-- Message -->
            <div class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Message</label>
                <RichTextEditor v-model="form.message" />
                <span class="text-sm text-red-500 italic" v-if="form.errors.message"> {{ form?.errors?.message }}</span>
            </div>

            <!-- Image -->
            <div class="space-y-2 pb-4">
                <ImageUploader v-model="form.image" label="Image" :error="form.errors.image" />
            </div>

            <!-- Maintenance Type -->
            <div class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Maintenance Type</label>
                <RadioGroup v-model="form.maintenance_type" class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <RadioGroupItem id="immediate" value="immediate" />
                        <Label for="immediate">Immediate</Label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <RadioGroupItem id="scheduled" value="scheduled" />
                        <Label for="scheduled">Scheduled</Label>
                    </div>
                </RadioGroup>
            </div>

            <!-- Status -->
            <div v-if="form.maintenance_type === 'immediate'" class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Status</label>
                <div class="flex items-center space-x-2">
                    <Switch id="status" v-model="form.status" />
                    <Label for="status">{{ form.status ? 'Enabled' : 'Disabled' }}</Label>
                </div>
                <span class="text-sm text-red-500 italic" v-if="form.errors.status"> {{ form?.errors?.status }}</span>
            </div>

            <!-- Scheduled Fields -->
            <div v-if="form.maintenance_type === 'scheduled'" class="flex gap-4 w-full">
                <!-- Start At -->
                <div class="flex-1 space-y-2">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Start At</label>
                    <Popover>
                        <PopoverTrigger as-child>
                            <Button
                                :variant="'outline'"
                                :class="cn('w-full justify-start text-left font-normal', !form.start_at && 'text-muted-foreground')"
                            >
                                <CalendarIcon class="mr-2 h-4 w-4" />
                                {{ form.start_at ? formatDate(form.start_at).date : 'Pick a date' }}
                            </Button>
                        </PopoverTrigger>
                        <PopoverContent class="w-auto p-0">
                            <Calendar v-model="form.start_at"  mode="date" :enable-time-picker="false"/>
                        </PopoverContent>
                    </Popover>
                    <span class="text-sm text-red-500 italic" v-if="form.errors.start_at"> {{ form?.errors?.start_at }}</span>
                </div>

                <!-- End At -->
                <div class="flex-1 space-y-2">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">End At</label>
                    <Popover>
                        <PopoverTrigger as-child>
                            <Button
                                :variant="'outline'"
                                :class="cn('w-full justify-start text-left font-normal', !form.end_at && 'text-muted-foreground')"
                            >
                                <CalendarIcon class="mr-2 h-4 w-4" />
                                {{ form.end_at ? formatDate(form.end_at).date : 'Pick a date' }}
                            </Button>
                        </PopoverTrigger>
                        <PopoverContent class="w-auto p-0">
                            <Calendar v-model="form.end_at" mode="date" :enable-time-picker="false" />
                        </PopoverContent>
                    </Popover>
                    <span class="text-sm text-red-500 italic" v-if="form.errors.end_at"> {{ form?.errors?.end_at }}</span>
                </div>
            </div>
        </form>
    </div>
</template>
