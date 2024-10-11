import DayName from "../schedule/day-name.type";

interface ArtSchedule {
    start_time: string;
    finish_time: string;
    days: DayName[];
}

export default ArtSchedule;

