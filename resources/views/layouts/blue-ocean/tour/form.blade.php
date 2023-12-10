<?php
    $min_price = min_price($detail);
    $package = $detail;

    if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.$package->tourimage) && $package->tourimage !='') {
        $package_image = asset('uploads/images/'.CNF_OWNER.'/'.$package->tourimage);
    } else {
        $package_image = asset('mmb/images/tour-noimage.jpg');
    }

    $is_bound = false;
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

<div id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true"
    data-backdrop="false" class="modal fade">
    <div role="document" class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="termsModalLabel" class="modal-title text-center text">ERROR</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container py-4">
                    <h3 style="color: red;">
                        Sorry, there's not enough capacity for the tour.
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection  
