<script setup lang="ts">
import FileUploader from '@/components/shared/FileUploader.vue';
import ImageUploader from '@/components/shared/ImageUploader.vue';
import ListTable from '@/components/shared/ListTable.vue';
import RichTextEditor from '@/components/shared/RichTextEditor.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import AppMainContent from '@/components/AppMainContent.vue';
import { type BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/vue3';
import pickBy from 'lodash/pickBy';
import { Save } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';
import BaseNavTabs from '@/components/base/BaseNavTabs.vue';


// Define the schema-related interfaces
interface SchemaItem {
    id: string;
    type: string;
    label: string;
    description?: string;
    rules?: string;
    items?: SchemaItem[];
    summary_field?: boolean;
}

interface SchemaSection {
    id: string;
    label: string;
    description?: string;
    items: SchemaItem[];
}

interface Schema {
    slug: string;
    label: string;
    show_metadata: boolean;
    sections: SchemaSection[];
}

// Define the Page type
interface Page {
    id: number;
    slug: string;
    label: string;
    show_metadata: number;
    content: Record<string, any> | null;
    title: string | null;
    description: string | null;
    keywords: string | null;
    robots_follow: string | null;
    robots_index: string | null;
    og_image_path: string | null;
    created_at: string;
    updated_at: string;
}

interface FileData {
    fieldName: string;
    file: File | null;
    base64: string;
}

const props = defineProps<{
    page: Page;
    schema: Schema;
    selectedTab?: string;
}>();
console.log(props.page);
// Form state management
const form = ref<Record<string, any>>({
    title: props.page.title || '',
    description: props.page.description || '',
    keywords: props.page.keywords || '',
    robots_follow: props.page.robots_follow || 'follow',
    robots_index: props.page.robots_index || 'index',
    og_image_path: props.page.og_image_path || '',
    _method: 'POST',
});

const formFiles = ref<Record<string, FileData>>({});
const formProcessing = ref(false);
const formErrors = ref<Record<string, string>>({});

// Initialize form with content values from the page
onMounted(() => {
    if (props.page.content) {
        initializeFormWithContent();
    }
});

// Initialize the form with values from the page content
const initializeFormWithContent = () => {
    const content = props.page.content || {};

    props.schema.sections.forEach((section) => {
        section.items.forEach((item) => {
            const fieldId = getFieldId(section.id, item.id);

            if (item.type === 'list_table') {
                // Handle list table data initialization
                form.value[fieldId] = content[fieldId] || [];
            } else {
                // Handle other field types
                form.value[fieldId] = content[fieldId] !== undefined ? content[fieldId] : '';
            }
        });
    });
};

// Setup active tab state
const activeTab = ref(props.selectedTab || props.schema.sections[0]?.id || '');

const pageTitle: string = props.page.label;

// For breadcrumb navigation
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pages',
        href: route('admin.cms.index'),
    },
    {
        title: props.page.label,
        href: '#',
    },
];

// Compute all available tabs including sections and SEO if applicable
const tabs = computed(() => {
    const sectionTabs = props.schema.sections.map((section) => ({
        id: section.id,
        label: section.label,
    }));

    if (props.page.show_metadata === 1) {
        sectionTabs.push({
            id: 'seo-metadata',
            label: 'SEO Metadata',
        });
    }

    return sectionTabs;
});


// Method to change tab
const selectTab = (tabId: string) => {
    router.get(route('admin.cms.edit', props.page.id), pickBy({ tab: tabId }), {
        preserveState: true,
        onSuccess: () => {
            activeTab.value = tabId;
        },
    });
};

// Method to handle form submission
const submit = () => {
    formProcessing.value = true;

    // Create form data for submission
    const formData = new FormData();

    // Add regular form fields
    Object.keys(form.value).forEach((key) => {
        // Handle array data (for list_table)
        if (Array.isArray(form.value[key])) {
            formData.append(key, JSON.stringify(form.value[key]));
        } else {
            formData.append(key, form.value[key] || '');
        }
    });

    // Add file uploads
    Object.values(formFiles.value).forEach((fileData) => {
        if (fileData.file) {
            formData.append(fileData.fieldName, fileData.file);
        }
    });

    router.post(route('admin.cms.update', props.page.id), formData, {
        onSuccess: () => {
            formProcessing.value = false;
        },
        onError: (errors) => {
            formErrors.value = errors;
            formProcessing.value = false;
        },
        onFinish: () => {
            formProcessing.value = false;
        },
    });
};

// Method to handle file uploads
const handleFileSelected = (fileData: FileData) => {
    if (fileData.file === null && fileData.base64 === '') {
        // File was cleared - remove it from formFiles
        delete formFiles.value[fileData.fieldName];
        form.value[fileData.fieldName] = '';
    } else {
        // File was selected
        formFiles.value[fileData.fieldName] = fileData;
        form.value[fileData.fieldName] = fileData.file ? fileData.fieldName : fileData.base64;
    }
};

// Method to handle list updates
const updateListData = (fieldId: string, data: any[]) => {
    form.value[fieldId] = data;
};

// Helper method to get form field ID
const getFieldId = (sectionId: string, itemId: string) => {
    return `${sectionId}_${itemId}`;
};

// Helper to check if a field has an error
const hasError = (fieldId: string) => {
    return formErrors.value[fieldId] !== undefined;
};

// Helper to get error message for a field
const getErrorMessage = (fieldId: string) => {
    return formErrors.value[fieldId];
};
</script>

<template>
    <AppLayout>
        <AppMainContent :breadcrumbs="breadcrumbs" :title="pageTitle" class="pb-40">
            <template #button>
               <div class="flex space-x-2 self-start">
                        <Button @click="submit" :disabled="formProcessing" size="lg">
                            <Save class="h-4 w-4" />
                            <span v-if="formProcessing">Saving...</span>
                            <span v-else>Save Changes</span>
                        </Button>
                    </div>
            </template>
            <BaseNavTabs v-if="tabs && tabs.length > 0" :tabs="tabs" v-model="activeTab" @update:model-value="value => console.log(value)" />
        <div class="flex flex-1 flex-col gap-6">
            <!-- Tab Navigation -->
            <Tabs v-model="activeTab" class="w-full pb-40">
                <!-- Tab Content for Each Section -->
                <template v-for="section in schema.sections" :key="section.id">
                    <TabsContent :value="section.id" class="mt-4 px-6">
                        <Card>
                            <CardHeader>
                                <CardTitle>{{ section.label }}</CardTitle>
                                <CardDescription v-if="section.description">
                                    {{ section.description }}
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <!-- Render different field types -->
                                    <template v-for="item in section.items" :key="`${section.id}_${item.id}`">
                                        <!-- Text Field -->
                                        <div
                                            v-if="item.type === 'text'"
                                            class="space-y-2 md:col-span-2"
                                            :class="{ 'md:col-span-2': item.id === 'title' }"
                                        >
                                            <Label :for="getFieldId(section.id, item.id)">{{ item.label }}</Label>
                                            <Input
                                                :id="getFieldId(section.id, item.id)"
                                                v-model="form[getFieldId(section.id, item.id)]"
                                                :placeholder="item.label"
                                                :class="{ 'border-destructive': hasError(getFieldId(section.id, item.id)) }"
                                            />
                                            <p v-if="item.description" class="text-muted-foreground text-sm">{{ item.description }}</p>
                                            <p v-if="hasError(getFieldId(section.id, item.id))" class="text-destructive text-sm">
                                                {{ getErrorMessage(getFieldId(section.id, item.id)) }}
                                            </p>
                                        </div>

                                        <!-- Textarea Field -->
                                        <div v-if="item.type === 'textarea'" class="col-span-full space-y-2">
                                            <Label :for="getFieldId(section.id, item.id)">{{ item.label }}</Label>
                                            <Textarea
                                                :id="getFieldId(section.id, item.id)"
                                                v-model="form[getFieldId(section.id, item.id)]"
                                                :placeholder="item.label"
                                                rows="4"
                                                :class="{ 'border-destructive': hasError(getFieldId(section.id, item.id)) }"
                                            />
                                            <p v-if="item.description" class="text-muted-foreground text-sm">{{ item.description }}</p>
                                            <p v-if="hasError(getFieldId(section.id, item.id))" class="text-destructive text-sm">
                                                {{ getErrorMessage(getFieldId(section.id, item.id)) }}
                                            </p>
                                        </div>

                                        <!-- HTML Editor Field -->
                                        <div v-if="item.type === 'htmleditor'" class="col-span-full space-y-2">
                                            <Label :for="getFieldId(section.id, item.id)">{{ item.label }}</Label>
                                            <RichTextEditor
                                                :id="getFieldId(section.id, item.id)"
                                                v-model="form[getFieldId(section.id, item.id)]"
                                                :placeholder="item.label"
                                                height="500px"
                                            />
                                            <p v-if="item.description" class="text-muted-foreground text-sm">{{ item.description }}</p>
                                            <p v-if="hasError(getFieldId(section.id, item.id))" class="text-destructive text-sm">
                                                {{ getErrorMessage(getFieldId(section.id, item.id)) }}
                                            </p>
                                        </div>

                                        <!-- URL Field -->
                                        <div v-if="item.type === 'url'" class="space-y-2">
                                            <Label :for="getFieldId(section.id, item.id)">{{ item.label }}</Label>
                                            <Input
                                                type="url"
                                                :id="getFieldId(section.id, item.id)"
                                                v-model="form[getFieldId(section.id, item.id)]"
                                                :placeholder="'https://example.com'"
                                                :class="{ 'border-destructive': hasError(getFieldId(section.id, item.id)) }"
                                            />
                                            <p v-if="item.description" class="text-muted-foreground text-sm">{{ item.description }}</p>
                                            <p v-if="hasError(getFieldId(section.id, item.id))" class="text-destructive text-sm">
                                                {{ getErrorMessage(getFieldId(section.id, item.id)) }}
                                            </p>
                                        </div>

                                        <!-- Checkbox Field -->
                                        <div v-if="item.type === 'checkbox'" class="space-y-2">
                                            <div class="flex items-center space-x-2">
                                                <Checkbox :id="getFieldId(section.id, item.id)" v-model="form[getFieldId(section.id, item.id)]" />
                                                <Label
                                                    :for="getFieldId(section.id, item.id)"
                                                    class="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                                >
                                                    {{ item.label }}
                                                </Label>
                                            </div>
                                            <p v-if="item.description" class="text-muted-foreground ml-6 text-sm">{{ item.description }}</p>
                                            <p v-if="hasError(getFieldId(section.id, item.id))" class="text-destructive text-sm">
                                                {{ getErrorMessage(getFieldId(section.id, item.id)) }}
                                            </p>
                                        </div>

                                        <!-- Image Upload Field -->
                                        <div v-if="item.type === 'image'" class="col-span-full space-y-2">
                                            <ImageUploader
                                                :label="item.label"
                                                :model-value="form[getFieldId(section.id, item.id)] || ''"
                                                :error="formErrors[getFieldId(section.id, item.id)]"
                                                @update:model-value="(value) => (form[getFieldId(section.id, item.id)] = value)"
                                                @file-selected="handleFileSelected"
                                            />
                                            <p v-if="item.description" class="text-muted-foreground text-sm">{{ item.description }}</p>
                                        </div>

                                        <!-- Video Upload Field -->
                                        <div v-if="item.type === 'video'" class="col-span-full space-y-2">
                                            <FileUploader
                                                :model-value="form[getFieldId(section.id, item.id)] || ''"
                                                :label="item.label"
                                                :error="formErrors[getFieldId(section.id, item.id)]"
                                                @update:model-value="(value) => (form[getFieldId(section.id, item.id)] = value)"
                                                @file-selected="handleFileSelected"
                                                type="video"
                                            />

                                            <p v-if="item.description" class="text-muted-foreground text-sm">{{ item.description }}</p>
                                        </div>

                                        <!-- File Upload Field -->
                                        <div v-if="item.type === 'file'" class="col-span-full space-y-2">
                                            <FileUploader
                                                :model-value="form[getFieldId(section.id, item.id)] || ''"
                                                :label="item.label"
                                                :error="formErrors[getFieldId(section.id, item.id)]"
                                                @update:model-value="(value) => (form[getFieldId(section.id, item.id)] = value)"
                                                @file-selected="handleFileSelected"
                                                type="document"
                                            />

                                            <p v-if="item.description" class="text-muted-foreground text-sm">{{ item.description }}</p>
                                        </div>

                                        <!-- List Table Field -->
                                        <div v-if="item.type === 'list_table'" class="col-span-full space-y-4">
                                            <ListTable
                                                :field-id="getFieldId(section.id, item.id)"
                                                :items="form[getFieldId(section.id, item.id)] || []"
                                                :schema="item.items || []"
                                                :label="item.label"
                                                @update:items="updateListData(getFieldId(section.id, item.id), $event)"
                                            />
                                            <p v-if="item.description" class="text-muted-foreground text-sm">{{ item.description }}</p>
                                        </div>
                                    </template>
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>
                </template>

                <!-- SEO Metadata Tab -->
                <TabsContent v-if="page.show_metadata === 1" value="seo-metadata" class="mt-4 px-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>SEO Metadata</CardTitle>
                            <CardDescription> Enter your SEO data such as title, description, keywords, and image. </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-1 gap-6">
                                <div class="space-y-2">
                                    <Label for="seo_title">Title</Label>
                                    <Input
                                        id="seo_title"
                                        v-model="form.title"
                                        placeholder="SEO Title"
                                        :maxlength="60"
                                        :class="{ 'border-destructive': hasError('title') }"
                                    />
                                    <p class="text-muted-foreground text-sm">50-60 characters max</p>
                                    <p v-if="hasError('title')" class="text-destructive text-sm">
                                        {{ getErrorMessage('title') }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="seo_description">Description</Label>
                                    <Textarea
                                        id="seo_description"
                                        v-model="form.description"
                                        placeholder="SEO Description"
                                        rows="3"
                                        :maxlength="160"
                                        :class="{ 'border-destructive': hasError('description') }"
                                    />
                                    <p class="text-muted-foreground text-sm">150-160 characters recommended for search engines</p>
                                    <p v-if="hasError('description')" class="text-destructive text-sm">
                                        {{ getErrorMessage('description') }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="seo_keywords">Keywords</Label>
                                    <Textarea
                                        id="seo_keywords"
                                        v-model="form.keywords"
                                        placeholder="SEO Keywords"
                                        rows="2"
                                        :class="{ 'border-destructive': hasError('keywords') }"
                                    />
                                    <p class="text-muted-foreground text-sm">Comma-separated values</p>
                                    <p v-if="hasError('keywords')" class="text-destructive text-sm">
                                        {{ getErrorMessage('keywords') }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <ImageUploader
                                        label="OG Image"
                                        :model-value="form.og_image_path || ''"
                                        :error="formErrors.og_image_path"
                                        @update:model-value="(value) => (form.og_image_path = value)"
                                        @file-selected="handleFileSelected"
                                    />
                                    <p class="text-muted-foreground text-sm">Max file size: 2MB | Recommended dimension: 1200px x 630px</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>
            </Tabs>
        </div>
        </AppMainContent>
    </AppLayout>
</template>
