import { Dayjs } from "dayjs";

/**
 * Representa un rango de tiempo de un día de la semana.
 */
class Time {
    constructor(public start: Dayjs, public end: Dayjs) {}
}

export default Time;
