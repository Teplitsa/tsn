<!DOCTYPE html>
<html>

<head>
<style>
    .voting div {
        cursor: pointer;
    }

    .voting div.active {
        color: #0f8f31;
    }
</style>
    <link rel="stylesheet" href="/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/plugins/select2/select2.min.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ананас.ТСН</title>

    <link href="{!! elixir('css/style.css') !!}" rel="stylesheet">


    <script>
        window.App = {
            userId: {{ auth()->id() }},
            apiToken: '{{ auth()->user()->api_token }}',
            forms: {},
            toastrs: {!! $toastrs !!}
        }

        window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
        ]) !!};
    </script>


</head>

<body class="">

<div id="wrapper">

    @include('layouts.partials.sidebar')

    <div id="page-wrapper" class="gray-bg">
        @include('layouts.partials.header')
        <layout-headerline
                page-title="{{ $pageTitle or '' }}"
                :actions="{{ $actionButtons }}"
                :breadcrumbs="{{ $breadcrumbs }}"
        >

        </layout-headerline>


        <div class="wrapper wrapper-content">
            @include('flash::message')

            @if(isset($component))
                <component is="{{ $component }}" inline-template>
                    @yield('content')
                </component>
            @else
                @yield('content')
            @endif
        </div>
        <div class="footer">
            <div class="pull-right">

            </div>
            <div>
                <div class="row">
                    <div class="col-md-6">
                        Разработка: Компания Ананас.
                    </div>
                    <div class="col-md-6 text-right">
                        <small>© {!! issued_dates() !!}</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@yield('after_body')

<!-- Mainly scripts -->
<script src="{!! elixir('js/app.js') !!}"></script>

<!-- Custom and plugin javascript -->
<script src="/js/inspinia.js"></script>
<script src="/js/pace.min.js"></script>
<script src="/js/bootstrap-datepicker.js"></script>
<script src="/js/jquery.maskedinput.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="/plugins/select2/select2.min.js" type="text/javascript"></script>


@yield('after_js')

<script>
    $(function(){
        $('.select2-my').select2();
    })
    $("form:not(.dont-disable)").submit(function(){
        $(this).find('[type="submit"]').prop('disabled', true);
    });
</script>

</body>

</html>
