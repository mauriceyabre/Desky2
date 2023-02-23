<template>
    <AppModal id="attendance" :modal="modal" :title="modalTitle" modal-size="large" centered no-header>
        <template #form="modalData">
            <div class="d-flex flex-row">
                <div class="flex-row-fluid px-10 py-12">
                    <div v-if="!isLoading">
                        <div class="d-flex flex-column gap-3 gap-xxl-6">
                            <div class="d-flex flex-center">
                                <span class="fs-5 fw-normal">{{ modalTitle }}</span>
                            </div>
                            <div class="row g-2 g-lg-4">
                                <div class="col" v-if="(!form.hasAbsence) || (permitTime < 480)">
                                    <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 position-relative">
                                        <div class="m-0">
                                            <span class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">{{ ordinaryTime.toPrintedDuration() }}</span>
                                            <span class="text-gray-500 fw-semibold fs-6">Ordinario</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col" v-if="!!overTime">
                                    <div class="bg-light-info bg-opacity-70 rounded-2 px-6 py-5">
                                        <div class="m-0">
                                            <span class="text-info fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">{{ overTime.toPrintedDuration() }}</span>
                                            <span class="text-info opacity-75 fw-semibold fs-6">Straordinario</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col" v-if="!!permitTime && form.hasAbsence">
                                    <div class="bg-light-danger bg-opacity-70 rounded-2 px-6 py-5">
                                        <div class="m-0">
                                            <span class="text-danger fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">{{ permitTime.toPrintedDuration() }}</span>
                                            <span class="text-danger opacity-75 fw-semibold fs-6">Assenza</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="rounded-2 px-6 py-5 position-relative" :class="(totalTime < 480) ? 'bg-light-danger' : 'bg-gray-100 bg-opacity-70'">
                                        <div class="m-0">
                                            <span class="fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1" :class="(totalTime < 480) ? 'text-danger' : 'text-gray-700'">{{ totalTime.toPrintedDuration() }}
                                            </span>
                                            <span class="fw-semibold fs-6" :class="(totalTime < 480) ? 'text-danger' : 'text-gray-500'">Totale</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.hasErrors" class="alert alert-danger d-flex align-items-center mb-0 py-2 px-4 mt-3">
                            <ul class="m-0">
                                <li v-for="(error, key) in form.errors" :key="key">{{ error }}</li>
                            </ul>
                        </div>
                    </div>
                    <AppBoxLoader v-if="isLoading" class="mt-xxl-6 mt-3" />
                    <div v-else>
                        <div v-if="tab === 'attendance'" class="d-flex flex-column gap-3 gap-xxl-6">
                            <div class="separator separator-content mt-10">Sessioni</div>
                            <div>
                                <table class="table table-responsive table-row-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap"></th>
                                            <th>Inizio</th>
                                            <th>Fine</th>
                                            <th class="text-center">Durata</th>
                                            <th class="text-nowrap"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="align-middle">
                                        <tr v-for="(presence, index) in form.presences" :key="index" v-if="form.presences">
                                            <td class="text-nowrap">{{ index + 1 }}</td>
                                            <td>
                                                <InputTime :form="form" :name="'presence-'+index" v-model.lazy.trim="presence.started_at" @focus="form.clearErrors()" />
                                            </td>
                                            <td>
                                                <InputTime :form="form" :name="'presence-'+index" v-model.lazy.trim="presence.ended_at" @focus="form.clearErrors()" />
                                            </td>
                                            <td class="text-center">
                                                {{ printedDuration(presence.started_at, presence.ended_at) }}
                                            </td>
                                            <td class="text-nowrap text-end">
                                                <button role="button" class="btn btn-icon btn-light btn-active-light-danger btn-sm" title="Elimina" @click.prevent="removeSession(index)">
                                                    <i class="bi bi-x-lg text-hover-danger fs-4" />
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="!form.presences.length">
                                            <td colspan="5" class="text-center pb-0">
                                                <p class="p-4 w-100 bg-light rounded m-0">Nessuna Sessione</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button role="button" class="btn btn-primary w-100" title="Aggiungi Sessione" @click.prevent="addSession">
                                    Aggiungi Sessione
                                    <i class="bi bi-plus-lg fs-4" />
                                </button>
                            </div>
                            <div class="card card-bordered" v-if="!!permitTime">
                                <div class="card-body row px-6 py-4">
                                    <div class="col d-flex flex-start align-items-center">
                                        <div class="form-check form-switch form-check-custom form-check-danger form-check-solid py-3">
                                            <input id="toggle_absence" v-model="form.hasAbsence" checked class="form-check-input " type="checkbox" />
                                            <label class="form-check-label" for="toggle_absence">
                                                <span v-if="form.hasAbsence" class="text-danger fw-bold">
                                                    <span v-if="permitTime === 480">Assenza Totale per:</span>
                                                    <span v-else>Assenza Parziale per:</span>
                                                </span>
                                                <span v-else>Nessuna Assenza</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col d-flex flex-center">
                                        <InputSelect class="w-100" v-if="form.hasAbsence" hide-search :items="absenceCauses" :form="form" name="absence" in-modal required v-model="form.absence" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="tab === 'activities'" class="d-flex flex-column gap-3 gap-xxl-6">
                            <div class="separator separator-content mt-10">Attività</div>
                            <div class="row g-2 g-lg-4">
                                <div class="col">
                                    <table class="table table-row-bordered table-responsive m-0">
                                        <thead>
                                            <tr>
                                                <th>Progetto</th>
                                                <th class="text-nowrap">Data Inizio</th>
                                                <th class="text-nowrap">Ore Dedicate</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody class="align-middle">
                                            <tr v-for="(project, key) in form.projects" :key="project.id" v-if="form.projects.length">
                                                <td>
                                                    <span class="mb-1 fs-6 text-dark fw-bold">{{ project.name }}</span>
                                                    <span class="text-muted d-block">{{ project.customer_name }}</span>
                                                </td>
                                                <td>
                                                    {{ moment(project.started_at).format('MMM YYYY').capitalize() }}
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-solid" v-model="form.projects[key].duration">
                                                        <option v-for="option in timeOptions" :key="option.value" :value="option.value" :selected="project.duration === option.value">{{ option.text }}
                                                        </option>
                                                    </select>
                                                </td>
                                                <td class="text-nowrap text-end">
                                                    <button role="button" class="btn btn-icon btn-light btn-sm btn-active-light-danger" title="Elimina" @click.prevent="quickRemoveProject(project.id)">
                                                        <i class="bi bi-x-lg text-hover-danger fs-4"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr v-if="form.projects.length" class="text-dark">
                                                <td class="text-end" colspan="2">Totale:</td>
                                                <td>{{ projectsTimeDedicated.toPrintedDuration() }}</td>
                                                <td></td>
                                            </tr>
                                            <tr v-if="!form.projects.length">
                                                <td colspan="4" class="text-center">
                                                    <p class="p-4 bg-light w-100 rounded m-0">Nessun Progetto</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row" v-if="!(projectsTimeDedicated >= totalOrdinaryTime)">
                                <div class="col">
                                    <InputSelectProjects :user_id="props.user_id" @project-selected="quickAddProject" placeholder="Selezione Progetto..." :form="form" name="projects" in-modal />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-row-auto min-w-200px">
                    <div class="bg-gray-100 pe-6 ps-0 py-6 gap-4 rounded-end h-100 d-flex flex-column">
                        <div class="d-flex flex-column gap-2 flex-column-fluid mb-12">
                            <a class="btn bg-hover-body rounded-start-0" :class="(tab === 'attendance') ? 'btn-bg-white' : 'btn-bg-light'" @click.prevent="changeTab('attendance')">
                                <i class="bi bi-calendar-week fs-4 me-2" />
                                Sessioni
                            </a>
                            <a class="btn bg-hover-body rounded-start-0" :class="(tab === 'activities') ? 'btn-bg-white' : 'btn-bg-light'" @click.prevent="changeTab('activities')">
                                <i class="b bi-activity fs-4 me-2" />
                                Attività
                            </a>
                        </div>
                        <div class="d-flex flex-row gap-2 ps-6 flex-column-auto"></div>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="modal-footer w-100">
                <div class="d-flex flex-center">
                    <div class="text-end">
                        <button type="button" class="btn btn-light me-3" @click.prevent="modal?.close()">
                            Annulla
                        </button>
                        <button @click.prevent="submit" class="btn btn-primary" :disabled="form.processing || !form.isDirty">
                            <span class="indicator-label" v-if="!(form.processing)">Salva</span>
                            <span class="indicator-label" v-else>Attendere...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </AppModal>
</template>
<script lang="ts" setup>
    import AppModal from "@Components/AppModal.vue";
    import { computed, onMounted, ref, watch } from "vue";
    import Attendance from "@Models/Attendance";
    import InputTime from "@Components/Forms/InputTime.vue";
    import InputSelect from "@Components/Forms/InputSelect.vue";
    import InputSelectProjects from "@Components/Forms/InputSelectProjects.vue";
    import useForm from "@Composables/useForm";
    import useModal from "@Composables/useModal";
    import AppBoxLoader from "@Components/AppBoxLoader.vue";
    import { useGlobalStore } from "@Stores/useGlobalStore";
    import { useAttendancesStore } from "@Stores/useAttendancesStore";

    interface Props {
        date: string,
        user_id: number,
        attendance_id?: number
    }

    interface FormattedPresence {
        id: string | null,
        started_at: string,
        ended_at: string,
        index: number
    }

    const props = defineProps<Props>()
    const emits = defineEmits(['modal_closed'])

    const modal = useModal('attendance_modal')
    const tab = ref('attendance')
    const isLoading = ref(true)

    const Global = useGlobalStore()
    const attendancesStore = useAttendancesStore()

    const attendance = ref<Attendance|null>(null)
    const projects = computed(() => attendance.value?.projects ?? [])
    const presences = computed(() => {
        if (!!attendance.value?.hasPresences) {
            return attendance.value.presences!.map((item, index) => {
                return {
                    index: index + 1,
                    id: item.id,
                    started_at: item.start_time,
                    ended_at: item.end_time,
                } as FormattedPresence
            })
        } else {
            return []
        }
    })
    const timeOptions = computed(() => {
        return [
            { value: 0, text: '--' },
            { value: 30, text: '0.5h' },
            { value: 60, text: '1h' },
            { value: 90, text: '1.5h' },
            { value: 120, text: '2h' },
            { value: 150, text: '2.5h' },
            { value: 180, text: '3h' },
            { value: 210, text: '3.5h' },
            { value: 240, text: '4h' },
            { value: 270, text: '4.5h' },
            { value: 300, text: '5h' },
            { value: 330, text: '5.5h' },
            { value: 360, text: '6h' },
            { value: 390, text: '6.5h' },
            { value: 420, text: '7h' },
            { value: 450, text: '7.5h' },
            { value: 480, text: '8h' },
            { value: 510, text: '8.5h' },
            { value: 540, text: '9h' },
            { value: 570, text: '9.5h' },
            { value: 600, text: '10h' },
            { value: 630, text: '10.5h' },
            { value: 660, text: '11h' },
            { value: 690, text: '11.5h' },
            { value: 720, text: '12h' }
        ]
    })
    const absence = computed(() => {
        return attendance.value?.absence ?? null
    })
    const modalTitle = computed(() => {
        return (attendance.value) ? moment(attendance.value.date).format('dddd, DD MMM YYYY').capitalize() : moment(props.date).format('dddd, DD MMM YYYY').capitalize()
    })

    const form = useForm({
        presences: [] as FormattedPresence[],
        absence: null as string|null,
        hasAbsence: false,
        projects: [] as {
            id?: number,
            name?: string | null,
            customer_name?: string,
            duration: number,
            started_at?: string | null
        }[]
    })

    function setForm() {
        form.presences = presences.value
        form.absence = absence.value
        form.hasAbsence = !!absence.value
        form.projects = projects.value.length ? projects.value.map(project => {
            return {
                id: project.id,
                name: project.name,
                customer_name: project.customer?.name,
                duration: project.pivot.duration,
                started_at: project.started_at
            }
        }) : []

        form.setDefaults()
    }

    const totalOrdinaryTime = computed(() => {
        const sum = roundedSumPresences(form.presences);
        return (sum < 0) ? 0 : sum
    })

    const ordinaryTime = computed(() => {
        return (totalOrdinaryTime.value > 480) ? 480 : totalOrdinaryTime.value
    })

    const overTime = computed(() => {
        const hasOvertime = totalOrdinaryTime.value > 480
        return (hasOvertime) ? (totalOrdinaryTime.value - 480) : 0
    })

    const permitTime = computed(() => {
        return (totalOrdinaryTime.value <= 450) ? (480 - totalOrdinaryTime.value) : 0
    })

    const totalTime = computed(() => {
        return (!!attendance.value || !!totalOrdinaryTime.value || !!permitTime.value) ? ((form.hasAbsence) ? (totalOrdinaryTime.value + permitTime.value) : totalOrdinaryTime.value) : 0
    })

    const projectsTimeDedicated = computed(() => {
        return (!!form.projects.length) ? form.projects.reduce((sum, prev) => sum + prev.duration, 0) : 0
    })

    const absenceCauses = computed<SelectOption[]>(() => {
        return [{
            value: 'holidays',
            title: 'Ferie'
        },
        {
            value: 'permit',
            title: 'Permesso'
        },
        {
            value: 'sickness',
            title: 'Malattia'
        }]
    })

    function changeTab(tabName: string) {
        tab.value = tabName;
    }

    watch(() => form.hasAbsence, (value) => {
        form.absence = value ? 'permit' : null
    })

    watch(() => totalOrdinaryTime.value, value => {
        if (value >= 480) {
            if (form.hasAbsence) {
                form.hasAbsence = false
                form.absence = null
            }
        }
    })

    function roundedSumPresences(presences: FormattedPresence[]): number {
        const total = presences.reduce((sum, prev) => {
            const previousValue = prev.ended_at ? moment(prev.ended_at, 'HH:mm').diff(moment(prev.started_at, 'HH:mm'), 'minute') : 0
            return (!!previousValue) ? sum + previousValue : sum
        }, 0)
        const module = total % 30
        return (module >= 20) ? total + (30 - module) : total - module
    }

    function printedDuration(start: string, end: string): string {
        const endTime = moment(end, 'HH:mm')
        const startTime = moment(start, 'HH:mm')

        if (startTime.valueOf() < endTime.valueOf()) {
            return endTime.diff(startTime, 'minute').toPrintedDuration()
        } else {
            return 'Invalid'
        }
    }

    function addSession() {
        const prevSessionEnd = !!form.presences.length ? moment(form.presences[form.presences.length - 1].ended_at, 'HH:mm') : null;
        const start = prevSessionEnd?.clone().add(30, 'minutes') ?? moment('09:30', 'HH:mm');
        const end = (start.clone().add(4, 'hours').isBefore(start)) ? moment('23:59', 'HH:mm') : start.clone().add(4, 'hours')
        const newObject = {
            index: form.presences?.length + 1 ?? 1,
            id: null,
            started_at: start.format('HH:mm'),
            ended_at: end.format('HH:mm')
        } as FormattedPresence
        form.presences.push(newObject);
    }

    function removeSession(index: number) {
        if (index === 0) {
            form.presences.shift()
        } else {
            form.presences.splice(index, 1)
        }
    }

    function submit() {
        form.transform(data => {
            const initialSessions = form.defaults().presences

            let toEditSessions: FormattedPresence[] = data.presences.filter(item => !!(initialSessions.find(object => object.id === item.id && JSON.stringify(object) !== JSON.stringify(item))))
            let toDeleteSessions: FormattedPresence[] = initialSessions.filter(item => !data.presences.find(object => object.id === item.id))
            let toCreateSession: FormattedPresence[] = data.presences.filter(item => !item.id)

            let toEditAbsence;

            const hasAbsenceIsSame = data.hasAbsence === form.defaults().hasAbsence
            const absenceIsSame = JSON.stringify(data.absence) === JSON.stringify(form.defaults().absence)

            if (hasAbsenceIsSame) {
                if (data.hasAbsence && !absenceIsSame) {
                    toEditAbsence = data.absence
                }
            } else {
                if (data.hasAbsence) {
                    toEditAbsence = data.absence
                } else {
                    toEditAbsence = null
                }
            }

            const toSyncProjects = (JSON.stringify(form.defaults().projects) !== JSON.stringify(data.projects)) ? data.projects.map(item => {
                return {
                    id: item.id,
                    duration: item.duration
                }
            }) : undefined;

            return {
                edit: toEditSessions?.length ? toEditSessions : undefined,
                create: toCreateSession.length ? toCreateSession : undefined,
                delete: toDeleteSessions.length ? toDeleteSessions : undefined,
                absence: toEditAbsence,
                date: props.date,
                user_id: props.user_id,
                projects: toSyncProjects
            }
        }).put( 'attendances/' + (attendance.value?.id ?? '') , {
            onBefore() {
            },
            async onSuccess(res?) {
                attendance.value = new Attendance(res.data.attendance)
                Global.pushToast('success', res.data.toast)

                setForm()
                await attendancesStore.load(props.user_id, props.date)
            },
            onError(error?): any {
                form.setErrors(error.data.errors)
            },
            onFinish() {
            }
        })
    }

    function quickAddProject(project: { id: number, name: string, customer_name: string, duration: number, started_at: string }) {
        form.projects.push(project);
    }

    function quickRemoveProject(projectId: number) {
        form.projects = form.projects.filter(item => item.id !== projectId)
    }

    onMounted(  () => {
        modal.open()
        modal.onClosed(() => {
            emits('modal_closed')
        })
        modal.onOpened(async() => {
            if (props.attendance_id) {
                isLoading.value = true
                await axios.get('attendances/' + props.attendance_id, { params: { with: ['projects'] } }).then((res) => {
                    attendance.value = new Attendance(res.data.attendance)

                    setForm()

                }).finally(() => {
                    isLoading.value = false
                })
            } else {
                isLoading.value = false
            }
        })

    })
</script>
<style>
	.list-move, /* apply transition to moving elements */
	.list-enter-active,
	.list-leave-active {
		transition: all 0.5s ease;
		}

	.list-enter-from,
	.list-leave-to {
		opacity: 0;
		transform: translateX(30px);
		}

	/* ensure leaving items are taken out of layout flow so that moving
       animations can be calculated correctly. */
	.list-leave-active {
		position: absolute;
		}
</style>
