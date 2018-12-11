<template>

    <div class="admin-login-card">
        <form class="text-center border border-light p-5" @submit.prevent="submit">
            <p class="h5">
                <img src="../../images/wow.jpg" width="30%">
            </p>
            <div class=" mb-3">
                <!--<a @click="switchLang('mm')">Myanmar</a>/-->
                <!--<a @click="switchLang('en')">English</a>-->
            </div>
            <input type="text" v-model="credentials.phone"  class="form-control mb-3" placeholder="Enter Phone No.">
            <input type="password" v-model="credentials.password"  class="form-control mb-3" placeholder="Enter Password">
            <button class="btn btn-login btn-block my-3" type="submit">Sign in</button>
        </form>

    </div>
</template>
<script>

    export default {
        data() {
            return {
                credentials: {
                    email: null,
                    password: null
                },
                error: ''
            };
        },
        methods: {
            submit() {
                var credentials = {
                    phone: this.credentials.phone,
                    password: this.credentials.password
                }
                auth.login(this, credentials, 'dashboard')
                localStorage.getItem('access_token');
            }
        }
    }
</script>
<style lang="css" scoped>
    .admin-login-card{
        padding:0.5rem 2rem 4rem 2rem;
        margin: 0 auto;
        background-color: white;
        border-radius: 8px;
        box-shadow: 1px 0px 20px rgba(0, 0, 0, 0.08);
        -webkit-box-shadow: 1px 0px 20px rgba(0, 0, 0, 0.08);
        -moz-box-shadow: 1px 0px 20px rgba(0, 0, 0, 0.08);
        width: 320px!important;
        height: auto!important;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);

    }
    .btn-login{
        background-color: #00B297;
        color: white;
    }
    .mb-3{
        margin-bottom:15px;
    }
    .mt-3{
        margin-top:20px;
    }
</style>
