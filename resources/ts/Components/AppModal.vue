<template>
    <div class="modal fade" :data-bs-backdrop="static ? 'static' : null" :id="modalId" tabindex="-1"
            :aria-labelledby="modalId+'_label'" aria-hidden="true">
        <div class="modal-dialog" :class="[{'modal-dialog-centered': centered}, size]">
            <div class="modal-content">
                <div class="modal-header" v-if="!noHeader">
                    <div class="fw-bolder modal-title h2" :id="modalId+'_label'">
                        <slot name="title">
                            {{ title }}
                        </slot>
                    </div>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" v-if="!hideCloseButton"
                            @click="closeModal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                      fill="currentColor"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
                <AppBoxLoader v-if="isLoading" />
                <div class="modal-body p-0" v-else>
                    <slot name="form" :form-id="formId" :modal-id="modalId" />
                    <slot name="default" />
                </div>
                <slot name="footer" />
            </div>
        </div>
    </div>
</template>
<script lang="ts">
    import { defineComponent, PropType } from "vue";
    import AppBoxLoader from "@Components/AppBoxLoader.vue";

    export default defineComponent({
        name: 'AppModal',
        components: { AppBoxLoader },
        props: {
            title: {
                type: String,
                default: 'Form'
            },
            id: {
                type: String,
                required: true
            },
            modal: Object,
            modalSize: {
                type: String as PropType<'small'|'large'|'extra-large'>,
                default: 'base'
            },
            centered: {
                type: Boolean,
                default: false
            },
            static: {
                type: Boolean,
                default: false
            },
            hideCloseButton: {
                type: Boolean,
                default: false
            },
            noHeader: Boolean,
            isLoading: Boolean
        },
        computed: {
            size() {
                const size = this.modalSize;
                switch (size) {
                    case 'small':
                        return 'modal-sm';
                    case 'large':
                        return 'modal-lg';
                    case 'extra-large':
                        return 'modal-xl';
                    default:
                        return ''
                }
            },
            formId() {
                return `${this.id}_form`
            },
            modalId() {
                return `${this.id}_modal`
            }
        },
        methods: {
            closeModal() {
                this.modal?.close()
            }
        }
    })
</script>
