/**
 * App entry point
 */
import Vue from 'vue';
import Vuex from 'vuex'
import BootstrapVue from 'bootstrap-vue/dist/bootstrap-vue.esm'
import App from '../layout/App.vue' ;
import AppHeader from '../layout/AppHeader'
import AppFooter from '../layout/AppFooter'
import VueRouter from 'vue-router'
import routes from '../utils/routes'

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import '../css/app.css';

/*
    Installing Vue Plugins
    BootstrapVue : Boostrap for vuejs
    Vuex : state store
    VueRouter : Used for routing
 */
Vue.use(BootstrapVue);
Vue.use(Vuex);
Vue.use(VueRouter);

// creation of the store
const store = new Vuex.Store({

    state: {
        user : null ,
    },
    getters: {
        //returns if the user user is logged in or not
        is_logged_in : state => state.user !== null ,
        user_email : (state,getters) => ! (getters.is_logged_in) ?  null : state.user.email

    }
});


// loading the routes and using them
// passing the store to the routes to check user permissions before redirecting him
const router = new VueRouter({
    routes : routes(store)
});

// Vue init
new Vue({
    components: {App, AppHeader , AppFooter} ,
    store ,
    router ,
    el : "#app",
});
