@extends ('layouts.base')

@section ('content')


    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $voting->name }}</div>
                    <div class="panel-body form-horizontal">
                        {!! csrf_field() !!}

                        <div class="row">
                            <div class="col-sm-4">Повестка дня</div>
                            <div class="col-sm-6">
                                @foreach($voting->vote_items as $vote_items)
                                    {{ $vote_items->name != null ? $vote_items->name : "Не указано" }};
                                    {{ $vote_items->description != null ? $vote_items->description : "Не указано" }};
                                    {{ $vote_items->text != null ? $vote_items->text : "Не указано" }};
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{route('voting.index') }}" class="btn btn-primary buy-btn" role="button">К списку резюме</a>
                                <a href="{{route('voting.create') }}" class="btn btn-primary buy-btn" role="button">Добавить резюме</a>
                                <a href="{{route('voting.edit', $voting->id) }}" class="btn btn-primary buy-btn" role="button">Изменить резюме</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

