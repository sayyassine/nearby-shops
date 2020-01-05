import Login from '../components/Login'
import Register from '../components/Register'
import Profile from '../components/Profile'
import NearbyShops from '../components/NearbyShops'
import MyStores from '../components/MyStores'
/**
 * All the routes used by the app
 * /stores : Component that lists all the stores (excepts unliked ones)
 * /my_stores : Component that lists all liked stores (Requires login)
 * /profile : profile page (Requires login)
 * /login : login page (redirects to profile in case user already logged in)
 * /register : registration page (redirects to profile in case user already logged in)
 */
export default store => [
    {
        path : '/stores',
        name :'stores',
        component : NearbyShops
    },
    {
        path : '/my-stores',
        name :'my_stores',
        component : MyStores ,
        beforeEnter: (to, from, next) => {
            if (store.getters.is_logged_in === false) {
                next("login");
            } else {
                next();
            }
        }
    },
    {
        path: "/profile",
        name: "profile",
        component: Profile,
        beforeEnter: (to, from, next) => {
            if (store.getters.is_logged_in === false) {
                next("login");
            } else {
                next();
            }
        }
    },{
        path: "/login",
        name: "login",
        component: Login,
        beforeEnter: (to, from, next) => {
            if (store.getters.is_logged_in === true ) {
                next("profile");
            } else {
                next();
            }
        }
    },{
        path: "/register",
        name: "register",
        component: Login,
        beforeEnter: (to, from, next) => {
            if (store.getters.is_logged_in === true ) {
                next("profile");
            } else {
                next();
            }
        }
    }
]