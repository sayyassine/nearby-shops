<!--The Page header : container the navigation and the profile box -->
<template>
    <div>
    <b-card-header header-tag="nav">
        <b-nav card-header tabs>
            <!-- <b-nav-item>'s with child routes. Note the trailing slash on the first <b-nav-item> -->
            <b-nav-item to="/login" exact exact-active-class="active">Login</b-nav-item>
            <b-nav-item to="/profile" exact exact-active-class="active">Profile</b-nav-item>
        </b-nav>
    </b-card-header>

   </div>
</template>

<script>
    export default {
        name: "AppHeader"
    }
</script>

<style scoped>

</style>