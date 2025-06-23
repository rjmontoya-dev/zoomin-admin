<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Image as ImageIcon, Video, FileText, Upload, X } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

// Define file type options
type FileType = 'image' | 'video' | 'document' | 'any';

interface Props {
    modelValue: string;
    label: string;
    type?: FileType;
    maxSize?: number; // in MB
    accept?: string;
    placeholder?: string;
    error?: string;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'image',
    maxSize: 5, // 5MB default max size
    accept: '', // Will be computed based on type
    placeholder: 'No file selected',
});

const emit = defineEmits(['update:modelValue', 'fileSelected']);

const selectedFile = ref<File | null>(null);
const uploadError = ref('');
const filePreview = ref(props.modelValue);

// Compute accept attribute based on type
const acceptTypes = computed(() => {
    if (props.accept) return props.accept;

    switch (props.type) {
        case 'image': return 'image/*';
        case 'video': return 'video/*';
        case 'document': return '.pdf,.doc,.docx,.txt,.xls,.xlsx,.ppt,.pptx,.csv';
        case 'any': return '';
        default: return '';
    }
});

// Get appropriate icon based on file type
const fileIcon = computed(() => {
    switch (props.type) {
        case 'image': return ImageIcon;
        case 'video': return Video;
        default: return FileText;
    }
});

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

    // Validate file type based on the component type
    let validType = true;
    if (props.type === 'image' && !file.type.startsWith('image/')) {
        validType = false;
    } else if (props.type === 'video' && !file.type.startsWith('video/')) {
        validType = false;
    } else if (props.type === 'document' && !isDocumentType(file.type)) {
        validType = false;
    }

    if (!validType && props.type !== 'any') {
        uploadError.value = `Only ${props.type} files are allowed`;
        selectedFile.value = null;
        input.value = '';
        return;
    }

    uploadError.value = '';
    selectedFile.value = file;

    // Create a preview and handle base64 encoding
    if (file.type.startsWith('image/')) {
        createFilePreviewWithBase64(file);
    } else if (file.type.startsWith('video/')) {
        // Use both approaches for videos: base64 for server and objectURL for preview
        createFilePreviewWithBase64(file, true);
    } else if (props.type === 'document' || isDocumentType(file.type)) {
        // For documents: create base64 for server, but no visual preview
        createFilePreviewWithBase64(file, false, true);
    } else {
        // For other non-previewable files
        filePreview.value = '';
        emit('update:modelValue', '');
        emit('fileSelected', {
            fieldName: props.label.toLowerCase().replace(/\s+/g, '-'),
            file: selectedFile.value,
            base64: '',
        });
    }
};

// Check if file is a document type
const isDocumentType = (type: string): boolean => {
    const documentTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'text/plain',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/csv'
    ];
    return documentTypes.includes(type);
};

// Create file preview with base64 - works for images, videos, and documents
const createFilePreviewWithBase64 = (file: File, isVideo: boolean = false, isDocument: boolean = false) => {
    const reader = new FileReader();
    reader.onload = (e) => {
        const base64Data = e.target?.result as string;

        if (isVideo) {
            // For video, create an object URL for the preview and send base64 to server
            filePreview.value = URL.createObjectURL(file);

            emit('update:modelValue', base64Data); // Send base64 data to the server
            emit('fileSelected', {
                fieldName: props.label.toLowerCase().replace(/\s+/g, '-'),
                file: selectedFile.value,
                base64: base64Data, // Send base64 data to match backend expectations
                objectUrl: filePreview.value, // For local preview only
            });
        } else if (isDocument) {
            // For documents, no visual preview but send base64 to server
            filePreview.value = ''; // No preview for documents

            emit('update:modelValue', base64Data); // Send base64 data to the server
            emit('fileSelected', {
                fieldName: props.label.toLowerCase().replace(/\s+/g, '-'),
                file: selectedFile.value,
                base64: base64Data, // Send base64 data to match backend expectations
            });
        } else {
            // For images, use base64 for both preview and server
            filePreview.value = base64Data;
            emit('update:modelValue', filePreview.value);
            emit('fileSelected', {
                fieldName: props.label.toLowerCase().replace(/\s+/g, '-'),
                file: selectedFile.value,
                base64: filePreview.value,
            });
        }
    };
    reader.readAsDataURL(file);
};

// Clear the selected file
const clearFile = () => {
    // Clean up any object URLs to avoid memory leaks
    if (isVideo.value && filePreview.value && !filePreview.value.startsWith('data:')) {
        URL.revokeObjectURL(filePreview.value);
    }

    selectedFile.value = null;
    filePreview.value = '';
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
        if (newValue !== filePreview.value) {
            filePreview.value = newValue;
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
            return `Selected ${props.type} (not yet uploaded)`;
        }
        return props.modelValue.split('/').pop() || props.modelValue;
    }

    return props.placeholder;
});

// Check if the current value is a base64 file (not yet uploaded)
const isBase64File = computed(() => {
    return props.modelValue && props.modelValue.startsWith('data:');
});

// Check if the preview should be shown
const showPreview = computed(() => {
    return (props.type === 'image' || props.type === 'video') && filePreview.value;
});

// Check if the file is a video
const isVideo = computed(() => {
    return props.type === 'video' ||
           (selectedFile.value && selectedFile.value.type.startsWith('video/'));
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
                    <component :is="fileIcon" class="h-4 w-4 text-muted-foreground" />
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
                                :accept="acceptTypes"
                                class="absolute inset-0 cursor-pointer opacity-0 z-10"
                                @change="handleFileChange"
                            />
                        </Button>
                    </label>

                    <Button v-if="filePreview || selectedFile" type="button" variant="outline" size="sm" class="h-10 hover:bg-red-500 hover:text-white transition-all" @click="clearFile">
                        <X class="h-4 w-4" />
                    </Button>
                </div>
            </div>

            <!-- Preview area -->
            <div v-if="showPreview" class="relative max-w-xs mt-4">
                <!-- Image preview -->
                <img v-if="props.type === 'image'" :src="filePreview" :alt="label" class="h-48 w-80 rounded-md border bg-muted/20 object-contain" />

                <!-- Video preview -->
                <video v-else-if="isVideo" :src="filePreview" controls class="h-48 w-full rounded-md border bg-muted/20 object-cover"></video>

                <!-- Remove button -->
                <Button type="button" variant="destructive" size="icon" class="absolute right-2 top-2 h-6 w-6 z-[5]" @click="clearFile">
                    <X class="h-3 w-3" />
                </Button>

                <div v-if="isBase64File" class="mt-1 text-xs text-amber-500">This file will be uploaded when you save the form</div>
            </div>

            <!-- Error message -->
            <p v-if="uploadError || props.error" class="text-sm text-destructive mt-2">
                {{ uploadError || props.error }}
            </p>
        </div>
    </div>
</template>
