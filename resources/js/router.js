import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

let router= new Router({
    linkActiveClass: 'active',
    linkExactActiveClass: "active",
    hashbang: false,
    mode: 'history',
    routes:
        [
            {
                path: '/login',
                name: 'Login',
                component: resolve => require(['./pages/Login'], resolve),
                // component: require("./pages/Login"),
                meta: {
                    layout: "no-sidebar",
                    guest: true,
                    isLogin:true,
                    requiresAuth: false,
                },
            },
            {
                path: '/',
                name: 'Dashboard',
                component: resolve => require(['./pages/Dashboard'], resolve),
                meta: {
                    requiresAuth: true,
                }
            }

        ]
})
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (localStorage.getItem('access_token') == null) {
            next({
                path: '/login',
                params: {nextUrl: to.fullPath}
            })
        } else {
            next()
        }
    } else if (to.matched.some(record => record.meta.requiresAuth)) {
        if (localStorage.getItem('access_token') != null) {
            next({
                path: '/',
                params: {nextUrl: to.fullPath}
            })
        }else{
            next()
        }

    }else {
        var token = localStorage.getItem('access_token');


        if (to.matched.some(record => record.meta.isLogin)) {

            if(token!=null){
                next({
                    path: '/',
                    params: {nextUrl: to.fullPath}
                })
            }
        }
        next()
    }
})

export default router
