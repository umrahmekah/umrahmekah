
@extends('layouts.app')

@section('content')
<?php  
    use Carbon\Carbon;
    $payment = DB::table('invoice_payments')->where('invoiceID', $row->invoiceID )->sum('amount');
    $maxamount=$row->InvTotal-$payment;
?>
	<div class="box-header with-border">
				<div class="box-header-tools pull-left" >
			   		<a href="{{ url('invoice?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-left fa-2x"></i></a>
					@if($access['is_add'] ==1)
			   		<a href="{{ url('invoice/update/'.$id.'?return='.$return) }}" class="tips text-green" title="{{ Lang::get('core.btn_edit') }}"><i class="fa  fa-pencil fa-2x"></i></a>
					@endif 
          @if($invoice->booking)
            <a title="Set Discount" class="tips text-blue" data-toggle="modal" data-target="#set_discount"><i class="fa fa-tag fa-2x"></i></a>
          @endif
				</div>	
				<div class="box-header-tools pull-right " >
					<a href="{{ ($prevnext['prev'] != '' ? url('invoice/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips" title="Previous"><i class="fa fa-arrow-left fa-2x"></i>  </a>	
					<a href="{{ ($prevnext['next'] != '' ? url('invoice/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips" title="Next"> <i class="fa fa-arrow-right fa-2x"></i>  </a>
				</div> 
			</div>

<div class="box-body" >
<php></php>
    <section class="invoice">
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            @if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO) && CNF_LOGO !='')
              <img style="height:70px" src="{{ asset('uploads/images/'.CNF_OWNER.'/'.CNF_LOGO)}}" />
            @else
              <img style="height:70px" src="{{ asset('mmb/images/logo.png')}}" />
            @endif </td>
              <small class="pull-right">{{Lang::get('core.dateissued')}}: {{ SiteHelpers::TarihFormat($row->DateIssued)}}  </small>
          </h2>
        </div>
      </div>
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          <address>
            <strong>{{ CNF_COMNAME }}</strong><br>
            {{ CNF_ADDRESS }}<br>
            {{Lang::get('core.phone')}}: {{ CNF_TEL }}<br>
            {{Lang::get('core.email')}}: {{ CNF_EMAIL }}
          </address>
        </div>
        <div class="col-sm-4 invoice-col">
          <address>
            <strong>{{ $traveller->fullname }}</strong><br>
            {{ $traveller->address }} 
            {{ $traveller->city }} 
            @if($traveller->country){{ $traveller->country->country_name }}@endif <br>
            {{Lang::get('core.phone')}}: {{ $traveller->phone }}<br>
            {{Lang::get('core.email')}}: {{ $traveller->email }}
          </address>
        </div>
        <div class="col-sm-4 invoice-col">
          <h2>{{Lang::get('core.invoiceno')}}: {{ $row->invoiceID}}</h2>
          <b>{{Lang::get('core.bookingno')}}: @if($invoice->booking) {{ $invoice->booking->bookingno }} @else No Booking Found @endif </b><br>
          <b>{{Lang::get('core.duedate')}}: </b>{!! InvoiceStatus::Paymentstatus($row->DueDate) !!}<br>
          {!! InvoiceStatus::Payments($payment , $row->InvTotal) !!}
        </div>
      </div>
    
      <div class="row">
        <div class="col-xs-12 table-responsive">
        
            <table class="table table-striped">
			<thead>
				<tr>
                    <th width="150">{{Lang::get('core.productcode')}} </th>
					<th>{{Lang::get('core.product')}}</th>
                    <th width="50"> {{Lang::get('core.qty')}} </th>
					<th width="130"> {{Lang::get('core.price')}}  </th>
					<th width="130"> {{Lang::get('core.total')}} </th>
				</tr>
			</thead>
			<tbody>
				@foreach ($items as $child)
				<tr class="clone clonedInput">
					<td>{{ $child->Code}}</td>
					<td>{{ $child->Items}}</td>
                    <td>{{ $child->Qty}}</td>
					<td>{{ SiteHelpers::formatLookUp($row->currency,'currency','1:def_currency:currencyID:currency_sym|symbol') }} {{ $child->Amount}}</td>
					<td>{{ SiteHelpers::formatLookUp($row->currency,'currency','1:def_currency:currencyID:currency_sym|symbol') }} {{ $child->Qty * $child->Amount }} </td>
				</tr>
				@endforeach
<tr><td></td></tr>
                <tr>
                    <td colspan="3"></td>
					<td><b>{{Lang::get('core.subtotal')}}</b></td>
					<td>{{ SiteHelpers::formatLookUp($row->currency,'currency','1:def_currency:currencyID:currency_sym|symbol') }} {{ $row->Subtotal }}</td>
				</tr>
                @if ($row->discount !='0')
				<tr >
                    <td colspan="3"></td>
					<td><b>{{Lang::get('core.discount_per_pax')}}</b></td>
					<td>{{ SiteHelpers::formatLookUp($row->currency,'currency','1:def_currency:currencyID:currency_sym|symbol') }} {{ $row->discount}}</td>
				</tr>
                @endif
				@if ($row->tax !='0')
                <tr >
                    <td colspan="3"></td>
					<td><b>{{Lang::get('core.tax')}} ( {{ $row->tax}} % )</b></td>
					<td>{{ SiteHelpers::formatLookUp($row->currency,'currency','1:def_currency:currencyID:currency_sym|symbol') }} {{ ( $row->Subtotal - $row->discount ) * ($row->tax / 100) }} </td>
				</tr>
                @endif
				<tr >
                    <td colspan="3"></td>
					<td><b>{{Lang::get('core.total')}}</b></td>
					<td>{{ SiteHelpers::formatLookUp($row->currency,'currency','1:def_currency:currencyID:currency_sym|symbol') }} {{ $row->InvTotal}}</td>
				</tr>
              <tr>
                <td colspan="3"></td>
                <td><b>{{Lang::get('core.totalpayment')}}</b></td>
                <td>{{ SiteHelpers::formatLookUp($row->currency,'currency','1:def_currency:currencyID:currency_sym|symbol') }}<?php echo $payment ; ?> </td>
              </tr>
            @if ( $maxamount !='0')
                <tr>
                <td colspan="3"></td>
                <td><b>{{Lang::get('core.balancedue')}}</b></td>
                <td>{{ SiteHelpers::formatLookUp($row->currency,'currency','1:def_currency:currencyID:currency_sym|symbol') }} {{ $row->InvTotal - $payment }} </td>
              </tr>
                @endif
			</tbody>
		</table>
            
            <table class="table no-border ">
            <thead>
				<tr>
                    <th width="50"> </th>
                    <th ></th>
					<th width="130"></th>
					<th width="130"></th>
				</tr>
			</thead>

			<tbody>
				<tr>
                    <td colspan="1"></td>
                    <td>{{Lang::get('core.paymenttype')}}</td>
				</tr>
				<tr >
                    <td colspan="1"></td>
                    <td > {{Lang::get('core.notes')}}</td>
				</tr>
				<tr >
                    <td colspan="1"></td>
                    <td ><p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
              {{ $row->notes}}  
              @if(isset($gpn) && isset($gpn->txnResponse))
              <br><br>
                <strong>Gerbang Pembayaran Nasional</strong><br>
                <strong>Status</strong>: 
                  @if($gpn->txnResponse->txnStatus == 'Completed')
                    Berhasil
                  @elseif($gpn->txnResponse->txnStatus == 'Voided')
                    Void
                  @else
                    Gagal
                  @endif
                <br>
                <strong>Deskripsi</strong>: 
                @if($gpn->txnResponse->txnStatus == 'Completed')
                  Pembayaran telah diterima
                @elseif($gpn->txnResponse->txnStatus == 'Voided')
                  Pembayaran telah dibatalkan
                @else
                  Pembayaran gagal
                @endif
              @endif         
                        </p></td>
				</tr>

			</tbody>
		</table>
          
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          <p class="lead"> </p>
        </div>
      </div>
      <div class="row no-print">
        <div class="col-xs-12">
        @if ( $maxamount > 0 && $invoice->booking)
          <a href="{{ url('payments/update?travellerID='.$row->travellerID.'&maxamount='.$maxamount)}}" title="{{Lang::get('core.addnewpayment')}}" onclick="MmbModal(this.href,'{{Lang::get('core.addnewpayment')}}'); return false;" target="_blank">
          <button type="button" class="btn btn-success pull-right" style="margin-right: 5px;"><i class="fa fa-cc-visa fa-2x"></i> {{Lang::get('core.addnewpayment')}}
          </button></a>
        @endif

          <a href="{{ URL::to('invoice/show/'.$id.'?pdf=true') }}" target="_blank"> <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-file-pdf-o fa-2x"></i> {{Lang::get('core.createpdf')}}
          </button></a>
        </div>
      </div>
    </section> 
			</div>
<div class="row" style="margin: 0px 36px;">
  <div class="box box-warning">
    <div class="box-header with-border">
      History
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
          Invoice created @if($invoice->booking) for booking {{ $invoice->booking->bookingno }} @endif @if($invoice->entryByUser) by {{ $invoice->entryByUser->fullName }} @endif at {{ $invoice->created_at->format('h:i:s A, d M Y') }}
        </div>
        @if($invoice->discount)
          <div class="col-md-12">
            Discount given @if($invoice->discountByUser) by {{ $invoice->discountByUser->fullName }} @endif for {{CURRENCY_SYMBOLS}}{{ $invoice->discount }} per pax @if($invoice->booking) ({{CURRENCY_SYMBOLS}}{{ $invoice->discount*$invoice->booking->pax }}) @endif at @if($invoice->discount_at) {{ $invoice->discount_at->format('h:i:s A, d M Y') }} @else {{ \Carbon::parse($invoice->created_at)->format('h:i:s A, d M Y') }} @endif
          </div>
        @endif
        @foreach($invoice->payments as $payment)
          <div class="col-md-12">
            Payment {{CURRENCY_SYMBOLS}}{{ $payment->amount }} added @if($payment->entryByUser) by {{ $payment->entryByUser->fullName }} @endif at {{ $payment->created_at->format('h:i:s A, d M Y') }}
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<div class="modal inmodal" id="set_discount" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content animated bounceInRight">
        <form method="POST" action="/invoice/setdiscount">
          <input type="hidden" name="invoice_id" value="{{$row->invoiceID}}">
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
                        <input type="number" name="discount" class="form-control" value="{{ $row->discount }}">
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

	  
@stop