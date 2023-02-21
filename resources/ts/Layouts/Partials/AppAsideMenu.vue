<template>
    <div id="kt_aside_menu_wrapper" class="w-100 d-flex flex-column h-100 py-12">
        <div id="kt_aside_menu" class="col menu menu-column">
            <div v-for="(item, index) in menuItems" :key="index" :class="{'here': item.starts.includes(pathName), 'm-0': (index + 1) === menuItems.length}" :title="item.title" class="menu-item py-2"
                    data-bs-dismiss="click" data-bs-placement="right" data-bs-toggle="tooltip">
                <RouterLink class="menu-link menu-center" :to="item.route">
                    <span class="menu-icon me-0" v-html="item.icon" />
                </RouterLink>
            </div>
        </div>
<!--        <div class="col d-flex justify-content-end flex-column">
            <div v-if="route().current('projects.*')" class="menu menu-column">
                <div :class="{'here': route().current().endsWith('completed')}" class="menu-item m-0 py-2" data-bs-dismiss="click" data-bs-placement="right" data-bs-toggle="tooltip"
                        title="Progetti Completati">
                    <AppLink :href="route('projects.completed')" class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="bi bi-check-lg fs-1" />
                        </span>
                    </AppLink>
                </div>
                <div :class="{'here': route().current().endsWith('archived')}" class="menu-item m-0 py-2" data-bs-dismiss="click" data-bs-placement="right" data-bs-toggle="tooltip"
                        title="Progetti Archiviati">
                    <AppLink :href="route('projects.archived')" class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="bi bi-archive fs-1" />
                        </span>
                    </AppLink>
                </div>
            </div>
            <div v-if="route().current('team.index')" class="menu menu-column">
                <div :class="{'here': route().current().endsWith('archived')}" class="menu-item m-0 py-2" data-bs-dismiss="click" data-bs-placement="right" data-bs-toggle="tooltip"
                        title="Membri Archiviati">
                    <AppLink :href="route('team.members.archived')" class="menu-link menu-center">
                        <span class="menu-icon me-0">
                            <i class="bi bi-archive fs-1" />
                        </span>
                    </AppLink>
                </div>
            </div>
        </div>-->
    </div>
</template>
<script lang="ts">
    import { defineComponent } from "vue";

    export default defineComponent({
        name: 'AppAsideMenu',
        data() {
            return {
                menuItems: [
                    {
                        title: 'Dashboard',
                        slug: 'dashboard',
                        route: {name: 'dashboard'},
                        starts: ['dashboard'],
                        icon: `<span class="svg-icon svg-icon-muted svg-icon-2hx"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M13.0021 10.9128V3.01281C13.0021 2.41281 13.5021 1.91281 14.1021 2.01281C16.1021 2.21281 17.9021 3.11284 19.3021 4.61284C20.7021 6.01284 21.6021 7.91285 21.9021 9.81285C22.0021 10.4129 21.5021 10.9128 20.9021 10.9128H13.0021Z" fill="currentColor"/>
<path opacity="0.3" d="M11.0021 13.7128V4.91283C11.0021 4.31283 10.5021 3.81283 9.90208 3.91283C5.40208 4.51283 1.90209 8.41284 2.00209 13.1128C2.10209 18.0128 6.40208 22.0128 11.3021 21.9128C13.1021 21.8128 14.7021 21.3128 16.0021 20.4128C16.5021 20.1128 16.6021 19.3128 16.1021 18.9128L11.0021 13.7128Z" fill="currentColor"/>
<path opacity="0.3" d="M21.9021 14.0128C21.7021 15.6128 21.1021 17.1128 20.1021 18.4128C19.7021 18.9128 19.0021 18.9128 18.6021 18.5128L13.0021 12.9128H20.9021C21.5021 12.9128 22.0021 13.4128 21.9021 14.0128Z" fill="currentColor"/>
</svg>
</span>`
                    },
                    {
                        title: 'Clienti',
                        slug: 'customers',
                        starts: ['customers'],
                        route: 'customers.index',
                        icon: `<span class="svg-icon svg-icon-muted svg-icon-2hx"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z" fill="currentColor"/>
<path opacity="0.3" d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z" fill="currentColor"/>
</svg>
</span>`
                    },
                    {
                        title: 'Progetti',
                        slug: 'projects',
                        starts: ['projects'],
                        route: 'projects.index',
                        icon: `<span class="svg-icon svg-icon-muted svg-icon-2hx"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path opacity="0.3" d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z" fill="currentColor"/>
<path d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z" fill="currentColor"/>
</svg>
</span>`
                    },
                    {
                        title: 'Team',
                        slug: 'team',
                        starts: ['members'],
                        route: 'members.index',
                        icon: `<span class="svg-icon svg-icon-muted svg-icon-2hx"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z" fill="currentColor"/>
<rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4" fill="currentColor"/>
</svg>
</span>`
                    }
                ],
            }
        },
        computed: {
            pathName() {
                return 'dashboard';
            }
        }
    })
</script>
