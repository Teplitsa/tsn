@extends ('layouts.base')

@section ('content')

    <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading"> Votings </div>
                        <div class="panel-body form-horizontal">
                            @foreach($votings as $this_voting)

                                <div class="row" style="margin-bottom: 10px;">
                                    <div class="col-md-4"></div>

                                    <div class="col-md-8">

                                        <a href="{{route('voting.show',[$this_voting->id]) }}">Показать</a>
                                        <a href="{{route('voting.edit',[$this_voting->id]) }}">Изменить</a>


                                    </div>
                                </div>

                            @endforeach

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">

                                    <a href="{{route('voting.create',[]) }}">Создать</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


@stop

