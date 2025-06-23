<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { ArrowDown, ArrowUp, Edit, Plus, Trash, CircleAlert } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue'; // Added watch import
import ImageUploader from './ImageUploader.vue';
import RichTextEditor from './RichTextEditor.vue';
// import { useToast } from '@/components/ui/toast';

interface SchemaItem {
    id: string;
    type: string;
    label: string;
    description?: string;
    rules?: string;
    summary_field?: boolean;
}

interface FileData {
    fieldName: string;
    file: File | null;
    base64: string;
}

const props = defineProps<{
    fieldId: string;
    items: any[];
    maxItem?: number;
    schema: SchemaItem[];
    label: string;
}>();

const emit = defineEmits<{
    'update:items': [items: any[]];
}>();

// Create a copy of the items for local state
const localItems = ref<any[]>([]);

// Watch for changes to props.items and update localItems accordingly
watch(
    () => props.items,
    (newItems) => {
        if (Array.isArray(newItems)) {
            localItems.value = [...newItems];
        } else {
            localItems.value = [];
        }
    },
    { immediate: true, deep: true },
);

// Track file uploads for the editor
const itemFiles = ref<Record<string, FileData>>({});

// Dialog state
const isDialogOpen = ref(false);
const editingItemIndex = ref<number | null>(null);
const editingItem = ref<Record<string, any>>({});
// const { toast } = useToast();

// Computed property to determine which fields should be shown in the summary
const summaryFields = computed(() => {
    const fields = props.schema.filter((item) => item.summary_field === true);
    // If no summary fields defined, use the first two fields
    if (fields.length === 0 && props.schema.length > 0) {
        return props.schema.slice(0, Math.min(2, props.schema.length));
    }
    return fields;
});

// Initialize a new item with empty values for all schema fields
const initializeNewItem = () => {
    const newItem: Record<string, any> = {};
    props.schema.forEach((schemaItem) => {
        newItem[schemaItem.id] = '';
    });
    return newItem;
};

// Open the dialog to add a new item
const openAddDialog = () => {
    editingItemIndex.value = null;
    editingItem.value = initializeNewItem();
    isDialogOpen.value = true;
};

// Open the dialog to edit an existing item
const openEditDialog = (index: number) => {
    editingItemIndex.value = index;
    editingItem.value = { ...localItems.value[index] };
    isDialogOpen.value = true;
};

// Close the dialog
const closeDialog = () => {
    isDialogOpen.value = false;
    editingItem.value = {};
    editingItemIndex.value = null;
};

const saveItem = () => {
    // First, validate all required fields
    if (!validateRequiredFields()) {
        return;
    }
    // Apply file uploads if any
    Object.entries(itemFiles.value).forEach(([key, fileData]) => {
        if (fileData.file && key.startsWith(`${props.fieldId}_`)) {
            const fieldId = key.replace(`${props.fieldId}_`, '');
            editingItem.value[fieldId] = fileData.base64;
        }
    });

    if (editingItemIndex.value === null) {
        // Add new item
        localItems.value.push({ ...editingItem.value });
    } else {
        // Update existing item
        localItems.value[editingItemIndex.value] = { ...editingItem.value };
    }
    // Emit update event
    emit('update:items', [...localItems.value]);
    // Close the dialog
    closeDialog();
};

const validateRequiredFields = () => {
    // Reset errors
    formErrors.value = {};
    let isValid = true;

    // Return early if no schema
    if (!props.schema || !Array.isArray(props.schema)) {
        return true; // No valid schema, no validation needed
    }

    // Iterate through each field definition in the schema array
    for (let i = 0; i < props.schema.length; i++) {
        const fieldDef = props.schema[i];

        // Skip if no field definition or no rules
        if (!fieldDef || !fieldDef.rules) {
            continue;
        }

        // Get the field ID (which should be used as the property name in editingItem)
        const fieldId = fieldDef.id;

        // Check if field has 'required' rule
        const isRequired = typeof fieldDef.rules === 'string'
            ? fieldDef.rules.includes('required')
            : Array.isArray(fieldDef.rules)
                ? fieldDef.rules.includes('required')
                : false;

        if (isRequired && fieldId) {
            // Get the value from editingItem using the field id
            const value = editingItem.value[fieldId];

            // Check if value is empty
            if (value === undefined || value === null || value === '') {
                formErrors.value[fieldId] = true;
                isValid = false;
            }

            // For arrays or objects, check if they're empty
            if (Array.isArray(value) && value.length === 0) {
                formErrors.value[fieldId] = true;
                isValid = false;
            }

            if (typeof value === 'object' && value !== null && Object.keys(value).length === 0) {
                formErrors.value[fieldId] = true;
                isValid = false;
            }
        }
    }

    // Show toast if there are errors
    if (!isValid) {
        // toast({
        //     title: 'Validation Failed!',
        //     icon: CircleAlert,
        //     iconClass: "text-red-500",
        //     description: 'Please fill in all required fields highlighted in red',
        //     variant: 'destructive',
        //     duration: 7000,
        // });
    }

    // All required fields are valid
    return isValid;
};

// Remove an item
const removeItem = (index: number) => {
    if (confirm('Are you sure you want to remove this item?')) {
        localItems.value.splice(index, 1);
        emit('update:items', [...localItems.value]);
    }
};

// Move an item up in the list
const moveItemUp = (index: number) => {
    if (index > 0) {
        const item = localItems.value[index];
        localItems.value.splice(index, 1);
        localItems.value.splice(index - 1, 0, item);
        emit('update:items', [...localItems.value]);
    }
};

// Move an item down in the list
const moveItemDown = (index: number) => {
    if (index < localItems.value.length - 1) {
        const item = localItems.value[index];
        localItems.value.splice(index, 1);
        localItems.value.splice(index + 1, 0, item);
        emit('update:items', [...localItems.value]);
    }
};

// Handle file uploads
const handleFileSelected = (fileData: FileData) => {
    itemFiles.value[fileData.fieldName] = fileData;
};

// Get a value for display in the summary
const getDisplayValue = (item: any, fieldId: string): string => {
    const value = item[fieldId];
    if (!value) return '-';
    if (typeof value === 'boolean') return value ? 'Yes' : 'No';
    if (typeof value === 'string') {
        // For HTML content, strip tags
        if (value.startsWith('<')) {
            return value.replace(/<[^>]*>/g, '').substring(0, 50) + (value.length > 50 ? '...' : '');
        }
        return value.substring(0, 50) + (value.length > 50 ? '...' : '');
    }
    return String(value);
};

const formErrors = ref<Record<string, boolean>>({});

// Add computed property to check if the save button should be disabled
// const isButtonDisabled = computed(() => {
//     // Check if there are any required fields that are empty
//     for (const field of props.schema) {
//         if (field.rules && field.rules.includes('required')) {
//             const value = editingItem.value[field.id];
//             // Check if the field is empty based on its type
//             if (value === undefined || value === null || value === '') {
//                 return true;
//             }
//             if (Array.isArray(value) && value.length === 0) {
//                 return true;
//             }
//             if (typeof value === 'object' && value !== null && Object.keys(value).length === 0) {
//                 return true;
//             }
//         }
//     }
//     return false;
// });

// Watch for changes in editingItem to clear errors when a field is corrected
watch(
    () => editingItem.value,
    () => {
        // Clear errors for fields that now have values
        Object.keys(formErrors.value).forEach(fieldId => {
            const field = props.schema.find(f => f.id === fieldId);
            if (field && editingItem.value[fieldId]) {
                const value = editingItem.value[fieldId];
                if (value !== undefined && value !== null && value !== '') {
                    formErrors.value[fieldId] = false;
                }
            }
        });
    },
    { deep: true }
);
</script>

<template>
    <div class="space-y-4">
        <div class="flex items-end justify-between">
            <p class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">{{ label }} ({{ localItems.length }} items)</p>
            <Button
                v-if="!maxItem || (maxItem && localItems.length < maxItem)"
                variant="outline"
                size="sm"
                class="h-10"
                @click="openAddDialog"
            >
                <Plus class="mr-1 h-4 w-4" />
                Add Item
            </Button>
        </div>

        <!-- List table -->
        <div class="rounded-md border">
            <table class="w-full">
                <thead>
                    <tr class="border-b bg-muted/50">
                        <th v-for="item in summaryFields" :key="item.id" class="px-4 py-2 text-left text-sm font-medium">
                            {{ item.label }}
                        </th>
                        <th class="px-4 py-2 text-right text-sm font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="localItems.length > 0">
                        <tr v-for="(item, index) in localItems" :key="index" class="border-b last:border-0">
                            <td v-for="field in summaryFields" :key="field.id" class="px-4 py-2 text-sm">
                                <!-- Display different field types appropriately -->
                                <div v-if="field.type === 'image' && item[field.id]" class="flex items-center">
                                    <img :src="item[field.id]" :alt="field.label" class="h-8 w-8 rounded-md object-cover" />
                                    <span class="ml-2 text-xs text-muted-foreground">Image</span>
                                </div>
                                <div v-else>{{ getDisplayValue(item, field.id) }}</div>
                            </td>
                            <td class="px-4 py-2 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <Button variant="ghost" size="icon" @click="moveItemUp(index)" :disabled="index === 0">
                                        <ArrowUp class="h-4 w-4" />
                                    </Button>
                                    <Button variant="ghost" size="icon" @click="moveItemDown(index)" :disabled="index === localItems.length - 1">
                                        <ArrowDown class="h-4 w-4" />
                                    </Button>
                                    <Button variant="ghost" size="icon" @click="openEditDialog(index)">
                                        <Edit class="h-4 w-4" />
                                    </Button>
                                    <Button variant="ghost" size="icon" @click="removeItem(index)">
                                        <Trash class="h-4 w-4" />
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr v-if="localItems.length === 0" class="border-b last:border-0">
                        <td class="px-4 py-2 text-sm" :colspan="summaryFields.length + 1">
                            <p class="py-4 text-center text-muted-foreground">No items added yet.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Add/Edit Dialog -->
        <Dialog :open="isDialogOpen" @update:open="(value) => !value && closeDialog()">
            <DialogContent class="max-w-2xl">
                <DialogHeader>
                    <DialogTitle>{{ editingItemIndex === null ? 'Add New Item' : 'Edit Item' }}</DialogTitle>
                    <DialogDescription> Fill in the details for this item. All fields marked with * are required. </DialogDescription>
                </DialogHeader>

                <div class="grid max-h-[60vh] gap-6 overflow-y-auto px-2 py-4">
                    <template v-for="item in schema" :key="item.id">
                        <!-- Text Field -->
                        <div v-if="item.type === 'text'" class="space-y-2">
                            <Label :for="`dialog-${item.id}`" :class="{ 'text-destructive': formErrors[item.id] }">
                                {{ item.label }}
                                <span v-if="item.rules && item.rules.includes('required')" class="text-destructive">*</span>
                            </Label>
                            <Input :id="`dialog-${item.id}`" v-model="editingItem[item.id]" :placeholder="item.label" :class="{ 'border-destructive': formErrors[item.id] }" />
                            <p v-if="formErrors[item.id]" class="text-sm text-destructive">This field is required</p>
                            <p v-else-if="item.description" class="text-sm text-muted-foreground">{{ item.description }}</p>
                        </div>

                        <!-- Textarea Field -->
                        <div v-if="item.type === 'textarea'" class="space-y-2">
                            <Label :for="`dialog-${item.id}`" :class="{ 'text-destructive': formErrors[item.id] }">
                                {{ item.label }}
                                <span v-if="item.rules && item.rules.includes('required')" class="text-destructive">*</span>
                            </Label>
                            <Textarea :id="`dialog-${item.id}`" v-model="editingItem[item.id]" :placeholder="item.label" rows="4" :class="{ 'border-destructive': formErrors[item.id] }" />
                            <p v-if="formErrors[item.id]" class="text-sm text-destructive">This field is required</p>
                            <p v-else-if="item.description" class="text-sm text-muted-foreground">{{ item.description }}</p>
                        </div>

                        <!-- HTML Editor Field -->
                        <div v-if="item.type === 'htmleditor'" class="space-y-2">
                            <Label :for="`dialog-${item.id}`" :class="{ 'text-destructive': formErrors[item.id] }">
                                {{ item.label }}
                                <span v-if="item.rules && item.rules.includes('required')" class="text-destructive">*</span>
                            </Label>
                            <div :class="{ 'border border-destructive rounded-md': formErrors[item.id] }">
                                <RichTextEditor :id="`dialog-${item.id}`" v-model="editingItem[item.id]" :placeholder="item.label" />
                            </div>
                            <p v-if="formErrors[item.id]" class="text-sm text-destructive">This field is required</p>
                            <p v-else-if="item.description" class="text-sm text-muted-foreground">{{ item.description }}</p>
                        </div>

                        <!-- URL Field -->
                        <div v-if="item.type === 'url'" class="space-y-2">
                            <Label :for="`dialog-${item.id}`" :class="{ 'text-destructive': formErrors[item.id] }">
                                {{ item.label }}
                                <span v-if="item.rules && item.rules.includes('required')" class="text-destructive">*</span>
                            </Label>
                            <Input type="url" :id="`dialog-${item.id}`" v-model="editingItem[item.id]" placeholder="https://example.com" :class="{ 'border-destructive': formErrors[item.id] }" />
                            <p v-if="formErrors[item.id]" class="text-sm text-destructive">This field is required</p>
                            <p v-else-if="item.description" class="text-sm text-muted-foreground">{{ item.description }}</p>
                        </div>

                        <!-- Checkbox Field -->
                        <div v-if="item.type === 'checkbox'" class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <Checkbox :id="`dialog-${item.id}`" v-model="editingItem[item.id]" :class="{ 'border-destructive': formErrors[item.id] }" />
                                <Label
                                    :for="`dialog-${item.id}`"
                                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-50"
                                >
                                    {{ item.label }}
                                    <span v-if="item.rules && item.rules.includes('required')" class="text-destructive">*</span>
                                </Label>
                            </div>
                            <p v-if="formErrors[item.id]" class="ml-6 text-sm text-destructive">This field is required</p>
                            <p v-else-if="item.description" class="ml-6 text-sm text-muted-foreground">{{ item.description }}</p>
                        </div>

                        <!-- Image Upload Field -->
                        <div v-if="item.type === 'image' || item.type === 'video'" class="space-y-2">
                            <!-- <Label :class="{ 'text-destructive': formErrors[item.id] }">
                                {{ item.label }}
                                <span v-if="item.rules && item.rules.includes('required')" class="text-destructive">*</span>
                            </Label> -->
                            <!-- <div :class="{ 'border border-destructive rounded-md p-1': formErrors[item.id] }"> -->
                                <ImageUploader
                                    accept="image/*, video/*"
                                    :type="item.type"
                                    :label="item.label"
                                    :model-value="editingItem[item.id] || ''"
                                    @update:model-value="(value) => (editingItem[item.id] = value)"
                                    @file-selected="handleFileSelected"
                                    :error="!formErrors[item.id] ? false : item.description"
                                />
                            <!-- </div> -->
                            <!-- <p v-if="formErrors[item.id]" class="text-sm text-destructive">This field is required</p> -->
                            <p v-if="!formErrors[item.id] && item.description" class="text-sm text-muted-foreground">{{ item.description }}</p>
                        </div>
                    </template>
                </div>

                <DialogFooter>
                    <Button variant="outline" @click="closeDialog">Cancel</Button>
                    <!-- Optional to submit button `:disabled="isButtonDisabled"` -->
                    <Button type="submit"  @click="saveItem">Save</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </div>
</template>
