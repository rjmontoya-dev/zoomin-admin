<script setup lang="ts">
import { defineProps } from 'vue';
import { Textarea } from '@/components/ui/textarea';
import { CalendarDate, parseDate } from '@internationalized/date'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { cn } from '@/lib/utils'
import { useDateFormat } from '@/composables/useDateFormat'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Calendar as CalendarIcon } from 'lucide-vue-next'
import { Calendar, EnhancedCalendar } from '@/components/ui/calendar'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'

defineProps({
    form: {
        type: Object,
        required: true
    },
    statuses: Object,
    users: Object,
})

const { formatDate } = useDateFormat()


</script>
<template>
    <div class="flex-1 space-y-6 ">
        <form @submit.prevent>
            <div class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Reported Issue</label>
                <Textarea
                    v-model="form.reported_issue"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    placeholder="Enter reported issue"
                />
            </div>
             <div class="space-y-2 pb-4">
                <Label for="date_reported">Date Reported</Label>
                <Popover>
                    <PopoverTrigger as-child>
                    <Button
                        :variant="'outline'"
                        :class="cn('w-full justify-start text-left font-normal', !form.date_reported && 'text-muted-foreground')"
                    >
                        <CalendarIcon class="mr-2 h-4 w-4" />
                        {{ form.date_reported ? formatDate(form.date_reported).date : 'Pick a date' }}
                    </Button>
                    </PopoverTrigger>
                    <PopoverContent class="w-auto p-0">
                    <EnhancedCalendar
                        :model-value="form.date_reported ? parseDate(form.date_reported) : undefined"
                        @update:model-value="v => form.date_reported = v ? v.toString() : null"
                    />
                    </PopoverContent>
                </Popover>
                <p v-if="form.errors.date_reported" class="text-sm text-red-500 mt-1 italic">{{ form.errors.date_reported }}</p>
            </div>
            <div class="space-y-2 pb-4">
                <Label for="payment_status">Status</Label>
                <Select v-model="form.status"  class="w-full">
                    <SelectTrigger  class="w-full">
                    <SelectValue placeholder="Select status" />
                    </SelectTrigger>
                    <SelectContent>
                    <SelectItem v-for="status in statuses" :key="status.value" :value="status.id">
                        {{ status.label }}
                    </SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="form.errors['status']" class="text-sm text-red-500 mt-1 italic">{{ form.errors['status'] }}</p>
            </div>
            <div class="space-y-2 pb-4">
            <Label for="status">Assigned Agent</Label>
            <Select v-model="form.user_id" class="w-full">
                <SelectTrigger  class="w-full">
                <SelectValue placeholder="Select Agent" />
                </SelectTrigger>
                <SelectContent>
                <SelectItem v-for="(agent, index) in users" :key="index" :value="agent.id">
                    {{ agent.label }}
                </SelectItem>
                </SelectContent>
            </Select>
            <p v-if="form.errors.user_id" class="text-sm text-red-500 mt-1 italic">{{ form.errors.user_id }}</p>
            </div>
            <div class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Remarks</label>
                <Textarea
                    v-model="form.remarks"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    placeholder="Enter remarks"
                />
                <span class="text-sm text-red-500 italic" v-if="form.errors.remarks"> {{  form?.errors?.remarks  }}</span>
            </div>

            <div class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Repair Date</label>
                <Popover>
                    <PopoverTrigger as-child>
                    <Button
                        :variant="'outline'"
                        :class="cn('w-full justify-start text-left font-normal', !form.repair_date && 'text-muted-foreground')"
                    >
                        <CalendarIcon class="mr-2 h-4 w-4" />
                        {{ form.repair_date ? formatDate(form.repair_date).date : 'Pick a date' }}
                    </Button>
                    </PopoverTrigger>
                    <PopoverContent class="w-auto p-0">
                    <EnhancedCalendar
                        :model-value="form.repair_date ? parseDate(form.repair_date) : undefined"
                        @update:model-value="v => form.repair_date = v ? v.toString() : null"
                    />
                    </PopoverContent>
                </Popover>
                <p v-if="form.errors.repair_date" class="text-sm text-red-500 mt-1 italic">{{ form.errors.repair_date }}</p>
            </div>

            <div class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Amount</label>
                 <Input id="amount" v-model="form.amount" type="number" :error="form.errors.amount" />
            </div>

            <div class="space-y-2 pb-4">
                <label class="block text-sm font-medium text-gray-900 dark:text-white">Date Finished</label>
                <Popover>
                    <PopoverTrigger as-child>
                    <Button
                        :variant="'outline'"
                        :class="cn('w-full justify-start text-left font-normal', !form.date_finished && 'text-muted-foreground')"
                    >
                        <CalendarIcon class="mr-2 h-4 w-4" />
                        {{ form.date_finished ? formatDate(form.date_finished).date : 'Pick a date' }}
                    </Button>
                    </PopoverTrigger>
                    <PopoverContent class="w-auto p-0">
                    <EnhancedCalendar
                        :model-value="form.date_finished ? parseDate(form.date_finished) : undefined"
                        @update:model-value="v => form.date_finished = v ? v.toString() : null"
                    />
                    </PopoverContent>
                </Popover>
                <p v-if="form.errors.date_finished" class="text-sm text-red-500 mt-1 italic">{{ form.errors.date_finished }}</p>
            </div>
        </form>
    </div>
</template>
