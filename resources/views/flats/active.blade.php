@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="wrapper wrapper-content animated fadeInUp">

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Ваши голосования</h5>
                        <div class="ibox-tools">
                            <a href="{!! route('houses.votings.create', [$flat->house]) !!}"
                               class="btn btn-primary btn-xs">Новое голосование</a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="project-list">

                            <table class="table table-hover">
                                <tbody>
                                @forelse($votings as $voting)
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
                                            Активных голосований нет
                                        </td>
                                    </tr>
                                @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection