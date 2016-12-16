import Pusher from "pusher-js";
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

require('./components/bootstrap');
require('./plugins');
require('./helpers/bootstrap');

App.Instance = new Vue({
    el: 'body',
    ready(){
        console.log('Vue js ready');
        Pusher.logToConsole = true;

        $('#side-menu').metisMenu();

        window.Helpers.recalculateWith();


        if (typeof App.toastrs != "undefined") {
            _.forEach(App.toastrs, function (item) {
                let {type, msg, title, onclick} = item;
                App.Toastr(type, msg, title, onclick);
            });
        }

        if(App.userId)
        {
            this.getUser();
            this.getNotifications();
        }


        let vm = this;
        Echo.private('App.Models.User.' + App.userId)
            .notification(function(notification) {
                console.log(notification);
                let notificationItem = {
                    icon: notification.icon,
                    text: notification.text,
                    link: '/notification/' + notification.id,
                    date: new Date(),
                    id: notification.id
                };
                vm.$broadcast('newNotification', notificationItem);
            });




    },
    data: {},
    methods:{
        getUser() {
            let vm = this;
            this.$http.get('/internal-api/user')
                .then(function (response) {
                    let user = response.json();
                    console.log(user);
                    vm.$broadcast('userRetrieved', user);
                });
        },

        getNotifications()
        {
            let vm = this;
            this.$http.get('/internal-api/notifications')
                .then(function (response) {
                    let notifications = response.json();
                    vm.$broadcast('notificationsLoaded', notifications);
                });
        }
    }
});
