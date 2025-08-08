<script setup lang="ts">
import { ref, watch, defineProps, computed } from 'vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { CalendarDate, parseDate } from '@internationalized/date'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Calendar } from '@/components/ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { cn } from '@/lib/utils'
import { useDateFormat } from '@/composables/useDateFormat'
import { Calendar as CalendarIcon } from 'lucide-vue-next'
import { PaymentType } from '@/pages/Enums/PaymentType'
import PaymentScheduleModal from './PaymentScheduleModal.vue'

const { formatDate } = useDateFormat()

const showPaymentScheduleModal = ref(false)

const editingScheduleIndex = ref<number | null>(null)

const props = defineProps({
  form: {
    type: Object,
    required: true,
  },
  projectStatuses: {
    type: Array,
    required: true,
  },
  businessTypes: {
    type: Array,
    required: true,
  },
  paymentTypes: {
    type: Array,
    required: true,
  },
  paymentStatuses: {
    type: Array,
    required: true,
  },
  paymentScheduleStatuses: {
    type: Array,
    required: true,
  },
  agents: {
    type: Array,
    required: true,
  },
})

const addSchedule = (schedule: any) => {
  if (editingScheduleIndex.value !== null) {
    props.form.payment_details.payment_schedules[editingScheduleIndex.value] = schedule
  } else {
    props.form.payment_details.payment_schedules.push(schedule)
  }
  editingScheduleIndex.value = null
  showPaymentScheduleModal.value = false
}

const removeSchedule = (index: number) => {
  props.form.payment_details.payment_schedules.splice(index, 1)
}

const editSchedule = (index: number) => {
  editingScheduleIndex.value = index
  showPaymentScheduleModal.value = true
}

watch(() => props.form.payment_details?.payment_type, (newVal: number) => {
  if (newVal === PaymentType.FULL) {
    props.form.payment_details.number_installments = 0
  }
})


const paymentScheduleErrors = computed(() => {
  const errors: { [key: string]: string } = {}
  if (props.form.errors) {
    for (const key in props.form.errors) {
      if (key.startsWith('payment_details.payment_schedules')) {
        errors[key] = props.form.errors[key]
      }
    }
  }
  return errors
})
</script>

<template>
<div class="grid grid-cols-1 gap-4">
    <PaymentScheduleModal
          :show="showPaymentScheduleModal"
          :payment-schedule-statuses="paymentScheduleStatuses"
          :schedule="editingScheduleIndex !== null ? form.payment_details.payment_schedules[editingScheduleIndex] : null"
          @add-schedule="addSchedule"
          @close="showPaymentScheduleModal = false"
        />
    <Card>
      <CardHeader>
        <CardTitle>Project Details</CardTitle>
        <CardDescription>Fill in the project details.</CardDescription>
      </CardHeader>
      <CardContent class="grid grid-cols-2 gap-4">
        <div class="space-y-2">
          <Label for="project_code">Project Code</Label>
          <Input id="project_code" v-model="form.project_code" type="text" :error="form.errors.project_code" />
        </div>
        <div class="space-y-2">
          <Label for="client_name">Client Name</Label>
          <Input id="client_name" v-model="form.client_name" type="text" :error="form.errors.client_name" />
        </div>
        <div class="space-y-2">
          <Label for="amount">Amount</Label>
          <Input id="amount" v-model="form.amount" type="number" step="0.01" min="0" :error="form.errors.amount" />
        </div>
        <div class="space-y-2">
          <Label for="status">Assigned Agent</Label>
          <Select v-model="form.user_id" class="w-full">
            <SelectTrigger  class="w-full">
              <SelectValue placeholder="Select status" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="(agent, index) in agents" :key="index" :value="agent.id">
                {{ agent.label }}
              </SelectItem>
            </SelectContent>
          </Select>
          <p v-if="form.errors.user_id" class="text-sm text-red-500 mt-1 italic">{{ form.errors.user_id }}</p>
        </div>
        <div class="space-y-2">
          <Label for="contact_person">Contact Person</Label>
          <Input id="contact_person" v-model="form.contact_person" type="text" :error="form.errors.contact_person" />
        </div>
        <div class="space-y-2">
          <Label for="province">Province</Label>
          <Input id="province" v-model="form.province" type="text" :error="form.errors.province" />
        </div>
        <div class="space-y-2">
          <Label for="town">Town</Label>
          <Input id="town" v-model="form.town" type="text" :error="form.errors.town" />
        </div>
        <div class="space-y-2">
          <Label for="barangay">Barangay</Label>
          <Input id="barangay" v-model="form.barangay" type="text" :error="form.errors.barangay" />
        </div>
        <div class="space-y-2">
          <Label for="business_type">Business Type</Label>
          <Select v-model="form.business_type"  class="w-full">
            <SelectTrigger  class="w-full">
              <SelectValue placeholder="Select business type" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="(type, index) in businessTypes" :key="index" :value="type.id">
                {{ type.label }}
              </SelectItem>
            </SelectContent>
          </Select>
          <p v-if="form.errors.business_type" class="text-sm text-red-500 mt-1 italic">{{ form.errors.business_type }}</p>
        </div>
        <div class="space-y-2">
          <Label for="contact_number">Contact Number</Label>
          <Input id="contact_number" v-model="form.contact_number" type="text" :error="form.errors.contact_number" />
        </div>
        <div class="space-y-2">
          <Label for="fb_name">Facebook Name</Label>
          <Input id="fb_name" v-model="form.fb_name" type="text" :error="form.errors.fb_name" />
        </div>
        <div class="space-y-2">
          <Label for="project_acquired">Project Acquired</Label>
          <Input id="project_acquired" v-model="form.project_acquired" type="text" :error="form.errors.project_acquired" />
        </div>
        <div class="space-y-2">
          <Label for="start_date">Start Date</Label>
          <Popover>
            <PopoverTrigger as-child>
              <Button
                :variant="'outline'"
                :class="cn('w-full justify-start text-left font-normal', !form.start_date && 'text-muted-foreground')"
              >
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{ form.start_date ? formatDate(form.start_date).date : 'Pick a date' }}
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar
                :model-value="form.start_date ? parseDate(form.start_date) : undefined"
                @update:model-value="v => form.start_date = v ? v.toString() : null"
              />
            </PopoverContent>
          </Popover>
          <p v-if="form.errors.start_date" class="text-sm text-red-500 mt-1 italic">{{ form.errors.start_date }}</p>
        </div>
        <div class="space-y-2">
          <Label for="end_date">End Date</Label>
          <Popover>
            <PopoverTrigger as-child>
              <Button
                :variant="'outline'"
                :class="cn('w-full justify-start text-left font-normal', !form.end_date && 'text-muted-foreground')"
              >
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{ form.end_date ? formatDate(form.end_date).date : 'Pick a date' }}
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar
                :model-value="form.end_date ? parseDate(form.end_date) : undefined"
                @update:model-value="v => form.end_date = v ? v.toString() : null"
              />
            </PopoverContent>
          </Popover>
          <p v-if="form.errors.end_date" class="text-sm text-red-500 mt-1 italic">{{ form.errors.end_date }}</p>
        </div>
        <div class="space-y-2">
          <Label for="end_date">Tech Warranty</Label>
          <Popover>
            <PopoverTrigger as-child>
              <Button
                :variant="'outline'"
                :class="cn('w-full justify-start text-left font-normal', !form.tech_warranty && 'text-muted-foreground')"
              >
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{ form.tech_warranty ? formatDate(form.tech_warranty).date : 'Pick a date' }}
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar
                :model-value="form.tech_warranty ? parseDate(form.tech_warranty) : undefined"
                @update:model-value="v => form.tech_warranty = v ? v.toString() : null"
              />
            </PopoverContent>
          </Popover>
          <p v-if="form.errors.tech_warranty" class="text-sm text-red-500 mt-1 italic">{{ form.errors.tech_warranty }}</p>
        </div>
        <div class="space-y-2">
          <Label for="end_date">Hardware Warranty</Label>
          <Popover>
            <PopoverTrigger as-child>
              <Button
                :variant="'outline'"
                :class="cn('w-full justify-start text-left font-normal', !form.hardware_warranty && 'text-muted-foreground')"
              >
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{ form.hardware_warranty ? formatDate(form.hardware_warranty).date : 'Pick a date' }}
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar
                :model-value="form.hardware_warranty ? parseDate(form.hardware_warranty) : undefined"
                @update:model-value="v => form.hardware_warranty = v ? v.toString() : null"
              />
            </PopoverContent>
          </Popover>
          <p v-if="form.errors.hardware_warranty" class="text-sm text-red-500 mt-1 italic">{{ form.errors.hardware_warranty }}</p>
        </div>
        <div class="space-y-2">
          <Label for="expenses">Expenses</Label>
          <Input id="expenses" v-model="form.expenses" type="number" :error="form.errors.expenses" />
        </div>
        <div class="space-y-2 col-span-2">
          <Label for="project_detail">Project Detail</Label>
          <Textarea id="project_detail" v-model="form.project_detail" />
          <p v-if="form.errors.project_detail" class="text-sm text-red-500 mt-1 italic">{{ form.errors.project_detail }}</p>
        </div>
      </CardContent>
    </Card>

    <Card>
      <CardHeader>
        <CardTitle>Payment Details</CardTitle>
        <CardDescription>Manage payment information.</CardDescription>
      </CardHeader>
      <CardContent class="grid grid-cols-2 gap-4">
        <div class="space-y-2">
          <Label for="total_amount">Total Amount</Label>
          <Input id="total_amount" v-model="form.payment_details.total_amount" type="number" :error="form.errors['payment_details.total_amount']" />
        </div>
        <div class="space-y-2">
          <Label for="payment_type">Payment Type</Label>
          <Select v-model="form.payment_details.payment_type"  class="w-full">
            <SelectTrigger  class="w-full">
              <SelectValue placeholder="Select payment type" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="type in paymentTypes" :key="type.value" :value="type.id">
                {{ type.label }}
              </SelectItem>
            </SelectContent>
          </Select>
          <p v-if="form.errors['payment_details.payment_type']" class="text-sm text-red-500 mt-1 italic">{{ form.errors['payment_details.payment_type'] }}</p>
        </div>
        <div v-if="form.payment_details?.payment_type === PaymentType.INSTALLMENT" class="space-y-2">
          <Label for="number_installments">Number of Installments</Label>
          <Input id="number_installments" v-model="form.payment_details.number_installments" type="number" :error="form.errors['payment_details.number_installments']" />
        </div>
        <div class="space-y-2">
          <Label for="payment_status">Payment Status</Label>
          <Select v-model="form.payment_details.status"  class="w-full">
            <SelectTrigger  class="w-full">
              <SelectValue placeholder="Select status" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="status in paymentStatuses" :key="status.value" :value="status.id">
                {{ status.label }}
              </SelectItem>
            </SelectContent>
          </Select>
          <p v-if="form.errors['payment_details.status']" class="text-sm text-red-500 mt-1 italic">{{ form.errors['payment_details.status'] }}</p>
        </div>
        <div class="space-y-2">
          <Label for="amount_paid">Amount Paid</Label>
          <Input id="amount_paid" v-model="form.payment_details.amount_paid" type="number" :error="form.errors['payment_details.amount_paid']" />
        </div>
        <div class="space-y-2">
          <Label for="balance">Balance</Label>
          <Input id="balance" v-model="form.payment_details.balance" type="number" :error="form.errors['payment_details.balance']" />
        </div>
        <div class="space-y-2">
          <Label for="date_payment">Date of Payment</Label>
          <Popover>
            <PopoverTrigger as-child>
              <Button
                :variant="'outline'"
                :class="cn('w-full justify-start text-left font-normal', !form.payment_details.date_payment && 'text-muted-foreground')"
              >
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{ form.payment_details.date_payment ? formatDate(form.payment_details.date_payment).date : 'Pick a date' }}
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar
                :model-value="form.payment_details.date_payment ? parseDate(form.payment_details.date_payment) : undefined"
                @update:model-value="v => form.payment_details.date_payment = v ? v.toString() : null"
              />
            </PopoverContent>
          </Popover>
          <p v-if="form.errors['payment_details.date_payment']" class="text-sm text-red-500 mt-1 italic">{{ form.errors['payment_details.date_payment'] }}</p>
        </div>
        <div class="space-y-2 col-span-2">
          <Label for="payment_remarks">Remarks</Label>
          <Textarea id="payment_remarks" v-model="form.payment_details.remarks" />
          <p v-if="form.errors['payment_details.remarks']" class="text-sm text-red-500 mt-1 italic">{{ form.errors['payment_details.remarks'] }}</p>
        </div>
      </CardContent>
    </Card>

    <Card v-if="form.payment_details?.payment_type === PaymentType.INSTALLMENT">
      <CardHeader>
        <CardTitle>Payment Schedules</CardTitle>
        <CardDescription>Manage payment schedules for installment plans.</CardDescription>
      </CardHeader>
      <CardContent>
        <Button @click="showPaymentScheduleModal = true" :disabled="form.payment_details.number_installments <= form.payment_details?.payment_schedules?.length">Add Installment Schedule</Button>
        <div v-if="Object.keys(paymentScheduleErrors)?.length > 0" class="mt-4 text-sm text-red-500">
            <p class="font-bold">Please correct the following errors in the payment schedules:</p>
            <ul>
                <li v-for="(error, key) in paymentScheduleErrors" :key="key">
                {{ error }}
                </li>
            </ul>
        </div>
        <Table class="mt-4">
          <TableHeader>
            <TableRow>
              <TableHead>Due Date</TableHead>
              <TableHead>Due Amount</TableHead>
              <TableHead>Status</TableHead>
              <TableHead>Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow
              v-for="(schedule, index) in form.payment_details.payment_schedules"
              :key="index"
            >
              <TableCell>{{ formatDate(schedule.due_date).date }}</TableCell>
              <TableCell>{{ schedule.due_amount }}</TableCell>
              <TableCell>{{ schedule.status }}</TableCell>
              <TableCell class="space-x-2">
                <Button variant="outline" size="sm" @click="editSchedule(index)">Edit</Button>
                <Button v-if="form.payment_details.payment_schedules[editingScheduleIndex]" variant="destructive" size="sm" @click="removeSchedule(index)">Remove</Button>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </CardContent>
    </Card>
  </div>
</template>
