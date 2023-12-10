<!DOCTYPE html>
<html>
<head>
	<title></title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

	<style>
		body{
			font-size: 12px;
		}
		table{
			width: 100%;
		}
		table, th, td{
			border: 1px;
			border-style: solid;
		}
		th, td{
			padding-right: 5px;
			padding-left: 5px;
		}
		th{
			background-color: #D3D3D3;
		}
		.font-10{
			font-size: 10px;
		}
	</style>

</head>
<body>
	<table style="border-style: none">
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

	<table style="border-style: none">
		<tr>
			<td style="border-style: none; width: 150px;"><b>{{ Lang::get('core.booking_list_for') }}</b></td>
			<td style="border-style: none;">: {{ $tourdate->tour->tour_name }} ({{ $tourdate->tour_code }})</td>
		</tr>
		<tr>
			<td style="border-style: none;"><b>{{ Lang::get('core.tourcategory') }}</b></td>
			<td style="border-style: none;">: {{ $tourdate->tourcategory->tourcategoryname }}</td>
		</tr>
		<tr>
			<td style="border-style: none;"><b>{{ Lang::get('core.packagedate') }}</b></td>
			<td style="border-style: none;">: {{ \Carbon::parse($tourdate->start)->format('d M Y') }} - {{ \Carbon::parse($tourdate->end)->format('d M Y') }} </td>
		</tr>
	</table>
	<br>

	<table>
		<thead>
			<tr>
				<th>No.</th>
				<th>{{ Lang::get('core.bookingdate') }}</th>
				<th>{{ Lang::get('core.bookingid') }}</th>
				<th>{{ Lang::get('core.name') }}</th>
				<th>{{ Lang::get('core.phone') }}</th>
				<th>Pax</th>
				<th>{{ Lang::get('core.bookingvalue') }}</th>
				<th>{{ Lang::get('core.paymentamount') }}</th>
				<th>{{ Lang::get('core.status') }}</th>
			</tr>
		</thead>
		<tbody>
			<?php $num = 1; ?>
			@foreach($tourdate->booktours as $booktour)
				<?php $booking = $booktour->booking; ?>
				@if($booking)
					<tr>
						<td>{{ $num++ }}</td>
						<td>{{ \Carbon::parse($booking->created_at)->format('d M Y') }}</td>
						<td>{{ $booking->bookingno }}</td>
						<td>@if($booking->traveller) {{ $booking->traveller->fullname }} @else Traveller Not Found @endif</td>
						<td>@if($booking->traveller) {{ $booking->traveller->phone }} @else Traveller Not Found @endif</td>
						<td>{{ $booking->pax }}</td>
						<td>@if($booking->invoice) {{ $booking->invoice->InvTotal }} @else Invoice Not Found @endif</td>
						<td>@if($booking->invoice) {{ $booking->invoice->total_paid }} @else Invoice Not found @endif</td>
						<td>@if($booktour->status == 2) Pending @elseif($booktour->status == 1) Confirmed @else Cancelled @endif</td>
					</tr>
				@endif
			@endforeach
		</tbody>
	</table>
</body>
</html>