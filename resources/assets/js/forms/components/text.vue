<template>
    <div>
        <div :class="{'form-group': true, 'has-error': form.errors.has(name) }">
            <label class="col-sm-2 control-label">
                {{{ display }}}
                <br v-if="help != ''"/>
                <small v-if="help != ''" class="text-navy">{{ help }}</small>
            </label>
            <div class="col-sm-10">
                <input type="text" class="form-control" v-model="form[name]" :placeholder="placeholder">
                <span class="help-block" v-show="form.errors.has(name)">
                    <strong>{{ form.errors.get(name) }}</strong>
                </span>
            </div>
        </div>
        <div class="hr-line-dashed"></div>
    </div>
</template>

<script>
    export default{
        props: ['display', 'placeholder', 'form', 'name', 'help']
    }
</script>

