<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Авторизация в Ананас.ТСЖ</title>

    <link href="{!! elixir('css/style.css') !!}" rel="stylesheet">

    <script>
        window.App = {
            forms: {},
        }

        window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>

<body class="gray-bg">

@yield('content')

@yield('after_body')

<!-- Mainly scripts -->
<script src="{!! elixir('js/app.js') !!}"></script>

<!-- Custom and plugin javascript -->
<script src="/js/inspinia.js"></script>
<script src="/js/pace.min.js"></script>


</body>

</html>
