@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Документ о праве собственности</h5>
                </div>
                <div class="ibox-content">
                        <img src="/storage/{{$flat->scan}}" width="100%">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Информация о квартире: {{$pageTitle}}</h5>
                </div>
                <div class="ibox-content">
                    <div class="clients-list">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tbody>

                                        <tr>
                                            <td>Собственник</td>
                                            <td>
                                                {{$flat->user->full_name}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Площадь в собственности</td>
                                            <td>
                                                {{$flat->square*$flat->up_part/$flat->down_part}} м <sup>2</sup>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Площадь в квартиры</td>
                                            <td>
                                                {{$flat->square}} м <sup>2</sup>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Номер документа </td>
                                            <td>
                                                {{$flat->number_doc}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Дата выдачи документа </td>
                                            <td>
                                                {{$flat->date_doc->format('d.m.Y')}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Документ выдан</td>
                                            <td>
                                                {{$flat->issuer_doc}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Документ</td>
                                            <td>
                                                <a href="{{route('houses.flat.download',[$house,$flat])}}"><i
                                                            class="fa fa-file"></i> </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Статус квартиры</td>
                                            <td>
                                                @if(!$flat->active)
                                                    <a href="{{route('houses.flat.active',[$house,$flat])}}"><span
                                                                class="label label-primary">Подключить</span></a>
                                                    @else
                                                    <a href="javascript:void(0)"><span
                                                                class="label label-default">Подключена</span></a>
                                                @endif

                                            </td>
                                        </tr>

                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@stop