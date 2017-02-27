<template>
    <div>
            <div class="col-sm-12" >
                <input type="text"  style="display:none"  class="form-control" v-model="form[name]" :val="" :placeholder="placeholder" :id="id" :disabled="disabled">
            </div>
    </div>
</template>

<script>
    export default{
        props: [ 'placeholder', 'form', 'name', 'help','id','disabled']
    }
</script>

