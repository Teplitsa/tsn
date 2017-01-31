<template>
    <div>
        <div :class="{'row form-group': true, 'has-error': form.errors.has(name) }">
            <label class="col-sm-2 control-label">
                {{{ display }}}
                <br v-if="help != ''"/>
                <small v-if="help != ''" class="text-navy">{{ help }}</small>
            </label>
            <div class="col-sm-10">
                <select class="form-control " :name="name" style="width:100%" v-select="form[name]" multiple v-model="form[name]">
                    <option v-for="(key, val) in items" :value="key">
                        {{ val }}
                    </option>
                </select>
                <span class="help-block" v-show="form.errors.has(name)">
                    <strong>{{ form.errors.get(name) }}</strong>
                </span>
            </div>
        </div>
        <div class="hr-line-dashed"></div>
    </div>
</template>

<script>
    export default {
        props: ['display', 'placeholder', 'form', 'name', 'help', 'items'],
        ready(){

            let val = this.form[this.name];
        }
    }
</script>

