@include('layouts.leo.header')    
<nav class="breadcrumb"> <a class="breadcrumb-item" href="{{ url('') }}">{{ Lang::get('core.home') }}</a> <a class="breadcrumb-item" href="{{ url('package') }}">{{ Lang::get('core.packages') }}</a> <span class="breadcrumb-item active"> {{ isset($_GET['cat']) ? SiteHelpers::formatLookUp($_GET['cat'],'tourcategoriesID','1:def_tour_categories:tourcategoriesID:tourcategoryname') : '' }}
</span> </nav>
<div id="content" class="py-3 py-lg-3">
      <div class="container">
        <div class="row">
          <div class="col-lg-9 order-lg-2">
            <div class="row">
<!--
              <div class="col-lg-6 mb-3 mb-lg-0">
                <form class="form-inline justify-content-lg-start text-sm">
                  <label class="control-label mr-2">Keyword:</label>
                  <input type="text" class="form-control form-control-sm mr-lg-2" placeholder="ie. Turkey Tour">
                  <a href="#adv-search" data-toggle="collapse" class="text-sm">advanced search</a> 
                </form>
              </div>
-->
                
              <div class="col-lg-12">
                <form action="{{ url('/package?')}}" method="GET" class="form-inline justify-content-lg-end text-sm">
                  <label class="control-label mr-2">{{ Lang::get('core.sort_by') }}:</label>
                  <select name="sort" class="form-control form-control-sm" id="sort">
                    <option value="tour_name">{{ Lang::get('core.tourname') }}</option>
                    <option value="tourID">{{ Lang::get('core.tourcode') }}</option>
                    <option value="tourcategoriesID">{{ Lang::get('core.tourcategory') }}</option>
                    <option value="total_days">{{ Lang::get('core.totaldays') }}</option>
                    <option value="views">{{ Lang::get('core.popularity') }}</option>
                    <option value="departs">{{ Lang::get('core.departtype') }}</option>
                  </select>
                  <!--<label class="control-label mr-2 ml-lg-3">Show:</label>
                  <select class="form-control form-control-sm">
                    <option value="#">15</option>
                    <option value="#">20</option>
                    <option value="#">25</option>
                    <option value="#">30</option>
                    <option value="#">35</option>
                    <option value="#">40</option>
                    <option value="#">45</option>
                    <option value="#">50</option>
                  </select>-->
                  <label class="control-label mr-2 ml-lg-3">{{ Lang::get('core.grid_order') }}:</label>
                  <select name="order" class="form-control form-control-sm" id="order">
                    <option value="asc">{{ Lang::get('core.grid_asc') }}</option>
                    <option value="desc">{{ Lang::get('core.grid_desc') }}</option>
                  </select>
                  <button type="submit" style="margin-left: 5px"class="btn btn-default" onclick="location.href='\package?sort='+ document.getElementById('sort').value+'&order='+document.getElementById('order').value;"><i class="fa fa-chevron-circle-right"></i></button>

                </form>
              </div>
              <div class="col-lg-12 collapse" id="adv-search">
                <!-- Advanced search form -->
                <form class="mt-3 bg-faded p-3 rounded mb-4 text-sm">
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <label>Category</label>
                      <select class="form-control">
            @foreach ($category as $cat)
                        <option value="{{$cat->tourcategoriesID}}">{{$cat->tourcategoryname}}</option>
            @endforeach

                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label>Depart Type</label>
                      <select class="form-control">
                        <option value="1">Daily</option>
                        <option value="2">On Request</option>
                        <option value="3">Set Date</option>
                      </select>
                    </div>
                    <div class="col-md-4 mb-3">
                      <label>Duration</label>
                      <div class="row">
                        <div class="input-group col-md-6">
                          <input type="text" class="form-control" placeholder="days">
                        </div>
                        <div class="input-group col-md-6">
                          <input type="text" class="form-control" placeholder="nights">
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-md-12">
                      <button type="submit" class="btn btn-primary"> <i class="fa fa-search"></i> Search </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <hr class="my-4" />
            <div class="row">
@foreach ($rowData as $row)
     @if($row->status ==1)
              <div class="col-lg-12">
                <div class="card product-card mb-4  @if($row->featured==1)
card-outline-primary @endif">
                @if($row->departs==2)
                <div class="card-ribbon card-ribbon-bottom card-ribbon-right bg-danger text-white">{{ Lang::get('core.onrequest') }}</div>
                @elseif($row->departs==1)
                <div class="card-ribbon card-ribbon-bottom card-ribbon-right bg-info text-white">{{ Lang::get('core.daily') }}</div>
                @endif
                @if($row->featured==1)
                <div class="card-ribbon card-ribbon-top card-ribbon-left bg-primary text-white">{{ Lang::get('core.featured') }}</div>
                @endif
                  <div class="card-body p-3 pos-relative row">
                    <div class="col-md-4 mb-2 mb-md-0">
                      <img class="rounded img-fluid img-thumbnail" src="@if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.$row->tourimage) && $row->tourimage !='')
        {{ asset('uploads/images/'.CNF_OWNER.'/'.$row->tourimage)}}
        @else
        {{ asset('mmb/images/tour-noimage.jpg')}}
        @endif " alt="{{$row->tour_name}}">
                    </div>
                    <div class="col-md-8 d-flex flex-column align-items-start">
                      <p class="text-muted text-uppercase text-xs mb-0"><a href="?cat={{$row->tourcategoriesID}}" > <span class="text-danger">{{ SiteHelpers::formatLookUp($row->tourcategoriesID,'tourcategoriesID','1:def_tour_categories:tourcategoriesID:tourcategoryname') }}</span></a></p>
                      <h4 class="card-title mb-2">
                        <a href="?view={{$row->tourID}}" class="text-grey-dark">{{$row->tour_name}}</a> 
                      </h4>
                      <p class="font-weight-bold"> {{$row->total_days}} {{ Lang::get('core.days') }} {{$row->total_nights}} {{ Lang::get('core.nights') }}</p>
                      <p class="text-muted text-xs">{!! str_limit($row->tour_description, $limit = 200, $end = '....') !!}</p>
                    </div>
                  </div>
                </div>
              </div>
@endif
@endforeach 
              </div>
            
            <!-- Pagination -->
            <nav aria-label="Page navigation">
             {!! $pagination->render() !!} 
            </nav>
          </div>

        <div class="col-lg-3 order-lg-1">
          <div data-toggle="sticky" data-settings='{"parent":"#content","mind":"#header", "top":10, "breakpoint":480}'>
            <ul class="nav nav-section-menu mb-4 py-3">
              <li class="nav-header">{{ Lang::get('core.tourcategory') }}</li>
            @foreach ($category as $cat)
            <li><a href="?cat={{$cat->tourcategoriesID}}" class="nav-link @if(isset($_GET['cat']))
    @if( $_GET['cat'] == $cat->tourcategoriesID )
    active
    @endif    
@endif
 text-uppercase text-slab">
                  {{$cat->tourcategoryname}}
                  <small class="d-inline-block text-xs text-lowercase">({{ $cat->category_count }})</small>
                  <i class="fa fa-angle-right"></i> 
                </a></li>
            @endforeach
            </ul>
          </div>
          </div>
        </div>
      </div>
    </div>
@include('layouts.leo.footer')  