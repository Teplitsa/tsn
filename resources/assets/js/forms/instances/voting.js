Vue.component('app-voting', {

    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {

        this.active = _.first(this.form.items);
        $(function () {
            $('.first-field').filter(':visible:first').focus();
        });
    },

    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            form: new AppForm(App.forms.voting),
            active: {}
        }
    },


    methods: {
        vote(item, result){
            let $vm = this;
            item.v = result;
            let action = $(this.$el).find('form').attr('action') + '/' + item.id + '/' + result;
            let method = $(this.$el).find('input[name="_method"]').val() || 'post';
            method = method.toLowerCase();
            let $last = _.last(this.form.items);
            if ($last.i >= item.i + 1) {
                this.active = this.form.items[item.i + 1];
            }
            else {
                this.active = _.first(this.form.items);
            }
            App[method](action, this.form)
                .then(function (response) {

                }, function (response) {
                    // error
                });
        },

        selectItem(item){
            this.active = item;
        }
    },
});