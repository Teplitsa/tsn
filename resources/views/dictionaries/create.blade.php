@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">

                    <form action="{{ route('employees.store') }}" class="form-horizontal">

                        <app-text
                                display="Имя"
                                placeholder="Введите ваше имя"
                                :form="form"
                                name="first_name"
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

                        <div class="row">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" v-on:click.prevent="submit()" class="btn btn-primary">
                                    Сохранить
                                </button>
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
        App.forms.employees = {
            first_name: '',
            last_name: '',
            email: ''
        }
    </script>
@endsection