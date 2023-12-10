<!DOCTYPE html>
<html>
<head>
	<title></title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>

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

	<div class="box-body" >

		<div class="row">
			<div class="col-md-12">
				<table>
                    <thead>
                    <tr>
                        <th>PIF NO</th>
                        <th>GROUP NAME</th>
                        <th>LEADER NAME</th>
                        <th>NO OF PAX</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{sprintf('%04d',$piform->pif_number)}}</td>
                        <td>{{$piform->group_name}}</td>
                        <td>@if($piform->leader){{$piform->leader->name}}@endif</td>
                        <td style="padding: 0px">
                        	<table>
                        		<thead>
                        			<tr>
                        				<th>Adt</th>
                        				<th>Chd</th>
                        				<th>Inf</th>
                        				<th>Total</th>
                        			</tr>
                        		</thead>
                        		<tbody>
                        			<tr>
                        				<td>{{$pax['adults']}}</td>
                        				<td>{{$pax['children']}}</td>
                        				<td>{{$pax['infants']}}</td>
                        				<td>{{$pax['total']}}</td>
                        			</tr>
                        		</tbody>
                        	</table>
                        </td>
                    </tr>
                    </tbody>
                </table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4>Flight Schedule</h4>
				@if($flight)
					<table>
	                    <thead>
	                    <tr>
	                        <th>CARRIER</th>
	                        <th>FROM / TO</th>
	                        <th>DATE</th>
	                        <th>ETD</th>
	                        <th>ETA</th>
	                        <th>PNR</th>
	                    </tr>
	                    </thead>
	                    <tbody>
	                    <tr>
	                        <td>{{$flight->depart->flightDate->flight_company}}</td>
	                        <td>{{$flight->depart->sector}}</td>
	                        <td>{{$flight->departure_date}}</td>
	                        <td>{{$flight->depart->dep_time}}</td>
	                        <td>{{$flight->depart->arr_time}}</td>
	                        <td>{{$flight->pnr}}</td>
	                    </tr>
	                    <tr>
	                        <td>{{$flight->return->flightDate->flight_company}}</td>
	                        <td>{{$flight->return->sector}}</td>
	                        <td>{{$flight->return_date}}</td>
	                        <td>{{$flight->return->dep_time}}</td>
	                        <td>{{$flight->return->arr_time}}</td>
	                        <td>{{$flight->pnr}}</td>
	                    </tr>
	                    </tbody>
	                </table>
                @else
                	<p>There's no confirmed flight booking found. This section will be automatically updated once flight booking have been made for this package.</p>
                @endif
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4>Accomodation</h4>
				<table class="font-10">
                    <thead>
                    <tr>
                        <th>CITY</th>
                        <th>HOTEL</th>
                        <th>CHECK IN</th>
                        <th>CHECK OUT</th>
                        <th>DURATION</th>
                        <th>TWO BED</th>
                        <th>THREE BED</th>
                        <th>FOUR BED</th>
                        <th>FIVE BED</th>
                        <th>SIX BED</th>
                        <th>BOOKING BY</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($piform->accomodations as $accomodation)
                    <tr>
                        <td>{{$accomodation->hotel->city->city_name}}</td>
                        <td>{{$accomodation->hotel->hotel_name}}</td>
                        <?php $check_in = \Carbon::parse($accomodation->check_in); ?>
                        <td>{{$check_in->format('d M')}}</td>
                        <?php $check_out = \Carbon::parse($accomodation->check_out); ?>
                        <td>{{$check_out->format('d M')}}</td>
                        <td>{{$check_out->diffInDays($check_in)}} Days {{$check_out->diffInDays($check_in)-1}} Nights</td>
                        <td>{{$accomodation->double}}</td>
                        <td>{{$accomodation->triple}}</td>
                        <td>{{$accomodation->quad}}</td>
                        <td>{{$accomodation->quint}}</td>
                        <td>{{$accomodation->sext}}</td>
                        <td>{{CNF_COMNAME}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4>Ziarah</h4>
				<table>
                    <thead>
                    <tr>
                        <th>CITY</th>
                        <th>DATE</th>
                        <th>TIME</th>
                        <th>TRANSPORT</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($piform->ziarahs as $ziarah)
                    <tr>
                        <td>{{$ziarah->city}}</td>
                        <td>{{\Carbon::parse($ziarah->date)->format('d M')}}</td>
                        <td>{{$ziarah->time}}</td>
                        <td>{{$ziarah->transportsupplier->name}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4>Transportation</h4>
				<table>
                    <thead>
                    <tr>
                        <th>FROM</th>
                        <th>TO</th>
                        <th>DATE</th>
                        <th>TIME</th>
                        <th>REMARK</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($piform->transportations as $transportation)
                    <tr>
                        <td>{{$transportation->from}}</td>
                        <td>{{$transportation->to}}</td>
                        <td>{{\Carbon::parse($transportation->date)->format('d M')}}</td>
                        <td>{{$transportation->time}}</td>
                        <td>{{$transportation->remarks}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4>Local Contact Person</h4>
				<table>
                    <tbody>
                    @foreach($piform->localContacts as $lc)
                    <tr>
                    	<td>{{$lc->name}}</td>
                        <td>{{$lc->contact}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4>Remark</h4>
				<table>
                    <tbody>
                    <?php $num = 1;?>
                    @foreach($piform->remarks as $remark)
                    <tr>
                    	<td>{{$num++}}</td>
                        <td>{{$remark->remark}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
			</div>
		</div>
	</div>

</body>
</html>