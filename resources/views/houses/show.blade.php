@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-md-6 col-sm-6">
            <div class="ibox">
                <div class="ibox-content">
                    <span class="text-muted small pull-right">@if($house->square!=0)
                            {!! round($house->connectedFlatsSquare() / $house->square*100) !!}%
                        @else
                            0%
                        @endif
                        <small> подключено к системе</small>
                    </span>
                    <h2>Собственники дома</h2>
                    <p>
                        площадью {{$house->square}} м <sup>2</sup>
                    </p>
                    <div class="clients-list">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tbody>
                                @foreach( $house->flats as $flat)
                                    @foreach($flat->registered_flats as $registeredFlat)
                                        <tr>
                                            <td>{!! $registeredFlat->user->full_name !!}
                                                </td>
                                            <td><small>{!! $registeredFlat->flat->address_full !!} <br>
                                                    в собственности: {!! $registeredFlat->user_share !!} м <sup>2</sup>
                                                </small></a>
                                            </td>
                                            <td>  <a href="{{route('houses.flat.download',[$house,$registeredFlat])}}"><i
                                                            class="fa fa-file"></i> </a></td>
                                            <td>    @if($registeredFlat->active)
                                                    <span class="label label-success">Подключена</span>
                                                @else
                                                    <span class="label label-warning">Не подключена</span>
                                                @endif</td>
                                            <td class="client-status"><a href="{{route('houses.flat.show',[$house,$registeredFlat])}}" class="label label-default">Просмотреть</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                    </div>

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
                            <div class="project-list">

                                <table class="table table-hover">
                                    <tbody>
                                    @forelse($house->votings as $voting)
                                        <tr>
                                            <td class="project-status">
                                                @if($voting->isOpen())
                                                    <span class="label label-primary">Идет</span>
                                                @else
                                                    <span class="label label-default">Завершено</span>
                                                @endif
                                            </td>
                                            <td class="project-title">
                                                <a href="javascript:void(0)">{!! $voting->name !!}</a>
                                                <br>
                                                <small>с: {!! $voting->created_at->format('d.m.Y') !!}</small>
                                                <br>
                                                <small>по: {!! $voting->end_at->format('d.m.Y') !!}</small>
                                            </td>
                                            <td class="project-completion">
                                                <small>Прошло времени: {!! $voting->current_percent !!}%</small>
                                                <div class="progress progress-mini">
                                                    <div style="width: {!! $voting->current_percent !!}%;"
                                                         class="progress-bar"></div>
                                                </div>
                                            </td>
                                            <td><a
                                                        href="{!! route('houses.votings.show', [$house, $voting]) !!}"
                                                        class="btn btn-white btn-block"
                                                >
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                Активных голосований нет
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
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
                                        <input type="hidden" value="{{$house->id}}" name="house_id">
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