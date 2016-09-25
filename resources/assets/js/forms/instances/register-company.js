Vue.component('app-register-company', {
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
                last_name: '',
                middle_name: '',
                inn: '',
                kpp: '',
                ogrn: '',
                title: '',
                agreed: '',
            }),
            loading: false
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
        },

        loadInfo: function()
        {
            let $vm = this;
            this.loading = true;
            let inn = this.form.inn != '' ? this.form.inn : 0;
            this.$http.get('/new-company/' + inn).then(function(data){
                $vm.loading = false;
                let result = data.json().data;
                $vm.form.inn = result.inn;
                $vm.form.title = result.title;
                $vm.form.kpp = result.kpp;
                $vm.form.ogrn = result.ogrn;
                $vm.form.first_name = result.first_name;
                $vm.form.last_name = result.last_name;
                $vm.form.middle_name = result.middle_name;
            }, function(data){
                $vm.loading = false;
                console.log(data);
            });
        }
    },
});