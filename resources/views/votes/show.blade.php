@extends ('layouts.base')

@section ('content')
    <div>
        <form class="form-horizontal" action="{!! route('flat.voting', [$flat, $voting]) !!}">
            <div class="row">
                <div class="col-md-8 col-sm-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title" style="background-color: #1ab394;color: #ffffff;">
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
                            <div class="row voting">
                                <div :class="{'col-md-4 text-center':true, 'active': active.v == '1'}" v-on:click.prevent="vote(active, '1')">
                                    <i class="fa fa-thumbs-up" style="font-size: 30px"></i>
                                    <br/>
                                    За
                                </div>
                                <div :class="{'col-md-4 text-center':true, 'active': active.v == '0'}"  v-on:click.prevent="vote(active, '0')">
                                    <i class="fa fa-minus" style="font-size: 30px"></i>
                                    <br/>
                                    Воздержусь
                                </div>
                                <div :class="{'col-md-4 text-center':true, 'active': active.v == '-1'}"  v-on:click.prevent="vote(active, '-1')">
                                    <i class="fa fa-thumbs-down" style="font-size: 30px"></i>
                                    <br/>
                                        Против
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <h3>Информация по голосованию</h3>

                            <p>Название: <b>@{{ form.name }}</b></p>
                            <p>Крайний срок: <b>@{{ form.closed_at }}</b></p>


                            <h3>Повестка дня</h3>
                            <ul class="list-group">
                                <div v-for="(i, item) in form.items">
                                <li class="list-group-item"
                                    v-if="item.id==active.id" style="background-color: #1ab394;color: #ffffff;"
                                    v-on:click.prevent="selectItem(item)">
                                    @{{ i+1 }}. @{{ item.name }}
                                </li>
                                    <li class="list-group-item"
                                        v-else
                                        v-on:click.prevent="selectItem(item)">
                                        @{{ i+1 }}. @{{ item.name }}
                                    </li>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
@stop


@section('after_body')
    <script>
        App.forms.voting = {!! json_encode($voting->getInfo()) !!}
    </script>
@stop