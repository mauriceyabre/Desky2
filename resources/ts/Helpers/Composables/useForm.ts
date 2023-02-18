import isEqual from 'lodash/isEqual'
import { reactive, watch } from 'vue'
import cloneDeep from 'lodash/cloneDeep'

interface ADFormProps<TForm> {
    isDirty: boolean;
    errors: { [key: string]: string[] };
    hasErrors: boolean;
    processing: boolean;
    progress: null | number;
    wasSuccessful: boolean;
    recentlySuccessful: boolean;

    data(): TForm;
}

type ADForm<TForm> = TForm & ADFormProps<TForm>

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
        }
    })

    watch(form, newValue => {
        form.isDirty = !isEqual(form.data(), defaults)
    }, { immediate: true, deep: true })

    return form as ADForm<TForm>
}
