import { createApp } from 'vue';
import './bootstrap';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from '../js/routes.js';


const app = createApp({});

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
})

app.use(router);

app.mount('#app');
