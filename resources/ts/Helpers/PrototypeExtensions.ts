declare interface String {
    capitalize(): string,
    capitalizeFirst(): string,
}

declare interface Array<T> {
    groupBy( key: string, order?: string): T[]
    hasMin(attribute: string): T|null
    hasMinDate(date: string): T|null
}

declare interface Number {
    toCurrency(): string
    toPrintedDuration(): string
}

String.prototype.capitalize = function () {
    const words = this.split(" ");
    return words.map((word) => {
        return word[0].toUpperCase() + word.substring(1);
    }).join(" ");
}

String.prototype.capitalizeFirst = function () {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

Number.prototype.toCurrency = function () {
    return Intl.NumberFormat('it_IT', { style: 'currency' }).format(this.valueOf());
}

Array.prototype.groupBy = function ( key) {
    return this.reduce(function (rv, x) {
        (rv[x[key]] = rv[x[key]] || []).push(x);
        return rv;
    }, {});
}

Array.prototype.hasMin = function (attrib: string) {
    const checker = (o: any, i: any) => typeof (o) === 'object' && o[i]
    return (this.length && this.reduce(function (prev, curr) {
        const prevOk = checker(prev, attrib);
        const currOk = checker(curr, attrib);
        if (!prevOk && !currOk) return {};
        if (!prevOk) return curr;
        if (!currOk) return prev;
        return prev[attrib] < curr[attrib] ? prev : curr;
    })) || null;
}

Array.prototype.hasMinDate = function (date: string) {
    const checker = (o: any, i: any) => typeof (o) === 'object' && o[i]
    return (this.length && this.reduce(function (prev, curr) {
        const prevOk = checker(prev, date);
        const currOk = checker(curr, date);
        if (!prevOk && !currOk) return {};
        if (!prevOk) return curr;
        if (!currOk) return prev;
        return moment(prev[date]).valueOf() < moment(curr[date]).valueOf() ? prev : curr;
    })) || null;
}

/**
 * Capitalize only first letter of sentence.
 */
Object.defineProperty(String.prototype, 'capitalizeFirst', {
    value: function () {
        return this.charAt(0).toUpperCase() + this.slice(1);
    },
    enumerable: false
});

/**
 * Convert number to Euro.
 */
Object.defineProperty(Number.prototype, 'toCurrency', {
    value: function () {
        return new Intl.NumberFormat('it-IT', {
            // style: 'currency',
            // currency: 'EUR'
        }).format(this)
    },
    enumerable: false
});

Object.defineProperty(Number.prototype, 'toPrintedDuration', {
    value: function () {
        return (Math.floor(this / 60).toString()) + 'h ' + (this % 60).toString().padStart(2, '0') + 'm'
    },
    enumerable: false
});
