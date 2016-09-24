@extends('layouts.base')

@section('content')
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <form action="{{ route('dictionary.save') }}">
                <div class="clients-list" id="dictionaries">
                    <ul class="nav nav-tabs">
                        <li :class="{'active':isActive(dictionary)}" v-for="dictionary in form.dictionary">
                            <a href="#" @click.prevent="setActive(dictionary)">
                                @{{ dictionary.name }}
                                <i class="fa fa-close" v-on:click.prevent="removeDictionary(dictionary)"></i>
                            </a>
                        </li>
                        <li id="new"><a v-on:click.prevent="addDictionary()" href="#"><i class="fa fa-plus"></i></a></li>
                    </ul>
                    <div class="tab-content">
                        <div :class="{'tab-pane': true, 'active':isActive(dictionary)}"
                             v-for="dictionary in form.dictionary"
                        >
                            <div class="full-height-scroll">

                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="control-label">Ключевое слово</label>
                                    <input type="text" v-if="dictionary.id == ''"
                                           v-model="dictionary.keyword"
                                           class="form-control">
                                    <input type="text" v-else
                                           readonly="" v-model="dictionary.keyword"
                                           class="form-control">
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group">
                                    <label class="control-label">Название</label>
                                    <input type="text" v-model="dictionary.name"
                                           class="form-control">
                                </div>
                                <div class="hr-line-dashed"></div>
                                <table class="table table-striped table-hover">
                                    <tbody>
                                    <tr>
                                        <th>Значение</th>
                                        <th>Текст</th>
                                        <th>Удалить?</th>
                                    </tr>
                                    <tr v-for="value in dictionary.items">
                                        <td><input v-model="value.value" class='form-control'></td>
                                        <td><input v-model="value.text" class='form-control'></td>
                                        <td>
                                            <a class="btn btn-outline btn-danger" href="#"
                                               v-on:click.prevent="removeRow(dictionary,value)"><i
                                                        class=" fa fa-trash"></i></a></td>
                                    </tr>
                                    <tr v-if="dictionary.items.length==0" class="ibox-heading text-center">
                                        <td colspan="3"><strong> Значений не найдено </strong></td>
                                    </tr>

                                    </tbody>
                                </table>
                                <div>
                                    <button class="btn btn-white" v-on:click.prevent="addRow(dictionary)"><i
                                                class="fa fa-plus"> </i>
                                        Добавить
                                        новое значение
                                    </button>
                                    <button type="submit" v-on:click.prevent="submit()" class="btn btn-primary">
                                        Сохранить
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

@endsection
@section('after_body')
    <script>
        App.forms.dictionary = {dictionary: {!! json_encode($dictionaries['data']) !!} }
    </script>
@endsection
