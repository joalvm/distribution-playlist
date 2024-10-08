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
     * @returns {Day|null}
     */
    getDay(dayName) {
        if (!this.#daysMap.has(dayName)) {
            return null;
        }

        return new Day(dayName, this.#daysMap.get(dayName), this.#currentDate, this.#timezone);
    }

    /**
     * Obtiene el dia actual en base a la fecha actual.
     *
     * @returns {Day|null}
     */
    getCurrentDay() {
        if (!this.#currentDate) {
            return null;
        }

        return this.getDay(this.#currentDate.format('dddd').toLowerCase());
    }

    /**
     * Establece la zona horaria y la fecha actual.
     *
     * @param {string} date
     */
    setCurrentDate(date) {
        this.#currentDate = dayjs(date).tz(this.#timezone).startOf('day');

        return this;
    }
}

module.exports = Schedule;
