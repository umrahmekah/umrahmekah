@if(empty($excel))
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
				@if(empty($excel))
					@if(file_exists(public_path().'/mmb/images/'.CNF_LOGO) && CNF_LOGO !='')
						<img style="max-width: 250px" src="{{ asset('mmb/images/'.CNF_LOGO)}}" />
			        @else
			        	<img style="max-width: 250px" src="{{ asset('mmb/images/logo.png')}}" />
			        @endif
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
@endif

	<table style="border-style: none">
		<tr>
			<td style="border-style: none; @if(empty($excel)) width: 150px; @endif"><b>{{ Lang::get('core.masterlistfor') }}</b></td>
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
				<th>{{ Lang::get('core.firstname') }}</th>
				<th>{{ Lang::get('core.lastname') }}</th>
				<th>{{ Lang::get('core.tel') }}</th>
				<th>{{ Lang::get('core.nric') }}</th>
				<th>{{ Lang::get('core.dateofbirth') }}</th>
				<th>{{ Lang::get('core.nationality') }}</th>
				<th>{{ Lang::get('core.passportno') }}</th>
				<th>{{ Lang::get('core.dateofexpiry') }}</th>
				<th>{{ Lang::get('core.dateissued') }}</th>
				<th>{{ Lang::get('core.place_made') }}</th>
				<th>{{ Lang::get('core.countryofissue') }}</th>
				<th>Sex</th>
			</tr>
		</thead>
		<tbody>
			<?php $num = 1; ?>
			@foreach($tourdate->booktours as $booktour)
				<?php $booking = $booktour->booking; ?>
				@if($booking)
					@foreach($booking->bookRoom as $room)
						@foreach($room->travellerList as $traveller)
							<tr>
								<td>{{ $num++ }}</td>
								@if($traveller->nameandsurname)
									<td>{{ $traveller->nameandsurname }}</td>
								@else
									<td style="color: red">{{ Lang::get('core.firstnamenotfound') }}</td>
								@endif
								@if($traveller->last_name)
									<td>{{ $traveller->last_name }}</td>
								@else
									<td style="color: red">{{ Lang::get('core.lastnamenotfound') }}</td>
								@endif
								<td>{{ $traveller->phone }}</td>
								@if($traveller->NRIC)
									<td>{{ $traveller->NRIC }}</td>
								@else
									<td style="color: red">{{ Lang::get('core.nricnotfound') }}</td>
								@endif
								@if($traveller->dateofbirth)
									<td>{{ $traveller->dateofbirth }}</td>
								@else
									<td style="color: red">{{ Lang::get('core.dobnotfound') }}</td>
								@endif
								@if($traveller->nationality)
									<td>{{ $traveller->nationalityCountry->country_name }}</td>
								@else
									<td style="color: red">{{ Lang::get('core.nationalitynotfound') }}</td>
								@endif
								@if($traveller->passportno)
									<td>{{ $traveller->passportno }}</td>
								@else
									<td style="color: red">{{ Lang::get('core.passportnumbernotfound') }}</td>
								@endif
								@if($traveller->passportexpiry)
									<td>{{ $traveller->passportexpiry }}</td>
								@else
									<td style="color: red">{{ Lang::get('core.expirydatenotfound') }}</td>
								@endif
								@if($traveller->passportissue)
									<td>{{ $traveller->passportissue }}</td>
								@else
									<td style="color: red">{{ Lang::get('core.issuedatenotfound') }}</td>
								@endif
								@if($traveller->passport_place_made)
									<td>{{ $traveller->passport_place_made }}</td>
								@else
									<td style="color: red">{{ Lang::get('core.issueplacenotfound') }}</td>
								@endif
								@if($traveller->passportcountry)
									<td>{{ $traveller->passportCountry->country_name }}</td>
								@else
									<td style="color: red">{{ Lang::get('core.issuecountrynotfound') }}</td>
								@endif
								@if($traveller->gender)
									<td>{{ $traveller->gender }}</td>
								@else
									<td style="color: red">{{ Lang::get('core.sexnotfound') }}</td>
								@endif
							</tr>
						@endforeach
					@endforeach
				@endif
			@endforeach
		</tbody>
	</table>
@if(empty($excel))
</body>
</html>
@endif