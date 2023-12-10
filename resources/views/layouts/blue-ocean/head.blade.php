<head>
    @include('layouts.blue-ocean.components.meta')
    @include('layouts.blue-ocean.components.head-icon')
    <title>{{ CNF_COMNAME }} | {{ $pageTitle }}</title>
    @include('layouts.blue-ocean.components.ga')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/theme/blue-ocean/css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/theme/blue-ocean/css/custom.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
    <style type="text/css">
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }
    </style>
    @stack('styles')
</head>