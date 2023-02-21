import "./bootstrap.ts";
import "./Helpers/PrototypeExtensions.ts"

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './Router/router'
import flatpickr from "flatpickr";
import Toast, { PluginOptions, POSITION } from "vue-toastification"
import { Italian } from "flatpickr/dist/l10n/it"

import App from './App.vue'


flatpickr.localize(Italian);

const toastOptions: PluginOptions = {
    position: POSITION.BOTTOM_RIGHT,
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: false,
    icon: true,
    rtl: false,
    transition: "Vue-Toastification__fade",
    maxToasts: 5,
    newestOnTop: true
}

moment.locale('it');
moment.defaultFormat = 'YYYY-MM-DD HH:mm:ss'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(Toast, toastOptions)
    .mixin({ methods: { moment } })

app.mount('#app')
