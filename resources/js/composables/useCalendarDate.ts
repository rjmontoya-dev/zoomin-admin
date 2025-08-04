import { computed } from 'vue';
import { parseDate } from '@internationalized/date';
import type { CalendarDate } from '@internationalized/date';

export function useCalendarDate(form: any, field: string) {
  return computed<CalendarDate | undefined>({
    get: () => {
      const value = form[field];
      return value ? parseDate(value) : undefined;
    },
    set: (val) => {
      form[field] = val ? val.toString() : null;
    },
  });
}