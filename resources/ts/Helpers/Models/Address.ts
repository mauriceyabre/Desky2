import Country from "@Models/Country";

export default class Address {
    id: number
    street?: string
    city?: string
    postcode?: string
    province?: string
    state?: string
    country_code: string
    addressable_id: number

    constructor(address: any) {
        this.id = address.id
        this.street = address.street
        this.city = address.city
        this.postcode = address.postcode
        this.province = address.province
        this.state = address.state
        this.country_code = address.country_code
        this.addressable_id = address.addressable_id
    }

    get plainAddress(): string | undefined {
        let address = '';
        if (this?.street) address += `${ this.street }</br>`;
        if (this?.postcode) address += `${ this.postcode } `;
        if (this?.city) address += `${ this.city } `;
        if (this?.province) address += `(${ this.province })`;

        return address;
    }

    get shortAddress(): string | undefined {
        if (this) {
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

    get values() {
        return Object.entries(this)
            .filter(item => ['street', 'city', 'postcode', 'province', 'state'].includes(item[0]))
            .reduce((item, [key, value]) => {
                item[key] = value
                return item
            }, {});
    }

    get countryName(): string | null {
        return !!this?.country_code ? Country.getName(this.country_code) : null
    }
}
