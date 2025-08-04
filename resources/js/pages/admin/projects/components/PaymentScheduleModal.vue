<script setup>
import { defineProps, defineEmits, watch } from 'vue'
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { CalendarDate, parseDate } from '@internationalized/date'
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { useDateFormat } from '@/composables/useDateFormat'
import { Calendar as CalendarIcon } from 'lucide-vue-next'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { cn } from '@/lib/utils'

const { formatDate } = useDateFormat()

const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  paymentScheduleStatuses: {
    type: Array,
    required: true,
  },
  schedule: {
    type: Object,
    required: false,
    default: null,
  },
});

const emit = defineEmits(['add-schedule', 'close']);

const form = useForm({
  due_date: null,
  due_amount: null,
  status: null,
  amount_paid: null,
  remarks: '',
  date_payment: null,
});

const addSchedule = () => {
  emit('add-schedule', { ...form.data() });
  form.reset();
};

watch(() => props.schedule, (newSchedule) => {
  if (newSchedule) {
    form.due_date = newSchedule.due_date
    form.due_amount = newSchedule.due_amount
    form.status = newSchedule.status
    form.amount_paid = newSchedule.amount_paid
    form.remarks = newSchedule.remarks
    form.date_payment = newSchedule.date_payment
  } else {
    form.reset()
  }
})
</script>

<template>
  <Dialog :open="show" @update:open="emit('close')">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>{{ schedule ? 'Edit Installment Schedule' : 'Add Installment Schedule' }}</DialogTitle>
        <DialogDescription>
          Fill in the details to {{ schedule ? 'edit the' : 'add a new' }} payment schedule.
        </DialogDescription>
      </DialogHeader>

      <div class="grid gap-4 py-4">
        <div class="grid grid-cols-4 items-center gap-4">
          <Label for="due_date" class="text-right">Due Date</Label>
          <div class="col-span-3">
            <Popover>
              <PopoverTrigger as-child>
                <Button
                  :variant="'outline'"
                  :class="cn('w-full justify-start text-left font-normal', !form.due_date && 'text-muted-foreground')"
                >
                  <CalendarIcon class="mr-2 h-4 w-4" />
                  {{ form.due_date ? formatDate(form.due_date).date : 'Pick a date' }}
                </Button>
              </PopoverTrigger>
              <PopoverContent class="w-auto p-0">
                 <Calendar
                    :model-value="form.due_date ? parseDate(form.due_date) : undefined"
                    @update:model-value="v => form.due_date = v ? v.toString() : null"
                />
              </PopoverContent>
            </Popover>
          </div>
        </div>
        <div class="grid grid-cols-4 items-center gap-4">
          <Label for="due_amount" class="text-right">Due Amount</Label>
          <Input id="due_amount" v-model="form.due_amount" type="number" class="col-span-3" />
        </div>
        <div class="grid grid-cols-4 items-center gap-4">
          <Label for="status" class="text-right">Status</Label>
          <Select id="status" v-model="form.status" class="w-full">
            <SelectTrigger class="col-span-3 w-full">
              <SelectValue placeholder="Select a status" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem
                  v-for="status in paymentScheduleStatuses"
                  :key="status.value"
                  :value="status.id"
                >
                  {{ status.label }}
                </SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
        <div class="grid grid-cols-4 items-center gap-4">
          <Label for="amount_paid" class="text-right">Amount Paid</Label>
          <Input id="amount_paid" v-model="form.amount_paid" type="number" class="col-span-3" />
        </div>
        <div class="grid grid-cols-4 items-center gap-4">
          <Label for="date_payment" >Date of Payment</Label>
          <div class="col-span-3">
            <Popover>
              <PopoverTrigger as-child>
                <Button
                  :variant="'outline'"
                  :class="cn('w-full justify-start text-left font-normal', !form.date_payment && 'text-muted-foreground')"
                >
                  <CalendarIcon class="mr-2 h-4 w-4" />
                  {{ form.date_payment ? formatDate(form.date_payment).date : 'Pick a date' }}
                </Button>
              </PopoverTrigger>
              <PopoverContent class="w-auto p-0">
                 <Calendar
                    :model-value="form.date_payment ? parseDate(form.date_payment) : undefined"
                    @update:model-value="v => form.date_payment = v ? v.toString() : null"
                />
              </PopoverContent>
            </Popover>
          </div>
        </div>
        <div class="grid grid-cols-4 items-center gap-4">
          <Label for="remarks" class="text-right">Remarks</Label>
          <Textarea id="remarks" v-model="form.remarks" class="col-span-3" />
        </div>
      </div>

      <DialogFooter>
        <Button type="submit" @click="addSchedule">
          {{ schedule ? 'Update Schedule' : 'Add Schedule' }}
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>