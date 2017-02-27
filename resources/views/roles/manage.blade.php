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
                        <app-select
                                display="Должность"
                                :form="form"
                                name="role_id"
                                :items="{{ json_encode($roles) }}"></app-select>

                        <app-email
                                display="Email"
                                placeholder="Введите email"
                                :form="form"
                                name="email"
                        ></app-email>

                        <app-contact
                                display="Контакты"
                                :form="form"
                                :items="{{json_encode(\App\Enums\ContactTypes::humanValues())}}"

                                name="contacts"
                        ></app-contact>

                        <app-image
                                display="Фотография"
                                :form="form"
                                name="avatar"
                                :email="form.email"
                                width="200px"
                        ></app-image>


                        <div class="row">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" v-on:click.prevent="submit()" class="btn btn-primary">
                                    <i class="fa fa-check"></i>
                                    Сохранить
                                </button>
                                <a href="{{route('employees.index')}}" class="btn btn-default pull-right">
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
        App.forms.employees = {
            first_name: '{{$employee->first_name}}',
            last_name: '{{ $employee->last_name }}',
            middle_name: '{{ $employee->middle_name }}',
            role_id: '{{ $employee->role_id }}',
            email: '{{ $employee->email }}',
            city: '{{ $employee->city }}',
            avatar: '{!!  $employee->avatar_url  !!}',
            contacts: {!! $employee->renderedContacts !!}
        }
    </script>
@endsection