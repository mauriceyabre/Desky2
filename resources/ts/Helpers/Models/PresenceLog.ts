export default class PresenceLog {
    id: string
    attendance_id: string
    started_at: string
    ended_at: string | null
    is_active: boolean

    constructor(log: any) {
        this.id = log.id
        this.attendance_id = log.attendance_id
        this.started_at = log.started_at
        this.ended_at = log.ended_at ?? null
        this.is_active = log.is_active
    }

    get date(): string {
        return moment(this.started_at).format('YYYY-MM-DD')
    }

    get duration(): number {
        let range;
        if (this.ended_at) {
            range = moment(this.ended_at)
        } else {
            range = moment()
        }
        return range.diff(moment(this.started_at), 'minutes')
    }

    get start_time(): string|null {
        return (this.started_at) ? moment(this.started_at).format('HH:mm') : null
    }

    get end_time(): string|null {
        return (this.ended_at) ? moment(this.ended_at).format('HH:mm') : null
    }
}
