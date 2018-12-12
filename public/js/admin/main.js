webpackJsonp([3],{

/***/ 12:
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ 13:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(14);


/***/ }),

/***/ 14:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__App__ = __webpack_require__(36);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__App___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__App__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__router__ = __webpack_require__(39);
window.Vue = __webpack_require__(2);
window.axios = __webpack_require__(5);
window.auth = __webpack_require__(35);


__webpack_require__(40);
new Vue({
    router: __WEBPACK_IMPORTED_MODULE_1__router__["a" /* default */],
    render: function render(h) {
        return h(__WEBPACK_IMPORTED_MODULE_0__App___default.a);
    }
}).$mount("#app");

/***/ }),

/***/ 35:
/***/ (function(module, exports) {

// URL and endpoint constants
// const API_URL = 'http://localhost:8080/'
// const LOGIN_URL = API_URL + 'api/login'

module.exports = {

    // User object will let us check authentication status
    user: {
        authenticated: false

    },
    user_info: {
        id: null,
        full_name: null,
        user_name: null,
        email: null
    },

    login: function login(context, creds, redirect) {
        var _this = this;

        axios.post('/api/login', creds).then(function (_ref) {
            var data = _ref.data;

            if (data.success) {
                localStorage.setItem('access_token', data.access_token);
                localStorage.setItem('user_info', JSON.stringify(data.user));

                _this.user.authenticated = true;

                if (redirect) {
                    context.$router.push('/');
                }
            }
        }).catch(function (error) {});
    },
    logout: function logout() {
        axios.get('/api/logout').then(function (_ref2) {
            var data = _ref2.data;
        }).catch(function (error) {});
        localStorage.removeItem('access_token');
        localStorage.removeItem('user_info');
        this.user.authenticated = false;
        window.location.href = '/login';
    },
    checkAuth: function checkAuth() {
        var jwt = localStorage.getItem('access_token');
        if (jwt) {
            this.user.authenticated = true;
        } else {
            this.user.authenticated = false;
        }
    },
    getToken: function getToken() {
        return localStorage.getItem('access_token');
    },
    getAuthHeader: function getAuthHeader() {
        var access_token = localStorage.getItem('access_token');
        return {

            'Authorization': 'Bearer ' + access_token
        };
    },
    getAuthInfo: function getAuthInfo() {
        var user_info = JSON.parse(localStorage.getItem('user_info'));
        return user_info;
    }
};

/***/ }),

/***/ 36:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(12)
/* script */
var __vue_script__ = __webpack_require__(37)
/* template */
var __vue_template__ = __webpack_require__(38)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/App.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-f348271a", Component.options)
  } else {
    hotAPI.reload("data-v-f348271a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 37:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {};
    }
});

/***/ }),

/***/ 38:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [_c("router-view")], 1)
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-f348271a", module.exports)
  }
}

/***/ }),

/***/ 39:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue__ = __webpack_require__(2);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_vue_router__ = __webpack_require__(11);



__WEBPACK_IMPORTED_MODULE_0_vue___default.a.use(__WEBPACK_IMPORTED_MODULE_1_vue_router__["default"]);

var router = new __WEBPACK_IMPORTED_MODULE_1_vue_router__["default"]({
    linkActiveClass: 'active',
    linkExactActiveClass: "active",
    hashbang: false,
    mode: 'history',
    routes: [{
        path: '/login',
        name: 'Login',
        component: function component(resolve) {
            return __webpack_require__.e/* require */(1).then(function() { var __WEBPACK_AMD_REQUIRE_ARRAY__ = [__webpack_require__(42)]; ((resolve).apply(null, __WEBPACK_AMD_REQUIRE_ARRAY__));}.bind(this)).catch(__webpack_require__.oe);
        },
        // component: require("./pages/Login"),
        meta: {
            layout: "no-sidebar",
            guest: true,
            isLogin: true,
            requiresAuth: false
        }
    }, {
        path: '/',
        name: 'Dashboard',
        component: function component(resolve) {
            return __webpack_require__.e/* require */(0).then(function() { var __WEBPACK_AMD_REQUIRE_ARRAY__ = [__webpack_require__(43)]; ((resolve).apply(null, __WEBPACK_AMD_REQUIRE_ARRAY__));}.bind(this)).catch(__webpack_require__.oe);
        },
        meta: {
            requiresAuth: true
        }
    }]
});
router.beforeEach(function (to, from, next) {
    if (to.matched.some(function (record) {
        return record.meta.requiresAuth;
    })) {
        if (localStorage.getItem('access_token') == null) {
            next({
                path: '/login',
                params: { nextUrl: to.fullPath }
            });
        } else {
            next();
        }
    } else if (to.matched.some(function (record) {
        return record.meta.requiresAuth;
    })) {
        if (localStorage.getItem('access_token') != null) {
            next({
                path: '/',
                params: { nextUrl: to.fullPath }
            });
        } else {
            next();
        }
    } else {
        var token = localStorage.getItem('access_token');

        if (to.matched.some(function (record) {
            return record.meta.isLogin;
        })) {

            if (token != null) {
                next({
                    path: '/',
                    params: { nextUrl: to.fullPath }
                });
            }
        }
        next();
    }
});

/* harmony default export */ __webpack_exports__["a"] = (router);

/***/ }),

/***/ 40:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });

function errorResponseHandler(error) {
    // check for errorHandle config
    if (error.config.hasOwnProperty('errorHandle') && error.config.errorHandle === false) {
        return Promise.reject(error);
    }

    // if has response show the error
    if (error.response) {
        if (error.response.status == 401) {
            auth.logout();
            window.location.href = '/login';
        }
    }
}

// apply interceptor on response
axios.interceptors.response.use(function (response) {
    return response;
}, errorResponseHandler);
axios.interceptors.request.use(function (config) {
    // Do something before request is sent
    var token = auth.getToken();
    if (token != null) {
        config.headers.Authorization = 'Bearer ' + token;
    }

    return config;
}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});
/* harmony default export */ __webpack_exports__["default"] = (errorResponseHandler);

/***/ })

},[13]);