@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-md-4 col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Охват дома</h5>
                </div>
                <div class="ibox-content">
                    <h2>
                        {!! $house->connectedFlats()->count() !!}/{!! $house->flats()->count() !!}
                        <small> квартир подключено к системе</small>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
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
                            <div class="col-md-8">
                                {!! $voting->name !!}
                            </div>
                            <div class="col-md-2">
                                @if($voting->closed_at > \Carbon\Carbon::now())
                                    <span class="label label-warning">Идет</span>
                                @else
                                    <span class="label label-success">Завершено</span>
                                @endif
                            </div>
                            <div class="col-md-2"><a
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