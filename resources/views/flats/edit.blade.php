@extends('layouts.base')

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="row">
                <div class="col-md-7">
                    <form action="{!! route('flats.update',$flat) !!}" class="form-horizontal">
                        <input type="hidden" name="_method" value="POST"/>
                        <app-select
                                display="Укажите ваш город"
                                :form="form"
                                name="city"
                                :items="{{ json_encode($cities) }}"
                        ></app-select>

                        <app-select
                                display="Улица"
                                :form="form"
                                name="street_id"
                                :items="streets"
                        ></app-select>

                        <app-text
                                display="Номер дома"
                                :form="form"
                                name="number"
                        ></app-text>

                        <app-text
                                display="Номер квартиры"
                                :form="form"
                                name="flat"
                        ></app-text>

                        <app-text
                                display="Площадь квартиры м<sup>2</sup>"
                                :form="form"
                                name="square"
                        ></app-text>

                        <div class="row">
                            <div class="col-sm-2"><label class="text-right">Доля в собственности</label></div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input type="text" class="form-control pull-right"
                                           v-model="form.up_part"
                                           placeholder="Числитель">
                                    <div class="input-group-addon">/</div>
                                    <input type="text" class="form-control pull-right"
                                           v-model="form.down_part"
                                           placeholder="Знаменатель">
                                </div>
                            </div>
                            <div class="col-sm-6" v-if="mySquare > 0">
                                Площадь в собственности: @{{ mySquare }} м<sup>2</sup>
                            </div>
                        </div>

                        <hr class="hr-line-dashed"/>

                        <app-text
                                display="Номер документа о праве собственности"
                                :form="form"
                                name="number_doc"
                        ></app-text>

                        <app-text
                                display="Дата выдачи документа"
                                :form="form"
                                name="date_doc"
                        ></app-text>

                        <app-text
                                display="Кем выдан документ"
                                :form="form"
                                name="issuer_doc"
                        ></app-text>


                        <div class="row">
                            <div class="col-sm-2"><label class="text-right">Скан документа</label></div>
                            <div class="col-sm-10">
                                <img :src="form.scan" v-if="form.scan" style="height: 200px;" />
                                <button class="btn btn-success" @click.prevent="openScan">
                                    Выбрать изображение
                                </button>
                                <input type="file" @change="previewImage" name="scan" style="display: none" />
                            </div>
                        </div>

                        <hr class="hr-line-dashed"/>

                        <button class="col-sm-offset-2 btn btn-success"
                                :disabled="form.busy"
                                v-on:click.prevent="submit()"
                        >
                            <span v-if="form.busy">
                                <i class="fa fa-btn fa-spinner fa-spin"></i> Сохранение
                            </span>
                            <span v-else>
                                <i class="fa fa-plus"></i> Сохранить
                            </span>
                        </button>
                    </form>
                </div>
                <div class="col-md-5">
                    <h3>Для чего нужно привязывать квартиру?</h3>
                    <p>
                        Зарегистрировав личный кабинет, вы получили возможность управлять судьбой всех квартир в одном
                        месте. Для того чтобы принять участие в голосовании, вам надо быть собственником помещения.
                        Вам потребуется подтвердить право собственоности на квартиру (долю в квартире), для этого
                        загрузить скан вашего свидетельства о праве собственности, и после ручной проверки Вашим
                        председателем ТСЖ, вы получите доступ к голосованиям.
                    </p>

                    <h3>Я не могу добавить квартиру. Дом не зарегистрирован в системе.</h3>
                    <p>
                        Ваш дом не добавлен в систему. Обратитесь к Вашему председателю ТСН с просьбой
                        зарегистрироваться, проведите очное собрание, для придания юридической силы результатам
                        голосования и начинайте вершить демократию не вставая с дивана!
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_body')
    <script>
        App.forms.flats = {
            city: '{{$flat->flat->house->street->city_id}}',
            street_id: '{{$flat->flat->house->street->id}}',
            number: '{{ $flat->flat->house->number }}',
            number_doc: '{{ $flat->number_doc }}',
            issuer_doc: '{{ $flat->issuer_doc }}',
            up_part: '{{ $flat->up_part}}',
            down_part: '{{ $flat->down_part}}',
            flat: '{{ $flat->flat->id }}',
            square: '{{ $flat->square }}',
            scan: '/storage/{{ $flat->scan }}',
            date_doc: '{{ $flat->date_doc->format('d.m.Y') }}',
        }
    </script>
@endsection