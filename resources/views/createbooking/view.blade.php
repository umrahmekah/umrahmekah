@extends('layouts.app')

@section('content')
    <section class="content-header" style="margin-top: 10px">
      <h1> {{Lang::get('core.bookingno')}} {{ $row->bookingno}} <small> (<a @if($main) href="{{ url('travellers/show/'.$row->travellerID) }}" @endif target="_blank"> @if($main) {{ $main->fullname }} @else Traveller Not Found @endif </a>) </small></h1>
    </section>

	<div class="box-header with-border" style="margin-top: 10px">
		<div class="box-header-tools pull-left" >
	   		<a href="{{ url('createbooking?return='.$return) }}" class="tips text-red" title="{{ Lang::get('core.btn_back') }}"><i class="fa  fa-arrow-left fa-2x"></i></a>
        @if($access['is_edit'] ==1)
          <a href="{{ url('createbooking/update/'.$id.'?return='.$return) }}" class="tips text-green" title="{{ Lang::get('core.editbookingdetails') }}"><i class="fa fa-pencil fa-2x"></i></a>
        @endif  
        <a  target="_blank" class="tips text-green" title="{{ Lang::get('core.add_room') }}" data-toggle="modal" data-target="#add_room"><i class="fa fa-plus fa-2x"></i> </a>
        <a  href="{{ url('createbooking/show/'.$id.'?pdf=true') }}" target="_blank" class="tips text-red" title="Generate Booking Sheet"><i class="fa fa-file-pdf-o fa-2x"></i> </a> 
        {{-- <a  href="{{ url('invoice/booking/'.$row->bookingno) }}" target="_blank" class="tips text-blue" title="{{ Lang::get('core.invoice') }}"><i class="fa fa-money fa-2x"></i> </a> --}}
        @if($invoice)
        <a href="{{ url('invoice/payment?invoiceid='.$invoice->invoiceID.'&amount='.$invoice->InvTotal.'&paid='.$paid.'&balance='.$balance_due)}}" title="{{ Lang::get('core.addnewpayment') }}"  onclick="MmbModal(this.href,'{{Lang::get('core.addnewpayment')}}'); return false;"  class="tips text-blue"><i class="fa fa-money fa-2x"></i></a>
        <a href="{{ url('invoice/history?invoiceid='.$invoice->invoiceID)}}" title="{{ Lang::get('core.paymenthistory') }}"  onclick="MmbModal(this.href,'{{Lang::get('core.paymenthistory')}}'); return false;"  class="tips text-blue"><i class="fa fa-history fa-2x"></i></a>
        <?php $payment = DB::table('invoice_payments')->where('invoiceID', $invoice->invoiceID )->sum('amount'); ?>
        @if($payment)
        <a {{-- href="/createbooking/depositletter?booking_id={{ $row->bookingsID }}&traveller_id={{$row->travellerID}}" --}} title="Deposit Confirmation Letter" class="tips text-blue" data-toggle="modal" data-target="#deposit_letter"><i class="fa fa-file-text fa-2x"></i></a>
        @endif
        <a title="Set Discount" class="tips text-blue" data-toggle="modal" data-target="#set_discount"><i class="fa fa-tag fa-2x"></i></a>
        @endif
		</div>	

		<div class="box-header-tools " >
			<a href="{{ ($prevnext['prev'] != '' ? url('createbooking/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips" title="{{ Lang::get('core.previousbooking') }}" ><i class="fa fa-arrow-left fa-2x"></i>  </a>	
			<a href="{{ ($prevnext['next'] != '' ? url('createbooking/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips" title="{{ Lang::get('core.nextbooking') }}"> <i class="fa fa-arrow-right fa-2x"></i>  </a>
		</div>


	</div>
<div class="row">
    <aside class="col-md-3" id="sidebar">
        <div class="theiaStickySidebar">
          <div class="box box-primary">
            <div class="box-body box-profile">
              <h3 class="profile-username text-center">
                <a target="_blank">Package</a>
              </h3>
              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>{{Lang::get('core.tourname')}} </b> <a class="pull-right">{{ $row->bookTour->tour->tour_name ?? "Not Found" }}</a>
                </li>
                <li class="list-group-item">
                  <b>{{Lang::get('core.tourcode')}}</b> <a class="pull-right">{{ $row->bookTour->tourdate->tour_code ?? "Not Found" }}</a>
                </li>
                <li class="list-group-item">
                  <b>{{Lang::get('core.start')}}</b> <a class="pull-right">@if($row->bookTour && $row->bookTour->tourdate) {{ \Carbon::parse($row->bookTour->tourdate->start)->format('d M Y')}} @else Not Found @endif</a>
                </li>
                <li class="list-group-item">
                  <b>{{Lang::get('core.end')}}</b> <a class="pull-right">@if($row->bookTour && $row->bookTour->tourdate) {{ Carbon::parse($row->bookTour->tourdate->end)->format('d M Y')}} @else Not Found @endif</a>
                </li>
                <li class="list-group-item">
                  <b>{{Lang::get('core.status')}}</b> <a class="pull-right">{!! BookingStatus::Status($row->bookTour->status) !!}</a>
                </li>

              </ul>

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
          @else
            <div class="box box-primary">
              <div class="box-body box-profile">
                <h3 class="profile-username text-center">
                  <a target="_blank"> No Invoice </a>
                </h3>
              </div>
            </div>
          @endif
        </div>
        
    </aside>

<div class="col-md-9">
  @if(!$row->settled)
    <div class="alert alert-warning">
        {{$row->adult_number}} Adults, {{$row->child_number}} Children, {{$row->infant_number}} Infants. <a class="alert-link pull-right" href="#" onclick="dismiss()">Dismiss</a>
    </div>
  @endif
  <?php $num = 1; ?>
  @foreach($row->bookRoom as $room)
    @if(!in_array($room->roomtype, [7,8,9]))
      <div class="box box-warning">
        <div class="box-header with-border">
          <span class="box-title">
            <i class="fa fa-list fa-lg text-green" aria-hidden="true"></i> {{Lang::get('core.room')}} {{ $num }} {{ $room->roomTypeName }}
          </span>
          <a class="tips text-green pull-right" title="{{ Lang::get('core.editroom') }}" data-toggle="modal" data-target="#edit_room_{{$num++}}"><i class="fa fa-pencil fa-2x"></i></a>
        </div>
        <div class="box-body">
          <div class="row">
            <table id="rooms" class="table table-striped">
              <thead>
                <tr>
                  <th>{{Lang::get('core.travellers')}}</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{!! SiteHelpers::showTravellers ($room->travellers) !!}</td>
                </tr>
                @foreach($room->childRoom as $child_room)
                  @if(in_array($child_room->roomtype, [7,8]))
                    <tr>
                      <th>{{$child_room->roomTypeName}}</th>
                    </tr>
                  @else
                    <tr>
                      <th>{{Lang::get('core.infant')}}</th>
                    </tr>
                  @endif
                  <tr>
                    <td>{!! SiteHelpers::showTravellers ($child_room->travellers) !!}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    @endif
  @endforeach
  <div class="row">
    <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#add_room"> <i class="fa fa-plus"></i> Add New Room</button>
  </div>
  <div class="box box-primary">
    <div class="box-header with-border">
      Checklist
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-6">
          <h5>Warganegara</h5>
          <input type="checkbox" class="checklist" @if($checklist->sijil_nikah) checked @endif value="sijil_nikah"> Salinan Sijil Nikah <br>
          <input type="checkbox" class="checklist" @if($checklist->sijil_lahir) checked @endif value="sijil_lahir"> Salinan Sijil Lahir <br>
          <input type="checkbox" class="checklist" @if($checklist->surat_izin) checked @endif value="surat_izin"> Surat Izin <br>
          <input type="checkbox" class="checklist" @if($checklist->ic_pemberi_izin) checked @endif value="ic_pemberi_izin"> Salinan Kad Pengenalan Pemberi Izin <br>
          <input type="checkbox" class="checklist" @if($checklist->sijil_cerai) checked @endif value="sijil_cerai"> Salinan Sijil Cerai Ibu Bapa Kandung Anak Tiri <br>
          <input type="checkbox" class="checklist" @if($checklist->sijil_anak_angkat) checked @endif value="sijil_anak_angkat"> Salinan Sijil Anak Angkat
        </div>
        <div class="col-md-6">
          <h5>Warganegara Asing</h5>
          <input type="checkbox" class="checklist" @if($checklist->wa_ic) checked @endif value="wa_ic"> Salinan Kad Pengenalan (JPN) <br>
          <input type="checkbox" class="checklist" @if($checklist->wa_declaration_statuary) checked @endif value="wa_declaration_statuary"> Declarataion Statuary <br>
          <input type="checkbox" class="checklist" @if($checklist->wa_consent_letter) checked @endif value="wa_consent_letter"> Consent Letter <br>
          <input type="checkbox" class="checklist" @if($checklist->wa_ic_pemberi_izin) checked @endif value="wa_ic_pemberi_izin"> Salinan Kad Pengenalan/Paspot Pemberi Izin <br>
          <input type="checkbox" class="checklist" @if($checklist->wa_surat_majikan) checked @endif value="wa_surat_majikan"> Surat Pengesahan Majikan <br>
          <input type="checkbox" class="checklist" @if($checklist->wa_surat_kolej) checked @endif value="wa_surat_kolej"> Surat pengesahan Kolej/Universiti/Sekolah <br>
          <input type="checkbox" class="checklist" @if($checklist->wa_sijil_nikah) checked @endif value="wa_sijil_nikah"> Salinan Sijil Nikah <br>
          <input type="checkbox" class="checklist" @if($checklist->wa_sijil_lahir) checked @endif value="wa_sijil_lahir"> Salinan Sijil Lahir <br>
          <input type="checkbox" class="checklist" @if($checklist->visa) checked @endif value="visa"> Visa/Permit <br>
        </div>
      </div>
    </div>
  </div>
  <div class="box box-warning">
    <div class="box-header with-border">
      History
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
          Booking created @if($row->entryByUser) by {{ $row->entryByUser->fullName }} @endif at {{ $row->created_at->format('h:i:s A, d M Y') }}
        </div>
        @if($invoice->discount)
          <div class="col-md-12">
            Discount given @if($row->invoice->discountByUser) by {{ $row->invoice->discountByUser->fullName }} @endif for {{CURRENCY_SYMBOLS}}{{ $row->invoice->discount }} per pax @if($invoice->booking) ({{CURRENCY_SYMBOLS}}{{ $row->invoice->discount*$row->pax }}) @endif at @if($invoice->discount_at) {{ $invoice->discount_at->format('h:i:s A, d M Y') }} @else {{ \Carbon::parse($invoice->created_at)->format('h:i:s A, d M Y') }} @endif
          </div>
        @endif
        @foreach($row->invoice->payments as $payment)
          <div class="col-md-12">
            Payment {{CURRENCY_SYMBOLS}}{{ $payment->amount }} added @if($payment->entryByUser) by {{ $payment->entryByUser->fullName }} @endif at {{ $payment->created_at->format('h:i:s A, d M Y') }}
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<div class="modal inmodal" id="deposit_letter" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content animated bounceInRight">
        <form method="POST" action="/createbooking/depositletter">
          <input type="hidden" name="booking_id" value="{{$row->bookingsID}}">
          {{csrf_field()}}
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                  <h4 class="modal-title">Deposit Confirmation Letter</h4>
                  <small class="font-bold">Select the traveller for the letter.</small>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                    <label>Traveller</label> 
                    <div class="">
                      <select class="select2" name="traveller_id" id="status">
                        @foreach($trvs as $trv)
                          <option value="{{$trv->travellerID}}">{{$trv->NRIC}} - {{$trv->nameandsurname}} {{$trv->last_name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Generate Letter</button>
              </div>
            </form>
        </div>
    </div>
</div>

<div class="modal inmodal" id="add_room" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content animated bounceInRight">
        <form method="POST" action="/createbooking/addroom">
          <input type="hidden" name="booking_id" value="{{$row->bookingsID}}">
          {{csrf_field()}}
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                  <h4 class="modal-title">Add Room</h4>
                  <small class="font-bold">Add room for this booking.</small>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                    <label>{{Lang::get('core.roomtype')}}</label> 
                    <div class="">
                      <select name='roomtype' id='roomtype' class='select2 ' required  >
                        <option disabled selected>Please Select Room Type</option>
                        @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_single)
                          <option value="1">{{Lang::get('core.single')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_single}}</option>
                        @else'
                          <option disabled>{{Lang::get('core.single')}}</option>
                        @endif
                        @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_double)
                          <option value="2">{{Lang::get('core.double')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_double}}</option>
                        @else'
                          <option disabled>{{Lang::get('core.double')}}</option>
                        @endif
                        @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_triple)
                          <option value="3">{{Lang::get('core.triple')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_triple}}</option>
                        @else'
                          <option disabled>{{Lang::get('core.triple')}}</option>
                        @endif
                        @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_quad)
                          <option value="4">{{Lang::get('core.quad')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_quad}}</option>
                        @else'
                          <option disabled>{{Lang::get('core.quad')}}</option>
                        @endif
                        @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_quint)
                          <option value="5">{{Lang::get('core.quint')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_quint}}</option>
                        @else'
                          <option disabled>{{Lang::get('core.quint')}}</option>
                        @endif
                        @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_sext)
                          <option value="6">{{Lang::get('core.sext')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_sext}}</option>
                        @else'
                          <option disabled>{{Lang::get('core.sext')}}</option>
                        @endif
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>{{Lang::get('core.traveller')}}</label> 
                    <div class="">
                      <select name='travellers[]' multiple rows='5' id='travellers' class='select2 ' required  >
                        @foreach($travellers as $traveller)
                          <option value="{{$traveller->travellerID}}">@if($traveller->NRIC){{$traveller->NRIC}}@else NIRC not set @endif - {{$traveller->nameandsurname}} {{$traveller->last_name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label> {{Lang::get('core.travel_child')}} </label>
                    <input type="checkbox" name="child_check" class="form-control" id="childCheck">
                  </div>
                  <div id="child_form" style="display: none;">
                    <div class="form-group  " >
                      <label>{{Lang::get('core.roomtype')}}</label>
                      <div class="">
                        <select name="child_room" class='select2'>
                          <option disabled selected>Please Select Room Type</option>
                          @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_child)
                            <option value="7">{{Lang::get('core.tourcostchild')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_child}}</option>
                          @else'
                            <option disabled>{{Lang::get('core.tourcostchild')}}</option>
                          @endif
                          @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_child_wo_bed)
                            <option value="8">{{Lang::get('core.tourcostchildwithoutbed')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_child_wo_bed}}</option>
                          @else'
                            <option disabled>{{Lang::get('core.tourcostchildwithoutbed')}}</option>
                          @endif
                        </select>
                      </div> 
                      </div>
                      <div class="form-group" >
                      <label>{{Lang::get('core.child')}}</label>
                      <div class="">
                        <select name='children[]' multiple rows='5' id='child' class='select2'>
                          @foreach($children as $child)
                            <option value="{{$child->travellerID}}">@if($child->NRIC){{$child->NRIC}}@else NIRC not set @endif - {{$child->nameandsurname}} {{$child->last_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div> 
                  </div>
                  <div class="form-group">
                    <label> {{Lang::get('core.travel_infant')}} </label>
                    <input type="checkbox" name="infant_check" class="form-control" id="infantCheck">
                  </div>
                  <div id="infant_form" style="display: none;">
                    <div class="form-group  " >
                      <label>{{Lang::get('core.roomtype')}}</label>
                      <div class="">
                        <select name="infant_room" class='select2'>
                          <option disabled selected>Please Select Room Type</option>
                          @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_infant_wo_bed)
                            <option value="8" selected>{{Lang::get('core.tourcostinfant')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_infant_wo_bed}}</option>
                          @else'
                            <option disabled>{{Lang::get('core.tourcostinfant')}}</option>
                          @endif
                        </select>
                      </div> 
                      </div>
                      <div class="form-group" >
                      <label>{{Lang::get('core.infant')}}</label>
                      <div class="">
                        <select name='infants[]' multiple rows='5' id='infant' class='select2'>
                          @foreach($infants as $infant)
                            <option value="{{$infant->travellerID}}">@if($infant->NRIC){{$infant->NRIC}}@else NIRC not set @endif - {{$infant->nameandsurname}} {{$infant->last_name}}</option>
                          @endforeach
                        </select>
                      </div> 
                    </div> 
                  </div>
                  
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Add Room</button>
              </div>
            </form>
        </div>
    </div>
</div>

<?php $roomnum = 1; ?>

@foreach($row->bookRoom as $room)
@if(!in_array($room->roomtype, [7,8,9]))
  <div class="modal inmodal" id="edit_room_{{$roomnum}}" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
          <form method="POST" action="/createbooking/addroom">
            <input type="hidden" name="booking_id" value="{{$row->bookingsID}}">
            <input type="hidden" name="roomID" value="{{$room->roomID}}">
            {{csrf_field()}}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Edit Room</h4>
                    <small class="font-bold">Edit room for this booking.</small>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label>{{Lang::get('core.roomtype')}}</label> 
                      <div class="">
                        <select name='roomtype' id='roomtype_{{$roomnum}}' class='select2 ' required  >
                          <option disabled selected>Please Select Room Type</option>
                          @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_single)
                            <option value="1" @if($room->roomtype == 1) selected @endif>{{Lang::get('core.single')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_single}}</option>
                          @else'
                            <option disabled>{{Lang::get('core.single')}}</option>
                          @endif
                          @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_double)
                            <option value="2" @if($room->roomtype == 2) selected @endif>{{Lang::get('core.double')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_double}}</option>
                          @else'
                            <option disabled>{{Lang::get('core.double')}}</option>
                          @endif
                          @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_triple)
                            <option value="3" @if($room->roomtype == 3) selected @endif>{{Lang::get('core.triple')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_triple}}</option>
                          @else'
                            <option disabled>{{Lang::get('core.triple')}}</option>
                          @endif
                          @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_quad)
                            <option value="4" @if($room->roomtype == 4) selected @endif>{{Lang::get('core.quad')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_quad}}</option>
                          @else'
                            <option disabled>{{Lang::get('core.quad')}}</option>
                          @endif
                          @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_quint)
                            <option value="5" @if($room->roomtype == 5) selected @endif>{{Lang::get('core.quint')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_quint}}</option>
                          @else'
                            <option disabled>{{Lang::get('core.quint')}}</option>
                          @endif
                          @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_sext)
                            <option value="6" @if($room->roomtype == 6) selected @endif>{{Lang::get('core.sext')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_sext}}</option>
                          @else'
                            <option disabled>{{Lang::get('core.sext')}}</option>
                          @endif
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label>{{Lang::get('core.traveller')}}</label> 
                      <div class="">
                        <select name='travellers[]' multiple rows='5' id='travellers_{{$roomnum}}' class='select2 ' required  >
                          @foreach($travellers as $traveller)
                            <option value="{{$traveller->travellerID}}" @if(in_array($traveller->travellerID, explode(',', $room->travellers))) selected @endif>@if($traveller->NRIC){{$traveller->NRIC}}@else NIRC not set @endif - {{$traveller->nameandsurname}} {{$traveller->last_name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label> {{Lang::get('core.travel_child')}} </label>
                      <input type="checkbox" name="child_check" class="form-control" id="childCheck_{{$roomnum}}" @if(!is_null($room->childrenRoom)) checked @endif>
                    </div>
                    <div id="child_form_{{$roomnum}}" @if(!is_null($room->childrenRoom)) @else style="display: none;" @endif>
                      <div class="form-group  " >
                        <label>{{Lang::get('core.roomtype')}}</label>
                        <div class="">
                          <select name="child_room" class='select2'>
                            <option disabled selected>Please Select Room Type</option>
                            @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_child)
                              <option value="7" @if(!is_null($room->childrenRoom) && $room->childrenRoom->roomtype == 7) selected @endif>{{Lang::get('core.tourcostchild')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_child}}</option>
                            @else'
                              <option disabled>{{Lang::get('core.tourcostchild')}}</option>
                            @endif
                            @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_child_wo_bed)
                              <option value="8" @if(!is_null($room->childrenRoom) && $room->childrenRoom->roomtype == 8) selected @endif>{{Lang::get('core.tourcostchildwithoutbed')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_child_wo_bed}}</option>
                            @else'
                              <option disabled>{{Lang::get('core.tourcostchildwithoutbed')}}</option>
                            @endif
                          </select>
                        </div> 
                        </div>
                        <div class="form-group" >
                        <label>{{Lang::get('core.child')}}</label>
                        <div class="">
                          <select name='children[]' multiple rows='5' id='child_{{$roomnum}}' class='select2'>
                            @foreach($children as $child)
                              <option value="{{$child->travellerID}}" @if(!is_null($room->childrenRoom) && in_array($child->travellerID, explode(',', $room->childrenRoom->travellers) ) ) selected @endif>@if($child->NRIC){{$child->NRIC}}@else NIRC not set @endif - {{$child->nameandsurname}} {{$child->last_name}}</option>
                            @endforeach
                          </select>
                        </div> 
                      </div> 
                    </div>
                    <div class="form-group">
                      <label> {{Lang::get('core.travel_infant')}} </label>
                      <input type="checkbox" name="infant_check" class="form-control" id="infantCheck_{{$roomnum}}" @if(!is_null($room->infantRoom)) checked @endif>
                    </div>
                    <div id="infant_form_{{$roomnum}}" @if(!is_null($room->childrenRoom)) @else style="display: none;" @endif>
                      <div class="form-group  " >
                        <label>{{Lang::get('core.roomtype')}}</label>
                        <div class="">
                          <select name="infant_room" class='select2'>
                            <option disabled selected>Please Select Room Type</option>
                            @if($row->bookTour && $row->bookTour->tourdate && $row->bookTour->tourdate->cost_infant_wo_bed)
                              <option value="8" selected>{{Lang::get('core.tourcostinfant')}} {{CURRENCY_SYMBOLS}}{{$row->bookTour->tourdate->cost_infant_wo_bed}}</option>
                            @else'
                              <option disabled>{{Lang::get('core.tourcostinfant')}}</option>
                            @endif
                          </select>
                        </div> 
                        </div>
                        <div class="form-group" >
                        <label>{{Lang::get('core.infant')}}</label>
                        <div class="">
                          <select name='infants[]' multiple rows='5' id='infant_{{$roomnum++}}' class='select2'>
                            @foreach($infants as $infant)
                              <option value="{{$infant->travellerID}}" @if(!is_null($room->infantRoom) && in_array($infant->travellerID, explode(',', $room->infantRoom->travellers))) selected @endif>@if($infant->NRIC){{$infant->NRIC}}@else NIRC not set @endif - {{$infant->nameandsurname}} {{$infant->last_name}}</option>
                            @endforeach
                          </select>
                        </div> 
                      </div> 
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="deleteroom({{ $room->roomID }})">Delete</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
              </form>
          </div>
      </div>
  </div>
@endif
@endforeach

<div class="modal inmodal" id="set_discount" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content animated bounceInRight">
        <form method="POST" action="/createbooking/setdiscount">
          <input type="hidden" name="booking_id" value="{{$row->bookingsID}}">
          {{csrf_field()}}
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                  <h4 class="modal-title">Set Discount</h4>
                  <small class="font-bold">If there's already discount, the discount will be overwritten.</small>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-2">
                        <label style="margin-top: 7px">Discount ({{CURRENCY_SYMBOLS}})</label> 
                      </div>
                      <div class="col-md-4">
                        <input type="number" name="discount" class="form-control" value="{{ $row->invoice->discount }}">
                      </div>
                      <div class="col-md-2" style="margin-top: 7px"> Per Pax</div>
                    </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Apply Discount</button>
              </div>
            </form>
        </div>
    </div>
</div>

<script>
	jQuery('#sidebar').theiaStickySidebar({
		additionalMarginTop: 60
	});

  $('.checklist').on('ifChecked', function () {
    let column = $(this).val();
    axios.post('/createbooking/checklist/{{$row->bookingsID}}', {_token:'{{csrf_token()}}', type: 1, column:column})
    .then(response => {
      let boolean = response.data;
      if (boolean) {
        // alert('Checklist updated');
      }else{
        alert('Something went wrong');
      }
    }).catch(e => {
      alert(e);
    })
  });

  $('.checklist').on('ifUnchecked', function () {
    let column = $(this).val();
    axios.post('/createbooking/checklist/{{$row->bookingsID}}', {_token:'{{csrf_token()}}', type: 0, column:column})
    .then(response => {
      let boolean = response.data;
      if (boolean) {
        // alert('Checklist updated');
      }else{
        alert('Something went wrong');
      }
    }).catch(e => {
      alert(e);
    })
  });

  $('#childCheck').on('ifChecked', function () {
    document.getElementById('child_form').style.display = "block";
  })

  $('#childCheck').on('ifUnchecked', function () {
    document.getElementById('child_form').style.display = "none";
  })

  $('#infantCheck').on('ifChecked', function () {
    document.getElementById('infant_form').style.display = "block";
  })

  $('#infantCheck').on('ifUnchecked', function () {
    document.getElementById('infant_form').style.display = "none";
  })

  <?php $scriptnum = 1; ?>

  @foreach($row->bookRoom as $room)
    $('#childCheck_{{$scriptnum}}').on('ifChecked', function () {
      document.getElementById('child_form_{{$scriptnum}}').style.display = "block";
    })

    $('#childCheck_{{$scriptnum}}').on('ifUnchecked', function () {
      document.getElementById('child_form_{{$scriptnum}}').style.display = "none";
    })

    $('#infantCheck_{{$scriptnum}}').on('ifChecked', function () {
      document.getElementById('infant_form_{{$scriptnum}}').style.display = "block";
    })

    $('#infantCheck_{{$scriptnum++}}').on('ifUnchecked', function () {
      document.getElementById('infant_form_{{$scriptnum}}').style.display = "none";
    })
  @endforeach

  function dismiss() {
    if (confirm('Are you sure you want to dismiss it? This booking will be considered settled.')) {
      window.location.href = "/createbooking/dismiss/{{$row->bookingsID}}";
    }
  }

  function deleteroom(id) {
    if (confirm('Are you sure you want to delete this room?')) {
      post("{{ url('createbooking/deleteroom') }}"+"/"+id, "post");
    }
  }

  function post(path, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    let form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    let hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "_token");
    hiddenField.setAttribute("value", "{{ csrf_token()}}");

      form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();
  }
</script>
<div style="clear: both;"></div>
@if(session()->has('submit_payment_message'))
<script>
  alert({{session()->pull('submit_payment_message')}});
</script>
@endif

@stop