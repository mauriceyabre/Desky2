import "@/bootstrap.ts";

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './Router/router'

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
