@extends('layouts.base')

@section('content')
    <div>
        <add-house inline-template>
            <div>
                <form action="/houses" class="form-horizontal multipart-encoded">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox float-e-margins">

                                    <div class="col-md-6">

                                        <h3>Информация о доме</h3>

                                        <input type="hidden" name="_method" value="POST"/>

                                        <div>
                                            <app-text :class="ymaps-2-1-47-input__control"
                                                    display="Адрес"
                                                    :form="form"
                                                    name="address"
                                                    id="address"
                                            ></app-text>
                                            <div name="map" id="map" style="width: 450px; height: 350px"></div>
                                            <div class="hr-line-dashed"></div>

                                            <app-text-hidden
                                                    :form="form"
                                                    name="city"
                                                    id="city"
                                                    disabled="true"
                                            ></app-text-hidden>
                                            <app-text-hidden
                                                    :form="form"
                                                    name="street_id"
                                                    id="street_id"
                                                    disabled="true"
                                            ></app-text-hidden>
                                            <app-text-hidden
                                                    :form="form"
                                                    name="number"
                                                    id="number"
                                                    disabled="true"
                                            ></app-text-hidden>
                                            <br>
                                            <app-text
                                                    display="Количество квартир"
                                                    :form="form"
                                                    name="number_of_flats"
                                            ></app-text>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h3>Информация о квартире</h3>
                                        <table class="table table-bordered" v-if="form.flats.length > 0">
                                            <tr>
                                                <td>№</td>
                                                <td>Площадь</td>
                                            </tr>

                                            <tr v-for="(i, item) in form.flats">
                                                <td>@{{ i+1 }}</td>
                                                <td><input class="form-control" v-model="item.square"/></td>
                                            </tr>
                                        </table>
                                        <div v-else class="alert alert-info">
                                            <i class="fa fa-info-circle"></i> Укажите количество квартир
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        &nbsp;
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-success btn-block"
                                    :disabled="form.busy || form.flats.length == 0"
                                    v-on:click.prevent="submit()"
                            >
                    <span v-if="form.flats.length == 0">
                        <i class="fa fa-times-circle-o"></i> Добавьте квартиры
                    </span>
                                <span v-if="form.busy">
                    <i class="fa fa-btn fa-spinner fa-spin"></i> Создание дома
                    </span>
                                <span v-if="form.flats.length != 0 && !form.busy">
                        <i class="fa fa-plus"></i> Создать дом
                    </span>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        &nbsp;
                    </div>
                </form>
            </div>

        </add-house>

    </div>

@stop
@section('after_body')
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">
        ymaps.ready(init);
        let myMap;
        function init() {
            let myPlacemark;
            let myMap = new ymaps.Map('map', {
                center: [55.753994, 37.622093],
                zoom: 12,
                controls: ['zoomControl','trafficControl']
                });
                $("#address").keyup(function() {
                ymaps.geocode($("#address").val(), {
                    results: 1
                }).then(function (res) {
                    let firstGeoObject = res.geoObjects.get(0);
                    let coords = firstGeoObject.geometry.getCoordinates();
                    App.House.form.city=firstGeoObject.getLocalities();
                    App.House.form.street_id=firstGeoObject.getThoroughfare();
                    App.House.form.number=firstGeoObject.getPremiseNumber();
                    myMap.setCenter(coords, 16);
                    // Если метка уже создана – просто передвигаем ее.
                    if (myPlacemark) {
                        myPlacemark.geometry.setCoordinates(coords);
                    }
                    // Если нет – создаем.
                    else {
                        myPlacemark = createPlacemark(coords);
                        myMap.geoObjects.add(myPlacemark);
                        // Слушаем событие окончания перетаскивания на метке.
                        myPlacemark.events.add('dragend', function () {
                            getAddress(myPlacemark.geometry.getCoordinates());
                        });
                    }
                    getAddressFromInput(coords);
                });
            });

            // Слушаем клик на карте.
            myMap.events.add('click', function (e) {
                let coords = e.get('coords');
                // Если метка уже создана – просто передвигаем ее.
                if (myPlacemark) {
                    myPlacemark.geometry.setCoordinates(coords);
                }
                // Если нет – создаем.
                else {
                    myPlacemark = createPlacemark(coords);
                    myMap.geoObjects.add(myPlacemark);
                    // Слушаем событие окончания перетаскивания на метке.
                    myPlacemark.events.add('dragend', function () {
                        getAddress(myPlacemark.geometry.getCoordinates());
                    });
                }
                getAddress(coords);
            });

            // Создание метки.
            function createPlacemark(coords) {

                return new ymaps.Placemark(coords, {
                    iconCaption: 'поиск...'
                }, {
                    preset: 'islands#violetDotIconWithCaption',
                    draggable: true
                });

            }
            function getAddress(coords) {
                myPlacemark.properties.set('iconCaption', 'поиск...');
                ymaps.geocode(coords, {}).then(function (res) {
                    let firstGeoObject = res.geoObjects.get(0);
                    App.House.form.city=firstGeoObject.getLocalities();
                    App.House.form.street_id=firstGeoObject.getThoroughfare();
                    App.House.form.number=firstGeoObject.getPremiseNumber();
                    App.House.form.address=firstGeoObject.getLocalities()+', '+firstGeoObject.getThoroughfare()+', '+firstGeoObject.getPremiseNumber();
                    myPlacemark.properties
                        .set({
                            iconCaption: firstGeoObject.properties.get('name'),
                            balloonContent: firstGeoObject.properties.get('text')
                        });

                });
            }
            function getAddressFromInput(coords) {
                myPlacemark.properties.set('iconCaption', 'поиск...');
                ymaps.geocode(coords, {}).then(function (res) {
                    let firstGeoObject = res.geoObjects.get(0);
                    App.House.form.city=firstGeoObject.getLocalities();
                    App.House.form.street_id=firstGeoObject.getThoroughfare();
                    App.House.form.number=firstGeoObject.getPremiseNumber();
                    myPlacemark.properties
                        .set({
                            iconCaption: firstGeoObject.properties.get('name'),
                            balloonContent: firstGeoObject.properties.get('text')
                        });

                });
            }
        }
    </script>
    <script type="text/javascript">

    </script>
@endsection