<template>
    <li class="nav-header">
        <div class="dropdown profile-element">
            <span>
                <img alt="image" class="img-circle" :src="user.avatarUrl" width="48"/>
            </span>

            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <span class="clear">
                    <span class="block m-t-xs">
                        <strong class="font-bold">{{ user.fullName }}</strong>
                    </span>
                    <span class="text-muted text-xs block">
                        {{ user.duty }}
                        <b class="caret"></b>
                    </span>
                </span>
            </a>
            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                <li class="divider"></li>
                <li><a href="/logout">Logout</a></li>
            </ul>
        </div>
        <div class="logo-element">
            ERP
        </div>
    </li>
</template>


<script>
    export default {
        data(){
            return {
                user: {
                    fullName: "",
                    duty: "",
                    avatarUrl: "",
                }
            }
        },
        events: {
            userRetrieved: function (user) {
                this.user = user.data;
            }
        }
    }
</script>
