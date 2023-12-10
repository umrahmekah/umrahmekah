<?php  
    use Carbon\Carbon;
    $payment = DB::table('invoice_payments')->where('invoiceID', $row->invoiceID )->sum('amount');
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{ CNF_COMNAME }}</title>
<style type="text/css">
body {
  position: relative;
  color: #555555;
  font-size: 14px; 
  font-family: Lato;
}

table.maintable {
    width: 100%;
}
table.gridtable {
    width: 100%;
    border-width: 0px;
}
table.gridtable th {
	padding: 8px;
}
table.gridtable td {
	padding: 8px;
}

.green {font-family: 'Montserrat', sans-serif;
  font-size: 15px; color: #ffffff ; background-color: #8bc34a; }
.gray {font-family: 'Montserrat', sans-serif;
  font-size: 13px; color: #000000 ; background-color: #efefef; }
</style>
  </head>
  <body>
  <table style="border-style: none; width: 100%;">
    <tr>
      <td style="border-style: none">
        @if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO) && CNF_LOGO !='')
          <img style="max-width: 250px" src="{{ asset('uploads/images/'.CNF_OWNER.'/'.CNF_LOGO)}}" />
        @else
          <img style="max-width: 250px" src="{{ asset('mmb/images/logo.png')}}" />
        @endif 
      </td>
      <td style="border-style: none">
        <table style="border-style: none">
          <tr>
            <td align="right" style="font-size: 25px; vertical-align: bottom; border-style: none"><strong>{{ CNF_COMNAME }}</strong></td>
          </tr>
          <tr>
            <td align="right" style="border-style: none">{{ CNF_ADDRESS }}</td>
          </tr>

          <tr>
            <td align="right" style="border-style: none">{{ CNF_TEL }}</td>
          </tr>

          <tr>
            <td align="right" style="border-style: none">{{ CNF_EMAIL }}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<hr>
<br>
<table width="100%">
  <tr>
    <td width="65%">
      <table class="maintable">
        <tbody>
          <tr>
            <td align="left" style="font-family: 'Montserrat', sans-serif;
              font-size: 15px;"><b>{{ $traveller->fullname }}</b></td>
          </tr>
          <tr>
            <td align="left">{{ $traveller->address }}</td>
          </tr>
          <tr>
            <td align="left"> {{ $traveller->city }} 
            @if($traveller->country){{ $traveller->country->country_name }}@endif</td>
          </tr>
          <tr>
            <td align="left">{{ $traveller->email }}</td>
          </tr>
          <tr>
            <td align="left">{{ $traveller->phone }}</td>
          </tr>
        </tbody>
      </table>
    </td>
    <td>
      <table class="maintable">
        <tbody>
          <tr>
            <td align="right" style="font-family: 'Montserrat', sans-serif;
              font-size: 15px;">{{Lang::get('core.invoiceno')}} : </td>
            <td align="right" style="font-family: 'Montserrat', sans-serif;
              font-size: 15px;">{{ $row->invoiceID}}</td>
          </tr>
          <tr>
            <td align="right">{{Lang::get('core.bookingno')}} : </td>
            <td align="right">{{ $booking->bookingno }}</td>
          </tr>
          <tr>
            <td align="right">{{Lang::get('core.dateissued')}} : </td>
            <td align="right"> {{ SiteHelpers::TarihFormat($row->DateIssued)}}</td>
          </tr>
          <tr>
            <td align="right">{{Lang::get('core.duedate')}} : </td>
            <td align="right">{{ SiteHelpers::TarihFormat($row->DueDate)}}</td>
          </tr>
        </tbody>
      </table>
    </td>
  </tr>
</table>
      
    <br>    
      <table class="gridtable">
        <thead>
          <tr class="green">
            <td >{{Lang::get('core.productcode')}}</td>
            <td >{{Lang::get('core.product')}}</td>
            <td >{{Lang::get('core.price')}}</td>
            <td >{{Lang::get('core.qty')}}</td>
            <td >{{Lang::get('core.total')}}</td>
          </tr>
        </thead>
        <tbody>
            @foreach ($items as $child)

          <tr>
            <td >{{ $child->Code}}</td>
            <td >{{ $child->Items}}</td>
            <td >{{ \DB::table('def_currency')->where('currencyID', CNF_CURRENCY)->first()->symbol ?? null }} {{ number_format($child->Amount, 2) }}</td>
            <td align="center">{{ $child->Qty}}</td>
            <td >{{ \DB::table('def_currency')->where('currencyID', CNF_CURRENCY)->first()->symbol ?? null }} {{ number_format($child->Qty * $child->Amount, 2) }}</td>
          </tr>
            @endforeach
          <tr>
            <td class="gray" colspan="4" align="right" >{{Lang::get('core.subtotal')}}</td>
            <td class="green">{{ \DB::table('def_currency')->where('currencyID', CNF_CURRENCY)->first()->symbol ?? null }} {{ number_format($row->Subtotal, 2) }}</td>
          </tr>
        @if ($row->discount !='0')
            <tr>
            <td class="gray"colspan="4" align="right">{{Lang::get('core.discount')}}</td>
            <td class="green">{{ \DB::table('def_currency')->where('currencyID', CNF_CURRENCY)->first()->symbol ?? null }} {{ number_format($row->discount, 2) }}</td>
          </tr>
        @endif

            @if ($row->tax !='0')
            <tr >
            <td class="gray"colspan="4" align="right">{{Lang::get('core.tax')}} ( {{ $row->tax}} % )</td>
            <td class="green">{{ \DB::table('def_currency')->where('currencyID', CNF_CURRENCY)->first()->symbol ?? null }} {{ number_format(( $row->Subtotal - $row->discount ) * ($row->tax / 100), 2) }}</td>
            </tr>
                @endif
          <tr>
            <td class="gray" colspan="4" align="right">{{Lang::get('core.total')}}</td>
            <td class="green">{{ \DB::table('def_currency')->where('currencyID', CNF_CURRENCY)->first()->symbol ?? null }} {{ number_format($row->InvTotal, 2)}}</td>
          </tr>
         @if ($payment >'0')
          <tr>
            <td class="gray" colspan="4" align="right">{{Lang::get('core.totalpayment')}}</td>
            <td class="green">{{ \DB::table('def_currency')->where('currencyID', CNF_CURRENCY)->first()->symbol ?? null }} <?php echo number_format($payment, 2) ; ?></td>
          </tr>
        @endif
            @if ( ( $row->InvTotal - $payment ) !='0')
          <tr>
            <td class="gray"colspan="4" align="right">{{Lang::get('core.balancedue')}}</td>
            <td class="green">{{ \DB::table('def_currency')->where('currencyID', CNF_CURRENCY)->first()->symbol ?? null }} {{ number_format($row->InvTotal - $payment, 2) }}</td>
          </tr>
            @endif
          <tr>
            <td >{{Lang::get('core.paymenttype')}}</td>
            <td colspan="4">{!! SiteHelpers::showPaymentOptions($row->payment_type) !!}</td>
          </tr>
          <tr>
            <td >{{Lang::get('core.notes')}}</td>
            <td colspan="4">{{ $row->notes}}</td>
          </tr>

          </tbody>
      </table>