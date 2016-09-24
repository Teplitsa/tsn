import Echo from "laravel-echo"

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '936f2fe286444e60289a',
    cluster: 'eu'
});

window._ = require('lodash');
window.Cookies = require('js-cookie');
window.Promise = require('promise');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');
require('bootstrap-sass/assets/javascripts/bootstrap');

/**
 * Vue is a modern JavaScript for building interactive web interfaces using
 * reacting data binding and reusable components. Vue's API is clean and
 * simple, leaving you to focus only on building your next great idea.
 */

window.Vue = require('vue');
require('vue-resource');


/**
 * We'll register a HTTP interceptor to attach the "XSRF" header to each of
 * the outgoing requests issued by this application. The CSRF middleware
 * included with Laravel will automatically verify the header's value.
 */

Vue.http.interceptors.push(function (request, next) {
    request.headers['X-XSRF-TOKEN'] = Cookies.get('XSRF-TOKEN');

    next(function (response) {

        let data = response.json();

        if (typeof data.meta != "undefined" && typeof data.meta.toastrs != "undefined") {
            _.forEach(data.meta.toastrs, function (item) {
                let {type, msg, title, onclick} = item;
                App.Toastr(type, msg, title, onclick);
            });
        }
    });
});

App.Toastr = function (type, msg, title, onclick = null) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "timeOut": "7000",
        "extendedTimeOut": "1000",
        "showMethod": "slideDown",
        "progressBar": true,
        "preventDuplicates": true,
        "positionClass": "toast-top-right",
        "onclick": onclick,
        "hideMethod": "fadeOut"
    };

    toastr[type](msg, title);
};



require('./forms/bootstrap');