<template>
    <div>
        <div v-if="pageTitle != null" class="row wrapper border-bottom white-bg page-heading">
            <div class="col-sm-8">
                <h2>{{ pageTitle }}</h2>
                <layout-breadcrumbs :items="breadcrumbs"></layout-breadcrumbs>
            </div>
            <div class="col-sm-4">
                <div class="title-action">
                    <div class="btn-group">
                        <a :href="action.href" :class="action.class" v-for="action in actions">
                            <i v-if="action.icon" :class="iconClass(action)"></i>
                            {{ action.text }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            'pageTitle': {
                type: String,
                default: ''
            },
            'actions': {
                type: Array,
                default: function () {
                    return []
                }
            },
            'breadcrumbs': {
                type: Array,
                default: function () {
                    return []
                }
            }
        },

        methods: {
            iconClass: function (action) {
                if (action.icon != '') {
                    let styles = {'fa': true};
                    styles[action.icon] = true;
                    return styles;
                }
                return {}
            }
        },

        events: {
            headerlineUpdated(info){
                let [pageTitle = null, actions = [], breadcrumbs = []] = info;

                this.pageTitle = pageTitle;
                this.actions = actions;
                this.breadcrumbs = breadcrumbs;
            },


        }
    }
</script>