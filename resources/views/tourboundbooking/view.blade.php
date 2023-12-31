@extends('layouts.app')

@section('content')
    <section class="content-header" style="margin-top: 10px">
      <h1> {{Lang::get('core.bookingno')}} {{ $row->bookingno}} <small> </small></h1>
    </section>

	<div class="box-header with-border" style="margin-top: 10px">
		<div class="box-header-tools pull-left" >
	   		<a href="{{ url('tourboundbooking?return='.$return) }}" class="tips text-red" title="{{ Lang::get('core.btn_back') }}"><i class="fa  fa-arrow-left fa-2x"></i></a>
        <a  href="{{ url('tourboundbooking/show/'.$id.'?pdf=true') }}" target="_blank" class="tips text-red" title="PDF"><i class="fa fa-file-pdf-o fa-2x"></i> </a> 
        <!-- <a  href="{{ url('invoice/booking/'.$row->bookingno) }}" target="_blank" class="tips text-blue" title="{{ Lang::get('core.invoice') }}"><i class="fa fa-money fa-2x"></i> </a> -->
        @if($invoice)
        <a href="{{ url('invoice/payment?invoiceid='.$invoice->invoiceID.'&amount='.$invoice->InvTotal.'&paid='.$paid.'&balance='.$balance_due)}}" title="{{ Lang::get('core.addnewpayment') }}"  onclick="MmbModal(this.href,'{{Lang::get('core.addnewpayment')}}'); return false;"  class="tips text-blue"><i class="fa fa-money fa-2x"></i></a>
        <a href="{{ url('invoice/history?invoiceid='.$invoice->invoiceID)}}" title="{{ Lang::get('core.paymenthistory') }}"  onclick="MmbModal(this.href,'{{Lang::get('core.paymenthistory')}}'); return false;"  class="tips text-blue"><i class="fa fa-history fa-2x"></i></a>
        @endif
		</div>	

		<div class="box-header-tools " >
			<a href="{{ ($prevnext['prev'] != '' ? url('tourboundbooking/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips" title="{{ Lang::get('core.previousbooking') }}" ><i class="fa fa-arrow-left fa-2x"></i>  </a>	
			<a href="{{ ($prevnext['next'] != '' ? url('tourboundbooking/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips" title="{{ Lang::get('core.nextbooking') }}"> <i class="fa fa-arrow-right fa-2x"></i>  </a>
		</div>


	</div>
<div class="row">
    <aside class="col-md-3" id="sidebar">
        <div class="theiaStickySidebar">
          <div class="box box-primary">
            <div class="box-body box-profile">
              <h3 class="profile-username text-center">
                <a href="{{ url('travellers/show/'.$row->travellerID) }}" target="_blank"> {{ SiteHelpers::formatLookUp($row->travellerID,'travellerID','1:travellers:travellerID:nameandsurname') }}</a>
                @if($access['is_edit'] ==1)
                  <a href="{{ url('tourboundbooking/update/'.$id.'?return='.$return) }}" class="tips text-green" title="{{ Lang::get('core.editbookingdetails') }}"><i class="fa fa-pencil fa-1x"></i></a>
                @endif  
              </h3>
              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>{{Lang::get('core.created')}} </b> <a class="pull-right">{{ SiteHelpers::TarihFormat($row->created_at)}}
</a>
                </li>
                  <li class="list-group-item">
                  <b>{{Lang::get('core.updated')}}</b> <a class="pull-right">{{ SiteHelpers::TarihFormat($row->updated_at)}}
                    </a>
                </li>
                    
                  @if($access['is_edit'] =='1')
                  
                  <li class="list-group-item">   
					 <a href="{{ url('bookroom/update?bookingID='.$id)}}"  onclick="MmbModal(this.href,'{{Lang::get('core.addnewroom')}}'); return false;"  class="btn btn-primary btn-social btn-block "><i class="fa fa-user"></i> {{Lang::get('core.addroom')}}</a>
 </li>
@if ( $row->tour == 1 && !$trs )
                  <li class="list-group-item"><a href="{{ url('booktour/update?bookingID='.$id.'&type=2')}}"  onclick="MmbModal(this.href,'{{Lang::get('core.addnewtour')}}'); return false;"  class="btn btn-primary btn-social btn-block"><i class="fa fa-bus"></i> {{Lang::get('core.addtour')}}</a>
</li>
@endif
@if ( $row->hotel == 1 )
                  <li class="list-group-item">					 <a href="{{ url('bookhotel/update?bookingID='.$id)}}"  onclick="MmbModal(this.href,'{{Lang::get('core.addnewhotel')}}'); return false;"  class="btn btn-primary btn-social btn-block"><i class="fa fa-bed"></i> {{Lang::get('core.addhotel')}}</a>
 </li>
           @endif
@if ( $row->flight == 1 )
       
                  <li class="list-group-item">					 <a href="{{ url('bookflight/update?bookingID='.$id)}}"  onclick="MmbModal(this.href,'{{Lang::get('core.addflight')}}'); return false;"  class="btn btn-primary btn-social btn-block "> <i class="fa fa-plane"></i>{{Lang::get('core.addflight')}}</a>
 </li>
           @endif
@if ( $row->car == 1 )
      
                  <li class="list-group-item"> 					 <a href="{{ url('bookcar/update?bookingID='.$id)}}"  onclick="MmbModal(this.href,'{{Lang::get('core.addcar')}}'); return false;"  class="btn btn-primary btn-social btn-block "> <i class="fa fa-car"></i>{{Lang::get('core.addcar')}}</a>
</li>
              					@endif
@if ( $row->extraservices == 1 )
       
                  <li class="list-group-item">					 <a href="{{ url('bookextra/update?bookingID='.$id)}}"  onclick="MmbModal(this.href,'{{Lang::get('core.addextraservice')}}'); return false;"  class="btn btn-primary btn-social btn-block"> <i class="fa fa-plus-square"></i>{{Lang::get('core.addextra')}}</a>
 </li>
            @endif
              					@endif

                </ul>

            </div>
          </div>
        </div>
        @if($invoice)
        <?php 
        $currency = DB::table('def_currency')->select('symbol')->where('currencyID',CNF_CURRENCY)->get();
        if(empty($currency))
        $symbol = '';
        else
        $symbol = $currency[0]->symbol; 
        ?>
        <div class="theiaStickySidebar">
          <div class="box box-primary">
            <div class="box-body box-profile">
              <h3 class="profile-username text-center">
                <a target="_blank"> Payment Status </a>
              </h3>
              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Amount</b> <a class="pull-right">{{$symbol}} {{number_format($invoice->InvTotal)}}</a>
                </li>
                @if($paid)
                <li class="list-group-item">
                  <b>Paid</b> <a class="pull-right">{{$symbol}} {{number_format($paid)}}</a>
                </li>
                @endif
                @if($balance_due)
                <li class="list-group-item">
                  <b>Balance Due</b> <a class="pull-right">{{$symbol}} {{number_format($balance_due)}}</a>
                </li>
                @endif
                @if($balance_due!=0)
                <!-- if balance = 0, hide -->
                <li class="list-group-item">
                  <b>Due Date</b> <a class="pull-right">{{$due}}</a>
                </li>
                @endif
              </ul>
            </div>
          </div>
        </div>
        @else
        <div class="theiaStickySidebar">
          <div class="box box-primary">
            <div class="box-body box-profile">
              <h3 class="profile-username text-center">
                <a target="_blank"> No Invoice </a>
              </h3>
            </div>
          </div>
        </div>
        @endif
        </aside>
    <div class="col-md-9">
        <div class="box box-warning">
            <div class="box-header with-border">
              <span class="box-title"><i class="fa fa-list fa-lg text-green" aria-hidden="true"></i> {{Lang::get('core.passengerlist')}}</span>
            </div>
            <div class="box-body">
              <div class="row">
                <table id="rooms" class="table table-striped">
                <thead>
                <tr>
                  <th>{{Lang::get('core.roomtype')}}</th>
                  <th>{{Lang::get('core.travellers')}}</th>
                  <th width="100px">{{Lang::get('core.status')}}</th>
                  <th width="50px">{{Lang::get('core.action')}}</th>
                </tr>
                </thead>
                <tbody>
             @foreach($rms as $rs)
                <tr>
                  <td>{!! SiteHelpers::roomType ($rs['roomtype']) !!}</td>
                  <td>{!! SiteHelpers::showTravellers ($rs['travellers']) !!}</td>
                  <td>{!! BookingStatus::Status($rs['status']) !!}</td>
                  <td>
                    @if($access['is_edit'] =='1')
                      <a href="{{ url('bookroom/update/'.$rs['roomID'].'/?bookingID='.$id)}}"  onclick="MmbModal(this.href,'{{Lang::get('core.bookroom')}}'); return false;"  class="text-blue pull-right tips" title="{{Lang::get('core.editroom')}}"><i class="fa fa-pencil fa-2x"></i></a>
                    @endif
                  </td>
                </tr>
            @endforeach
                </tbody>
              </table>
              </div>
              </div>
          </div>
@if ( $row->tour == 1 )
        <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">
                <i class="fa fa-bus fa-lg text-green" aria-hidden="true"></i> {{Lang::get('core.tours')}}</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <table id="tours" class="table  table-striped">
                <thead>
                <tr>
                  <th>{{Lang::get('core.tourname')}}</th>
                  <th>{{Lang::get('core.tourcode')}}</th>
                  <th>{{Lang::get('core.start')}}</th>
                  <th>{{Lang::get('core.end')}}</th>
                  <th width="100px">{{Lang::get('core.status')}}</th>
                  <th width="50px">{{Lang::get('core.action')}}</th>
                </tr>
                </thead>
                <tbody>
             @foreach($trs as $ts)
                <tr>
                  <td><a href="{{ url('tours/show/'.$ts['tourID'])}}" target="_blank"><strong>{{ SiteHelpers::formatLookUp($ts['tourID'],'tourID','1:tours:tourID:tour_name') }}</strong></a></td>
                  <td><a href="{{ url('tourdates/show/'.$ts['tourdateID'])}}" target="_blank"><strong> {{ SiteHelpers::formatLookUp($ts['tourdateID'],'tourdateID','1:tour_date:tourdateID:tour_code') }}</strong></a></td>
                  <td>{{date("".CNF_DATE."",strtotime(SiteHelpers::formatLookUp($ts['tourdateID'],'tourdateID','1:tour_date:tourdateID:start'))) }}</td>
                  <td>{{ date("".CNF_DATE."",strtotime(SiteHelpers::formatLookUp($ts['tourdateID'],'tourdateID','1:tour_date:tourdateID:end'))) }}</td>
                  <td>{!! BookingStatus::Status($ts['status']) !!}</td>
                  <td>
                    @if($access['is_edit'] =='1')
                      <a href="{{ url('booktour/update/'.$ts['booktourID'].'/?bookingID='.$id.'&type=1')}}"  onclick="MmbModal(this.href,'{{Lang::get('core.booktour')}}'); return false;"  class="text-blue pull-right tips" title="{{Lang::get('core.edittour')}}"><i class="fa fa-pencil fa-2x"></i></a>
                    @endif      
                  </td>
                </tr>
            @endforeach
                </tbody>
              </table>
              </div>
              </div>
            </div>
@endif
@if ( $row->hotel == 1 )
        <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-bed fa-lg text-green" aria-hidden="true"></i> {{Lang::get('core.hotels')}}</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <table id="rooms" class="table  table-striped">
                <thead>
                <tr>
                  <th>{{Lang::get('core.hotel')}}</th>
                  <th>{{Lang::get('core.location')}}</th>
                  <th>{{Lang::get('core.checkindate')}}</th>
                  <th>{{Lang::get('core.checkoutdate')}}</th>
                  <th  class="pull-right">{{Lang::get('core.status')}}</th>
                </tr>
                </thead>
                <tbody>
             @foreach($hotel as $hot)
                <tr>
                  <td>{{ SiteHelpers::formatLookUp($hot['hotelID'],'hotelID','1:hotels:hotelID:hotel_name') }}</td>
                  <td>{{ SiteHelpers::formatLookUp($hot['cityID'],'cityID','1:def_city:cityID:city_name') }}, {{ SiteHelpers::formatLookUp($hot['countryID'],'countryID','1:def_country:countryID:country_name') }}</td>
                  <td>{{ SiteHelpers::TarihFormat($hot['checkin'])}}</td>
                  <td>{{ SiteHelpers::TarihFormat($hot['checkout'])}}</td>
                  <td  class="pull-right">{!! BookingStatus::Status($hot['status']) !!}
                      @if($access['is_edit'] =='1')
					 <a href="{{ url('bookhotel/update/'.$hot['bookhotelID'].'/?bookingID='.$id)}}"  onclick="MmbModal(this.href,'{{Lang::get('core.edithotel')}}'); return false;"  class="text-blue pull-right tips" title="{{Lang::get('core.edithotel')}}"><i class="fa fa-pencil fa-2x"></i></a>
					@endif
                    </td>
                </tr>
            @endforeach
                </tbody>
              </table>              
                
            </div>
              </div>
            </div>
@endif
@if ( $row->flight == 1 )
        <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-plane fa-lg text-green" aria-hidden="true"></i> {{Lang::get('core.flights')}}</h3>
            </div>
            <div class="box-body">
              <div class="row">
                  <table id="rooms" class="table  table-striped">
                <thead>
                <tr>
                  <th>{{Lang::get('core.travellers')}}</th>
                  <th>{{Lang::get('core.airline')}}</th>
                  <th>{{Lang::get('core.from')}}</th>
                  <th>{{Lang::get('core.to')}}</th>
                  <th>{{Lang::get('core.departuredate')}}</th>
                  <th>{{Lang::get('core.returndate')}}</th>
                  <th  class="pull-right">{{Lang::get('core.status')}}</th>
                </tr>
                </thead>
                <tbody>
             @foreach($flight as $fl)
                <tr>
                  <td>{!! SiteHelpers::showTravellers($fl['travellersID']) !!} </td>
                  <td>{{ SiteHelpers::formatLookUp($fl['airlineID'],'airlineID','1:def_airlines:airlineID:airline') }}</td>
                  <td>{{ SiteHelpers::formatLookUp($fl['depairportID'],'depairportID','1:def_airports:airportID:airport_name') }}</td>
                  <td>{{ SiteHelpers::formatLookUp($fl['arrairportID'],'depairportID','1:def_airports:airportID:airport_name') }}</td>
                  <td>{{ SiteHelpers::TarihFormat2($fl['departing'])}}</td>
                  <td>{{ SiteHelpers::TarihFormat2($fl['returning'])}}</td>
                  <td  class="pull-right">{!! BookingStatus::Status($fl['status']) !!}
                      @if($access['is_edit'] =='1')
					 <a href="{{ url('bookflight/update/'.$fl['bookflightID'].'/?bookingID='.$id)}}"  onclick="MmbModal(this.href,'{{Lang::get('core.editflight')}}'); return false;"  class="text-blue pull-right tips" title="{{Lang::get('core.editflight')}}"><i class="fa fa-pencil fa-2x"></i></a>
					@endif
                    </td>
                </tr>
            @endforeach
                </tbody>
              </table> 
              </div>
              </div>
            </div>
@endif
@if ( $row->car == 1 )

        <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-car fa-lg text-green" aria-hidden="true"></i> {{Lang::get('core.cars')}}</h3>
            </div>
            <div class="box-body">
              <div class="row">
                  <table id="rooms" class="table  table-striped">
                <thead>
                <tr>
                  <th>{{Lang::get('core.car')}}</th>
                  <th>{{Lang::get('core.start')}}</th>
                  <th>{{Lang::get('core.end')}}</th>
                  <th>{{Lang::get('core.pickup')}}</th>
                  <th>{{Lang::get('core.dropoff')}}</th>
                  <th  class="pull-right">{{Lang::get('core.status')}}</th>
                </tr>
                </thead>
                <tbody>
             @foreach($car as $cr)
                <tr>
                <td>{{ SiteHelpers::formatLookUp($cr['carbrandID'],'carbrandID','1:def_car_brands:carbrandID:brand') }} {{ SiteHelpers::formatLookUp($cr['carsID'],'carsID','1:cars:carsID:model') }}</td>
                <td>{{ SiteHelpers::TarihFormat($cr['start'])}}</td>
                <td>{{ SiteHelpers::TarihFormat($cr['end'])}}</td>
                <td>{{ $cr['pickup'] }}</td>
                <td>{{ $cr['dropoff'] }}</td>
                <td  class="pull-right">{!! BookingStatus::Status($cr['status']) !!}
                    @if($access['is_edit'] =='1')
					 <a href="{{ url('bookcar/update/'.$cr['carsID'].'/?bookingID='.$id)}}"  onclick="MmbModal(this.href,'{{Lang::get('core.editcar')}}'); return false;"  class="text-blue pull-right tips" title="{{Lang::get('core.editcar')}}"><i class="fa fa-pencil fa-2x"></i></a>
					@endif
                    </td>
                </tr>
            @endforeach
                </tbody>
              </table>               </div>
              </div>
            </div>
@endif
@if ( $row->extraservices == 1 )
        <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-plus fa-lg text-green" aria-hidden="true"></i> {{Lang::get('core.extraservices')}}</h3>
            </div>
            <div class="box-body">
              <div class="row">
                  <table id="rooms" class="table  table-striped">
                <thead>
                <tr>
                  <th>{{Lang::get('core.extraservice')}}</th>
                  <th  class="pull-right">{{Lang::get('core.status')}}</th>
                </tr>
                </thead>
                <tbody>
             @foreach($extra as $ex)
                <tr>
                <td>{{ SiteHelpers::formatLookUp($ex['extraserviceID'],'extraserviceID','1:def_extra_services:extraserviceID:service_name') }}</td>
                <td  class="pull-right">{!! BookingStatus::Status($ex['status']) !!}
                    @if($access['is_edit'] =='1')
					 <a href="{{ url('bookextra/update/'.$ex['bookextraID'].'/?bookingID='.$id)}}"  onclick="MmbModal(this.href,'{{Lang::get('core.editextra')}}'); return false;"  class="text-blue pull-right tips" title="{{Lang::get('core.editextra')}}"><i class="fa fa-pencil fa-2x"></i></a>
					@endif
                    </td>
                </tr>
            @endforeach
                </tbody>
              </table>               </div>
              </div>
            </div>
@endif
          
        </div>
        </div>
	<script>
		jQuery('#sidebar').theiaStickySidebar({
			additionalMarginTop: 60
		});
	</script>
	                    			<div style="clear: both;"></div>
@if(session()->has('submit_payment_message'))
<script>
  alert({{session()->pull('submit_payment_message')}});
</script>
@endif

@stop