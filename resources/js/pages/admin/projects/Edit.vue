<script setup lang="ts">
import AppMainContent from '@/components/AppMainContent.vue'
import { Button } from '@/components/ui/button'
import { Toaster } from '@/components/ui/sonner'
import AppLayout from '@/layouts/AppLayout.vue'
import { router, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import 'vue-sonner/style.css'
import { toast } from 'vue-sonner'
import FormSection from './components/FormSection.vue'

const props = defineProps({
  item: Object,
  projectStatuses: Array,
  businessTypes: Array,
  paymentTypes: Array,
  paymentStatuses: Array,
  paymentScheduleStatuses: Array,
  agents: Array,
})

const breadcrumbs = [
  {
    title: 'Projects',
    href: route('admin.projects.index'),
  },
  {
    title: 'Edit',
    href: route('admin.projects.edit', { project: props.item?.id }),
  },
]

const form = useForm({
  project_code: '',
  client_name: '',
  amount: '',
  status: '',
  contact_person: '',
  province: '',
  town: '',
  barangay: '',
  business_type: '',
  contact_number: '',
  fb_name: '',
  project_acquired: '',
  start_date: '',
  end_date: '',
  tech_warranty: '',
  hardware_warranty: '',
  expenses: [],
  project_detail: '',
  user_id: '',
  payment_details: {
    total_amount: '',
    payment_type: '',
    number_installments: '',
    status: '',
    amount_paid: '',
    balance: '',
    date_payment: '',
    remarks: '',
    payment_schedules: [],
  },
})

const isLoading = ref(false)

watch(
  () => props.item,
  (newProject) => {
    if (newProject && Object.keys(newProject).length > 0) {
      const formatDate = (dateStr) => {
        if (!dateStr) return null
        return new Date(dateStr).toLocaleDateString('en-CA')
      }

      form.project_code = newProject.project_code
      form.client_name = newProject.client_name
      form.amount = newProject.amount
      form.status = newProject.status
      form.contact_person = newProject.contact_person
      form.province = newProject.province
      form.town = newProject.town
      form.barangay = newProject.barangay
      form.business_type = newProject.business_type
      form.contact_number = newProject.contact_number
      form.fb_name = newProject.fb_name
      form.project_acquired = formatDate(newProject.project_acquired)
      form.start_date = formatDate(newProject.start_date)
      form.end_date = formatDate(newProject.end_date)
      form.tech_warranty = formatDate(newProject.tech_warranty)
      form.hardware_warranty = formatDate(newProject.hardware_warranty)
      form.expenses = newProject.expenses
      form.project_detail = newProject.project_detail
      form.user_id = newProject.user_id

      if (newProject.payment_details) {
        form.payment_details.total_amount = newProject.payment_details.total_amount
        form.payment_details.payment_type = newProject.payment_details.payment_type
        form.payment_details.number_installments = newProject.payment_details.number_installments
        form.payment_details.status = newProject.payment_details.status
        form.payment_details.amount_paid = newProject.payment_details.amount_paid
        form.payment_details.balance = newProject.payment_details.balance
        form.payment_details.date_payment = formatDate(newProject.payment_details.date_payment)
        form.payment_details.remarks = newProject.payment_details.remarks
        form.payment_details.payment_schedules = newProject.payment_details.payment_schedules.map(
          (schedule) => ({
            ...schedule,
            schedule_date: formatDate(schedule.schedule_date),
          }),
        )
      }
    }
  },
  { immediate: true, deep: true },
)
const submit = () => {
  isLoading.value = true
  form.post(route('admin.projects.update', { project: props.item?.id }), {
    onSuccess: () => {
      isLoading.value = false
      toast.success('Project successfully updated.')
    },
    onError: () => {
      isLoading.value = false
      toast.error('Something went wrong!')
    },
  })
}

const cancel = () => {
  router.get(route('admin.projects.index'))
}
</script>

<template>
  <Toaster :expand="true" richColors />
  <AppLayout>
    <AppMainContent :breadcrumbs="breadcrumbs" title="Edit Project">
      <div class="flex-1 overflow-y-auto p-6">
        <div class="flex justify-center">
          <div class="w-full max-w-4xl rounded border bg-white p-6 shadow">
            <FormSection
              :form="form"
              :project-statuses="projectStatuses"
              :business-types="businessTypes"
              :payment-types="paymentTypes"
              :payment-statuses="paymentStatuses"
              :payment-schedule-statuses="paymentScheduleStatuses"
              :agents="agents"
              :is-edit="true"
            />

            <!-- Sticky button container -->
            <div class="sticky bottom-0 mt-6 flex justify-end space-x-3 border-t bg-white p-4">
              <Button variant="outline" @click="cancel()">
                Cancel
              </Button>
              <Button variant="default" @click="submit()">
                Submit
              </Button>
            </div>
          </div>
        </div>
      </div>
    </AppMainContent>
  </AppLayout>
</template>
