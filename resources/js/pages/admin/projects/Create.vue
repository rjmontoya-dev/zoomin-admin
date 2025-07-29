<script setup lang="ts">
import AppMainContent from '@/components/AppMainContent.vue'
import { Button } from '@/components/ui/button'
import { Toaster } from '@/components/ui/sonner'
import AppLayout from '@/layouts/AppLayout.vue'
import { router, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import 'vue-sonner/style.css'
import { toast } from 'vue-sonner'
import FormSection from './components/FormSection.vue'

const props = defineProps({
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
    title: 'Create',
    href: route('admin.projects.create'),
  },
]

const form = useForm({
  project_code: '',
  client_name: '',
  amount: null,
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
  expenses: null,
  project_detail: '', 
  user_id: '',
  payment_details: {
    total_amount: null,
    payment_type: '',
    number_installments: null,
    status: '',
    amount_paid: null,
    balance: null,
    date_payment: '',
    remarks: '',
    payment_schedules: [],
  },
})

const isLoading = ref(false)
const convertDate = () => { 
    if (form.project_acquired) {
        form.project_acquired = new Date(form.project_acquired).toLocaleDateString('en-CA');
    }

    if (form.start_date) {
        form.start_date = new Date(form.start_date).toLocaleDateString('en-CA');
    }

     if (form.end_date) {
        form.end_date = new Date(form.end_date).toLocaleDateString('en-CA');
    }
     if (form.tech_warranty) {
        form.tech_warranty = new Date(form.tech_warranty).toLocaleDateString('en-CA');
    }
     if (form.hardware_warranty) {
        form.hardware_warranty = new Date(form.hardware_warranty).toLocaleDateString('en-CA');
    }
}
const submit = () => {
  isLoading.value = true
  form.post(route('admin.projects.store'), {
    onSuccess: () => {
      isLoading.value = false
      toast.success('Project successfully created.')
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
    <AppMainContent :breadcrumbs="breadcrumbs" title="Create Project">
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
