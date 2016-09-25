@extends('layouts.base')

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="row">
                <div class="col-xs-12">
                    <h1 style="text-align: center">Заявка на подключение успешно создана</h1>
                    <div class="alert alert-info">
                        Вы можете получить код для активации у председателя ТСЖ. Активационный код также придет к вам в
                        следующей
                        квитанции
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-3">
                    <form class="form-horizontal" action="{!! route('flats.activate', $flat) !!}">
                        <div :class="{'form-group': true, 'has-error': form.errors.has('code') }">
                            <div class="col-sm-12">
                                <input type="text" class="form-control" v-model="form['code']"
                                       placeholder="Код активации" /s>
                                <span class="help-block" v-show="form.errors.has('code')">
                                    <strong>@{{ form.errors.get('code') }}</strong>
                                </span>
                            </div>
                        </div>
                        <div :class="{'form-group'}">
                            <button type="submit" v-on:click.prevent="submit" :disable="form.busy"
                                    class="btn btn-block btn-primary"
                            >
                                <span v-if="form.busy">
                                    <i class="fa fa-btn fa-spinner fa-spin"></i> Активация
                                </span>
                                <span v-else>
                                    <i class="fa fa-check"></i> Активировать
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection