declare interface SelectOption {
    value: string | number | null,
    title: string
}

declare interface ModalData {
    formId?: string,
    modalId?: string,
}

declare interface Service {
    id: string | number,
    title: string,
    slug: string
}

declare interface Role {
    id: string,
    key: string,
    name: string,
    description?: string
}

declare interface CustomDraggable {
    from: HTMLElement,
    clone: HTMLElement,
    item: HTMLElement,
    newIndex: number,
    oldIndex: number,
    to: HTMLElement,
    eventPhase: number,
    type: string
}

declare interface PaginatedResponse {
    current_page: number,
    data: Object[],
    first_page_url: string | null,
    from: number,
    last_page: number,
    last_page_url: string | null,
    links: {
        url: string | null,
        label: string,
        active: boolean
    }[]
    next_page_url: string | null,
    path: string,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number
}

declare interface Paginated {
    current_page: number,
    data: Object[],
    first_page_url: string | null,
    from: number,
    last_page: number,
    last_page_url: string | null,
    links: {
        url: string | null,
        label: string,
        active: boolean
    }[]
    next_page_url: string | null,
    path: string,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number
}

declare interface Pagination {
    current_page: number,
    data: Object[],
    first_page_url: string | null,
    from: number,
    last_page: number,
    last_page_url: string | null,
    links: {
        url: string | null,
        label: string,
        active: boolean
    }[]
    next_page_url: string | null,
    path: string,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number
}

declare interface VatEntity {
    id?: number,
    ref_id?: number,
    value?: number,
    description?: string
}

declare interface AppVisitOptions {
    onBefore?(data?): any,
    onStart?(event?): any,
    onError?(error?): any,
    onSuccess?(res?): any,
    onFinish?(): any
}

declare interface AppFormProps<TForm> {
    isDirty: boolean
    errors?: TForm
    hasErrors: boolean
    processing: boolean
    progress: null | number
    wasSuccessful: boolean
    recentlySuccessful: boolean

    setErrors(err: Object): void

    setError(field: keyof TForm, value: string): this

    setDefaults(data?: TForm): void
    setError(errors: Record<keyof TForm, string>): this
    transform(callback: (data: TForm) => object): this
    data(): TForm
    defaults(): TForm
    clearErrors(): this
    submit(method: 'get' | 'post' | 'put' | 'delete', url: string, options?: AppVisitOptions): Promise<any>

    post(url: string, options: AppVisitOptions)

    put(url: string, options: AppVisitOptions)

    get(url: string, options: AppVisitOptions)

    delete(url: string, options: AppVisitOptions)
}

declare type AppForm<TForm> = TForm & AppFormProps<TForm>
