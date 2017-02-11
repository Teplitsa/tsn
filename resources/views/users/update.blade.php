@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form action="{{ $handler }}" class="form-horizontal multipart-encoded">
                        <input type="hidden" name="_method" value="{{ $method }}"/>
                        <app-text
                                display="Имя"
                                placeholder="Введите ваше имя"
                                :form="form"
                                name="first_name"
                        ></app-text>

                        <app-text
                                display="Отчество"
                                placeholder="Введите отчество"
                                :form="form"
                                name="middle_name"
                        ></app-text>
                        <app-text
                                display="Фамилия"
                                placeholder="Введите фамилию"
                                :form="form"
                                name="last_name"
                        ></app-text>
                        <app-email
                                display="Email"
                                placeholder="Введите email"
                                :form="form"
                                name="email"
                        ></app-email>

                        <app-image
                                display="Фотография"
                                :form="form"
                                name="avatar"
                                :email="form.email"
                                width="200px"
                        ></app-image>

                        <app-password
                                display="Старый пароль"
                                placeholder="Введите старый пароль"
                                :form="form"
                                name="old_password"
                        ></app-password>
                        <app-password
                                display="Новый пароль"
                                placeholder="Введите новый пароль"
                                :form="form"
                                name="password"
                        ></app-password>
                        <app-password
                                display="Повторите новый пароль"
                                placeholder="Повторите пароль"
                                :form="form"
                                name="password_confirmation"
                        ></app-password>



                        <div class="row">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" v-on:click.prevent="submit()" class="btn btn-primary">
                                    <i class="fa fa-check"></i>
                                    Сохранить
                                </button>
                                <a href="{{route('index')}}" class="btn btn-default pull-right">
                                    <i class="fa fa-close"></i>
                                    Отменить изменения
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_body')
    <script>
        App.forms.users = {
            first_name: '{{$user->first_name}}',
            last_name: '{{ $user->last_name }}',
            middle_name: '{{ $user->middle_name }}',
            email: '{{ $user->email }}',
            avatar: '{!!  $user->avatar_url  !!}',

        }
    </script>
@endsection