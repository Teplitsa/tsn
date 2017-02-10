<template>
    <tr>
        <td>{{ i }}</td>
        <td>
            <input type="text" class="form-control" v-model="item.account_number"/>
        </td>
        <td>
            <input type="text" class="form-control" v-model="item.square"/>
        </td>
    </tr>

</template>

<script>
    export default{
        props: ['i', 'item', 'name', 'help', 'items'],
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

