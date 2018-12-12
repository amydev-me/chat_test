// URL and endpoint constants
// const API_URL = 'http://localhost:8080/'
// const LOGIN_URL = API_URL + 'api/login'

module.exports = {

    // User object will let us check authentication status
    user: {
        authenticated: false

    },
    user_info:{
        id: null,
        full_name: null,
        user_name: null,
        email:null,
    },

    login(context, creds, redirect) {
        axios.post('/api/login', creds).then(({data}) => {
            if(data.success){
                localStorage.setItem('access_token', data.access_token)
                localStorage.setItem('user_info', JSON.stringify(data.user))

                this.user.authenticated = true;

                if (redirect) {
                    context.$router.push('/')
                }
            }

        }).catch(error => {

        })
    },

    logout() {
        axios.get('/api/logout').then(({data}) => {


        }).catch(error => {

        })
        localStorage.removeItem('access_token')
        localStorage.removeItem('user_info')
        this.user.authenticated = false;
        window.location.href='/login'
    },

    checkAuth() {
        var jwt = localStorage.getItem('access_token')
        if (jwt) {
            this.user.authenticated = true
        }
        else {
            this.user.authenticated = false
        }
    },

    getToken(){
        return localStorage.getItem('access_token');
    },
    getAuthHeader() {
        var access_token=localStorage.getItem('access_token');
        return {

            'Authorization': 'Bearer ' + access_token
        }
    },

    getAuthInfo() {
        var user_info=JSON.parse(localStorage.getItem('user_info'));
        return user_info;
    }
}
