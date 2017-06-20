Vue.component('app-register-form', {
    props: [],

    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        $(function () {
            $('.first-field').filter(':visible:first').focus();
        });
    },

    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            form:  new AppForm({
                email: '',
                password: '',
                password_confirmation: '',
                first_name: '',
                agreed: '',
                house: null,
            })
        }
    },


    methods: {
        submit(){
            let $vm = this;
            let action = $(this.$el).find('form').attr('action');
            let method = $(this.$el).find('input[name="_method"]').val() || 'post';
            method = method.toLowerCase();
            App[method](action, this.form)
                .then(function (response) {
                    setTimeout(function(){
                        location.href = response.data.redirect;
                    }, 3000);
                }, function (response) {
                    // error
                });
        }
    },
});