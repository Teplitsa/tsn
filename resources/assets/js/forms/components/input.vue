<template>
    <div>
        <td :class="{'form-group': true, 'has-error': form.errors.has(name) }">
                <input type="text" class="form-control" :placeholder="placeholder">
                <span class="help-block" v-show="form.errors.has(name)">
                    <strong>{{ form.errors.get(name) }}</strong>
                </span>
          </td>

    </div>
</template>

<script>
    export default{
        props: ['display', 'placeholder', 'form', 'name', 'help']
    }
</script>

