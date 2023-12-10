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
				@if(file_exists(public_path().'/mmb/images/'.CNF_LOGO) && CNF_LOGO !='')
					<img style="max-width: 250px" src="{{ asset('mmb/images/'.CNF_LOGO)}}" />
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

	<h3 style="margin-bottom: 0px">{{ Lang::get('core.package_sales_and_collection_report') }}</h3>
    <h4>{{ Lang::get('core.agentdetails') }}</h4>
    <br>
    @foreach($results as $agent)
	    <h5>{{ $agent->agency_name }}</h5>
		<table>
			<thead>
				<tr>
					<th>{{ Lang::get('core.date') }}</th>
	                <th>{{ Lang::get('core.booking') }} #</th>
	                <th>{{ Lang::get('core.package') }}</th>
	                <th>{{ Lang::get('core.total_sales') }}</th>
	                <th>{{ Lang::get('core.payment_status') }}</th>
	                <th>{{ Lang::get('core.commission') }}</th>
				</tr>
			</thead>
			<tbody>
				@foreach($agent->bookings as $booking)
	                <tr>
	                    <td>{{ $booking->created_at->format('d M Y') }}</td>
	                    <td>{{ $booking->bookingno }}</td>
	                    <td>@if($booking->bookTour) {{ $booking->bookTour->tour->tour_name }} @else No Tour @endif</td>
	                    <td>@if($booking->invoice) {{ number_format($booking->invoice->InvTotal) }} @else No Invoice @endif</td>
	                    <td>@if($booking->invoice) {{ $booking->invoice->payStatus }} @else No Invoice @endif</td>
	                    <td>{{ number_format($booking->commissions) }}</td>
	                </tr>
	            @endforeach
			</tbody>
		</table>
		<hr>
	@endforeach
</body>
</html>