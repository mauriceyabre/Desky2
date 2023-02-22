import isEqual from 'lodash/isEqual'
import { computed, reactive, ref, watch } from 'vue'
import cloneDeep from 'lodash/cloneDeep'

export default function useForm<TForm>(args: TForm): AppForm<TForm> {
    const data = args ?? {}
    let defaults = cloneDeep(data as TForm)
    const errorsData = ref({})
    let transform = data => data

    let form = reactive({
        ...data,
        isDirty: false,
        errors: computed(() => errorsData.value),
        hasErrors: false,
        processing: false,
        progress: null,
        wasSuccessful: false,
        recentlySuccessful: false,
        setDefaults(data?: TForm) {
            const dataSet = data ?? this.data()
            defaults = cloneDeep(dataSet)
        },
        setErrors(err: Object) {
            if (!!Object.keys(err).length) {
                Object.entries(err).forEach(([key, value]) => {
                    errorsData.value[key] = value;
                    console.log(this.errors)
                })
                this.hasErrors = true
            } else {

            }
        },
        data() {
            return Object
                .keys(data)
                .reduce((carry, key) => {
                    carry[key] = this[key]
                    return carry
                }, {}) as TForm
        },
        defaults() {
            return computed(() => defaults).value as TForm
        },
        transform(callback) {
            transform = callback
            return this
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
        async submit(method: 'get'|'post'|'put'|'delete', url: string, options : AppVisitOptions) {
            this.processing = true
            const data = transform(this.data())

            const _options = {
                ...options,
                onBefore: visit => {
                    this.wasSuccessful = false
                    this.recentlySuccessful = false

                    if (options.onBefore) {
                        return options.onBefore(visit)
                    }
                },
                onSuccess: async page => {
                    this.processing = false
                    this.progress = null
                    this.clearErrors()
                    this.wasSuccessful = true
                    this.recentlySuccessful = true

                    const onSuccess = options.onSuccess ? options.onSuccess(page) : null
                    this.isDirty = false
                    return onSuccess
                },
                onError: errors => {
                    this.processing = false
                    this.progress = null
                    // this.clearErrors().setError(errors)

                    if (options.onError) {
                        return options.onError(errors)
                    }
                },
                onFinish: () => {
                    this.processing = false
                    this.progress = null

                    if (options.onFinish) {
                        return options.onFinish()
                    }
                },
            }

            _options?.onBefore(data)

            if (method === 'delete') {
                return await axios.delete(url).then((res) => {
                    _options?.onSuccess(res.data)
                })
                    .catch(err => {
                        _options?.onError(err)
                    })
                    .finally(() => {
                    this.processing = false
                        _options.onFinish()
                })
            }
            return await axios[method](url, { ...data, params: _options })
                .then((res) => {
                    _options?.onSuccess(res)
                })
                .catch((err) => {
                    (!!err.response.data.errors) ? Object.entries(err.response.data.errors).forEach(([key, value]) => {
                        errorsData.value[key] = value?.[0];
                    }) : {}
                    _options?.onError(err.response)
                })
                .finally(() => {
                this.processing = false
                    _options.onFinish()
            })
        },
        post(url: string, options: AppVisitOptions) {
            this.submit('post', url, options)
        },
        put(url: string, options: AppVisitOptions) {
            this.submit('put', url, options)
        },
        get(url: string, options: AppVisitOptions) {
            this.submit('get', url, options)
        },
        delete(url: string, options: AppVisitOptions) {
            this.submit('delete', url, options)
        }
    })

    watch(form, () => {
        form.isDirty = !isEqual(form.data(), defaults)
    }, { immediate: true, deep: true })

    return form as AppForm<TForm>
}
