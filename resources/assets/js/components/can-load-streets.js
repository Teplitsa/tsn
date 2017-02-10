module.exports = {

    data(){
        return {
            streets: [],
            cities: [],
        }
    },

    methods: {
        loadStreets(){
            this.$http.post('/houses/load_streets', {city_id: this.form.city}).then(
                function (response) {
                    this.streets = response.data.streets;
                });
        },
    },

    watch: {
        'form.city': function(){
            this.loadStreets();
        }
    }
};