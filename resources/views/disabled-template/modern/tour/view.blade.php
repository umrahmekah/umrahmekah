@include('layouts.modern.header')

<!-- DETAIL WRAPPER -->
<div class="list-wrapper bg-grey-2">
    <div class="container">
        <ul class="list-breadcrumb clearfix">
            <li><a class="color-grey link-dr-blue" href="{{ url('') }}">{{ Lang::get('core.home') }}</a> /</li>
            <li><a class="color-grey link-dr-blue" href="{{ url('package') }}">{{ Lang::get('core.packages') }}</a> /</li>
            <li>{{ $row->tour_name }}</li>
        </ul>
        <div class="detail-header">
            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <div class="detail-category color-grey-3">{{ $row->total_days}} {{ Lang::get('core.days') }} - {{ $row->total_nights}} {{ Lang::get('core.nights') }}</div>
                    <h2 class="detail-title color-dark-2">{{ $row->tour_name }}</h2>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="detail-price color-dark-2" id="perperson_tag">{{ Lang::get('core.from') }}  <span class="color-dr-blue" id="min_price_tag"> </span> {{ Lang::get('core.perperson') }}</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-9">
                <div class="detail-content color-1">
                    <div class="detail-top slider-wth-thumbs style-2">
                        <div class="swiper-container thumbnails-preview" data-autoplay="0" data-loop="1" data-speed="500" data-center="0" data-slides-per-view="1">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide active" data-val="0">
                                    <img class="img-responsive img-full" src="@if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.$row->tourimage) && $row->tourimage !='')
                                    {{ asset('uploads/images/'.CNF_OWNER.'/'.$row->tourimage)}}
                                    @else
                                    {{ asset('mmb/images/tour-noimage.jpg')}}
                                    @endif " alt="">
                                </div>
                                @if($row->gallery!='')
                                    <?php $img_counter = 1; ?>
                                    @foreach(explode(',', $row->gallery) as $image)
                                        <div class="swiper-slide" data-val="{{ $img_counter++ }}">
                                            <img class="img-responsive img-full" src="{{ asset('uploads/images/'.CNF_OWNER.'/'.$image)}}" alt="">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="pagination pagination-hidden"></div>
                        </div>
                        <div class="swiper-container thumbnails swiper-swiper-unique-id-1 initialized pagination-hidden" data-autoplay="0" data-loop="0" data-speed="500" data-center="0" data-slides-per-view="responsive" data-xs-slides="3" data-sm-slides="5" data-md-slides="5" data-lg-slides="5" data-add-slides="5" id="swiper-unique-id-1">
                            <div class="swiper-wrapper" style="width: 750px; height: 102px;">
                                @if($row->gallery!='')
                                    <div class="swiper-slide current active swiper-slide-visible swiper-slide-active" data-val="0">
                                        <img class="img-responsive img-full" src="@if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.$row->tourimage) && $row->tourimage !='')
                                        {{ asset('uploads/images/'.CNF_OWNER.'/'.$row->tourimage)}}
                                        @else
                                        {{ asset('mmb/images/tour-noimage.jpg')}}
                                        @endif " alt="" style="height: 150px !important;">
                                    </div>
                                    <?php $img_counter = 1; ?>
                                    @foreach(explode(',', $row->gallery) as $image)
                                        <div class="swiper-slide swiper-slide-visible" data-val="{{ $img_counter++ }}">
                                            <img class="img-responsive img-full" src="{{ asset('uploads/images/'.CNF_OWNER.'/'.$image)}}" style="height: 150px !important;" alt="" >
                                        </div>
                                    @endforeach
                                @endif
                                {{--<div class="swiper-slide current active swiper-slide-visible swiper-slide-active" data-val="0" style="width: 150px; height: 102px;">--}}
                                    {{--<img class="img-responsive img-full" src="img/detail/m_slide_1s.jpg" alt="">--}}
                                {{--</div>--}}
                                {{--<div class="swiper-slide swiper-slide-visible" data-val="1" style="width: 150px; height: 102px;">--}}
                                    {{--<img class="img-responsive img-full" src="img/detail/m_slide_2s.jpg" alt="">--}}
                                {{--</div>--}}
                                {{--<div class="swiper-slide swiper-slide-visible" data-val="2" style="width: 150px; height: 102px;">--}}
                                    {{--<img class="img-responsive img-full" src="img/detail/m_slide_3s.jpg" alt="">--}}
                                {{--</div>--}}
                                {{--<div class="swiper-slide swiper-slide-visible" data-val="3" style="width: 150px; height: 102px;">--}}
                                    {{--<img class="img-responsive img-full" src="img/detail/m_slide_4s.jpg" alt="">--}}
                                {{--</div>--}}
                                {{--<div class="swiper-slide swiper-slide-visible" data-val="4" style="width: 150px; height: 102px;">--}}
                                    {{--<img class="img-responsive img-full" src="img/detail/m_slide_5s.jpg" alt="">--}}
                                {{--</div>--}}
                            </div>
                            <div class="pagination hidden pagination-swiper-unique-id-1"><span class="swiper-pagination-switch swiper-visible-switch swiper-active-switch" style="display: inline;"></span><span class="swiper-pagination-switch swiper-visible-switch" style="display: none;"></span><span class="swiper-pagination-switch swiper-visible-switch" style="display: none;"></span><span class="swiper-pagination-switch swiper-visible-switch" style="display: none;"></span><span class="swiper-pagination-switch swiper-visible-switch" style="display: none;"></span></div>
                        </div>

                    </div>

                    <div class="detail-content-block">
                        <h3>{{ Lang::get('core.tourdescription') }}</h3>
                        <p>{!! $row->tour_description !!}</p>
                    </div>

                    @if($dayTree)
                        <div class="detail-content-block">
                            <h3>{{ Lang::get('core.itinerary') }}</h3>
                            <div id="content">
                                <div class="container" id="about">
                                    <div class="timeline timeline-left">
                                        @foreach($dayTree as $dt)

                                            <div class="timeline-breaker">{{ Lang::get('core.day') }} {{ $dt['day'] }}</div>
                                            <!--Timeline item 1-->


                                            <div class="timeline-item animated fadeIn de-02">
                                                <div class="timeline-item-date">{{ $dt['title'] }}</div>
                                                <p class="timeline-item-description">{!! $dt['description'] !!}</p>
                                                @if($dt['siteID']!=NULL)<p class="timeline-item-description"><i class="fa fa-globe fa-lg fa-fw" data-toggle="tooltip" title="Places to visit" aria-hidden="true"></i>  {!! ToursController::placesToVisit($dt['siteID']) !!}
                                                </p>@endif
                                                @if($dt['hotelID']!=NULL)
                                                    <p class="timeline-item-description"><i class="fa fa-bed fa-lg fa-fwtips" data-toggle="tooltip" title="Accomodation" aria-hidden="true"></i>
                                                        {{ SiteHelpers::formatLookUp($dt['hotelID'],'hotelID','1:hotels:hotelID:hotel_name') }} {{ SiteHelpers::formatLookUp($dt['cityID'],'cityID','1:def_city:cityID:city_name') }}</p>
                                                @endif
                                                @if($dt['meal']!=NULL)
                                                    <p class="timeline-item-description "><i class="fa fa-cutlery fa-lgfa-fw" data-toggle="tooltip" title="Meals"aria-hidden="true"></i>  {{ $dt['meal']}} </p>
                                                @endif
                                                @if($dt['optionaltourID']!=NULL)<p class="timeline-item-description "><i class="fa fa-institution fa-lg fa-fw" aria-hidden="true" data-toggle="tooltip" title="Optional Tours"></i> {!! ToursController::optionalTours($dt['optionaltourID']) !!} </p>
                                                @endif
                                            </div>
                                            <br>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>

                    @endif
                    <?php $seat_available_total = 0; ?>
                    <div class="detail-content-block detail-inclusion">

                        <h3 id="anchor">{{ Lang::get('core.included') }}</h3>
                        <ul class="list-group list-group-striped">{!! SiteHelpers::showInclusions($row->inclusions) !!}</ul>
                    </div>

                    <div class="detail-content-block detail-inclusion">

                        <h3 id="anchor">{{ Lang::get('core.tandc') }}</h3>
                        <a href="/tnc?id={{$row->policyandterms}}&package={{$row->tour_name}}">Terms And Condition</a>
                    </div>



                </div>
            </div>
            <div class="col-xs-12 col-md-3">
                <div class="right-sidebar">
                    <div class="detail-block bg-dr-blue">
                        <h4 class="color-white" style="display:none">details</h4>
                        <div class="details-desc">
                            <p class="color-grey-9">{{ Lang::get('core.category') }}<span class="color-white">:  {{ SiteHelpers::formatLookUp($row->tourcategoriesID,'tourcategoriesID','1:def_tour_categories:tourcategoriesID:tourcategoryname') }}</span></p>
                            <p class="color-grey-9">{{ Lang::get('core.sector')}}<span class="color-white">:  {{ $row->sector }}</span></p></th>
                            <?php $flight = DB::table('flights')->where('iata',$row->flight)->get()[0] ?>
                            <p class="color-grey-9">{{ Lang::get('core.flight')}}<span class="color-white">:  @if($flight != null){{ $flight->name }}@endif</span></p></th>
                            <p class="color-grey-9">{{ Lang::get('core.transit')}}<span class="color-white">:  {{ $row->transit }}</span></p>
                            <p class="color-grey-9">{{ Lang::get('core.baggage_limit')}}<span class="color-white">:  {{ $row->baggage_limit }} kg</span></p>
                            <p class="color-grey-9">{{ Lang::get('core.capacity')}}<span class="color-white">: {{$seat_available_total}} {{ Lang::get('core.spot_left')}}</span></p>

                            {{--@if($row->cost_quad > 0 || $row->cost_triple> 0 || $row->cost_double > 0 || $row->cost_single > 0)--}}
                                {{--<p class="color-grey-9" style="margin-bottom:2px">{{ Lang::get('core.roomprice') }}</p>--}}
                            {{--@endif--}}
                            {{--@if($row->cost_quad > 0)--}}
                                {{--<p class="color-grey-9" style="margin-bottom:2px"> <i class="fa fa-user"></i> {{ Lang::get('core.quad') }}<span class="color-white">:  {{CURRENCY_SYMBOLS}} {{number_format($row->cost_quad)}} {{ Lang::get('core.perperson') }}</span></p>--}}
                            {{--@endif--}}
                            {{--@if($row->cost_triple> 0)--}}
                                {{--<p class="color-grey-9" style="margin-bottom:2px"> <i class="fa fa-user"></i> {{ Lang::get('core.triple') }}: <span class="color-white">{{CURRENCY_SYMBOLS}} {{number_format($row->cost_triple)}} {{ Lang::get('core.perperson') }}</span></p>--}}
                            {{--@endif--}}
                            {{--@if($row->cost_double > 0)--}}
                                {{--<p class="color-grey-9" style="margin-bottom:2px"> <i class="fa fa-user"></i> {{ Lang::get('core.double') }}: <span class="color-white">{{CURRENCY_SYMBOLS}} {{number_format($row->cost_double)}} {{ Lang::get('core.perperson') }}</span></p>--}}
                            {{--@endif--}}
                            {{--@if($row->cost_single > 0)--}}
                                {{--<p class="color-grey-9"  style="margin-bottom:2px"> <i class="fa fa-user"></i> {{ Lang::get('core.single') }}: <span class="color-white">{{CURRENCY_SYMBOLS}} {{number_format($row->cost_single)}} {{ Lang::get('core.perperson') }}</span></p>--}}
                            {{--@endif--}}

                        </div>
                        <div class="details-btn">
                            <a href="#booknow" class="c-button b-40 bg-white hv-transparent"><span>book now</span></a>
                        </div>
                    </div>



                    @if($row->similartours!=NULL)
                        <?php $similartours = explode(',',$row->similartours); ?>
                        <div class="popular-tours bg-grey-2" >
                            <h4 class="color-dark-2">{{ Lang::get('core.similartours') }}</h4>
                            @foreach($similartours as $tour)
                                <div class="hotel-small style-2 clearfix">
                                    <a class="hotel-img black-hover" href="{{ url('package?view='.$tour) }}">
                                        <?php
                                        $tour_img = \SiteHelpers::formatLookUp($tour,'tourID','1:tours:tourID:tourimage');
                                        if(empty(trim($tour_img)))
                                            $tour_img = asset('mmb/images/tour-noimage.jpg');
                                        else
                                            $tour_img = "uploads/images/" . CNF_OWNER . "/" . $tour_img;
                                        ?>
                                        <img class="img-responsive radius-3" src="{{ $tour_img }}" alt="">
                                        <div class="tour-layer delay-1"></div>
                                    </a>
                                    <div class="hotel-desc">
                                        <h4><a href={{ url('package?view='.$tour) }}>{{ SiteHelpers::formatLookUp($tour,'tourID','1:tours:tourID:tour_name')}}</a></h4>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{--booking pakej card view--}}
        <h3 class="color-dark-2">{{ Lang::get('core.listpackage') }}</h3>
        <div class="row padd-90" id="booknow">
            <?php $min_price = null; ?>
            @foreach($tdate as $td)
                <div class="col-xs-12 col-sm-4" >

                    <?php
                    if($td['cost_single'] > 0)
                        if(is_null($min_price))
                            $min_price = $td['cost_single'];
                        elseif ($td['cost_single'] < $min_price) {
                            $min_price = $td['cost_single'];
                        }
                    if($td['cost_double'] > 0 && $td['cost_double'] < $min_price)
                        if(is_null($min_price))
                            $min_price = $td['cost_double'];
                        elseif ($td['cost_double'] < $min_price) {
                            $min_price = $td['cost_double'];
                        }
                    if($td['cost_triple'] > 0 && $td['cost_triple'] < $min_price)
                        if(is_null($min_price))
                            $min_price = $td['cost_triple'];
                        elseif ($td['cost_triple'] < $min_price) {
                            $min_price = $td['cost_triple'];
                        }
                    if($td['cost_quad'] > 0 && $td['cost_quad'] < $min_price)
                        if(is_null($min_price))
                            $min_price = $td['cost_quad'];
                        elseif ($td['cost_quad'] < $min_price) {
                            $min_price = $td['cost_quad'];
                        }
                    ?>


                        <?php
                        $seat_booked = \GeneralStatus::tourCapacity($td['tourdateID'] , $td['total_capacity'] );
                        $seat_available = intval($td['total_capacity']) - $seat_booked;
                        $seat_percentage = intval(100*$seat_booked/intval($td['total_capacity']));
                        $seat_available_total += $seat_available;
                        ?>

                        <div class="tariff">
                            <div class="tariff-header bg-dr-blue-2">
                                <div class="tariff-title color-white">{{ $td['tourname'] }}</div>
                                <div class="tariff-trial color-white-light">{{ SiteHelpers::TarihFormat($td['start']) }} - {{ SiteHelpers::TarihFormat($td['end']) }}</div>
                            </div>
                            {{--<img class="tafiff-img img-responsive img-full" src="img/inner/tariff_1.jpg" alt="">--}}
                            <div class="tariff-content bg-white">
                                <div class="tariff-price color-dark-2">Deposit : {{CURRENCY_SYMBOLS}}{{ $td['cost_depo'] }}/pax</div>
                                <div class="tariff-line color-dark-2">{{ Lang::get('core.capacity') }} : {{ $seat_available.'/'.$td['total_capacity']}}</div>
                                <div class="tariff-line color-dark-2">{{ Lang::get('core.transit') }} : {{ $td['transit'] }}</div>
                                <div class="tariff-line color-dark-2">
                                    <p>{{ Lang::get('core.singleroom') }} :
                                    @if($td['cost_single']== 0)
                                        {{ Lang::get('core.notavailable') }}
                                    @else
                                        {{CURRENCY_SYMBOLS}}{{ $td['cost_single'] }}
                                    @endif
                                    </p>
                                    <p>{{ Lang::get('core.doubleroom') }} :
                                    @if($td['cost_double']== 0)
                                        {{ Lang::get('core.notavailable') }}
                                    @else
                                        {{CURRENCY_SYMBOLS}}{{ $td['cost_double'] }}
                                    @endif
                                    </p>
                                    <p>{{ Lang::get('core.tripleroom') }} :
                                    @if($td['cost_triple']== 0)
                                        {{ Lang::get('core.notavailable') }}
                                    @else
                                        {{CURRENCY_SYMBOLS}}{{ $td['cost_triple'] }}
                                    @endif
                                        </p>
                                    <p>{{ Lang::get('core.quadroom') }} :
                                    @if($td['cost_quad']== 0)
                                        {{ Lang::get('core.notavailable') }}
                                    @else
                                        {{CURRENCY_SYMBOLS}}{{ $td['cost_quad'] }}
                                    @endif
                                    </p>

                                </div>
                                <a href="booknow?tourID={{ $td['tourID'] }}&tourdateID={{ $td['tourdateID'] }}" class="c-button b-50 bg-dr-blue-2 hv-dr-blue-2-o"><span>{{ Lang::get('core.book') }}</span></a>
                            </div>
                        </div>

                </div>
            @endforeach
        </div>

        <div class="may-interested padd-90" style="display: none;">

            <div class="row">

                <div class="col-mob-12 col-xs-6 col-sm-6 col-md-3">
                    <div class="hotel-item">
                        <div class="radius-top">
                            <img srcx="SIMILARIMG" alt="">
                            <div class="price price-s-1">{{CURRENCY_SYMBOLS}}00</div>
                        </div>
                        <div class="title clearfix">
                            <h4><b>SIMILARTITLE</b></h4>

                            <span class="f-14 color-dark-2">X DAY X NIGHT</span>
                            <p class="f-14">SIMILARDESC.</p>
                            <a href="#" class="c-button bg-dr-blue hv-dr-blue-o b-50 fl">{{ Lang::get('core.view_package') }}</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    @if ($min_price)
        document.getElementById('min_price_tag').innerHTML = '{{CURRENCY_SYMBOLS}} {{number_format($min_price)}}';
    @else
        document.getElementById('perperson_tag').innerHTML = '';
    @endif
</script>

@include('layouts.modern.footer')  