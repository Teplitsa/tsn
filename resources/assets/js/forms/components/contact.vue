<template>
    <div>
        <div :class="{'form-group': true, 'has-error': form.errors.has(name) }">
            <label class="col-sm-2 control-label">
                {{ display }}
                <br v-if="help != ''"/>
                <small v-if="help != ''" class="text-navy">{{ help }}</small>
            </label>
            <div class="col-sm-10">
                <div class="input-group" style="margin-bottom: 20px;" v-for="item in form[name]">
                    <span class="input-group-addon" style="width: 10%;">{{item.type}}</span>
                    <input type="text" class="form-control" v-model="item.value"
                           placeholder="Введите ваш {{item.type}}">
                    <span class="input-group-btn">
                        <button class="btn btn-default" style="margin-bottom:0px;" type="button" v-on:click.prevent="removeContact(item)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </span>
                    <span class="help-block" v-show="form.errors.has(name)">
                        <strong>{{ form.errors.get(name) }}</strong>
                    </span>
                </div>
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" aria-expanded="false">
                        Добавить <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li v-for="(key, val) in items"><a href="#" v-on:click.prevent="addContact(key)">{{ val }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="hr-line-dashed"></div>
    </div>
</template>

<script>
    export default{
        props: ['display', 'form', 'name', 'help', 'items'],
        methods: {
            addContact(key) {
                var vm = this;
                vm.form[vm.name].push({
                    id: null,
                    type: key,
                    value: ''
                });
            },
            removeContact(contact){
                var vm = this;
                vm.form[vm.name].$remove(contact);
            }
        }
    }
</script>

