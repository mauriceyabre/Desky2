import isEqual from 'lodash/isEqual'
import { reactive, readonly, watch } from 'vue'
import cloneDeep from 'lodash/cloneDeep'
import { callbackFn } from "@tinymce/tinymce-vue/lib/es2015/main/ts/ScriptLoader";

interface DFormParams {}

interface ADFormProps<TForm> {
    isDirty: boolean
    errors: { [key: string]: string[] }
    hasErrors: boolean
    processing: boolean
    progress: null | number
    wasSuccessful: boolean
    recentlySuccessful: boolean

    data(): TForm
    defaults(): TForm
    clearErrors(): this
    submit(method: 'get' | 'post' | 'put' | 'delete', url: string, params?: Object, data?: Object): Promise<any>
}

export type ADForm<TForm> = TForm & ADFormProps<TForm>

export default function useForm<TForm>(args: TForm): ADForm<TForm> {
    const data = args || {}
    const defaults = cloneDeep(data)

    let form = reactive({
        ...data,
        isDirty: false,
        errors: {},
        hasErrors: false,
        processing: false,
        progress: null,
        wasSuccessful: false,
        recentlySuccessful: false,
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
            this.errors = Object
                .keys(this.errors)
                .reduce((carry, field) => ({
                    ...carry,
                    ...(fields.length > 0 && !fields.includes(field) ? { [field]: this.errors[field] } : {}),
                }), {})

            this.hasErrors = Object.keys(this.errors).length > 0
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
                        this.errors[key] = value?.[0];
                    }) : {}
                })
                .finally(() => {
                this.processing = false
            })
        }
    })

    watch(form, newValue => {
        form.isDirty = !isEqual(form.data(), defaults)
    }, { immediate: true, deep: true })

    return form as ADForm<TForm>
}
