@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form action="/houses" class="form-horizontal multipart-encoded">
                        <input type="hidden" name="_method" value="POST"/>
                        <app-text
                                display="Адрес"
                                placeholder="Введите адрес дома"
                                :form="form"
                                name="address"
                        ></app-text>

                        <app-text
                                display="Количество квартир"
                                :form="form"
                                name="number_of_flats"
                        ></app-text>

                        <h3>Информация о квартире</h3>
                        <table>
                            <tr>
                                <td rowspan="2">№</td>
                                <td rowspan="2">Счет</td>
                                <td colspan="5">Приборы учета</td>
                            </tr>
                            <tr>
                                <td>ХВС</td>
                                <td>ГВС</td>
                                <td>Эл. день</td>
                                <td>Эл. ночь</td>
                                <td>Газ</td>
                            </tr>
                            <tr v-for="(i, item) in form.flats">
                                <td>@{{ i }}</td>
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
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop