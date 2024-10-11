import dayjs, { Dayjs } from "dayjs";
import dayjsTz from "dayjs/plugin/timezone";
import dayjsUtc from "dayjs/plugin/utc";
import Day from "./day";
import DayName from "./day-name.type";

dayjs.extend(dayjsTz);
dayjs.extend(dayjsUtc);

class Schedule {
    private daysMap = new Map<DayName, {start: string, end: string}[]>();

    constructor(
        private readonly id: number,
        private readonly name: string,
        private readonly enabled: boolean,
        private readonly timezone: string,
        days: Record<DayName, { start: string; end: string }[]>,
    ) {
        for (let [key, day] of Object.entries(days)) {
            this.daysMap.set(key as DayName, day);
        }
    }

    getId() {
        return this.id;
    }

    isEnabled() {
        return this.enabled;
    }

    getName() {
        return this.name;
    }

    hasDay(dayName: DayName) {
        return (
            this.daysMap.has(dayName) && this.daysMap.get(dayName)?.length
        );
    }

    /**
     *
     * @param {DayName} dayName
     * @param {dayjs.Dayjs} date
     *
     * @returns {Day}
     */
    getDay(dayName: DayName, date: Dayjs) {
        if (date instanceof dayjs === false) {
            throw new Error("Invalid date object");
        }

        return new Day(
            dayName,
            date.clone().tz(this.timezone),
            this.daysMap.get(dayName) || []
        );
    }
}

export default Schedule;
