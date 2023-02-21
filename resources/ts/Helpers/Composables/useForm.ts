import isEqual from 'lodash/isEqual'
import { computed, reactive, readonly, ref, watch } from 'vue'
import cloneDeep from 'lodash/cloneDeep'

interface ADFormProps<TForm> {
    isDirty: boolean
    errors?: TForm
    hasErrors: boolean
    processing: boolean
    progress: null | number
    wasSuccessful: boolean
    recentlySuccessful: boolean
    setErrors(err: Object): void
    data(): TForm
    defaults(): TForm
    clearErrors(): this
    submit(method: 'get' | 'post' | 'put' | 'delete', url: string, params?: Object, data?: Object): Promise<any>
}

export type ADForm<TForm> = TForm & ADFormProps<TForm>

export default function useForm<TForm>(args: TForm): ADForm<TForm> {
    const data = args || {}
    const defaults = cloneDeep(data)
    const errorsData = ref({})

    let form = reactive({
        ...data,
        isDirty: false,
        errors: computed(() => errorsData.value),
        hasErrors: false,
        processing: false,
        progress: null,
        wasSuccessful: false,
        recentlySuccessful: false,
        setErrors(err: Object) {
            Object.entries(err).forEach(([key, value]) => {
                errorsData.value[key] = value?.[0];
                console.log(this.errors)
            })
        },
        data() {
            return Object
                .keys(data)
                .reduce((carry, key) => {
                    carry[key] = this[key]
                    return carry
                }, {})
        },
        defaults() {
            return readonly(defaults) as TForm
        },
        clearErrors(...fields) {
            errorsData.value = Object
                .keys(errorsData.value)
                .reduce((carry, field) => ({
                    ...carry,
                    ...(fields.length > 0 && !fields.includes(field) ? { [field]: errorsData.value[field] } : {}),
                }), {})

            this.hasErrors = Object.keys(errorsData.value).length > 0
            return this
        },
        async submit(method: 'get'|'post'|'put'|'delete', url, params?: {
            onBefore: (callback: (e?) => void) => void
        }) {
            this.processing = true

            params?.onBefore( (callback)  => {
                callback();
            })

            if (method === 'delete') {
                return await axios.delete(url, { params }).finally(() => {
                    this.processing = false
                })
            }
            return await axios[method](url, { ...this.data(), params: params })
                .catch((err) => {
                    (!!err.response.data.errors) ? Object.entries(err.response.data.errors).forEach(([key, value]) => {
                        errorsData.value[key] = value?.[0];
                    }) : {}
                })
                .finally(() => {
                this.processing = false
            })
        }
    })

    watch(form, () => {
        form.isDirty = !isEqual(form.data(), defaults)
    }, { immediate: true, deep: true })

    return form as ADForm<TForm>
}
