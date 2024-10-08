const dayjs = require('dayjs');
const utc = require('dayjs/plugin/utc');
const tz = require('dayjs/plugin/timezone');
const Schedule = require('./nodejs/schedule/schedule');
const Art = require('./nodejs/art/art');

dayjs.extend(utc);
dayjs.extend(tz);

const timezone = 'America/Lima';

const _schedule = require('./resources/schedule/simple.json');
const _defaultArt = require('./resources/default_art/carousel.json');
const programation = require('./resources/programation_arts.json');

const schedule = new Schedule(_schedule.id, _schedule.name, _schedule.enabled, _schedule.days, timezone);
const defaultArt = new Art(_defaultArt.id, _defaultArt.media_id, _defaultArt.type, _defaultArt.medias);

schedule.setCurrentDate('2024-10-15');

console.log(schedule.getDay('monday').totalSeconds);
