@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-lg-12">

            @forelse($flats as $flat)
                <div class="wrapper wrapper-content animated fadeInUp">
                    @if(!empty($flat->flat))
                        <div class="ibox-title">
                            <h5>Голосование для квартиры по адресу {{$flat->flat->address_full}}</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="project-list">

                                <table class="table table-hover">
                                    <tbody>
                                    @forelse($flat->flat->activeVotings as $voting)
                                        <tr>
                                            <td class="project-status">
                                                @if($voting->isOpen())
                                                    <span class="label label-primary">Идет</span>
                                                @else
                                                    <span class="label label-default">Завершено</span>
                                                @endif
                                            </td>
                                            <td class="project-title">
                                                <a href="{!! route('flat.voting', [$flat, $voting]) !!}">{!! $voting->name !!}</a>
                                                <br>
                                                <small>с: {!! $voting->created_at->format('d.m.Y') !!}</small>
                                                <small>по: {!! $voting->end_at->format('d.m.Y') !!}</small>
                                            </td>
                                            <td class="project-completion">
                                                <small>Прошло времени: {!! $voting->current_percent !!}%</small>
                                                <div class="progress progress-mini">
                                                    <div style="width: {!! $voting->current_percent !!}%;"
                                                         class="progress-bar"></div>
                                                </div>
                                            </td>
                                            <td class="project-actions">
                                                <a href="{!! route('flat.voting', [$flat, $voting]) !!}"
                                                   class="btn btn-white btn-sm">@if($voting->isFullForCurrentUser($flat))
                                                        Переголосовать @else Проголосовать@endif</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                Активных голосований нет по данному адресу. Вы можете их создать.
                                            </td>
                                        </tr>
                                    @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    @empty
                        <div class="wrapper wrapper-content animated fadeInUp">
                            <div class="alert alert-info">
                                Активных голосований нет. Вы можете их создать.
                            </div>
                        </div>
                </div>
            @endforelse

        </div>
    </div>

@endsection

@section('after_body')
@endsection
