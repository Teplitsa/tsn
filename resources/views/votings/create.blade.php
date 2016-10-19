@extends ('layouts.base')

@section ('content')
    <div>
        <form class="form-horizontal" action="{!! route('houses.votings.store', $house) !!}">
            <div class="row">
                <div class="col-md-8 col-sm-6">
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
                                    Предложение
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
                <div class="col-md-4 col-sm-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <h3>Информация по голосованию</h3>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                    Название
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="form.name"
                                           placeholder="Введите понятное название для собственников">
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                    Крайний срок
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="form.closed_at"
                                           placeholder="Введите дату"/>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>


                            <h3>Поверстка дня</h3>
                            <ul class="list-group">
                                <li class="list-group-item" v-for="(i, item) in form.items">
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