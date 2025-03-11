import './bootstrap';

import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';

import '../css/app.css';
import axios from 'axios';

axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.withCredentials = true;

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

import Vue3Toastify from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })

        app.use(Vue3Toastify, {
            autoClose: 3000, // Cierra el toast en 3 segundos
            position: "top-right"
        });

        app.config.globalProperties.$axios = axios;
        app.use(plugin).mount(el)
    },
})
