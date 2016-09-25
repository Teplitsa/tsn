@extends('layouts.auth')

@section('content')
    <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6">
                <h2 class="font-bold">Добро пожаловать в Ананас.ТСЖ</h2>


            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <auth-form inline-template>
                        <form class="m-t" role="form" action="/login">
                            <div class="form-group">
                                <div :class="{'form-group': true, 'has-error': form.errors.has('email') }">
                                    <input type="email" class="form-control" v-model="form['email']"
                                           placeholder="Введите ваш email" required/>
                                    <span class="help-block" v-show="form.errors.has('email')">
                                    <strong>@{{ form.errors.get('email') }}</strong>
                                </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" v-model="form['password']"
                                       placeholder="Введите пароль" required/>
                                <span class="help-block" v-show="form.errors.has('password')">
                                <strong>@{{ form.errors.get('password') }}</strong>
                            </span>
                            </div>
                            <button
                                    type="submit"
                                    class="btn btn-primary block full-width m-b"
                                    v-on:click.prevent="submit()"
                            >
                                Войти
                            </button>
                            <a type="submit" href="/register" class="btn btn-white block full-width m-b">
                                Зарегистрироваться
                            </a>
                            <a href="{!! route('auth.forgot') !!}">
                                <small>Забыли пароль?</small>
                            </a>
                            <hr>

                            <a class="btn btn-sm btn-success btn-block" href="{!! route('new-company') !!}">
                                Зарегистрировать ТСЖ
                            </a>
                        </form>
                    </auth-form>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Компания Ананас.
            </div>
            <div class="col-md-6 text-right">
                <small>© {!! issued_dates() !!}</small>
            </div>
        </div>
    </div>
@stop
