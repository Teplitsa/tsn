@extends('layouts.auth')

@section('content')
    <app-register-company inline-template v-cloak>
        <div class="text-center loginscreen animated fadeInDown loginColumns animated fadeInDown">
            <div>

                <h3>Зарегистрироваться в Ананас.ТСН</h3>
                <form class="form-horizontal" role="form" action="/new-company">
                    <app-email
                            display="Email"
                            placeholder="Введите ваш email"
                            :form="form"
                            name="email"
                    ></app-email>

                    <app-password
                            display="Пароль"
                            placeholder="Введите пароль"
                            :form="form"
                            name="password"
                    ></app-password>

                    <app-password
                            display="Подтвердите пароль"
                            placeholder="Введите пароль еще раз"
                            :form="form"
                            name="password_confirmation"
                    ></app-password>

                    <app-text
                            display="ИНН ТСН"
                            placeholder="Введите ИНН вашего ТСН"
                            :form="form"
                            name="inn"
                    ></app-text>
                    <div class="form-group">
                        <button type="button"
                                class="btn btn-info block full-width m-b"
                                :disabled="loadingInfo"
                                v-on:click.prevent="loadInfo()"
                        >
                            <span v-if="loading">
                                <i class="fa fa-btn fa-spinner fa-spin"></i> Поиск информации о ТСН
                            </span>
                            <span v-else>
                                <i class="fa fa-search"></i> Заполнить по ИНН
                            </span>
                        </button>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <app-text
                            display="Название ТСН"
                            placeholder="Введите название ТСН"
                            :form="form"
                            name="title"
                    ></app-text>

                    <app-text
                            display="КПП ТСН"
                            placeholder="Введите КПП ТСН"
                            :form="form"
                            name="kpp"
                    ></app-text>

                    <app-text
                            display="ОГРН ТСН"
                            placeholder="Введите ОГРН ТСН"
                            :form="form"
                            name="ogrn"
                    ></app-text>

                    <app-text
                            display="Имя"
                            placeholder="Введите Ваше имя"
                            :form="form"
                            name="first_name"
                    ></app-text>

                    <app-text
                            display="Отчество"
                            placeholder="Введите Ваше отчество"
                            :form="form"
                            name="middle_name"
                    ></app-text>

                    <app-text
                            display="Фамилия"
                            placeholder="Введите Вашу фамилию"
                            :form="form"
                            name="last_name"
                    ></app-text>

                    <div class="form-group">
                        <div class="checkbox i-checks">
                            <label>
                                <input type="checkbox" v-model="form['agreed']"><i></i> Нажимая "Зарегистрироваться", Вы соглашаетесь с условиями пользовательского соглашения

                            </label>
                        </div>
                    </div>
                    <button type="submit"
                            class="btn btn-primary block full-width m-b"
                            v-on:click.prevent="submit()"
                    >
                        <span v-if="form.busy">
                            Обработка
                        </span>

                        <span v-else>
                            Зарегистрироваться
                        </span>
                    </button>

                    <p class="text-muted text-center">
                        <small>Уже есть аккаунт?</small>
                    </p>
                    <a class="btn btn-sm btn-white btn-block" href="/login">
                        Авторизоваться
                    </a>
                </form>
                <p class="m-t">
                    <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small>
                </p>
            </div>
        </div>
    </app-register-company>
@endsection
