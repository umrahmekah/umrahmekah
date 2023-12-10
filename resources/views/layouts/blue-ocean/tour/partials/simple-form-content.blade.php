<style>
    input[type="date"]:before {
        content: attr(placeholder);
    }
    
    input[type="date"]:focus:before, 
    input[type="date"]:valid:before, 
    input[type="date"]:hover:before {
        content: "";
    }

    .hidden {
        display: none !important;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <form role="form" id="booking_form" action="" method="post">

                <div class="form container mb-5">
                    <div class="row layout__main">
                        <div class="col mt-3">

                            <div class="tab-content" id="booking-tab-contents">
                                <div class="tab-pane fade show active" id="jemaah-detail" role="tabpanel" aria-labelledby="jemaah-detail-tab">
                                    @include('layouts.blue-ocean.tour.partials.simple-form-jemaah')
                                </div>
                                <div class="tab-pane fade" id="booking-detail" role="tabpanel" aria-labelledby="booking-detail-tab">
                                    @include('layouts.blue-ocean.tour.partials.simple-form-booking')
                                </div>
                                <div class="tab-pane fade" id="booking-summary" role="tabpanel" aria-labelledby="booking-summary-tab">
                                    @include('layouts.blue-ocean.tour.partials.simple-form-summary')
                                </div>
                            </div>

                        </div>
                        <div class="layout__float col-sm-5" style="top: -190px;">
                            @include('layouts.blue-ocean.tour.partials.simple-form-pay-summary')
                            @include('layouts.blue-ocean.tour.partials.extra-info')
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


@include('layouts.blue-ocean.tour.partials.simple-form-scripts')