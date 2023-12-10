<!DOCTYPE html>
<html>
<head>
	<title></title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
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
		.ptb5{
			padding-top: 5px;
			padding-bottom: 5px;
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

	<table style="border-style: none; border: 0px; padding: 0px;">
		<tr style="border: 0px;">
			<td style="border-style: none; width: 65%; border: 0px; padding-left: 0px;">
				<table>
					<tr>
						<th>Butiran Tempahan</th>
					</tr>
					<tr>
						<td class="ptb5">
							<table style="border-style: none;">
								<tr>
									<th style="border-style: none; background-color: transparent;">
										Tarikh Tempahan
									</th>
									<td style="border-style: none">
										: {{ $row->bookingDate }}
									</td>
								</tr>
								<tr>
									<th style="border-style: none; background-color: transparent;">
										Nama
									</th>
									<td style="border-style: none">
										: {{ $row->traveller->fullname }}
									</td>
								</tr>
								<tr>
									<th style="border-style: none; background-color: transparent;">
										Alamat
									</th>
									<td style="border-style: none">
										: {{ $row->traveller->fullAddress }}
									</td>
								</tr>
								<tr>
									<th style="border-style: none; background-color: transparent;">
										No. Telefon
									</th>
									<td style="border-style: none">
										: {{ $row->traveller->phone }}
									</td>
								</tr>
								<tr>
									<th style="border-style: none; background-color: transparent;">
										Email
									</th>
									<td style="border-style: none">
										: {{ $row->traveller->email }}
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
			<td style="border-style: none; border: 0px;" align="center">
					<p>
						Booking .no<br>
						<span style="font-weight: bold; font-size: 30px;">{{ $row->bookingno }}</span>
					</p>
			</td>
		</tr>
	</table>
	<br>

	<table>
		<tr>
			<th>Butiran Pakej</th>
		</tr>
		<tr>
			<td class="ptb5">
				<table style="border-style: none;">
					<tr>
						<th style="border-style: none; background-color: transparent; width: 150px;">
							Nama Pakej
						</th>
						<td style="border-style: none">
							: {{ $row->bookTour->tour->tour_name }}
						</td>
					</tr>
					<tr>
						<th style="border-style: none; background-color: transparent;">
							Kod Pakej
						</th>
						<td style="border-style: none">
							: {{ $row->bookTour->tourdate->tour_code }}
						</td>
					</tr>
					<tr>
						<th style="border-style: none; background-color: transparent;">
							Tarikh Pelepasan
						</th>
						<td style="border-style: none">
							: {{ \Carbon::parse($row->bookTour->tourdate->start)->format('d M Y') }}
						</td>
					</tr>
					<tr>
						<th style="border-style: none; background-color: transparent;">
							Tarikh Pulang
						</th>
						<td style="border-style: none">
							: {{ \Carbon::parse($row->bookTour->tourdate->end)->format('d M Y') }}
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>

	<table>
		<tr>
			<th>Butiran Bilik</th>
		</tr>
		<tr>
			<td class="ptb5">
				<table style="border-style: none;">
					@foreach($row->paxPerRoomtype as $key => $pax)
						@if($pax)
							<tr>
								<th style="border-style: none; background-color: transparent; width: 150px;">
									{{ App\Models\Bookroom::ROOM_TYPE_MAP_MALAY[$key] }}
								</th>
								<td style="border-style: none">
									: {{ $pax }} pax
								</td>
							</tr>
						@endif
					@endforeach
				</table>
			</td>
		</tr>
	</table>
	<br>
	
	<table>
		<tr>
			<th>Senarai Jemaah</th>
		</tr>
		<tr>
			<td class="ptb5">
				<ol style="padding-left: 20px;">
					@foreach($row->bookRoom as $room)
						@foreach($room->travellerLIst as $traveller)
							<li>{{ $traveller->fullname }}</li>
						@endforeach
					@endforeach
				</ol>
			</td>
		</tr>
	</table>

	<hr>

	{{ $row->bookTour->tour->tandc->title }}<br>
	{!! $row->bookTour->tour->tandc->tandc !!}

	<br>

	<span>I have read and agreed to the terms and condition set forth on <b>{{ $row->created_at->format('d M Y') }}</b> at <b>{{ $row->created_at->format('h:i:s A') }}</b> for this booking.</span>

	
	
</body>
</html>