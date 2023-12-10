<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<p>Assalamua'laikum {{$traveller->nameandsurname}}</p>
<br>
<p>Kami di sini ingin mengingatkan bahawa anda mempunyai baki tertunggak untuk tempahan anda di {{ CNF_COMNAME }}.</p>

<br>
<p>Berikut adalah butirannya.</p>
<br>

<table>
	<tr>
		<td>Website : </td>
		<td><a href="{{CNF_DOMAIN}}">{{CNF_DOMAIN}}</a></td>
	</tr>
	<tr>
		<td>Nama Pakej </td>
		<td>: {{ $booking->bookTour->tour->tour_name }} ({{ $booking->bookTour->tourdate->tour_code }})</td>
	</tr>
	<tr>
		<td>Nombor Booking </td>
		<td>: {{$booking->bookingno}}</td>
	</tr>
	<tr>
		<td>Jumlah Bayaran Terdahulu </td>
		<td>: {{CURRENCY_SYMBOLS}} {{$booking->invoice->totalPaid}}</td>
	</tr>
	<tr>
		<td>Baki Tertunggak </td>
		<td>: {{CURRENCY_SYMBOLS}} {{$booking->invoice->balance}}</td>
	</tr>
	<tr>
		<td>Tarikh Akhir </td>
		<td>: {{\Carbon::parse($invoice->DueDate)->format('d F Y')}}</td>
	</tr>
</table>
<br>
<p>Sila jelaskan tunggakan sebelum tarikh akhir.</p>
<br>
<p>{{$owner->name}}</p>
<p>{{$owner->address}}</p>
<p>{{$owner->telephone}}</p>
<p>{{$owner->email}}</p>
</body>
</html>