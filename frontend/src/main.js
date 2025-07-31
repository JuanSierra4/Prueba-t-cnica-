import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// Bootstrap
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap'

// Font Awesome
import '@fortawesome/fontawesome-free/css/all.min.css'

const app = createApp(App)

app.use(router)

app.mount('#app')
