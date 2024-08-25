import { createApp } from 'vue'
import mdiVue from 'mdi-vue/v3'
import * as mdijs from '@mdi/js'
import mitt from 'mitt'
import App from './App.vue'
import './index.css'

const emitter = mitt()

const app = createApp(App).use(mdiVue, {
    icons: mdijs
})
app.provide('emitter', emitter);
app.mount('#app')