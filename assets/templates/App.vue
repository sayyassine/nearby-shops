<template>

</template>

<script>
    import AppHeader from './AppHeader'
    import AppFooter from './AppFooter'

    export default {
        components : {
            AppHeader , AppFooter
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