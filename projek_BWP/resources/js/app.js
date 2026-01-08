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
import NonMovieSchedule from './components/user/nonMovieSchedule.vue';
import Seats from './components/user/seats.vue';
import Blog from './components/user/blog.vue';
import Checkout from './components/user/checkout.vue';

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
        path: '/food-menu',
        name: 'food-menu',
        component: FoodMenu
    },
    {
        path: '/non-movie-schedule',
        name: 'nonMovieSchedule',
        component: NonMovieSchedule
    },
    {
        path: '/fun',
        name: 'fun',
        component: Fun
    },
    {
        path: '/blog',
        name: 'blog',
        component: Blog,
        props: route => ({
            postId: route.query.id ? Number(route.query.id) : null
        })
    },
    {
        path: '/checkout',
        name: 'checkout',
        component: Checkout
    },
    {
        path: '/seats/:scheduleId',
        name: 'seats',
        component: Seats,
        props: route => ({
            scheduleId: Number(route.params.scheduleId) || null,
            studioName: route.query.studioName ?? null,
            venueName: route.query.venueName ?? null,
            date: route.query.date ?? null,
        })
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


// register router and mount app with router
createApp(App).use(router).mount('#app');
