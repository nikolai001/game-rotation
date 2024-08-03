import { createApp } from 'vue'
import mdiVue from 'mdi-vue/v3'
import * as mdijs from '@mdi/js'
import App from './App.vue'
import './index.css'

createApp(App).use(mdiVue, {
    icons: mdijs
}).mount('#app')