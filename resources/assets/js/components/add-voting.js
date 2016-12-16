Vue.component('add-voting', {
    data(){
        return {
            form:new AppForm({
                city:'',
                street_id: '',
                flats: [],
                house_number:'',
                number_of_flats: 0
            }),
            streets: [],
            cities: [],
        }
    },

    methods:{

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
    },


});