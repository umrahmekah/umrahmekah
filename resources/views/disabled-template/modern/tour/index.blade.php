@include('layouts.modern.header')


<div class="list-wrapper bg-grey-2">
    <div class="container">
        <ul class="list-breadcrumb clearfix">
            <li><a class="color-grey link-dr-blue" href="{{ url('') }}">{{ Lang::get('core.home') }}</a> /</li>
            <li><a class="color-grey link-dr-blue" href="{{ url('package') }}">{{ Lang::get('core.packages') }}</a></li>
        </ul>
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="sidebar style-2 clearfix">
                    <div class="sidebar-block">
                        <h4 class="sidebar-title color-dark-2">{{ Lang::get('core.tourcategory') }}</h4>
                        <ul class="sidebar-category color-2">
                            <li class=" @if(!isset($_GET['cat'])) active  @endif ">
                                <a class="cat-drop nav-link text-uppercase text-slab" onclick="window.location.href ='{{ url('/package')}}'">{{ Lang::get('core.alltourcategory') }}<span class="fr">({{ count($category) }})</span></a>
                            </li>

                            @foreach ($category as $cat)
                                <li class=" @if(isset($_GET['cat'])) @if( $_GET['cat'] == $cat->tourcategoriesID ) active @endif  @endif ">
                                    <a class="cat-drop nav-link text-uppercase text-slab" onclick="window.location.href ='{{ url('/package?cat=')}}{{$cat->tourcategoriesID}}'">{{$cat->tourcategoryname}} <span class="fr">({{ $cat->category_count }})</span></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-9">
                <div class="list-header clearfix">
                    <form action="{{ url('/package?')}}" method="GET" class="">
                        <div class="drop-wrap drop-wrap-s-4 color-4 list-sort">
                            <div class="drop">
                                <b>{{ Lang::get('core.sort_by') }}</b>
                                <a href="#" class="drop-list"><i class="fa fa-angle-down"></i></a>
                                <span>
                    <a onclick="window.location.href ='{{ url('/package?sort=')}}tour_name&order={{ $order }}'">{{ Lang::get('core.tourname') }}</a>
                    <a onclick="window.location.href ='{{ url('/package?sort=')}}tourID&order={{ $order }}'">{{ Lang::get('core.tourcode') }}</a>
                    <a onclick="window.location.href ='{{ url('/package?sort=')}}tourcategoriesID&order={{ $order }}&order={{ $order }}'">{{ Lang::get('core.tourcategory') }}</a>
                    <a onclick="window.location.href ='{{ url('/package?sort=')}}total_days&order={{ $order }}'">{{ Lang::get('core.totaldays') }}</a>
                    <a onclick="window.location.href ='{{ url('/package?sort=')}}views&order={{ $order }}'">{{ Lang::get('core.popularity') }}</a>
                    <a onclick="window.location.href ='{{ url('/package?sort=')}}departs&order={{ $order }}'">{{ Lang::get('core.departtype') }}</a>
                  </span>
                            </div>
                        </div>
                        <div class="drop-wrap drop-wrap-s-4 color-4 list-sort">
                            <div class="drop">
                                <b>{{ Lang::get('core.grid_order') }}</b>
                                <a href="#" class="drop-list"><i class="fa fa-angle-down"></i></a>
                                <span>
                    <a onclick="window.location.href ='{{ url('/package?sort=')}}{{ $sort }}&order=asc'">{{ Lang::get('core.grid_asc') }}</a>
                    <a onclick="window.location.href ='{{ url('/package?sort=')}}{{ $sort }}&order=desc'">{{ Lang::get('core.grid_desc') }}</a>
                  </span>
                            </div>
                        </div>
                    </form>
                    <div class="list-view-change">
                        <div class="change-grid color-2 fr active"><i class="fa fa-th"></i></div>
                        <div class="change-list color-2 fr"><i class="fa fa-bars"></i></div>
                        <div class="change-to-label fr color-grey-8">{{ Lang::get('core.view') }}:</div>
                    </div>
                </div>
                <input type="hidden" value="{{ app('request')->input('aid') }}" name="affiliate">
                <div class="grid-content clearfix">
                    @foreach ($rowData as $row)
                        @if($row->status ==1)
                            <?php
                            $min_price = 0;
                            if($row->cost_single > 0 )
                                $min_price = $row->cost_single;
                            if($row->cost_double > 0 && $row->cost_double < $min_price)
                                $min_price = $row->cost_double ;
                            if($row->cost_triple > 0 && $row->cost_triple < $min_price)
                                $min_price = $row->cost_triple ;
                            if($row->cost_quad > 0 && $row->cost_quad < $min_price)
                                $min_price = $row->cost_quad ;
                            ?>

                            <div class="list-item-entry">
                                <div class="hotel-item style-8 bg-white">
                                    <div class="table-view">
                                        <div class="radius-top cell-view">
                                            <img src="@if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.$row->tourimage) && $row->tourimage !='') {{ asset('uploads/images/'.CNF_OWNER.'/'.$row->tourimage)}} @else {{ asset('mmb/images/tour-noimage.jpg')}} @endif" alt="{{$row->tour_name}}" style="height: 150px; object-fit: cover;">
                                            @if($row->departs==2)
                                                <div class="price price-s-3 red tt">{{ Lang::get('core.onrequest') }}</div>
                                            @elseif($row->departs==1)
                                                <div class="price price-s-3 red tt">{{ Lang::get('core.daily') }}</div>
                                            @endif
                                            @if($row->featured==1)
                                                <div class="price price-s-3 red tt">{{ Lang::get('core.featured') }}</div>
                                            @endif
                                        </div>
                                        <div class="title hotel-middle clearfix cell-view" style="height: 280px">
                                            <div class="hotel-person color-dark-2 list-hidden">{{ Lang::get('core.from') }} <span>{{CURRENCY_SYMBOLS}} {{ number_format($min_price) }}</span></div>
                                            <div class="rate-wrap">
                                                {{$row->total_days}} {{ Lang::get('core.days') }} {{$row->total_nights}} {{ Lang::get('core.nights') }}
                                            </div>
                                            <h4 style="margin-bottom: 15px"><b>{{$row->tour_name}}</b></h4>
                                        
                                        
                                        
                                        <div class="custom">
                                        <p class="departure"><b>Departure Dates</b></p> 
                                            @if(isset($row->start))
                                            @foreach($row->start as $start)
                                            <div>
                                                <li>{{ date('d-m-Y', strtotime($start)) }}</li>
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                            

                                            <div class="hotel-icons-block grid-hidden">
                                            </div>
                                            @if(app('request')->input('affiliate')!= null)
                                                <a href="?affiliate={{app('request')->input('affiliate')}}&view={{$row->tourID}}" class="c-button bg-dr-blue hv-dr-blue-o b-40 fl list-hidden" style="position: absolute; bottom: 15px;">{{ Lang::get('core.view_package') }}</a>
                                            @else
                                                <a href="?view={{$row->tourID}}" class="c-button bg-dr-blue hv-dr-blue-o b-40 fl list-hidden" style="position: absolute; bottom: 15px;">{{ Lang::get('core.view_package') }}</a>
                                            @endif
                                        </div>
                                        <div class="title hotel-right bg-dr-blue clearfix cell-view">
                                            <div class="hotel-person color-white">{{ Lang::get('core.from') }} <span>{{CURRENCY_SYMBOLS}}{{ $min_price }}</div>
                                            <a class="c-button b-40 bg-white color-dark-2 hv-dark-2-o grid-hidden" href="?view={{$row->tourID}}">{{ Lang::get('core.view_package') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>

                <div class="c_pagination clearfix padd-120">
                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        {!! $pagination->render() !!}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.modern.footer')