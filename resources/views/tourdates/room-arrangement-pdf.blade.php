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
			<td style="border-style: none; width: 150px;"><b>Booking List for</b></td>
			<td style="border-style: none;">: {{ $tourdate->tour->tour_name }} ({{ $tourdate->tour_code }})</td>
		</tr>
		<tr>
			<td style="border-style: none;"><b>Package Category</b></td>
			<td style="border-style: none;">: {{ $tourdate->tourcategory->tourcategoryname }}</td>
		</tr>
		<tr>
			<td style="border-style: none;"><b>Package Date</b></td>
			<td style="border-style: none;">: {{ \Carbon::parse($tourdate->start)->format('d M Y') }} - {{ \Carbon::parse($tourdate->end)->format('d M Y') }} </td>
		</tr>
	</table>
	<br>

	@foreach($rooms as $room)
		<h5>{{ App\Models\Bookroom::ROOM_TYPE_MAP[$room['room_type']] }}</h5>

		<table>
			<thead>
				<tr>
					<th width="30%">Booking No.</th>
					<th width="30%">Name</th>
					<th width="40%">Gender</th>
					<th width="40%">Passport</th>
				</tr>
			</thead>
			<tbody>
				@foreach($room['travellers'] as $traveller)
					<tr>
						<td>@if($traveller->tempBooking) {{ $traveller->tempBooking->bookingno }} @else Booking Not Found @endif</td>
						<td>{{ $traveller->fullname }}</td>
						<td>@if($traveller->gender === 'M') Male @elseif($traveller->gender === 'F') Female @else Gender not found @endif</td>
						<td>{{ $traveller->passportno }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endforeach
</body>
</html>