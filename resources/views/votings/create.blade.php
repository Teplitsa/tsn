@extends ('layouts.base')

@section ('content')
    <div>
        <form class="form-horizontal" action="{!! route('houses.votings.store', $house) !!}">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <h3>Информация по голосованию</h3>
                            <app-select-multiple
                                    display="Инициаторы голосования"
                                    :form="form"
                                    name="initiators"
                                    placeholder="Выберете инициаторов голосования"
                                    :items="{{ json_encode($house->users)}}"
                            >
                            </app-select-multiple>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    Название
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" v-model="form.name"
                                           placeholder="Введите понятное название для собственников">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <app-select
                                    display="Вид общего собрания"
                                    :form="form"
                                    name="voting_type"
                                    :items="{{ json_encode(\App\Enums\VotingTypes::humanValuesForVue()) }}">
                            </app-select>
                            <app-select
                                    display="Форма проведения собрания"
                                    :form="form"
                                    name="kind"
                                    :items="{{ json_encode(\App\Enums\VotingForms::humanValuesForVue()) }}">
                            </app-select>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    Дата и время проведения очного обсуждения
                                </label>

                                <div class="col-sm-10">

                                    <div class="input-group date">
                                        <div class="input-group-addon">C</div>

                                        <input type="text" class="form-control pull-right datetimepicker"
                                               v-model="form.public_at"
                                               name="deadline" placeholder="Введите дату">

                                        <div class="input-group-addon">До</div>

                                        <input type="text" class="form-control pull-right datetimepicker"
                                               v-model="form.end_at"
                                               name="deadline" placeholder="Введите дату">
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    Дата и время проведения заочного голосования
                                </label>

                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon">C</div>

                                        <input type="text" class="form-control pull-right datetimepicker"
                                               v-model="form.opened_at"
                                               name="deadline" placeholder="Введите дату">

                                        <div class="input-group-addon">До</div>

                                        <input type="text" class="form-control pull-right datetimepicker"
                                               v-model="form.closed_at"
                                               name="deadline" placeholder="Введите дату">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <h3>Место проведения очных слушаний</h3>
                            <app-select
                                    display="Город"
                                    :form="form"
                                    name="city"
                                    :items="{{ json_encode($cities) }}"></app-select>

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
                                    value="{{$house->number}}"
                            ></app-text>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Вопрос № @{{ active.i }}</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    Вопрос
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" v-model="active.name"
                                           placeholder="Введите суть вопроса">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    Решение
                                </label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" v-model="active.description"
                                              placeholder="Введите предложение для голосования"></textarea>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    Опишите идею подробнее
                                </label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" v-model="active.text"
                                              placeholder="Опишите идею подробнее, чтобы помочь собственникам принять верное решение">
                                    </textarea>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <h3>Повестка дня</h3>
                            <ul class="list-group">
                                <li class="list-group-item" v-for="(i, item) in form.items" @click.prevent="selectItem(item)">
                                    @{{ i+1 }}. @{{ item.name }}
                                </li>
                            </ul>
                            <a href="#" v-on:click.prevent="addItem()" class="btn btn-block btn-white">
                                <i class="fa fa-plus"></i> Добавить вопрос
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn-block btn btn-success"
                            :disabled="form.busy || hasErrors"
                            v-on:click.prevent="submit()"
                    >
                        <span v-if="hasErrors">
                            <i class="fa fa-times"></i> @{{ error }}
                        </span>
                        <span v-else>
                            <span v-if="form.busy">
                                        <i class="fa fa-btn fa-spinner fa-spin"></i> Создание голосования
                                    </span>
                            <span v-else>
                                <i class="fa fa-check"></i> Создать голосование
                            </span>
                        </span>
                    </button>

                </div>
            </div>
            <div class="row">&nbsp;</div>
        </form>
    </div>
@stop

@section('after_js')
    <script>

        $('.datetimepicker').datetimepicker({
            format: 'DD.MM.YYYY HH:mm',
            locale: 'ru'
        });

        $('.datetimepicker').mask('99.99.9999 99:99')

    </script>
@stop

@section('after_body')
    <script>
        App.forms.ManageVoting = {
            code: '',
            city: {{ $house->city_id }},
            street_id: '{{ $house->street_id }}',
            number: {{ $house->number }},
            name: '',
            initiators: [],
            kind: '',
            public_at: '',
            voting_type: '',
            opened_at: '',
            closed_at: '',
            end_at: '',
            items: []
        };

        App.streets = {!! json_encode($house->city->streets_for_vue)  !!};
    </script>
@stop