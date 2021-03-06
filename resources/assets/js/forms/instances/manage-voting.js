let base = require('../../components/can-load-streets');

Vue.component('app-manage-voting', {
    //mixins: [base],

    props: ['init'],

    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {

        if (this.init) {
            this.form.closed_at = this.init.closed_at;
            this.form.city = this.init.city;
            this.form.name = this.init.name;
            this.form.items = this.init.items;
        }

        if (this.form.items.length == 0) {
            this.addItem();
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
            form: new AppForm(App.forms.ManageVoting),
            streets: App.streets,
            active: {}
        }
    },

    computed: {
        hasErrors : function(){
            let result = false;
            if($.trim(this.form.name).length == 0)
                return true;
            if(this.form.items.length == 0)
                return true;

            for(let i in this.form.items)
            {
                if(this.form.items[i].name == '')
                {
                    return true;
                }
                if(this.form.items[i].description == '')
                {
                    return true;
                }
                if(this.form.items[i].text == '')
                {
                    return true;
                }
            }
            return false;
        },

        error : function(){

            if($.trim(this.form.name).length == 0)
                return 'Заполните название';
            if(this.form.items.length == 0)
                return 'Добавьте хотя бы один вопрос в повестку дня';

            for(let i in this.form.items)
            {
                if(this.form.items[i].name == '')
                {
                    return 'Заполните текст вопроса №' + (parseInt(i)+1);
                }
                if(this.form.items[i].description == '')
                {
                    return 'Заполните предложение вопроса №' + (parseInt(i)+1);
                }
                if(this.form.items[i].text == '')
                {
                    return 'Заполните полную информацию по вопросу №' + (parseInt(i)+1);
                }
            }
            return '';
        },
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
                    App.Toastr("error", $vm.form.errors.all().join(","), "Ошибка");
                });
        },

        selectItem(item){
            this.active = item;
        },

        addItem(){

            this.form.items.push({
                'i': this.form.items.length + 1,
                'name': '',
                'description': '',
                'text': '',
            });

            this.active = _.last(this.form.items);
        },

        removeItem(i){
            this.form.items.splice(i, 1);

            this.active = _.last(this.form.items);
        }
    },
});