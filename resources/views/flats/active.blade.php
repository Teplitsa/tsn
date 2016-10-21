@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-md-4 col-sm-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Голосование</h5>
                </div>
                <div class="ibox-content">
                    @forelse($votings as $voting)
                        <div class="row">
                            <div class="col-sm-8">
                                {!! $voting->name !!}
                            </div>
                            <div class="col-sm-4">
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