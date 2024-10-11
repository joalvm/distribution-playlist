import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import tz from "dayjs/plugin/timezone";
import Schedule from "./nodejs/schedule/schedule";
import Art from "./nodejs/art/art";
import ArtData from "./nodejs/art/art-data.interface";
import ArtType from "./nodejs/art/art-type.enum";

dayjs.extend(utc);
dayjs.extend(tz);

const timezone = "America/Lima";

const _schedule = require("./resources/schedule/simple.json");
const _defaultArt: ArtData = require("./resources/default_art/carousel.json");
const programation = require("./resources/programation_arts.json");

const schedule = new Schedule(
    _schedule.id,
    _schedule.name,
    _schedule.enabled,
    timezone,
    _schedule.days
);
const defaultArt = new Art(
    _defaultArt.id,
    _defaultArt.media_id,
    _defaultArt.type as ArtType,
    _defaultArt.medias
);

console.log(defaultArt);
