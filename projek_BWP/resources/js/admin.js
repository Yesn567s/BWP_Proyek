import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import AdminApp from './components/admin/AdminApp.vue';
import NewMovies from './components/admin/newMovies.vue';

const Empty = { render() { return null } }

const adminRoutes = [
  {
    path: '/',
    name: 'adminDashboard',
    // empty root — AdminApp will show its internal dashboard sections by default
    component: Empty
  },
  {
    path: '/movieCatalog',
    name: 'adminMovieCatalog',
    component: Empty
  },
  {
    path: '/cinemaCatalog',
    name: 'adminCinemaCatalog',
    component: Empty
  },
  {
    path: '/foodCatalog',
    name: 'adminFoodCatalog',
    component: Empty
  },
  {
    path: '/blogList',
    name: 'adminBlogList',
    component: Empty
  },
  {
    path: '/transactionLog',
    name: 'adminTransactionLog',
    component: Empty
  },
  {
    path: '/newMovies',
    name: 'adminNewMovies',
    component: NewMovies
  }
];

const router = createRouter({
  history: createWebHistory('/admin'), // scope admin router to /admin base
  routes: adminRoutes,
});

createApp(AdminApp).use(router).mount('#admin-app');
