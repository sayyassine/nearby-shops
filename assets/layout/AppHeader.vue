<!--The Page header : container the navigation and the profile box -->
<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div>
        <b-navbar toggleable="lg" type="dark" variant="primary">
            <b-navbar-brand href="#">Nearby Shops</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-item to="/stores"><font-awesome-icon icon="store"/> Stores</b-nav-item>
                    <b-nav-item to="/my-stores" v-if="is_logged_in"><font-awesome-icon icon="heart"/> My stores</b-nav-item>
                </b-navbar-nav>

                <b-navbar-nav class="ml-auto">


                    <b-nav-item-dropdown right v-if="is_logged_in">
                        <template v-slot:button-content  >
                            <em>{{user_email}}</em>
                        </template>
                        <b-dropdown-item to="/profile">Profile</b-dropdown-item>
                        <b-dropdown-item v-on:click="logout">Sign Out</b-dropdown-item>

                    </b-nav-item-dropdown>
                    <b-nav-item v-else to="/login"><font-awesome-icon icon="sign-in-alt" /> Login</b-nav-item>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>

   </div>
</template>

<script>
    export default {
        name: "AppHeader",
        computed : {
            is_logged_in () {
                return this.$store.getters.is_logged_in
            },
            user_email() {
                return this.$store.getters.user_email
            }
        },
        methods : {
            logout(){
                this.$store.commit('logout');
                this.$router.push('login');
            }
        }
    }
</script>

<style scoped>

</style>