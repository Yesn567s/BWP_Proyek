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
import CinemaPage from './components/admin/views/CinemaPage.vue';
import NewVenue from './components/admin/views/NewVenue.vue';
import TixFunCatalogView from './components/admin/views/TixFunCatalog.vue';
import AddTixFunView from './components/admin/views/AddTixFunView.vue';
import BlogPage from './components/admin/views/blogPage.vue';
import BlogEdit from './components/admin/views/blogEdit.vue';
import NewBlogPage from './components/admin/views/newBlogPage.vue';

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
        { path: 'tixFunCatalog', name: 'adminTixFunCatalog', component: TixFunCatalogView },
        { path: 'addTixFun', name: 'adminAddTixFun', component: AddTixFunView },
        { path: 'foodCatalog', name: 'adminFoodCatalog', component: FoodCatalogView },
        { path: 'blogList', name: 'adminBlogList', component: BlogPage },
        { path: 'transactionLog', name: 'adminTransactionLog', component: PlaceholderView, props: { title: 'Transaction Log' } },
        { path: 'newMovies', name: 'adminNewMovies', component: NewMovieView },
        { path: 'cinemas/:id', name: 'adminCinemaPage', component: CinemaPage },
        { path: 'newVenue', name: 'adminNewVenue', component: NewVenue },
        { path: '/admin/blog/:id', name: 'adminBlogEdit', component: BlogEdit },
        { path: '/admin/blog/new', name: 'adminBlogNew', component: NewBlogPage },
      ]
    }
  ]
});

createApp(AdminApp).use(router).mount('#admin-app');
