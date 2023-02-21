import Country from "@Models/Country";
import Project from "@Models/Project";
import Attendance from "@Models/Attendance";
import PresenceLog from "@Models/PresenceLog";
import Address from "@Models/Address";

export default class User {
    // Account
    id: number
    first_name?: string
    last_name?: string
    email?: string

    // Details
    job?: string
    phone?: string
    birthday?: string
    description?: string

    // Business Details
    iban?: string
    tax_id?: string
    vat_id?: string

    // Computed Details
    is_admin?: boolean

    // Dates
    last_login?: string
    hire_date?: string
    created_at?: string
    updated_at?: string
    deleted_at?: string

    // Relationships
    role?: Role
    address?: Address
    projects?: Project[]
    attendances?: Attendance[]
    latest_attendance?: Attendance
    today_attendance?: Attendance
    active_session_attendance?: Attendance

    constructor(user: any) {
        // Account
        this.id = user.id
        this.first_name = user.first_name
        this.last_name = user.last_name
        this.email = user.email

        // Details
        this.job = user.job
        this.phone = user.phone
        this.birthday = user.birthday
        this.description = user.description

        // Business Details
        this.iban = user.iban
        this.tax_id = user.tax_id
        this.vat_id = user.vat_id

        // Computed Details
        this.is_admin = user.is_admin

        // Dates
        this.last_login = user.last_login
        this.hire_date = user.hire_date
        this.created_at = user.created_at
        this.updated_at = user.updated_at
        this.deleted_at = user.deleted_at

        // Relationships
        this.role = user.role as Role
        this.address = !!user.address ? new Address(user.address) : undefined
        this.projects = !!user.projects ? Object.values(user.projects).map(project => new Project(project)) : []
        this.attendances = !!user.attendances ? Object.values(user.attendances).map(item => new Attendance(item)) : []
        this.latest_attendance = !!user.latest_attendance ? new Attendance(user.latest_attendance) : undefined
        this.today_attendance = !!user.today_attendance ? new Attendance(user.today_attendance) : undefined
        this.active_session_attendance = !!user.active_session_attendance ? new Attendance(user.active_session_attendance) : undefined
    }

    get isArchived(): boolean {
        return !!this.deleted_at
    }

    get roleName(): string {
        return this.role?.name ?? ''
    }
    get jobTitle(): string {
        return this.job ?? this.roleName
    }
    get countryName(): string | null {
        return !!this.address?.country_code ? Country.getName(this.address.country_code) : null
    }
    get name(): string {
        return `${ this.first_name?.capitalizeFirst() } ${ this.last_name?.capitalizeFirst() }`;
    }
    get formattedId(): string {
        if (this.id) {
            return this.id.toString().padStart(2, '0')
        }
        return 'No ID';
    }
    get initials(): string {
        if (this.first_name && this.last_name) {
            return this.first_name[0] + this.last_name[0];
        } else if (this.first_name) {
            return this.first_name[0]
        } else if (this.last_name) {
            return this.last_name[0]
        } else {
            return '--'
        }
    }

    get profileCompleteness(): number {
        const data = {
            first_name: this.first_name,
            last_name: this.last_name,
            email: this.email,
            phone: this.phone,
            addressCity: this.address?.city,
            addressProvince: this.address?.province,
            addressPostalCode: this.address?.postcode,
            addressStreet: this.address?.street,
            birthday: this.birthday,
            country_code: this.address?.country_code
        }
        const filledData = Object.values(data).filter(data => !!data);
        const dataCount = Object.keys(data).length;
        const filledDataCount = Object.keys(filledData).length;

        return Math.round(filledDataCount / dataCount * 100);
    }

    get hasAddress(): boolean {
        return (this.address) ? !!Object.values(this.address).filter(val => !!val).length : false;
    }

    get plainAddress(): string | null | undefined {
        if (!this.hasAddress) return null;

        let address = '';
        if (this.address?.street) address += `${ this.address.street }</br>`;
        if (this.address?.postcode) address += `${ this.address.postcode } `;
        if (this.address?.city) address += `${ this.address.city } `;
        if (this.address?.province) address += `(${ this.address.province })`;

        return address;
    }

    get city(): string|undefined {
        return this.address?.city
    }

    get province(): string|undefined {
        return this.address?.province
    }

    get shortAddress(): string | undefined {
        if (this.hasAddress) {
            if (this.city && this.province) {
                return this.city.capitalize() + ' ' + '(' + this.province.toUpperCase() + ')'
            } else if (this.city) {
                return this.city.capitalize()
            } else if (this.province) {
                return this.province.toUpperCase()
            } else {
                return undefined
            }
        }
    }

    // SESSIONS
    get hasLatestAttendance(): boolean {
        return !!this.latest_attendance;
    }

    get hasTodayAttendance(): boolean {
        return !!this.today_attendance
    }

    get todayAttendance(): Attendance|undefined {
        return this.today_attendance
    }
    get hasActiveSession(): boolean {
        return !!(this.active_session_attendance && this.active_session_attendance?.hasActiveSession)
    }

    get activeSession(): PresenceLog | null {
        return this.active_session_attendance?.activeSession ?? null;
    }

    get todayPresenceDuration(): number {
        return this.todayAttendance?.presenceDuration ?? 0
    }
}
