/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)


import Vue from 'vue';
import Vuex from 'vuex'
import BootstrapVue from 'bootstrap-vue/dist/bootstrap-vue.esm'
import App from '../layout/App.vue' ;
import AppHeader from '../layout/AppHeader'
import AppFooter from '../layout/AppFooter'

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import '../css/app.css';


Vue.use(BootstrapVue);
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        logged_in: false
    },
    getters: {
    }
});

new Vue({
    components: {App, AppHeader , AppFooter} ,
    store ,
    el : "#app",
});
