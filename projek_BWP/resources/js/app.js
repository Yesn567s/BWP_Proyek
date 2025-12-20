import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap'; // imports JS (includes Popper)

import { createApp } from 'vue';
import App from './App.vue';

// kalau mau register global 
// createApp(App).use(router).mount('#app');
createApp(App).mount('#app');
