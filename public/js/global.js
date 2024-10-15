function convertMinutesToFormat(totalMinutes) {
    // Constants for time conversions
    const minutesInHour = 60;
    const minutesInDay = 1440; // 24 hours * 60 minutes
    const minutesInWeek = 10080; // 7 days * 24 hours * 60 minutes

    // Calculate weeks, days, hours, and minutes
    const weeks = Math.floor(totalMinutes / minutesInWeek);
    totalMinutes %= minutesInWeek;

    const days = Math.floor(totalMinutes / minutesInDay);
    totalMinutes %= minutesInDay;

    const hours = Math.floor(totalMinutes / minutesInHour);
    const minutes = totalMinutes % minutesInHour;

    // Prepare the result string
    const result = [];

    if (weeks > 0) {
        result.push(`${weeks}w`);
    }
    if (days > 0) {
        result.push(`${days}d`);
    }
    if (hours > 0) {
        result.push(`${hours}h`);
    }
    if (minutes > 0) {
        result.push(`${minutes}m`);
    }

    return result.join(' ');
}
