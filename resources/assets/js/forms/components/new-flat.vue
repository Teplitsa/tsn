<template>
    <tr>
        <td>{{ i }}</td>
        <td>
            <input type="text" class="form-control" v-model="item.account_number"/>
        </td>
        <td>
            <input type="text" class="form-control" v-model="item.cold_watter"/>
        </td>
        <td>
            <input type="text" class="form-control" v-model="item.warm_watter"/>
        </td>
        <td>
            <input type="text" class="form-control" v-model="item.day_tariff"/>
        </td>
        <td>
            <input type="text" class="form-control" v-model="item.night_tariff"/>
        </td>
        <td>
            <input type="text" class="form-control" v-model="item.gas_tariff"/>
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

