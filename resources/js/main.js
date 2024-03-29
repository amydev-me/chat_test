window.Vue = require('vue');
window.axios = require('axios');
window.auth = require('./auth/index')
// window.signal = require('signal-protocol')
import App from './App'
import router from './router';
require('./AxiosIntercepter');
new Vue({
    router,
    render: h => h(App)
}).$mount("#app");
