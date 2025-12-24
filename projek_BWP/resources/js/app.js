import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/home.vue';
import Tickets from './components/tickets.vue';
import UserTickets from './components/userTickets.vue';
import AboutUs from './components/aboutUs.vue';
import ChooseDate from './components/schedule.vue';
import Schedule from './components/schedule.vue';

const routes=[
    {
        path:'/',
        component: Home
    },
    {
        path:'/tickets',
        // name: kalo ada pake alias nama route
        component: Tickets
    },
    {
        path:'/userTickets',
        // name:
        component: UserTickets
    },
    {
        path: '/aboutUs',
        component: AboutUs
    },
    {
        path: '/schedule',
        component: Schedule
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


// register router and mount app with router
createApp(App).use(router).mount('#app');
