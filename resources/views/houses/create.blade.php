@extends('layouts.base')

@section('content')
    <div>
        <form action="/houses" class="form-horizontal multipart-encoded">
            <div class="row">
                <div class="col-lg-7">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">

                            <h3>Информация о доме</h3>

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
                            <table class="table table-bordered" v-if="form.flats.length > 0">
                                <tr>
                                    <td>№</td>
                                    <td>
                                        Счет <br/>
                                        <a href="#" class="btn btn-xs btn-primary" v-on:click="generate()">
                                            Сгенерировать
                                        </a>
                                    </td>
                                    <td>Площадь</td>
                                </tr>

                                <tr v-for="(i, item) in form.flats">
                                    <td>@{{ i+1 }}</td>
                                    <td>
                                        <input type="text" class="form-control" v-model="item.account_number"/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" v-model="item.square"/>
                                    </td>
                                </tr>
                            </table>
                            <div v-else class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Укажите количество квартир
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-success btn-block"
                            :disabled="form.busy || form.flats.length == 0"
                            v-on:click.prevent="submit()"
                    >
                    <span v-if="form.flats.length == 0">
                        <i class="fa fa-times-circle-o"></i> Добавьте квартиры
                    </span>
                        <span v-if="form.busy">
                    <i class="fa fa-btn fa-spinner fa-spin"></i> Создание дома
                    </span>
                        <span v-if="form.flats.length != 0 && !form.busy">
                        <i class="fa fa-plus"></i> Создать дом
                    </span>
                    </button>
                </div>
            </div>
            <div class="row">
                &nbsp;
            </div>
        </form>
    </div>

@stop