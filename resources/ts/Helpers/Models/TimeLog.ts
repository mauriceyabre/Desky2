import moment from "moment";

interface TimeLogPause {
    id: number,
    started_at: string,
    ended_at: string,
    duration: number
}
export default class TimeLog {
    id: number
    attendance_id: number
    started_at: string
    ended_at: string | null
    //paused_at: string | null
    //pause_duration: number
    time_log_pauses: TimeLogPause[]
    user_id: string
    created_at?: string
    updated_at?: string
    constructor(log: any) {
        this.id = log.id
        this.attendance_id = log.attendance_id
        this.started_at = log.started_at
        this.ended_at = log.ended_at
        //this.paused_at = log.paused_at
        //this.pause_duration = log.pause_duration
        this.time_log_pauses = log.time_log_pauses as TimeLogPause[]
        this.user_id = log.user_id
        this.created_at = log.created_at
        this.updated_at = log.updated_at
    }
    get date(): string {
        return (this.started_at) ? moment(this.started_at).format('YYYY-MM-DD') : ''
    }
    get fromNow(): { hours: number, minutes: number, seconds: number }|string {
        if (this.ended_at) {
            return 'Nessuna Sessione Attiva';
        }
        const totalSeconds = moment().diff(moment(this.started_at), 'seconds')
        const hours = Math.floor(totalSeconds/3600);
        const minutes = Math.floor(totalSeconds / 60) % 60;
        const seconds = totalSeconds % 60;

        return {hours, minutes, seconds}
    }
    get duration(): number {
        let range: moment.Moment
        if (this.ended_at) {
            range = moment(this.ended_at)
        } else {
            range = moment()
        }
        return range.diff(moment(this.started_at), 'minutes')
    }

    get pause_duration(): number {
        return this.time_log_pauses?.reduce((sum, pause) => sum + Math.abs(moment(pause.started_at).diff(pause.ended_at)), 0);
    }
    get printedDuration(): string {
        return Math.floor(this.duration / 60).toString() + 'h ' + this.duration % 60 + 'm'
    }
    get overtime(): number {
        if (this.duration > (8 * 60)) {
            return this.duration - (8 * 60)
        }
        return 0
    }
    public printedFromNow(range: string = 'minutes'): string {
        if (typeof this.fromNow !== 'string') {
            switch (range) {
                case 'minutes':
                    return `${ this.fromNow.hours }h ${ this.fromNow.minutes }m`;
                case 'seconds':
                    return `${ this.fromNow.hours }h ${ this.fromNow.minutes }m ${ this.fromNow.seconds }s`
                case 'hours':
                    return `${ this.fromNow.hours }h`
            }
        }
        return 'Nessuna Sessione Attiva';
    }
}
