// import Countries from '@/Json/countries.json';
const Countries = {
    "IT": { "name": "Italia", "phone": 39, "alpha_3": "ITA" },
    "CH": { "name": "Svizzera", "phone": 41, "alpha_3": "CHE" },
}

export default class Country {
    key?: string;
    name?:string;
    phone?:number;
    alpha_3?:string;

    public static getAll() : Country[] {
        return Object.entries(Countries).map((value) => {
            return { key: value[0], ...value[1] };
        });
    }

    public static find(country_code: string) {
        return this.getAll().find(country => country.key === country_code);
    }

    public static getName(country_code: string) {
        return <string> (this.find(country_code)?.name)
    }

    public static getAllForSelect(): SelectOption[] {
        return Object.entries(Country.getAll()).map((value) => {
            return { value: value[1].key!, title: value[1].name! }
        });
    }
}
