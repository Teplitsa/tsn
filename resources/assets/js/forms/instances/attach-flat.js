let streets = require('../../components/can-load-streets');

Vue.component('attach-flat', {
    mixins: [streets],

    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        if (App.form) {
            this.form.city = App.form.city;
            this.form.street_id = App.form.street_id;
            this.form.number = App.form.number;
        }
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
                account: '',
                city: '',
                street_id: '',
                number: '',
                flat: '',
                square: '',
                up_part: 1,
                down_part: 1,
                number_doc: '',
                date_doc: '',
                issuer_doc: '',
                scan: ''
            }),
            streets: []
        }
    },

    computed: {
        mySquare()
        {
            let square = this.form.square / this.form.down_part * this.form.up_part;
            return Math.round((isNaN(square) ? 0 : square) * 100) / 100;
        }
    },


    methods: {
        submit(){
            let $vm = this;
            let action = $(this.$el).find('form').attr('action');
            let method = $(this.$el).find('input[name="_method"]').val() || 'post';
            method = method.toLowerCase();


            let data = new FormData();

            $.each(JSON.parse(JSON.stringify(this.form)), function(key, value) {
                if(['scan', 'successful', 'busy', 'errors'].indexOf(key) == -1)
                    data.append(key, value);
            });
            let input=$(this.$el).find("[name='scan']")[0];
            if (input && input.files && input.files[0]) {
                data.append('scan', input.files[0]);
            }

            this.form.startProcessing();
            this.$http[method](action, data)
                .then(function (response) {
                    $vm.form.finishProcessing();
                    location.href = response.data.data.redirect;

                }).catch(function (response) {
                    let error = response.data;

                    $vm.form.errors.set(error);
                    $vm.form.busy = false;
                });
        },

        previewImage(){
            var input = event.target;

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var vm = this;

                reader.onload = function (e) {
                    vm.form.scan = e.target.result;
                };

                reader.readAsDataURL(input.files[0]);
            }
        },


        openScan(){
            $(this.$el).find('[name="scan"]').click();
        }
    },
});