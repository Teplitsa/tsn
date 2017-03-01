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
                               <a href="{{route('houses.flat.download',[$house,$registeredFlat])}}"><i class="fa fa-file"></i> </a>
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
                                    <a href="{{route('houses.flat.active',[$house,$registeredFlat])}}"><span class="label label-primary">Подключить</span></a>
                                @endif
                            </div>
                        </div>
                @endforeach
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Голосования</h5>
            </div>
            <div class="ibox-content">
                <a href="{!! route('houses.votings.create', $house) !!}" class="btn btn-block btn-primary">
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
        </div>
    </div>
    </div>
@stop