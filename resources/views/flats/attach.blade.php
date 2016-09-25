@extends('layouts.base')

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="row">
                <div class="col-md-7">
                    <form action="{!! route('flats.attach.post') !!}" class="form-horizontal">
                        <app-text
                                display="Введите ваш номер счета"
                                placeholder="Номер счета"
                                :form="form"
                                name="account"
                                help="Его вам должен сообщить председеталь ТСЖ"
                        ></app-text>

                        <app-select
                                display="Выберите ТСЖ"
                                :form="form"
                                name="company_id"
                                help="Укажите ваше товарищество"
                                :items="{{ json_encode($companies) }}"
                        ></app-select>

                        <button class="col-sm-offset-2 btn btn-success"
                                :disabled="form.busy"
                                v-on:click.prevent="submit()"
                        >
                            <span v-if="form.busy">
                                <i class="fa fa-btn fa-spinner fa-spin"></i> Добавление
                            </span>
                            <span v-else>
                                <i class="fa fa-plus"></i> Добавить
                            </span>
                        </button>
                    </form>
                </div>
                <div class="col-md-5">
                    <h3>Для чего нужно привязывать квартиру?</h3>
                    <p>
                        Потому что это удобно! Вам не нужно больше думать стоять в томных очередях, заходить в интернет
                        банк. Все - что вам надо - это просто пройти регистрацию.
                        Процедура проста:
                    </p>
                    <ol>
                        <li>Укажите номер лицевого счета и УК</li>
                        <li>Дождитесь кода в следующей квитанции или получите его лично в Вашей УК</li>
                        <li>Введите код</li>
                        <li>Начинайте работать</li>
                    </ol>

                    <h3>Я не могу найти свое ТСЖ</h3>
                    <p>
                        Это означает, что Ваше ТСЖ не заключило договор с Ананас.ЖКХ. Расскажите о нас Вашему ТСЖ и
                        может уже в ближайшее время оно появится тут
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection