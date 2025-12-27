import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/user/home.vue';
import Tickets from './components/user/tickets.vue';
import UserTickets from './components/user/userTickets.vue';
import AboutUs from './components/user/aboutUs.vue';
import Schedule from './components/user/schedule.vue';
import AccountPage from './components/user/accountPage.vue';
import Movie from './components/user/movie.vue';
import Food from './components/user/food.vue';
import Fun from './components/user/fun.vue';
import FoodMenu from './components/user/foodMenu.vue';

const routes=[
    {
        path:'/',
        name: 'userHome',
        component: Home
    },
    {
        path:'/tickets',
        name: 'tickets',
        component: Tickets
    },
    {
        path:'/userTickets',
        name: 'userTickets',
        component: UserTickets
    },
    {
        path: '/aboutUs',
        name: 'aboutUs',
        component: AboutUs
    },
    {
        path: '/schedule',
        name: 'schedule',
        component: Schedule
    },
    {
        path: '/accountPage',
        name: 'accountPage',
        component: AccountPage
    },
    {
        path: '/movies',
        name: 'movies',
        component: Movie
    },
    {
        path: '/food',
        name: 'food',
        component: Food
    },
    {
        path: '/food/:venueId',
        name: 'food-menu',
        component: FoodMenu,
        props: true
    },
    {
        path: '/fun',
        name: 'fun',
        component: Fun
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


// register router and mount app with router
createApp(App).use(router).mount('#app');
