@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-md-6 col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Собственники дома &nbsp;</h5> {{$house->address}},
                    <small>площадью {{$house->square}} м <sup>2</sup></small>
                </div>
                <div class="ibox-content">
                    <h2>
                        @if($house->square!=0)
                            {!! round($house->connectedFlatsSquare() / $house->square*100) !!}%
                        @else
                            0%
                        @endif
                        <small> подключено к системе</small>
                    </h2>
                    @foreach( $house->flats as $flat)
                        @foreach($flat->registered_flats as $registeredFlat)
                            <div class="hr-line-dashed"></div>
                            <div class="row">
                                <div class="col-md-4">
                                    {!! $registeredFlat->user->full_name !!}
                                    <small>{!! $registeredFlat->flat->address_full !!} <br>
                                        в собственности: {!! $registeredFlat->user_share !!} м <sup>2</sup>
                                    </small>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{route('houses.flat.download',[$house,$registeredFlat])}}"><i
                                                class="fa fa-file"></i> </a>
                                </div>
                                <div class="col-md-3">
                                    @if($registeredFlat->active)
                                        <span class="label label-success">Подключена</span>
                                    @else
                                        <span class="label label-warning">Не подключена</span>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    @if(!$registeredFlat->active)
                                        <a href="{{route('houses.flat.active',[$house,$registeredFlat])}}"><span
                                                    class="label label-primary">Подключить</span></a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6">
            <div class="ibox-content float-e-margins">
                <div class="panel blank-panel">
                    <div class="panel-heading">
                        <div class="panel-options">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab-1" data-toggle="tab"
                                                      aria-expanded="true">Голосования</a></li>
                                <li class=""><a href="#tab-2" data-toggle="tab" aria-expanded="false">Как
                                        подключиться?</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="panel-body">

                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-1">
                            <a href="{!! route('houses.votings.create', $house) !!}"
                               class="btn btn-block btn-primary">
                                <i class="fa fa-plus"></i> Добавить голосование
                            </a>
                            @forelse($house->votings as $voting)
                                <div class="hr-line-dashed"></div>
                                <div class="row">
                                    <div class="col-md-6">
                                        {!! $voting->name !!}
                                    </div>
                                    <div class="col-md-3">
                                        @if($voting->closed_at > \Carbon\Carbon::now())
                                            <span class="label label-warning">Идет</span>
                                        @else
                                            <span class="label label-success">Завершено</span>
                                        @endif
                                    </div>
                                    <div class="col-md-3"><a
                                                href="{!! route('houses.votings.show', [$house, $voting]) !!}"
                                                class="btn btn-white btn-block"
                                        >
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info">
                                    Голосований не найдено
                                </div>
                            @endforelse
                        </div>
                        <div class="tab-pane" id="tab-2">
                            <div class="form-group">
                                <h4>Вы можете выслать приглашение на электроную почту</h4>
                            </div>

                            <form action="/send_invite" class="form-horizontal multipart-encoded">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">
                                            Электронная почта
                                        </label>
                                        <div class="col-sm-8">
                                            <input class="form-control" name="email"
                                                   placeholder="Введите электронную почту"/>
                                        </div>
                                    </div>
                                    <input type="submit" class="btn btn-primary btn-block" value="Отправить"/>
                                </div>
                            </form>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <h4>Или распечатать инструкцию</h4>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <h4>Инструкция по подключению к {{config('app.name')}}</h4>
                            <ol>
                                <li>
                                    Пройдите по ссылке <a href="https://tsn.ananas-web.ru/register">tsn.ananas-web.ru/register</a>
                                </li>
                                <li>
                                    Введите свои контактные данные
                                </li>
                                <li>
                                    В настройках пользователя укажите, жильцом какого дома Вы являетесь
                                </li>
                                <li>
                                    Ждите подтверждения от вашего управляющего
                                </li>
                                <li>
                                    Вы можете участвовать в голосовании
                                </li>
                            </ol>
                            <div class="hr-line-dashed"></div>
                            <div align="center">
                                <h4>QR-Code</h4>
                            </div>
                            <div style="text-align: center">
                                <img src="/img/chart.png" width="50%">
                            </div>

                            <input type="submit" class="btn btn-primary btn-block" value="Печать" onclick="print()"/>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop
@section('after_body')
    <script>
        function print() {
            let newWin = open('', 'Печать инструкции по подключению к {{config('app.name')}}', '', 'status=no,toolbar=no,menubar=no,location=no,scrollbar=0,resizable=yes');
            newWin.document.writeln('<html><head></head><body>' + '<h1>Инструкция по подключению к {{config('app.name')}}</h1>' +
                '<ol style="font-size: 25px;">' +
                '<li>' +
                'Пройдите по ссылке <a href="https://tsn.ananas-web.ru/register">tsn.ananas-web.ru/register</a>' +
                '</li>' +
                '<li>' +
                'Введите свои контактные данные' +
                '</li>' +
                '<li>' +
                'В настройках пользователя укажите, жильцом какого дома Вы являетесь' +
                '</li>' +
                '<li>' +
                'Ждите подтверждения от вашего управляющего' +
                '</li>' +
                '<li>' +
                'Вы можете участвовать в голосовании' +
                '</li>' +
                '</ol>' +
                '<h1>QR-Code для {{config('app.name')}}</h1>' +
                '<img src="/img/chart.png" width="50%"></body>' +
                '</html>');
            newWin.document.close();
            newWin.focus();
            newWin.print();
            newWin.close();
        }

    </script>
@stop