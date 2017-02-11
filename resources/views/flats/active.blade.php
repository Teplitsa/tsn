@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-md-6 col-sm-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Голосование</h5>
                </div>
                <div class="ibox-content">
                    <a href="{!! route('houses.votings.create', [$flat->house]) !!}" class="btn btn-success btn-block">
                        <i class="fa fa-plus"></i> Новое голосование
                    </a>
                    @forelse($votings as $voting)
                        <div class="row">
                            <div class="col-sm-6">
                                {!! $voting->name !!}
                            </div>
                            <div class="col-sm-6">
                                <a href="{!! route('flat.voting', [$flat, $voting]) !!}" class="btn btn-block btn-primary">Проголосовать</a>
                            </div>

                        </div>
                    @empty
                        <div class="alert alert-info">
                            Активных голосований нет
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
@endsection