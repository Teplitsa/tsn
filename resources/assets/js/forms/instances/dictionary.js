Vue.component('app-dictionary', {
    props: [],

    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        var vm = this;
        if(this.form.dictionary.length == 0)
            this.addDictionary();
        $(function () {
            vm.active = vm.form.dictionary[0];
            $('.first-field').filter(':visible:first').focus();
        });
    },

    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            form: new AppForm(App.forms.dictionary),
            active: null,
        }
    },


    methods: {
        submit(){
            let $vm = this;
            let action = $(this.$el).find('form').attr('action');
            App
                .post(action, this.form)
                .then(function (response) {
                    console.log(response);
                    setTimeout(function(){
                        location.href = response.data.redirect;
                    }, 3000);
                }, function (response) {

                });
        },
        setActive(dictionary){

            this.active=dictionary;

        },
        isActive(dictionary){
            return this.active==dictionary;
        },
        addRow: function (dictionary) {
            try {
                dictionary.items.push({
                    id:'',
                    text:'',
                    value:''
                });
            } catch(e)
            {
                console.log(e);
            }
        },
        removeRow: function (dictionary,row) {
            dictionary.items.$remove(row);
        },
        addDictionary: function () {
            try {
                this.form.dictionary.push({
                    id:'',
                    name:'',
                    keyword:'',
                    items:[]
                });
                this.active= this.form.dictionary[(this.form.dictionary.length) -1];
            } catch(e)
            {
                console.log(e);
            }
        },
        removeDictionary: function (dictionary) {
            this.form.dictionary.$remove(dictionary);
            this.active=this.form.dictionary[0];
        },
    },
});