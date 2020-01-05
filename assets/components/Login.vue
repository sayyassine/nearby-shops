<!--Login page holds the login form that permits the user to login-->

<template>

    <div class="container d-flex align-content-center justify-content-center">

        <b-card
            class="my-5 p-3"
            title="Login"
        >
            <b-form @submit="onSubmit" @reset="onReset" >
                <b-form-group
                        id="feed-back-holder"
                >
                    <b-form-invalid-feedback class="alert-danger alert mb-0" :state="!login_failed">
                        {{login_error}}
                    </b-form-invalid-feedback>

                </b-form-group>
                <b-form-group
                        id="input-group-1"
                        label="Email address:"
                        label-for="input-1"
                >
                    <b-form-input
                            id="input-1"
                            v-model="form.email"
                            type="email"
                            required
                            placeholder="Enter email"
                    ></b-form-input>
                </b-form-group>

                <b-form-group id="input-group-2" label="Your Name:" label-for="input-2">
                    <b-form-input
                            id="input-2"
                            v-model="form.password"
                            type="password"
                            required
                            placeholder="Enter password"
                    ></b-form-input>
                </b-form-group>
                <b-form-group>

                    <router-link to="/register">Create an account</router-link>
                </b-form-group>

                <b-button type="submit" variant="primary">Login</b-button>
                <b-button type="reset" variant="danger">Reset</b-button>
            </b-form>
        </b-card>
    </div>

</template>

<script>
    import axios from 'axios'

    axios.create({})
    export default {
        name: "Login",
        data :function () {
            return {
                form : {
                    email :'',
                    password :''
                },
                login_error : '' ,
            }
        },
        methods : {
            onSubmit(evt) {
                evt.preventDefault();
                this.loading = true ;

                this.$post("/login",{
                    data : this.form
                }).then(function (response) {
                    if(response.data.success && response.data.user ){
                        this.$store.commit("login" , user );
                        this.$router.push("/stores")
                    }else if(response.data.error){
                        this.login_error = response.data.error
                    }else {
                        this.login_error = "Login failed."
                    }

                    this.loading = false
                }).finally(
                    function () {
                        this.loading = false
                    }
                );
                alert(JSON.stringify(this.form))
            },
            onReset(evt) {
                evt.preventDefault();
                // Reset our form values
                this.form.email = '';
                this.password = '';
            },
        },
        computed : {
            login_failed (){
                return this.login_error.length > 0
            },
        }
    }
</script>

<style scoped>

</style>