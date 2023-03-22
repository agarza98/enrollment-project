import {createApp} from 'vue'
import App from './App.vue';
import {createWebHistory, createRouter} from "vue-router";
import {routes} from './routes';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/js/bootstrap.js'

const router = createRouter({
    history: createWebHistory(),
    routes,
});
import {Bootstrap4Pagination} from 'laravel-vue-pagination';

const app = createApp(App)

app.use(router)
app.component('Bootstrap4Pagination', Bootstrap4Pagination)
app.mount("#app")
