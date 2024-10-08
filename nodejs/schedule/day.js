const dayjs = require('dayjs');

/**
 * @typedef {{start: dayjs.Dayjs, end: dayjs.Dayjs}} Range
 */
class Day {
    /**
     *
     * @type {string}
     */
    #dayName;

    /**
     * Establece la fecha actual.
     *
     * @type {dayjs.Dayjs}
     */
    #currentDate;

    /**
     * Zona horaria para el manejo de las fechas.
     *
     * @type {string}
    */
    #timezone;

    /**
     * @type {Range[]}
     */
    #ranges = [];

    /**
     * @type {number}
     */
    totalSeconds = 0;

    /**
     *
     * @param {string} dayName
     * @param {{start:string, end: string}[]} times
     * @param {dayjs.Dayjs} currentDate
     * @param {string} timezone
     */
    constructor(dayName, times, currentDate, timezone) {
        this.#dayName = dayName;
        this.#currentDate = currentDate;
        this.#timezone = timezone;

        this.#handleTimes(times);
    }

    #handleTimes(times) {
        const date = this.#currentDate.format('YYYY-MM-DD');

        for (let time of times) {
            const startTime = dayjs(`${date} ${time.start}`).tz(this.#timezone);
            const endTime = dayjs(`${date} ${time.end}`).tz(this.#timezone);

            this.#ranges.push({ start: startTime, end: endTime });

            this.totalSeconds += endTime.diff(startTime, 'second');
        }
    }
}

module.exports = Day;
