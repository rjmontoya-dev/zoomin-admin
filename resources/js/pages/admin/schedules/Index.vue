<script setup lang="ts">
import { ref, onMounted } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import type { CalendarOptions, EventClickArg, EventContentArg, MoreLinkContentArg } from '@fullcalendar/core'
import AppLayout from '@/layouts/AppLayout.vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

const calendarOptions = ref<CalendarOptions>({
  plugins: [dayGridPlugin],
  initialView: 'dayGridMonth',
  dayMaxEventRows: true,
  events: [],
  eventClick: (info: EventClickArg) => {
    router.get(route('admin.projects.show', { id: info.event.id }))
  },
  eventContent: (arg: EventContentArg) => {
    const { event } = arg;
    const { extendedProps } = event;
    const { title, status, user, project_code, type } = extendedProps;
    const titleBgClass = type === 'project' ? 'bg-blue-500 text-white' : 'bg-red-500 text-white';
    const textColorClass = type === 'project' ? 'text-blue-700' : 'text-red-700';

    return {
      html: `
        <div>
          <div class="px-1 py-0.5 rounded ${titleBgClass}">${title}</div>
          <ul class="list-disc marker:${textColorClass} marker:text-xl list-inside mt-1 ${textColorClass}">
            <li>${status.title}</li>
            <li>${user}</li>
          </ul>
        </div>
      `
    }
  },
  moreLinkContent: (args: MoreLinkContentArg) => {
    return {
      html: `<div class="p-1 rounded bg-yellow-400 text-black font-semibold">+${args.num} More Details</div>`
    }
  }
})

onMounted(async () => {
  try {
    const response = await axios.get(route('admin.schedules.data'))
    const events = response.data.map((item: any) => ({
      id: item.id,
      start: item.start,
      end: item.end,
      backgroundColor: 'transparent',
      borderColor: 'transparent',
      extendedProps: {
        title: item.title,
        status: item.status,
        user: item.user,
        project_code: item.project_code,
        type: item.type,
      }
    }))
    calendarOptions.value.events = events
  } catch (error) {
    console.error('Error fetching schedule data:', error)
  }
})
</script>

<template>
  <AppLayout>
    <div class="p-4">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Schedules</h1>
        <div class="flex items-center space-x-4">
          <div class="flex items-center">
            <span class="w-4 h-4 bg-blue-500 rounded-full mr-2"></span>
            <span>Project</span>
          </div>
          <div class="flex items-center">
            <span class="w-4 h-4 bg-red-500 rounded-full mr-2"></span>
            <span>Repair</span>
          </div>
        </div>
      </div>
      <FullCalendar :options="calendarOptions" />
    </div>
  </AppLayout>
</template>

<style>
.fc-daygrid-event {
  white-space: normal !important;
}
</style>
