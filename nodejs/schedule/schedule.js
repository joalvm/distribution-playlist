const dayjs = require('dayjs');
const Day = require('./day');

/**
 * @typedef {'sunday' | 'monday' | 'tuesday' | 'wednesday' | 'thursday' | 'friday' | 'saturday'} DayName
 * @typedef {{start: string; end: string}} TimeRange
 */
class Schedule {
    /**
     * ID del horario.
     *
     * @type {number}
     */
    #id;

    /**
     * Nombre del horario.
     *
     * @type {string}
     */
    #name;

    /**
     * Estado del horario.
     *
     * @type {boolean}
     */
    #enabled;

    /**
     * Zona horaria para el manejo de las fechas.
     *
     * @type {string|null}
     */
    #timezone;

    /**
     * @type {Map<DayName, TimeRange[]>}
     */
    #daysMap = new Map();

    /**
     * Fecha para el manejo correcto de los tiempos.
     *
     * @type {dayjs.Dayjs|null}
     */
    #currentDate = null;

    /**
     *
     * @param {number} id
     * @param {string} name
     * @param {boolean} enabled
     * @param {{[key: string]: TimeRange[]}} days
     * @param {string} timezone
     */
    constructor(id, name, enabled, days, timezone) {
        this.#id = id;
        this.#name = name;
        this.#enabled = enabled;
        this.#timezone = timezone;

        for (let key in days) {
            this.#daysMap.set(key, days[key]);
        }
    }

    /**
     *
     * @param {DayName} dayName
     * @returns {Day}
     */
    getDay(dayName) {
        return new Day(dayName, this.#daysMap.get(dayName), this.#currentDate, this.#timezone);
    }

    /**
     * Establece la zona horaria y la fecha actual.
     *
     * @param {string} date
     * @param {string} timezone
     */
    setCurrentDate(date, timezone) {
        this.#currentDate = dayjs(date).tz(timezone).startOf('day');

        this.#timezone = timezone;

        return this;
    }
}

module.exports = Schedule;