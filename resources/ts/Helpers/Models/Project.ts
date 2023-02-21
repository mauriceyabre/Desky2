import Customer from "@Models/Customer";
import User from "@Models/User";

interface Phase {
    id: string,
    title: string,
    color: string
}

export default class Project {
    deposit: number
    completed_at: string | null
    created_at?: string | null
    created_by?: number | null
    customer_id?: number | null
    deadline: string | null
    deleted_at?: string | null
    description: string | null
    id?: number
    name?: string | null
    index: number
    quote: number
    started_at?: string | null
    delivered_at?: string | null
    // suspended_at?: string | null
    phase: string
    updated_at?: string | null
    members?: User[] | null
    customer: Customer | null
    creator?: User | null

    public static $SERVICES: Service[] = [
        { id: 'ext', slug: 'exterior', title: 'Render di Esterni' },
        { id: 'int', slug: 'interior', title: 'Render di Interni' },
        { id: 'prd', slug: 'products', title: 'Render di Prodotti' },
        { id: 'vrt', slug: 'virtual_tour', title: 'Virtual Tour' },
        { id: 'grf', slug: 'graphic', title: 'Grafica' },
        { id: 'vid', slug: 'video', title: 'Video' },
        { id: 'web', slug: 'web', title: 'Web' },
    ]

    public static $PHASES: Phase[] = [
        { id: 'deal', title: 'Opportunità', color: 'dark' },
        { id: 'new', title: 'Nuovo', color: 'info' },
        { id: 'ongoing', title: 'In Corso', color: 'primary' },
        { id: 'review', title: 'In Revisione', color: 'primary' },
        { id: 'approved', title: 'Approvato', color: 'primary' },
        { id: 'delivered', title: 'Consegnato', color: 'success' },
        { id: 'completed', title: 'Completato', color: 'success' },
    ]

    constructor(project: any) {
        this.id = project.id
        this.name = project.name
        this.phase = project.phase
        this.index = Number(project.index)
        this.deadline = (project.deadline) ?? null
        this.description = project.description ?? null
        this.quote = Number(project.quote)
        this.deposit = Number(project.deposit)
        this.members = (project.members) ? project.members?.map((user: any) => new User(user)) : null;
        this.customer = (!!project.customer) ? new Customer(project.customer) : null;
        this.created_by = Number(project.created_by) ?? null;
        this.started_at = project.started_at ?? null
        this.delivered_at = project.delivered_at ?? null
        this.completed_at = project.completed_at ?? null
        this.creator = (project.creator) ? new User(project.creator) : null;
        this.customer_id = (Number(project.customer_id)) ?? null;
        this.created_at = project.created_at
        this.updated_at = project.updated_at
        this.deleted_at = project.deleted_at
    }

    /**
     * Get phase by id
     * @param phaseId
     * @return Phase|null
     */
    public static getPhase(phaseId: string): Phase | null {
        return (this.$PHASES.find(phase => phase.id === phaseId)) ?? null;
    }

    public static getPhaseName(phaseId: string): string | null {
        return (this.getPhase(phaseId)?.title) ?? null;
    }

    get balance(): number {
        return (this.quote - this.deposit)
    }

    get phaseName(): string | null {
        return (!this.isCompleted) ? (Project.getPhaseName(this.phase)) : 'Completato'
    }

    get hasDeadline(): boolean {
        return !!this.deadline
    }

    get isCompleted(): boolean {
        return !!this.completed_at
    }

    get getPhase(): Phase | null {
        return Project.getPhase(this.phase);
    }

    // get daysToDeadline(): number | null {
    //     return (this.hasDeadline) ? moment(this.deadline).diff(now(), 'days') : null
    // }

    get isActive(): boolean {
        return ['new', 'ongoing', 'review', 'approved', 'delivered'].includes(this.phase) && !this.isCompleted;
    }

    get hasDates(): boolean {
        return (!!this.started_at || !!this.delivered_at || !!this.completed_at || !!this.deleted_at)
    }

    get membersIdArray(): number[] {
        return (this.members?.length) ? this.members.map(member => member.id ?? 0) : []
    }

    get daysToDeadline(): number | undefined {
        if (this.deadline) {
            if (!this.isCompleted) {
                return moment().startOf('day').diff(moment(this.deadline).startOf('day'), 'days');
            } else {
                return moment(this.completed_at).startOf('day').diff(moment(this.deadline).startOf('day'), 'days');
            }
        }
        return undefined;
    }

    get toDeadlineFormattedText() {
        if (this.daysToDeadline) {
            if (!this.isCompleted) {
                if (this.daysToDeadline <= 0) {
                    if (Math.abs(this.daysToDeadline) === 1) {
                        return 'Consegna Domani'
                    } else if (Math.abs(this.daysToDeadline) === 0) {
                        return 'Consegna Oggi'
                    } else {
                        return 'Consegna ' + moment(this.deadline).startOf('day').from(moment().startOf('day'));
                    }
                } else {
                    return 'Scaduto ' + moment(this.deadline).startOf('day').from(moment().startOf('day'));
                }
            } else {
                if (this.daysToDeadline < 0) {
                    return 'anticipo di ' + Math.abs(this.daysToDeadline) + 'gg';
                } else {
                    return 'ritardo di ' + this.daysToDeadline + 'gg';
                }
            }
        }
    }

    get isOverdue(): boolean {
        return !!(this.daysToDeadline && this.daysToDeadline > 0)
    }
}

export class ProjectWithPivot extends Project {
    pivot: {
        project_id: number,
        attendance_id: number,
        duration: number
    }

    constructor(project: any) {
        super(project);
        this.pivot = project.pivot
    }
}
