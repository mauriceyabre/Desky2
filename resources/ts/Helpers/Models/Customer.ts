import User from "@Models//User";
import Project from "@Models/Project";
import Address from "@Models/Address";
import Country from "@Models/Country";

export default class Customer {
    id: number
    ref_id?: number

    name: string
    type?: 'company'|'person'|'pa'|'condo'
    vat_number?: string
    tax_code?: string

    email?: string
    pec?: string
    phone?: string
    description?: string

    sdi_code?: string
    e_invoice?: boolean
    default_vat_id?: number
    default_vat_entity?: VatEntity

    // bank_name?: string
    // bank_iban?: string
    // bank_swift?: string

    category?: 'interior-design' | 'product-design' | 'architecture' | 'furniture-shop' | 'industrial' | 'real-estate' | 'other'

    website?: string
    social_links?: {
        facebook?: string,
        instagram?: string,
        behance?: string,
    }

    address?: Address
    creator?: User
    projects?: Project[]

    created_at?: string
    updated_at?: string
    deleted_at?: string

    projects_count?: number
    active_projects_count?: number
    completed_projects_count?: number
    time_bounded_projects_count?: number

    constructor(customer: any) {
        this.id = customer.id
        this.ref_id = customer.ref_id

        this.name = customer.name?.toLowerCase().capitalize()
        this.type = customer.type
        this.vat_number = customer.vat_number
        this.tax_code = customer.tax_code

        this.email = customer.email
        this.pec = customer.pec
        this.phone = customer.phone
        this.description = customer.description

        this.sdi_code = customer.sdi_code
        this.e_invoice = customer.e_invoice
        this.default_vat_id = customer.default_vat_id
        this.default_vat_entity = customer.default_vat_entity

        // this.bank_name = customer.bank_name
        // this.bank_iban = customer.bank_iban
        // this.bank_swift = customer.bank_swift

        this.category = customer.category

        this.website = customer.website
        this.social_links = !!customer.social_links ? JSON.parse(customer.social_links) : null

        this.address = customer.address ? new Address(customer.address) : undefined
        this.creator = customer.creator ? new User(customer.creator) : undefined
        this.projects = customer.projects ? Object.values(customer.projects).map(item => new Project(item)) : undefined

        this.created_at = customer.created_at
        this.updated_at = customer.updated_at
        this.deleted_at = customer.deleted_at

        this.projects_count = customer.projects_count ?? 0
        this.active_projects_count = customer.active_projects_count ?? 0
        this.completed_projects_count = customer.completed_projects_count ?? 0
        this.time_bounded_projects_count = customer.time_bounded_projects_count ?? 0
    }

    get initials() {
        const string = this.name.toUpperCase().replace(/[^a-zA-Z ]/g, '').trim();
        return string.substring(0,2)
    }

    get wantEInvoice() {
        return !!this.e_invoice;
    }

    get hasAddress(): boolean {
        return !!this.address
    }

    get timeBoundedProjects() {
        if (!this.projects) {
            return [] as Project[]
        } else {
            return this.projects.filter(project => {
                return project.isActive && project.deadline
            })
        }
    }

    get nearestTimeBoundedProject(): Project|undefined {
        if (this.timeBoundedProjects.length) {
            return this.timeBoundedProjects.reduce((acc, curr) => {
                if (moment(curr.deadline).isBefore(acc.deadline)) {
                    return curr
                } else {
                    return acc
                }
            })
        } else {
            return undefined
        }
    }
}
