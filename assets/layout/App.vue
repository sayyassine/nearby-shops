<template>
    <router-view></router-view>
</template>

<script>
    import Login from '../components/Login'
    import VueRouter from 'vue-router'
    import routes from '../utils/routes'

    const Router = new VueRouter({
        routes
    });

    export default {
        components : {
            Router
        },
        name: "App",
        data : function () {
            return {
                message: "Hello world."
            }
        }
    }
</script>

<style scoped>

</style>