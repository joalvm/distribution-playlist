import { Dayjs } from "dayjs";
import Time from "./time";

/**
 * Representa un día de la semana, el cual contiene un conjunto de rangos de tiempo
 * que representan las horas de trabajo.
 */
class Day {
    private timesList: Time[] = [];

    private totalSeconds = 0;

    constructor(
        private readonly dayName: string,
        private readonly date: Dayjs,
        times: { start: string; end: string }[]
    ) {
        this.handleTimes(times);
    }

    getDayName() {
        return this.dayName;
    }

    getTimes() {
        return this.timesList;
    }

    private handleTimes(times: { start: string; end: string }[]) {
        for (let time of times) {
            const startTime = this.makeTime(time.start);
            const endTime = this.makeTime(time.end);

            this.timesList.push(new Time(startTime, endTime));

            this.totalSeconds += endTime.diff(startTime, "second");
        }
    }

    /**
     * Crea una fecha con la hora establecida.
     */
    private makeTime(time: string): Dayjs {
        const [hours, minutes, seconds] = time.split(":").map(Number);

        return this.date
            .clone()
            .set("hour", hours)
            .set("minute", minutes)
            .set("second", seconds);
    }
}

export default Day;
