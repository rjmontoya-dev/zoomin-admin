<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Image as ImageIcon, Upload, X } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface Props {
    modelValue: string;
    label: string;
    maxSize?: number; // in MB
    accept?: string;
    placeholder?: string;
    error?: string | boolean;
}

const props = withDefaults(defineProps<Props>(), {
    maxSize: 2, // 2MB default max size
    accept: 'image/*',
    placeholder: 'No file selected',
});

const emit = defineEmits(['update:modelValue', 'fileSelected']);

const selectedFile = ref<File | null>(null);
const uploadError = ref('');
const imagePreview = ref(props.modelValue);

// Format the file size to a human-readable format
const formatFileSize = (bytes: number): string => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

// Handle file selection
const handleFileChange = (event: Event) => {
    const input = event.target as HTMLInputElement;
    if (!input.files || input.files.length === 0) {
        selectedFile.value = null;
        return;
    }

    const file = input.files[0];

    // Validate file size
    if (file.size > props.maxSize * 1024 * 1024) {
        uploadError.value = `File size exceeds the limit (${props.maxSize}MB)`;
        selectedFile.value = null;
        input.value = '';
        return;
    }

    // Validate file type
    if (!file.type.startsWith('image/')) {
        uploadError.value = 'Only image files are allowed';
        selectedFile.value = null;
        input.value = '';
        return;
    }

    uploadError.value = '';
    selectedFile.value = file;

    // Create a preview
    const reader = new FileReader();
    reader.onload = (e) => {
        imagePreview.value = e.target?.result as string;

        // Emit the base64 preview for temporary display
        emit('update:modelValue', imagePreview.value);

        // Also emit the selected File object for the parent to handle during form submission
        emit('fileSelected', {
            fieldName: props.label.toLowerCase().replace(/\s+/g, '-'),
            file: selectedFile.value,
            base64: imagePreview.value,
        });
    };
    reader.readAsDataURL(file);
};

// Clear the selected file
const clearFile = () => {
    selectedFile.value = null;
    imagePreview.value = '';
    uploadError.value = '';
    emit('update:modelValue', '');
    emit('fileSelected', {
        fieldName: props.label.toLowerCase().replace(/\s+/g, '-'),
        file: null,
        base64: '',
    });
};

// Watch for external changes to modelValue
watch(
    () => props.modelValue,
    (newValue) => {
        if (newValue !== imagePreview.value) {
            imagePreview.value = newValue;
        }
    },
);

// Computed property to show filename or URL
const displayValue = computed(() => {
    if (selectedFile.value) {
        return `${selectedFile.value.name} (${formatFileSize(selectedFile.value.size)})`;
    }

    if (props.modelValue) {
        if (props.modelValue.startsWith('data:')) {
            return 'Selected image (not yet uploaded)';
        }
        return props.modelValue.split('/').pop() || props.modelValue;
    }

    return props.placeholder;
});

// Check if the current value is a base64 image (not yet uploaded)
const isBase64Image = computed(() => {
    return imagePreview.value && imagePreview.value.startsWith('data:');
});
</script>

<template>
    <div class="space-y-2">
        <Label :for="label.toLowerCase().replace(/\s+/g, '-')">{{ label }}</Label>

        <div class="flex flex-col">
            <!-- Input and buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <div
                    class="flex flex-1 items-center gap-2 rounded-md border border-input px-3 py-2 text-sm h-10"
                    :class="{ 'border-destructive': uploadError || props.error }"
                >
                    <ImageIcon class="h-4 w-4 text-muted-foreground" />
                    <span class="truncate">{{ displayValue }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <label>
                        <Button type="button" variant="outline" size="sm" class="relative overflow-hidden h-10 cursor-pointer">
                            <Upload class="mr-1 h-4 w-4" />
                            <span>Browse</span>
                            <input
                                :id="label.toLowerCase().replace(/\s+/g, '-')"
                                type="file"
                                :accept="accept"
                                class="absolute inset-0 cursor-pointer opacity-0 z-10"
                                @change="handleFileChange"
                            />
                        </Button>
                    </label>

                    <Button v-if="imagePreview" type="button" variant="outline" size="sm" class="h-10 hover:bg-red-500 hover:text-white transition-all" @click="clearFile">
                        <X class="h-4 w-4" />
                    </Button>
                </div>
            </div>

            <!-- Preview image -->
            <div v-if="imagePreview" class="relative max-w-xs mt-4">
                <img :src="imagePreview" :alt="label" class="h-48 w-80 rounded-md border bg-muted/20 object-contain" />
                <Button type="button" variant="destructive" size="icon" class="absolute right-2 top-2 h-6 w-6 z-[5]" @click="clearFile">
                    <X class="h-3 w-3" />
                </Button>
                <div v-if="isBase64Image" class="mt-1 text-xs text-amber-500">This image will be uploaded when you save the article</div>
            </div>

            <!-- Error message -->
            <p v-if="uploadError || props.error" class="text-sm text-destructive mt-2">
                {{ uploadError || props.error }}
            </p>
        </div>
    </div>
</template>
