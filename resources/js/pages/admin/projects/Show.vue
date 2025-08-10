<script setup lang="ts">
import AppMainContent from '@/components/AppMainContent.vue';
import BaseNavTabs from '@/components/base/BaseNavTabs.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { useDateFormat } from '@/composables/useDateFormat';
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { ChevronDown } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Toaster, toast } from 'vue-sonner'
import 'vue-sonner/style.css'
import LoadingPage from '@/components/shared/LoadingPage.vue';

const isLoading = ref(false);

const props = defineProps<{
    project: any;
    projectStatuses: Object;
}>();

const project = computed(() => props.project);
const { formatDate } = useDateFormat();

const breadcrumbs = [
    {
        title: 'Projects',
        href: route('admin.projects.index'),
    },
    {
        title: project.value.project_code,
    },
];

const TABS = [{ label: 'Information', value: 'information' }];

const updateStatus = (status: number) => {
    isLoading.value = true;
    router.patch(
        route('admin.projects.update-status', { project: project.value.id }),
        { status },
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Project status updated successfully.');
                isLoading.value = false;
            },
            onError: () => {
                isLoading.value = false;
            },
        },
    );
};
</script>

<template>
    <Toaster :expand="true" />
    <LoadingPage :is-loading="isLoading" />
    <AppLayout title="Project Details">
        <AppMainContent :breadcrumbs="breadcrumbs" :title="project.client_name">
            <template #button>
                <div class="flex items-center space-x-2">
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button variant="outline" class="ml-auto">
                                {{ project.status.title }}
                                <ChevronDown class="ml-2 h-4 w-4" />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuItem v-for="status in projectStatuses" :key="status.value" @click="updateStatus(status.id)">
                                {{ status.label }}
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </template>

            <BaseNavTabs :tabs="TABS" model-value="information" />

            <div class="mx-auto max-w-7xl space-y-8 p-8">
                <!-- Project Details -->
                <div class="rounded-lg border bg-white p-6 shadow-sm">
                    <div class="mb-6 flex items-center justify-between border-b pb-3">
                        <div class="space-y-2">
                             <p class="leading-none font-semibold text-gray-900">Project Details</p>
                            <p class="text-muted-foreground text-sm">Information related to Project Details</p>
                        </div>
                        <Badge
                            :class="`border-${project.status.color}-500 bg-${project.status.color}-50 text-${project.status.color}-700`"
                            variant="outline"
                        >
                            {{ project.status.title }}
                        </Badge>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Project Code</p>
                            <p class="text-gray-900">{{ project.project_code }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Client Name</p>
                            <p class="text-gray-900">{{ project.client_name }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Amount</p>
                            <p class="font-semibold text-gray-900">{{ project.amount }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Contact Person</p>
                            <p class="text-gray-900">{{ project.contact_person }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Complete Address</p>
                            <p class="text-gray-900">{{ project.complete_address }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Contact Number</p>
                            <p class="text-gray-900">{{ project.contact_number }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Agent</p>
                            <p class="text-gray-900">{{ project.agent }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Project Acquired</p>
                            <p class="text-gray-900">{{ project.project_acquired }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Start Date</p>
                            <p class="text-gray-900">{{ formatDate(project.start_date).formatted }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">End Date</p>
                            <p class="text-gray-900">{{ formatDate(project.end_date).formatted }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Business Type</p>
                            <p class="text-gray-900" v-html="project.business_type.title" />
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Created At</p>
                            <p class="text-gray-900">{{ formatDate(project.created_at).formatted }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div v-if="project.payment_detail" class="rounded-lg border bg-white p-6 shadow-sm">
                    <div class="mb-6 flex items-center justify-between border-b pb-3">
                        <div class="space-y-2">
                             <p class="leading-none font-semibold text-gray-900">Payment Details</p>
                            <p class="text-muted-foreground text-sm">Information related to Payment Details</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Total Amount</p>
                            <p class="text-lg font-semibold text-gray-900">{{ project.payment_detail.total_amount }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Payment Type</p>
                            <p class="text-gray-900" v-html="project.payment_detail.payment_type_information.title" />
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Status</p>
                            <p class="text-gray-900">
                                 <Badge
                                            :class="`border-${project.payment_detail.status_information.color}-500 bg-${project.payment_detail.status_information.color}-50 text-${project.payment_detail.status_information.color}-700`"
                                            variant="outline"
                                        >
                                            {{ project.payment_detail.status_information.title }}
                                </Badge>
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Number of Installments</p>
                            <p class="text-gray-900">{{ project.payment_detail.number_installments }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Amount Paid</p>
                            <p class="font-semibold text-gray-900">{{ project.payment_detail.amount_paid }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Balance</p>
                            <p class="font-semibold text-gray-900">{{ project.payment_detail.balance }}</p>
                        </div>
                        <div class="space-y-1 md:col-span-2 lg:col-span-3 xl:col-span-4">
                            <p class="text-sm font-medium text-gray-500">Remarks</p>
                            <p class="text-gray-900">{{ project.payment_detail.remarks }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-medium text-gray-500">Date of Payment</p>
                            <p class="text-gray-900">{{ formatDate(project.payment_detail.date_payment).formatted }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Schedules -->
                <div
                    v-if="project.payment_detail && project.payment_detail.payment_schedules.length"
                    class="rounded-lg border bg-white p-6 shadow-sm"
                >
                     <div class="mb-6 flex items-center justify-between border-b pb-3">
                        <div class="space-y-2">
                             <p class="leading-none font-semibold text-gray-900">Payment Schedules</p>
                            <p class="text-muted-foreground text-sm">Information related to Payment Schedules</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Due Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Due Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Amount Paid</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Date of Payment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr
                                    v-for="schedule in project.payment_detail.payment_schedules"
                                    :key="schedule.id"
                                    class="transition-colors duration-200 hover:bg-gray-50"
                                >
                                    <td class="px-6 py-4 text-sm font-semibold whitespace-nowrap text-gray-900">
                                        {{ formatDate(schedule.due_date).formatted }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold whitespace-nowrap text-gray-900">
                                        {{ schedule.due_amount }}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900">
                                        <Badge
                                            :class="`border-${schedule.status_information.color}-500 bg-${schedule.status_information.color}-50 text-${schedule.status_information.color}-700`"
                                            variant="outline"
                                        >
                                            {{ schedule.status_information.title }}
                                        </Badge>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold whitespace-nowrap text-gray-900">
                                        {{ schedule.amount_paid }}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-900">
                                        {{ formatDate(schedule.date_payment).formatted }}
                                    </td>
                                    <td class="max-w-xs truncate px-6 py-4 text-sm text-gray-900" :title="schedule.remarks">
                                        {{ schedule.remarks }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AppMainContent>
    </AppLayout>
</template>
