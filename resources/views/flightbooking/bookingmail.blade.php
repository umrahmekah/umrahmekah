<meta charset="utf-8">

<table>
	<tr>
		<td>
			@if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO) && CNF_LOGO !='')
	        <img style="height:70px; padding-right: 50px !important;" src="{{ asset('uploads/images/'.CNF_OWNER.'/'.CNF_LOGO)}}" />
	        @else
	        <img style="height:70px; padding-right: 50px !important;" src="{{ public_path() }}/mmb/images/logo.png" />
	        @endif
	    </td>
	    <td>
	    	<h1 style="margin: 0px;">
	    		{{ CNF_COMNAME }}
	    	</h1>
	    	{{ CNF_ADDRESS }}<br>
	    	TEL: {{ CNF_TEL }}   EMAIL: {{ CNF_EMAIL }}
	    </td>
	</tr>
</table>

<hr>

<p> {{$bodyMessage}} </p>
<h4>Departs:</h4>
<table border="1">
	<tr>
		<th style="padding-right: 40px;">Fligt No</th>
		<th style="padding-right: 40px;">Sector</th>
		<th style="padding-right: 40px;">Day</th>
		<th style="padding-right: 40px;">Departure / Arrival</th>
		<th style="padding-right: 40px;">Number of Pax</th>
	</tr>
	<tr>
		<td>{{$depart_flight->flight_number}}</td>
		<td>{{$depart_flight->sector}}</td>
		<td>{{$depart_flight->day}}</td>
		<td>{{$depart_flight->dep_time}} / {{$depart_flight->arr_time}}</td>
		<td>{{$pax}}</td>
	</tr>
</table>
<h4>Returns:</h4>
<table border="1">
	<tr>
		<th style="padding-right: 40px;">Fligt No</th>
		<th style="padding-right: 40px;">Sector</th>
		<th style="padding-right: 40px;">Day</th>
		<th style="padding-right: 40px;">Departure / Arrival</th>
		<th style="padding-right: 40px;">Number of Pax</th>
	</tr>
	<tr>
		<td>{{$return_flight->flight_number}}</td>
		<td>{{$return_flight->sector}}</td>
		<td>{{$return_flight->day}}</td>
		<td>{{$return_flight->dep_time}} / {{$return_flight->arr_time}}</td>
		<td>{{$pax}}</td>
	</tr>
</table>