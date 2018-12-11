
function errorResponseHandler(error) {
    // check for errorHandle config
    if( error.config.hasOwnProperty('errorHandle') && error.config.errorHandle === false ) {
        return Promise.reject(error);
    }

    // if has response show the error
    if (error.response) {
        if(error.response.status == 401){
            auth.logout();
            window.location.href = '/login'
        }
    }
}

// apply interceptor on response
axios.interceptors.response.use(
    response => response,
    errorResponseHandler
);
axios.interceptors.request.use(function (config) {
    // Do something before request is sent
    var token = auth.getToken();
    if(token != null){
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});
export default errorResponseHandler;