@extends('layouts.auth')

@section('content')
    <app-register-form inline-template>
        <div class="middle-box text-center loginscreen   animated fadeInDown">
            <div>

                <h3>Зарегистрироваться в Ананас.ТСЖ</h3>
                <form class="m-t" role="form" action="/register">
                    <div :class="{'form-group': true, 'has-error': form.errors.has('first_name') }">
                        <input type="text" class="form-control" v-model="form['first_name']"
                               placeholder="Введите Ваше имя" required/>
                        <span class="help-block" v-show="form.errors.has('first_name')">
                        <strong>@{{ form.errors.get('first_name') }}</strong>
                    </span>
                    </div>
                    <div  :class="{'form-group': true, 'has-error': form.errors.has('email') }">
                        <input type="text" class="form-control" v-model="form['email']"
                               placeholder="Введите ваш email" required/>
                        <span class="help-block" v-show="form.errors.has('email')">
                        <strong>@{{ form.errors.get('email') }}</strong>
                    </span>
                    </div>
                    <div  :class="{'form-group': true, 'has-error': form.errors.has('password') }">
                        <input type="password" class="form-control" v-model="form['password']"
                               placeholder="Введите пароль" required/>
                        <span class="help-block" v-show="form.errors.has('password')">
                                <strong>@{{ form.errors.get('password') }}</strong>
                    </span>
                    </div>
                    <div  :class="{'form-group': true, 'has-error': form.errors.has('password_confirmation') }">
                        <input type="password" class="form-control" v-model="form['password_confirmation']"
                               placeholder="Повторите пароль" required/>
                        <span class="help-block" v-show="form.errors.has('password_confirmation')">
                                <strong>@{{ form.errors.get('password_confirmation') }}</strong>
                    </span>
                    </div>
                    <div class="form-group">
                        <div class="checkbox i-checks">
                            <label>
                                <input type="checkbox" v-model="form['agreed']"><i></i> Agree the terms and
                                policy
                            </label>
                        </div>
                    </div>
                    <button type="submit"
                            class="btn btn-primary block full-width m-b"
                            v-on:click.prevent="submit()"
                    >
                        Зарегистрироваться
                    </button>

                    <p class="text-muted text-center">
                        <small>Уже есть аккаунт?</small>
                    </p>
                    <a class="btn btn-sm btn-white btn-block" href="/login">
                        Зарегистрироваться
                    </a>
                </form>
                <p class="m-t">
                    <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small>
                </p>
            </div>
        </div>
    </app-register-form>
@endsection
