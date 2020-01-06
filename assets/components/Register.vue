<!-- Registration page-->
<!-- logs in the user automatically after registration-->
<!-- requires to be not logged in -->
<template>
    <div class="d-flex justify-content-center align-items-center-center">

        <b-card
                title="Registration"
                style="max-width: 20rem;"
                class="mt-5"
            >
            <div>
                <b-form @submit="onSubmit" @reset="onReset" v-if="show">
                    <d-form-group>
                        <b-alert v-model="registration_error.length > 0" variant="danger" dismissible >
                            <span v-html="registration_error"></span>
                        </b-alert>
                    </d-form-group>
                    <b-form-group
                            id="input-group-1"
                            label="Email address:"
                            label-for="input-1"
                            description="We'll never share your email with anyone else."
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
                                v-model="form.name"
                                required
                                placeholder="Full name"
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group id="input-group-3" label="Password:" label-for="input-3">

                        <b-form-input
                                id="input-3"
                                v-model="form.password"
                                type="password"
                                required
                                placeholder="password"
                                :state="password_valid"
                        ></b-form-input>
                        <b-form-text v-if="password_valid === null" id="password-help-block">
                            Your password must be 8-20 characters long.
                        </b-form-text>
                        <b-form-invalid-feedback :state="password_valid">
                            Your password must be 8-20 characters long, contain at least one upper case letter, one lower case letter and one number.
                        </b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group id="input-group-4" label="Password confirmation:" label-for="input-4">
                        <b-form-input
                                id="input-3"
                                v-model="form.repeated_password"
                                type="password"
                                required
                                placeholder="Repeat your password"
                                :state="passwords_match"
                        ></b-form-input>

                        <b-form-invalid-feedback :state="passwords_match">
                            The passwords doesn't match.
                        </b-form-invalid-feedback>
                    </b-form-group>


                    <b-button type="submit" :disabled="!form_valid" variant="primary">Register</b-button>
                    <b-button type="reset" variant="danger">Reset</b-button>
                </b-form>
            </div>
        </b-card>
    </div>

</template>

<script>
    export default {
        name: "Register",
        data : function () {
            return {
                form: {
                    email: '',
                    name: '',
                    password: '',
                    repeated_password: '',

                },
                show : true ,
                registration_error : false
            }
        },
        computed : {
            password_contains_letter(){
                return this.form.password.match(/[a-z]/g) !== null ;
            },
            password_contains_number(){
                return this.form.password.match(/[0-9]/g) !== null ;
            },
            password_contains_uppercase_letter(){
                return this.form.password.match(/[A-Z]/g) !== null ;
            },
            password_valid() {
                let password = this.form.password,
                    length = password.length,
                    content_valid = this.password_contains_letter && this.password_contains_uppercase_letter && this.password_contains_number ;

                return length === 0 ? null : (length > 8 && length < 20 && content_valid);
            },
            passwords_match() {
                return this.form.repeated_password.length === 0 ? null : ( this.form.password === this.form.repeated_password ) ;
            },
            form_valid(){
                return this.password_valid && this.passwords_match ;
            }

        },
        methods: {
            onSubmit(evt) {
                evt.preventDefault();
                this.$post("/register" , {
                    ...this.form
                }).then(
                    (response) => {
                        if(!response.success){
                            this.registration_error = "Registration failed. <ul>" ;
                            for(let error of response.errors){
                                this.registration_error += "<li>" + error + "</li>" ;
                            }
                            this.registration_error += "</ul>" ;
                            return ;
                        }
                        this.$store.commit( "login" , response.success );
                        this.$router.push( "/profile" );
                    }
                ).finally()

                //alert(JSON.stringify(this.form))
            },
            onReset(evt) {
                evt.preventDefault();
                // Reset our form values
                this.form.email = '';
                this.form.name = '';
                this.form.password = '';
                this.form.repeated_password = '';
                // reset/clear native browser form validation state
                this.show = false;
                this.$nextTick(() => {
                    this.show = true
                })
            }
        }
    }

</script>

<style scoped>

</style>