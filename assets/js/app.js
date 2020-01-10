/**
 * App entry point
 */
import Vue from 'vue';
import Vuex from 'vuex'
import createLogger from 'vuex/dist/logger'
import BootstrapVue from 'bootstrap-vue/dist/bootstrap-vue.esm'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faHeart, faThumbsDown  } from '@fortawesome/free-solid-svg-icons'
import { faHeart as faHeartRegular, faThumbsDown as faThumbsDownRegular } from '@fortawesome/free-regular-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import App from '../layout/App.vue' ;
import AppHeader from '../layout/AppHeader'
import AppFooter from '../layout/AppFooter'
import VueRouter from 'vue-router'
import VueAxios from 'vue-plugin-axios'
import routes from '../utils/routes'
import configured_axios from '../utils/axios'
import model from '../store/model'

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import '../css/app.css';


let plugins = [];
if(process.env.NODE_ENV === "development"){
    plugins.push(createLogger()) ;
}

//adding fontawesome icons
library.add(faHeart , faHeartRegular, faThumbsDown ,faThumbsDownRegular);
Vue.component('font-awesome-icon', FontAwesomeIcon);


// creation of the store
Vue.use(Vuex);
let store = new Vuex.Store({...model , plugins});

/*
    Installing Vue Plugins on vue
    BootstrapVue : Bootstrap for vuejs
    Vuex : state store
    VueRouter : Used for routing
    VueAxios : A plugin that wraps axios and makes it available inside child components
 */
Vue.use(BootstrapVue);
Vue.use(VueRouter);
// the second parameter an object containing the axios object and requests configuration
Vue.use(VueAxios,configured_axios(store));


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
