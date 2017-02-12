<template>
    <li class="dropdown">
        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
            <i class="fa fa-bell"></i>
            <span class="label label-primary" v-if="notifications.length != 0" >{{ notifications.length }}</span>
        </a>
        <ul class="dropdown-menu dropdown-alerts">
            <template v-for="notification in notifications.slice(0,4)">
                <li>
                    <a :href="notification.link">
                        <div>
                                <i :class="notification.icon + ' fa-fw'"></i> {{ notification.text }}
                                <timeago
                                        :auto-update="1"
                                        :max-time="60 * 60 * 24 * 14"
                                        class="pull-right text-muted small"
                                        :since="notification.date"></timeago>
                        </div>
                    </a>
                </li>
                <li class="divider"></li>
            </template>
            <li v-if="notifications.length == 0">
                <div class="text-center">
                    <div class="alert alert-info">
                        Новых оповещений нет
                    </div>

            </div>
            </li>
            <li v-if="notifications.length == 0" class="divider"></li>
            <li>
                <div class="text-center link-block">
                    <a href="/notifications">
                        <strong>Посмотреть все оповещения</strong>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </li>
        </ul>
    </li>
</template>


<script>
    export default {
        data() {
            return {
                notifications: []
            }
        },

        events: {

            notificationsLoaded(notifications){
                this.notifications = notifications.data;
            },

            newNotification(notification){
                this.notifications.unshift(notification);
            }
        }
    }
</script>
