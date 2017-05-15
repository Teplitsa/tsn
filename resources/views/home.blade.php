@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-6">
            <div class="ibox float-e-margins">

                @forelse($flats as $flat)
                        @if(!empty($flat->flat))
                        <div class="ibox-title">
                            <h5>Голосование для квартиры по адресу {{$flat->flat->address_full}}</h5>
                        </div>
                        <div class="ibox-content">
                            @forelse($flat->flat->activeVotings as $voting)
                                <div class="row">
                                    <div class="col-sm-6">
                                        {!! $voting->name !!}
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="{!! route('flat.voting', [$flat, $voting]) !!}"
                                           class="btn btn-block btn-primary">Проголосовать</a>
                                    </div>

                                </div>
                            @empty
                                <div class="alert alert-info">
                                    Активных голосований нет по данному адресу. Вы можете их создать.
                                </div>
                            @endforelse
                        @endif
                        </div>
                @empty
                    <div class="alert alert-info">
                        Активных голосований нет. Вы можете их создать.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection

@section('after_body')
@endsection
