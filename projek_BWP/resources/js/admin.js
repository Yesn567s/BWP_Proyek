import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import AdminApp from './components/admin/AdminApp.vue';
import AdminLayout from './components/admin/layout/AdminLayout.vue';
import DashboardView from './components/admin/views/DashboardView.vue';
import MovieCatalogView from './components/admin/views/MovieCatalogView.vue';
import MovieDetailView from './components/admin/views/MovieDetailView.vue';
import CinemaCatalogView from './components/admin/views/CinemaCatalogView.vue';
import FoodCatalogView from './components/admin/views/FoodCatalogView.vue';
import PlaceholderView from './components/admin/views/PlaceholderView.vue';
import NewMovieView from './components/admin/views/NewMovieView.vue';

const router = createRouter({
  history: createWebHistory('/admin'),
  routes: [
    {
      path: '/',
      component: AdminLayout,
      children: [
        { path: '', name: 'adminDashboard', component: DashboardView },
        { path: 'movieCatalog', name: 'adminMovieCatalog', component: MovieCatalogView },
        { path: 'movies/:id', name: 'adminMovieDetail', component: MovieDetailView },
        { path: 'cinemaCatalog', name: 'adminCinemaCatalog', component: CinemaCatalogView },
        { path: 'foodCatalog', name: 'adminFoodCatalog', component: FoodCatalogView },
        { path: 'blogList', name: 'adminBlogList', component: PlaceholderView, props: { title: 'Blogs Lists' } },
        { path: 'transactionLog', name: 'adminTransactionLog', component: PlaceholderView, props: { title: 'Transaction Log' } },
        { path: 'newMovies', name: 'adminNewMovies', component: NewMovieView }
      ]
    }
  ]
});

createApp(AdminApp).use(router).mount('#admin-app');
