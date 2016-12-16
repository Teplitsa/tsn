Vue.component('app-manage-voting', {
    props: ['init'],

    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        if (this.init) {
            this.form.closed_at = this.init.closed_at;
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
            form: new AppForm({
                code: '',
                name:'',
                voting_type:'',
                kind:'',
                public_at:'',
                opened_at:'',
                closed_at:'',
                end_at:'',
                items: []
            }),
            active: {}
        }
    },

    computed: {
        hasErrors : function(){
            var result = false;
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
                    // error
                });
        },

        addItem(){

            this.form.items.push({
                'i': this.form.items.length + 1,
                'name': '',
                'description': '',
                'text': '',
            });

            this.active = _.last(this.form.items);
        }
    },
});