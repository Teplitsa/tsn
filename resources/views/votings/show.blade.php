@extends ('layouts.base')

@section ('content')
    <div>

        @if($voting->closed_at < \Carbon\Carbon::now())

            <form class="form-horizontal" action="{!! route('houses.votings.peoples',[$house, $voting]) !!}">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Информация по голосованию</h5>
                            </div>
                            <div class="ibox-content">
                                <app-select
                                        display="Председатель"
                                        :form="form"
                                        name="predsed"
                                        placeholder="Выберете председателя голосования"
                                        :items="{{ json_encode($users)}}"
                                ></app-select>
                                <app-select
                                        display="Секретарь"
                                        :form="form"
                                        name="secretar"
                                        placeholder="Выберете секретаря голосования"
                                        :items="{{ json_encode($users)}}"
                                ></app-select>

                                <app-select-multiple
                                        display="Счетная коммисия"
                                        :form="form"
                                        name="count"
                                        placeholder="Выберете счетную коммисию"
                                        :items="{{ json_encode($house->users)}}"
                                ></app-select-multiple>
                                <button class="btn-block btn btn-success">
                                    <i class="fa fa-check"></i> Сохранить
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            </form>

        @endif
        <form class="form-horizontal" action="{!! route('houses.votings.store', $house) !!}">
            <div class="row">
                <div class="col-md-8 col-sm-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Вопрос № @{{ active.i + 1 }}</h5>
                        </div>
                        <div class="ibox-content">
                            <p>
                                <b>Вопрос:</b><br/>
                                @{{ active.name }}
                            </p>
                            <div class="hr-line-dashed"></div>

                            <p>
                                <b>Предложение:</b><br/>
                                @{{ active.description }}
                            </p>

                            <div class="hr-line-dashed"></div>

                            <p>
                                <b>Опишите идею подробнее:</b><br/>
                                @{{ active.text }}
                            </p>
                            <div class="hr-line-dashed"></div>
                            <div class="row" v-if="active.total > 0">
                                <div class="col-md-4 text-center">
                                    <i class="fa fa-thumbs-up" style="font-size: 30px"></i>
                                    <br/>
                                    @{{ active.pro }}/@{{ active.total }}
                                    (@{{ Math.round(active.pro/active.total*100,2) }}%)
                                </div>
                                <div class="col-md-4 text-center">
                                    <i class="fa fa-minus" style="font-size: 30px"></i>
                                    <br/>
                                    @{{ active.contra }}/@{{ active.total }}
                                    (@{{ Math.round(active.contra/active.total*100,2) }}%)
                                </div>
                                <div class="col-md-4 text-center">
                                    <i class="fa fa-thumbs-down" style="font-size: 30px"></i>
                                    <br/>
                                    @{{ active.refrained }}/@{{ active.total }}
                                    (@{{ Math.round(active.refrained/active.total*100,2) }}%)
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <h3>Информация по голосованию</h3>
                            @if($voting->closed_at < \Carbon\Carbon::now())
                                <a href="{{route('houses.votings.download',[$voting->house,$voting])}}">Скачать
                                    протокол</a>
                            @endif
                            <p>Название: <b>@{{ form.name }}</b></p>
                            <p>Крайний срок: <b>@{{ form.closed_at }}</b></p>


                            <h3>Повестка дня</h3>
                            <ul class="list-group">
                                <li class="list-group-item" v-for="(i, item) in form.items"
                                    v-on:click.prevent="selectItem(item)">
                                    @{{ i+1 }}. @{{ item.name }}
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


            <form class="form-horizontal" action="{!! route('houses.votings.solution',[$house, $voting]) !!}">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        @foreach($voting->vote_items as $key=>$item)
                            @if($voting->closed_at < \Carbon\Carbon::now())
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">
                                        Решение по вопросу {{$key+1}}
                                    </label>
                                    <div class="col-sm-8">
                                    <textarea class="form-control" name="solution[]" data-voting="{{$item->id}}"
                                              placeholder="Введите решение"></textarea>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                            <button class="btn-block btn btn-success">
                                <i class="fa fa-check"></i> Сохранить
                            </button>
                    </div>
                </div>
            </form>

@stop


@section('after_body')
    <script>
        App.forms.voting = {!! json_encode($voting->getInfo()) !!}
    </script>

    <script>
        App.forms.ManagePeople = {
            predsed: '',
            secretar: '',
            count: '',
        };

    </script>
@stop