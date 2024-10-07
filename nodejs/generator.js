const dayjs = require("dayjs");
const { Day } = require("./schedule/day");
const Art = require("./art/art");

class Generator {
    /**
     * @type {dayjs.Dayjs}
     */
    #date = date;

    /**
     * @type {Day}
     */
    #day = day;

    /**
     * @type {Art}
     */
    #defaultArt = defaultArt;

    /**
     * @type {Programation}
     */
    #programation = programation;

    /**
     * Constructor
     *
     * @param {dayjs.Dayjs} date
     * @param {Day} day
     * @param {Art} defaultArt
     * @param {Programation} programation
     */
    constructor(date, day, defaultArt, programation) {
        this.#date = date;
        this.#day = day;
        this.#defaultArt = defaultArt;
        this.#programation = programation;
    }
}

module.exports = Generator;
