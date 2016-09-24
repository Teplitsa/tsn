<template>
    <ol class="breadcrumb">
        <li v-for="breadcrumb in items" :class="{'active': breadcrumb.last}">
            <a :href="breadcrumb.url" v-if="!breadcrumb.last">{{ breadcrumb.title }}</a>
            <strong v-if="breadcrumb.last">{{ breadcrumb.title }}</strong>
        </li>
    </ol>
</template>

<script>
    export default {
        props: {
            'items': {
                type: Array,
                default: function () {
                    return []
                }
            }
        },

        events: {
            breadcrumbsUpdated(breadcrumbs){
                this.breadcrumbs = breadcrumbs;
            },
        }
    }
</script>
