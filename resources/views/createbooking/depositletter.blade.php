<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{Lang::get('core.bookingno')}} {{-- {{ $row->bookingno}} --}} - {{ CNF_COMNAME }}</title>
<style type="text/css">
body,td,th {
	font-family: Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
	font-style: normal;
	font-size: 11px;
	color: #393939;
}
table.maintable {
    width: 100%;
}
tr { 
    text-transform: uppercase;
    }
th, td {
    border-bottom: 1px solid #ddd;
    height: 30px;
    text-align: left;
}
.gray {
  background-color: #efefef;
    }
.darkgray {
  background-color: #999; 
  color: #fff;   
 text-align: center;
    }
.fontsize-15{
  font-size: 15px;
}
</style>
</head>
  <body>
<table  class="maintable">
<tbody>
<tr>
<td width="60%" rowspan="4" valign="top">@if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO) && CNF_LOGO !='')
        <img style="height:70px" src="{{ asset('uploads/images/'.CNF_OWNER.'/'.CNF_LOGO)}}" />
        @else
        <img style="height:70px" src="{{ asset('mmb/images/logo.png')}}" />
        @endif </td>
<td width="40%" align="right">{{ CNF_COMNAME }}</td>
</tr>
<tr>
<td align="right">{{ CNF_ADDRESS }}</td>
</tr>
<tr>
<td align="right">{{ CNF_TEL }}</td>
</tr>
<tr>
<td align="right">{{ CNF_EMAIL }}</td>
</tr>
</tbody>
</table>
<div class="fontsize-15">
<b>{{Lang::get('core.date')}}:</b> {{ \Carbon::today()->format('d F Y') }}<br>
<br>
To:<br>
WHOM IT MAY CONCERN<br>
<br>
_____________________<br>
<br>
Mr./Ms./Mrs,<br>
<br>
<b><u>Letter of Confirmation for performing Umrah from {{ \Carbon::parse($booking->bookTour->tourdate->start)->format('d F Y') }} to {{ \Carbon::parse($booking->bookTour->tourdate->end)->format('d F Y') }}</u></b><br>
<br>
<p>2. We hereby confirm that the person stated below will be performing Umrah with our company. Below are the details of the confirmation:</p><br>
<div style="margin-left: 70px;">
  Name: {{ $traveller->nameandsurname.' '.$traveller->last_name }}<br>
  NRIC: {{ $traveller->NRIC }}<br>
  Flight Details:<br>
  @if($flight)
  <table style="font-size: 15px;">
    <tr>
      <td width="50px">{{ \Carbon::parse($flight_booking->departure_date)->format('d M') }}</td>
      <td width="50px">{{ $flight_booking->depart->sector }}</td>
      <td width="50px">{{ $flight_booking->depart->flight_number }}</td>
      <td width="50px">{{ $flight_booking->depart->dep_time }}-{{ $flight_booking->depart->arr_time }}</td>
    </tr>
    <tr>
      <td width="50px">{{ \Carbon::parse($flight_booking->return_date)->format('d M') }}</td>
      <td width="50px">{{ $flight_booking->return->sector }}</td>
      <td width="50px">{{ $flight_booking->return->flight_number }}</td>
      <td width="50px">{{ $flight_booking->return->dep_time }}-{{ $flight_booking->return->arr_time }}</td>
    </tr>
  </table>
  @else
    {{ \Carbon::parse($booking->bookTour->tourdate->start)->format('d M') }} - {{ \Carbon::parse($booking->bookTour->tourdate->end)->format('d M') }}<br>
    {{ $booking->bookTour->tour->sector }}<br>
  @endif
  <p><b><i>* all program proposals and flight dates are subject to change by airline.</i></b></p>
</div><br>

<p>3. Your consideration and approval for the stated person to perform umrah is much appreciated. Thank you.</p>
<br>
<b>With Thanks,</b><br>
<b>{{CNF_COMNAME}}</b>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<i>* this document is computer generated</i>
</div>

</body>