<script setup lang="ts">
import AppMainContent from '@/components/AppMainContent.vue';
import BaseNavTabs from '@/components/base/BaseNavTabs.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { ArrowLeft, Mail, Building, DollarSign, Calendar, MessageSquare } from 'lucide-vue-next';
import { defineProps, ref } from 'vue';
import { useDateFormat } from '@/composables/useDateFormat';

interface Inquiry {
    id: number;
    name: string;
    email: string;
    company: string;
    service: string;
    budget: string;
    message: string;
    created_at: string;
    updated_at: string;
}

interface Tab {
    label: string;
    value: string;
    tabCount?: number;
}

const props = defineProps<{
    item: Inquiry;
}>()

const { formatDate } = useDateFormat();

const breadcrumbs = [
    {
        title: 'Inquiries',
        href: route('admin.inquiry.index'),
    },
    {
        title: 'View Inquiry',
        href: '#',
    },
];

const TABS: Tab[] = [{ label: 'Details', value: 'details' }];

const activeTab = ref(TABS[0].value);

const goBack = () => {
    router.get(route('admin.inquiry.index'));
};

const getServiceBadgeColor = (service: string) => {
    const colors: { [key: string]: string } = {
        'web-development': 'bg-blue-50 text-blue-700 border-blue-200',
        'mobile-app': 'bg-purple-50 text-purple-700 border-purple-200',
        'consulting': 'bg-green-50 text-green-700 border-green-200',
        'other': 'bg-gray-50 text-gray-700 border-gray-200',
    };
    return colors[service] || 'bg-gray-50 text-gray-700 border-gray-200';
};

const getBudgetBadgeColor = (budget: string) => {
    const colors: { [key: string]: string } = {
        'under-5k': 'bg-yellow-50 text-yellow-700 border-yellow-200',
        '5k-10k': 'bg-orange-50 text-orange-700 border-orange-200',
        '10k-25k': 'bg-red-50 text-red-700 border-red-200',
        'over-25k': 'bg-emerald-50 text-emerald-700 border-emerald-200',
    };
    return colors[budget] || 'bg-gray-50 text-gray-700 border-gray-200';
};
</script>

<template>
    <AppLayout>
        <AppMainContent :breadcrumbs="breadcrumbs" title="View Inquiry">
            <template #button>
                <div class="flex space-x-2 self-start">
                    <Button variant="outline" @click="goBack()">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Inquiries
                    </Button>
                </div>
            </template>
            
            <BaseNavTabs v-if="TABS && TABS.length > 0" :tabs="TABS" v-model="activeTab" @update:model-value="(value) => (activeTab = value)" />
            
            <div class="flex-1 overflow-y-auto p-6">
                <div class="mx-auto max-w-4xl space-y-6">
                    <!-- Contact Information Card -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Mail class="h-5 w-5" />
                                Contact Information
                            </CardTitle>
                            <CardDescription>
                                Details about the person who submitted this inquiry
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Full Name</label>
                                    <p class="text-lg font-semibold text-gray-900">{{ item.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Email Address</label>
                                    <p class="text-lg">
                                        <a :href="`mailto:${item.email}`" class="text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ item.email }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Company</label>
                                    <p class="text-lg flex items-center gap-2">
                                        <Building class="h-4 w-4 text-gray-400" />
                                        {{ item.company }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Submitted On</label>
                                    <p class="text-lg flex items-center gap-2">
                                        <Calendar class="h-4 w-4 text-gray-400" />
                                        {{ formatDate(item.created_at).formatted }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Project Details Card -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <DollarSign class="h-5 w-5" />
                                Project Details
                            </CardTitle>
                            <CardDescription>
                                Information about the requested service and budget
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Service Requested</label>
                                    <div class="mt-1">
                                        <Badge variant="outline" :class="getServiceBadgeColor(item.service)">
                                            {{ item.service }}
                                        </Badge>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Budget Range</label>
                                    <div class="mt-1">
                                        <Badge variant="outline" :class="getBudgetBadgeColor(item.budget)">
                                            {{ item.budget }}
                                        </Badge>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Message Card -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <MessageSquare class="h-5 w-5" />
                                Message
                            </CardTitle>
                            <CardDescription>
                                Additional details provided by the client
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ item.message }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Metadata Card -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Inquiry Metadata</CardTitle>
                            <CardDescription>
                                System information about this inquiry
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <label class="font-medium text-gray-500">Inquiry ID</label>
                                    <p class="text-gray-900">#{{ item.id }}</p>
                                </div>
                                <div>
                                    <label class="font-medium text-gray-500">Last Updated</label>
                                    <p class="text-gray-900">{{ formatDate(item.updated_at).formatted }}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppMainContent>
    </AppLayout>
</template>