import Vue from 'vue'
import Router from 'vue-router'

import HomePage from '@/components/HomePage'
import AddFoodPage from '@/components/AddFoodPage.vue';
import FoodLogPage from '@/components/FoodLogPage.vue'
import LoginPage from  '@/components/LoginPage.vue';
import Stats from '@/components/Stats.vue';

import Admin from '@/components/Admin.vue';
import Chef from '@/components/Chef.vue';

import UserProfilePage from '@/components/UserProfilePage.vue';
import RegisterPage from '@/components/RegisterPage.vue';


Vue.use(Router)

export default new Router({
  routes:  [
    { path: '/', component: HomePage},
    { path: '/home', component: HomePage },
    { path: '/dining-center', component: AddFoodPage},
    { path: '/dining-center/:location', component: AddFoodPage },
    { path: '/food-log/:date', component: FoodLogPage },
    { path: '/food-log', component: FoodLogPage },
    { path: '/stats', component: Stats},
    { path: '/login', component: LoginPage },
    { path: '/admin', component: Admin},
    { path: '/chef', component: Chef},
    { path: '/profile', component: UserProfilePage },
    { path: '/register', component: RegisterPage },

  ]
})
