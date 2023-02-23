<template>
    <div>
        <div class="row g-2 g-xxl-4">
            <div class="col-12">
                <div class="card card-flush">
                    <div class="card-header">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800 fs-1">{{ selectedDate.format('MMMM YYYY').capitalize() }}</span>
                        </h3>
                        <div class="card-toolbar">
                            <div class="btn-group btn-group-sm" role="group">
                                <button :disabled="isLoading" type="button" class="btn btn-light" @click.prevent="goToMonth('previous')">
                                    <i class="bi bi-chevron-left" />
                                </button>
                                <button :disabled="selectedDate.isSame(moment(), 'month') || isLoading" type="button" class="btn btn-light" @click.prevent="goToMonth('current')">
                                    Oggi
                                </button>
                                <button type="button" :disabled="selectedDate.isSame(moment(), 'month') || isLoading" class="btn btn-light" @click.prevent="goToMonth('next')">
                                    <i class="bi bi-chevron-right" />
                                </button>
                            </div>
                            <button type="button" class="btn btn-light btn-sm btn-icon ms-2" @click.prevent="printTimesheet" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Stampa">
                                <i class="bi bi-printer" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="row g-2 g-xxl-4">
                    <div class="col" v-for="(mappedAttendances, key) in mappedAttendancesGroups" :key="key">
                        <div class="card card-flush">
                            <div class="card-body">
                                <div :class="{'opacity-50': isLoading}">
                                    <table class="table table-responsive table-row-bordered align-middle">
                                        <thead>
                                            <tr class="fs-7 fw-bold text-gray-400 border-bottom-0">
                                                <th class="p-0 pb-3 min-w-200px" colspan="2">DATA</th>
                                                <th class="p-0 pb-3 min-w-225px">ORARIO</th>
                                                <th class="p-0 pb-3 text-end"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(attendance, date) in mappedAttendances" :key="date" :class="{'table-active': [5, 6].includes(moment(date).weekday())}">
                                                <td class="text-nowrap w-1px">
                                                    <div class="symbol symbol-40px">
                                                        <div class="symbol-label d-flex flex-column" :class="{'bg-success text-inverse-success': moment(date).isSame(moment().format('YYYY-MM-DD'))}">
                                                            <span class="fs-4 lh-1">
                                                                {{ moment(date).date() }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="mb-1 fs-6 text-dark fw-bold">
                                                        {{ moment(date).format('dddd').capitalize() }}
                                                    </span>
                                                    <span class="text-muted d-block">
                                                        {{ moment(date).format('MMMM').capitalize() }}
                                                    </span>
                                                </td>
                                                <td class="ps-0">
                                                    <div class="text-gray-600 fw-bold fs-6">
                                                        <div v-if="attendance?.roundedPresenceDuration" class="d-inline">
                                                            <span class="badge badge-light-dark fs-7 me-2 px-4 py-3" :class="{'animation-blink': attendance?.hasActiveSession}">
                                                                {{ attendance.roundedPresenceDuration.toPrintedDuration() }}
                                                            </span>
                                                        </div>
                                                        <span class="badge badge-light-danger fs-7 px-4 py-3" v-if="attendance?.hasAbsence" data-bs-toggle="tooltip" :title="attendance.absence.capitalize()">
                                                            <i class="bi bi-h-square me-2 text-danger" v-if="attendance.absence === 'sickness'" />
                                                            <i class="bi bi-exclamation-triangle me-2 text-danger" v-if="attendance.absence === 'permit'" />
                                                            <i class="bi bi-power me-2 text-danger" v-if="attendance.absence === 'holidays'" />
                                                            {{ attendance.roundedAbsenceDuration.toPrintedDuration() }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px" role="button" data-bs-toggle="tooltip" data-bs-dismiss="click"
                                                            title="Modifica" @click.prevent="editAttendance(date)">
                                                        <span class="svg-icon svg-icon-5 svg-icon-gray-700">
                                                            <i class="bi bi-pencil-fill" />
                                                        </span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-none" id="printable-area" ref="printableArea">
                        <h1 style="margin-bottom: 8px; line-height: 1">{{ user?.name }}</h1>
                        <h4>Presenze di: {{ selectedDate.format('MMMM YYYY').toUpperCase() }}</h4>
                        <table class="">
                            <thead>
                                <tr>
                                    <th>Giorno</th>
                                    <th>Ordinario</th>
                                    <th>Straordinari</th>
                                    <th>Permesso</th>
                                    <th>Ferie</th>
                                    <th>Malattia</th>
                                </tr>
                            </thead>
                            <tbody v-for="(group, key) in mappedAttendancesGroups" :key="'group'+key">
                                <tr v-for="(attendance, date) in group" :key="'item'+date">
                                    <td>{{ moment(date).format('ddd DD').capitalize() }}</td>
                                    <td>{{ (!!attendance && !!attendance.roundedPresenceDuration) ? ((attendance.hasOvertime) ? 8 : (attendance.roundedPresenceDuration / 60)) : null }}</td>
                                    <td>{{ (!!attendance && !!attendance.roundedOvertimeDuration) ? attendance.roundedOvertimeDuration / 60 : null }}</td>
                                    <td>{{ attendance?.hasAbsence && attendance.absence === 'permit' ? attendance.roundedAbsenceDuration / 60 : null }}</td>
                                    <td>{{ attendance?.hasAbsence && attendance.absence === 'holidays' ? attendance.roundedAbsenceDuration / 60 : null }}</td>
                                    <td>{{ attendance?.hasAbsence && attendance.absence === 'sickness' ? attendance.roundedAbsenceDuration / 60 : null }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <teleport to="body">
            <MemberAttendanceEditModal v-if="selectedDay" :user_id="user?.id" :date="selectedDay" @modal_closed="reset" :attendance_id="selectedAttendanceId" />
        </teleport>
    </div>
</template>
<script setup lang="ts">
    // import '@fullcalendar/core/vdom'
    import User from "@Models/User"
    import { computed, onMounted, onUpdated, ref, watch } from "vue"
    import Attendance from "@Models/Attendance";
    import MemberAttendanceEditModal from "@Pages/App/Members/Partials/MemberAttendanceEditModal.vue";
    import { useUserStore } from "@Stores/useUserStore";
    import { useAttendancesStore } from "@Stores/useAttendancesStore";
    import { useAuthStore } from "@Stores/useAuthStore";
    import debounce from "lodash.debounce";

    interface Props {
        date: String
    }

    const props = defineProps<Props>()

    const userStore = useUserStore()
    const attendancesStore = useAttendancesStore()

    const isLoading = ref(false)
    const selectedDay = ref<string | null>(null)
    const printableArea = ref<HTMLElement>()

    const user = computed(() => userStore.user)
    const attendances = computed(() => attendancesStore.attendances ?? [])
    const currentDate = computed(() => (attendances.value.length) ? attendances.value[0].date : moment().format())
    const theDate = ref(moment(currentDate.value).startOf('day').format())
    const selectedDate = computed(() => moment(theDate.value))
    const selectedAttendanceId = computed(() => {
        if (selectedDay.value) {
            return attendances.value.find(attendance => attendance.date === selectedDay.value)?.id ?? null
        }
        return null
    })

    const mappedAttendancesGroups = computed(() => {
        let mapped: { [key: string]: Attendance | null }[] = []
        let group1: { [key: string]: Attendance | null } = {}
        let group2: { [key: string]: Attendance | null } = {}
        const numberOfDays = selectedDate.value.daysInMonth()
        for (let i = 1; i <= numberOfDays; i++) {
            const date = selectedDate.value.date(i).format("YYYY-MM-DD")
            const attendance = attendances.value.find(item => moment(item.date).isSame(moment(date)))
            if (i <= 16) {
                group1[date] = attendance ?? null
            } else {
                group2[date] = attendance ?? null
            }
        }
        mapped.push(group1, group2)
        return mapped;
    })

    function goToMonth(action: 'previous' | 'next' | 'current' = 'current') {
        isLoading.value = true
        if (action === 'previous') {
            theDate.value = selectedDate.value.subtract(1, "month").format()
        } else if (action === 'next') {
            theDate.value = selectedDate.value.add(1, "month").format()
        } else if (action === 'current') {
            theDate.value = moment().format()
        }
    }

    function editAttendance(date: string) {
        selectedDay.value = date
    }

    function reset() {
        selectedDay.value = null
    }

    function printTimesheet() {
        const divElementContents = document.getElementById("printable-area")?.innerHTML ?? '';
        const windows = window.open('', '');
        windows?.document.write('<html>');
        windows?.document.write('<style>'+
                ' table, th, td { border:1px solid black;} '+
                ' td, th { padding: 4px 8px; }'+
                ' body table { border-spacing: 0;}' +
                ' body {' +
                '    font-family: monospace;' +
                '}'+
            '}'+
            '</style>')
        windows?.document.write('<body>');
        windows?.document.write(divElementContents);
        windows?.document.write('</body></html>');
        windows?.document.close();
        windows?.print();
    }

    watch(theDate,  (value, oldValue) => {
        if (value.valueOf() !== oldValue.valueOf()) {
            isLoading.value = false
            debounce(async () => {
                await attendancesStore.load(user.value!.id, value)
            }, 500)()
        }
    })

    onUpdated(() => {
        KTComponents.init();
    })

    onMounted(async () => {
        if (!userStore.user && useAuthStore().user) {
            await userStore.load(useAuthStore().user!.id)
        }
        if (userStore.user) {
            await attendancesStore.load(userStore.user!.id)
        }
    })
</script>
