<template>
    <li class="nav-header">
        <div class="dropdown profile-element">
            <span>
                <img alt="image" class="img-circle" :src="user.avatarUrl" width="48"/>
            </span>

            <a href="#">
                <span class="clear">
                    <span class="block m-t-xs">
                        <strong class="font-bold">{{ user.fullName }}</strong>
                    </span>
                    <span class="text-muted text-xs block">
                        {{ user.duty }}
                    </span>
                </span>
            </a>

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
