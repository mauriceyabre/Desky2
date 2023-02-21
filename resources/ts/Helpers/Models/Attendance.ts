import PresenceLog from "@Models/PresenceLog";
import { ProjectWithPivot } from "@Models/Project";

export default class Attendance {
    id: string
    date: string
    status: string
    user_id: string
    presences?: PresenceLog[]
    absence?: string
    created_at?: string
    updated_at?: string
    projects?: ProjectWithPivot[]

    constructor(attendance: any) {
        this.id = attendance.id
        this.date = attendance.date
        this.status = attendance.status
        this.user_id = attendance.user_id
        this.presences = !!attendance.presence_logs ? Object.values(attendance.presence_logs).map(item => new PresenceLog(item)) : undefined;
        this.absence = attendance.absence
        this.created_at = attendance.created_at
        this.updated_at = attendance.updated_at
        this.projects = attendance.projects ? Object.values(attendance.projects).map(item => new ProjectWithPivot(item)) : []
    }

    // PRESENCE LOGS
    get hasPresences(): boolean {
        return !!(this.presences?.length)
    }

    get latestPresence(): PresenceLog | undefined {
        return (this.hasPresences) ? this.presences!.reduce((latest, current) => {
            if (new Date(latest.started_at) < new Date(current.started_at)) {
                return current
            } else {
                return latest
            }
        }) : undefined
    }

    get sessionsCount(): number {
        return this.presences?.length ?? 0
    }

    get isLatestPresenceActive(): boolean {
        return !!(this.latestPresence?.is_active)
    }

    get presenceDuration(): number {
        return this.presences?.reduce((sum, current) => sum + current.duration, 0) ?? 0
    }

    get roundedPresenceDuration(): number {
        const module = this.presenceDuration % 30
        return (module >= 20) ? this.presenceDuration + (30 - module) : this.presenceDuration - module
    }

    get hasActiveSession(): boolean {
        return this.isLatestPresenceActive;
    }

    get activeSession(): PresenceLog | undefined {
        return !!(this.latestPresence && this.hasActiveSession) ? this.latestPresence : undefined
    }


    // OVERTIME
    get hasOvertime(): boolean {
        return this.roundedPresenceDuration > 480
    }

    get overtime_duration(): number {
        if (this.hasOvertime) {
            return this.presenceDuration - 480
        }
        return 0
    }

    get roundedOvertimeDuration(): number {
        const module = this.overtime_duration % 30
        return (module >= 20) ? this.overtime_duration + (30 - module) : this.overtime_duration - module
    }


    // ABSENCE LOGS
    get hasAbsence(): boolean {
        return !!this.absence
    }

    get absence_duration(): number {
        return this.hasAbsence ? ((this.presenceDuration < 480) ? 480 - this.presenceDuration : 480 ) : 0
    }

    get roundedAbsenceDuration(): number {
        const module = this.absence_duration % 30
        return (module > 10) ? this.absence_duration + (30 - module) : this.absence_duration - module
    }
}
