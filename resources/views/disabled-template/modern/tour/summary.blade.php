@include('layouts.modern.header')

<!-- DETAIL WRAPPER -->
<div class="list-wrapper bg-grey-2">
    <div class="container">
        <ul class="list-breadcrumb clearfix">
            <li><a class="color-grey link-dr-blue" href="{{ url('') }}">{{ Lang::get('core.home') }}</a> /</li>
            <li><a class="color-grey link-dr-blue" href="{{ url('package') }}">{{ Lang::get('core.packages') }}</a> /</li>
            <li>Summary</li>
        </ul>
        <div class="detail-header">
            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <div class="detail-category color-grey-3">{{ $row->total_days}} {{ Lang::get('core.days') }} - {{ $row->total_nights}} {{ Lang::get('core.nights') }}</div>
                        <h2 class="detail-title color-dark-2">{{ $row->tour_name }}</h2>
                        <table class="table table-hover ">
                        @foreach($travellers as $traveller)
                            <thead>
                                <tr>
                                    <th colspan="2"><b>{{ Lang::get('core.jemaah_info') }}</b></th>
                                </tr>
                            </thead>
                            <tr>
                                <td>name</td>
                                <td class="text-right">{{$traveller['nameandsurname']}}</td>
                            </tr>
                            <tr>
                                <td>email</td>
                                <td class="text-right">{{$traveller['email']}}</td>
                            </tr>
                            <tr>
                                <td>phone</td>
                                <td class="text-right">{{$traveller['phone']}}</td>
                            </tr>
                            <tr>
                                <td>dateofbirth</td>
                                <td class="text-right">{{$traveller['dateofbirth']}}</td>
                            </tr>
                            <tr>
                                <td>passportno</td>
                                <td class="text-right">{{$traveller['passportno']}}</td>
                            </tr>
                            <tr>
                                <td>passportissue</td>
                                <td class="text-right">{{$traveller['passportissue']}}</td>
                            </tr>

                        @endforeach
                        </table>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="right-sidebar">
                        <div class="detail-block bg-dr-blue">
                            <h4 class="color-white" style="display:none">Booking Summary</h4>
                            <div class="details-desc">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th><p class="color-grey-9">{{ Lang::get('core.category') }}</p></th><th><p><span class="color-white"><a href="?cat={{$row->tourcategoriesID}}">:  {{ SiteHelpers::formatLookUp($row->tourcategoriesID,'tourcategoriesID','1:def_tour_categories:tourcategoriesID:tourcategoryname') }}</a></span></p></th>
                                    </tr>
                                    <tr>
                                        <th><p class="color-grey-9">{{ Lang::get('core.sector')}}</p></th><th><p><span class="color-white">:  {{ $row->sector }}</span></p></th>
                                    </tr>
                                    <tr>
                                        <th><p class="color-grey-9">{{ Lang::get('core.flight')}}</p></th><th><p><span class="color-white">:  {{ $row->flight }}</span></p></th>
                                    </tr>
                                    <tr>
                                        <th><p class="color-grey-9">{{ Lang::get('core.transit')}}</p></th><th><p><span class="color-white">:  {{ $row->transit }}</span></p></th>
                                    </tr>
                                    <tr>
                                        <th><p class="color-grey-9">{{ Lang::get('core.baggage_limit')}}</p></th><th><p><span class="color-white">:  {{ $row->baggage_limit }}</span></p></th>
                                    </tr>
                                    <tr>
                                        <th><p class="color-grey-9">{{ Lang::get('core.departs')}}</p></th><th><p><span class="color-white">:  {{ $date->start }} - {{ $date->end }}</span></p></th>
                                    </tr>

                                    </tbody>
                                </table>
                                @if($date->cost_quad > 0)
                                    <p class="color-grey-9">{{ Lang::get('core.quadroom') }}<span class="color-white">:  {{CURRENCY_SYMBOLS}} {{$date->cost_quad}} {{ Lang::get('core.perperson') }}</span></p>
                                @endif
                                @if($date->cost_triple> 0)
                                    <p class="color-grey-9">{{ Lang::get('core.tripleroom') }}: <span class="color-white">{{CURRENCY_SYMBOLS}} {{$date->cost_triple}} {{ Lang::get('core.perperson') }}</span></p>
                                @endif
                                @if($date->cost_double > 0)
                                    <p class="color-grey-9">{{ Lang::get('core.doubleroom') }}: <span class="color-white">{{CURRENCY_SYMBOLS}} {{$date->cost_double}} {{ Lang::get('core.perperson') }}</span></p>
                                @endif
                                @if($date->cost_single > 0)
                                    <p class="color-grey-9">{{ Lang::get('core.singleroom') }}: <span class="color-white">{{CURRENCY_SYMBOLS}} {{$date->cost_single}} {{ Lang::get('core.perperson') }}</span></p>
                                @endif

                                <p class="color-grey-9">{{ Lang::get('core.deposit') }}: <span class="color-white">{{CURRENCY_SYMBOLS}} {{$date->cost_deposit}} {{ Lang::get('core.perperson') }}</span></p>
                                <p class="color-grey-9">{{ Lang::get('core.total_travellers') }}: <span class="color-white"> {{$total_travellers}} {{ Lang::get('core.person') }}</span></p>
                                <p class="color-grey-9">{{ Lang::get('core.totaldeposit') }}: <span class="color-white">{{CURRENCY_SYMBOLS}} {{$total_deposit}}</span></p>

                            </div>
                            <form action="/bookpackage/deposit" method="post">
                                {{csrf_field()}}
                                <input type="hidden" name="bookingID" value="{{$bookid}}">
                                <input type="hidden" name="total_deposit" value="{{$total_deposit}}">
                                <div class="details-btn">
                                    <button type="submit" class="c-button b-40 bg-white hv-transparent"><span>{{ Lang::get('core.pay_deposit') }}</span></button>
                                </div>
                            </form>

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
            </div>
        </div>
    </div>
</div>


@include('layouts.modern.footer')
