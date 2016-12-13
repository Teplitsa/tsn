Vue.component('app-create-house', {
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
            form: new AppForm({
                number_of_flats: '',
                address: '',
                flats: []
            })
        }
    },

    watch: {
        'form.number_of_flats': {
            handler: function (val, oldVal) {
                if (this.form.flats.length > val) {
                    this.form.flats = this.form.flats.slice(0, val);
                }
                while (this.form.flats.length < val) {
                    this.form.flats.push({
                        'account_number': '',
                        'square': ''
                    })
                }

            },
            deep: true
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
                    setTimeout(function () {
                        location.href = response.data.redirect;
                    }, 3000);
                }, function (response) {
                    // error
                });
        },
        generate(){
            for (let i in this.form.flats) {
                if (this.form.flats[i].account_number == '') {
                    this.form.flats[i].account_number = Math.floor(Math.random() * (9999999999 - 1000000000)) + 1000000000;

                }
            }
        }
    },
});