<?php
    $min_price = min_price($detail);
    $package = $detail;

    if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.$package->tourimage) && $package->tourimage !='') {
        $package_image = asset('uploads/images/'.CNF_OWNER.'/'.$package->tourimage);
    } else {
        $package_image = asset('mmb/images/tour-noimage.jpg');
    }

    $is_bound = true;
?>

@extends('layouts.blue-ocean.default') 

@push('styles')
    <style type="text/css">
        .panel, .panel-primary {
            box-shadow: rgba(0, 0, 0, 0) 0px 1px 2px !important;
        }
        .package__cover {
            position: relative;
        }
        .panel-heading {
            background-color: white !important;
            padding: 0.5rem;
        }
        .cover {
            background-position: center !important;
            background-size: cover !important;
        }
        .cover:after {
            content: "";
            position: absolute;
            height: 50vh;
            width: 100%;
            background: rgba(0,0,0,.5);
            z-index: 1;
            background-size: cover;
            background-position: 50%;
            background-repeat: no-repeat !important;
            overflow: hidden;
        }
        .layout__float {
            top: -225px;
        }
    </style>
@endpush

@section('content')
<main>
    <div class="package payment">
        @include('layouts.blue-ocean.tour.partials.form-header')
        @include('layouts.blue-ocean.tour.partials.form-content')
    </div>
</main>
@endsection  
