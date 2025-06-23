import { DateTime } from 'luxon';

export const useDateFormat = () => {
    const formatDate = (dateString: string) => {
        if (!dateString) return { formatted: '', date: '', time: '' };

        const date = DateTime.fromISO(dateString);
        return {
            date: date.toFormat('MMM dd, yyyy'),
            time: date.toFormat('hh:mm a'),
            formatted: `${date.toFormat('MMM dd, yyyy')} , ${date.toFormat('hh:mm a')}`,
        };
    };

    return { formatDate };
};
