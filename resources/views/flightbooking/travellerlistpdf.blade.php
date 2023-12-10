<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
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

	<br>

	<p>PNR: {{$row->pnr}}</p>

	<br>

	{{-- <table>
		<tr>
			<th>Date: </th>
			<td></td>
		</tr>
		<tr></tr>
	</table> --}}

	<table border="1">
		<tr>
			<td style="border: 0px; width: 100px;">{{$departMatch->sector}}</td>
			<td style="border: 0px; width: 100px;">{{strtoupper(\Carbon::parse($row->departure_date)->format('d M'))}}</td>
			<td style="border: 0px; width: 100px;">{{$departMatch->flight_number}}</td>
			<td style="border: 0px; width: 100px;">{{$departMatch->dep_time}}/{{$departMatch->arr_time}}</td>
		</tr>
		<tr>
			<td style="border: 0px; width: 100px;">{{$returnMatch->sector}}</td>
			<td style="border: 0px; width: 100px;">{{strtoupper(\Carbon::parse($row->return_date)->format('d M'))}}</td>
			<td style="border: 0px; width: 100px;">{{$returnMatch->flight_number}}</td>
			<td style="border: 0px; width: 100px;">{{$returnMatch->dep_time}}/{{$returnMatch->arr_time}}</td>
		</tr>
	</table>

	<br>
	
	<?php $i = 1; ?>
 	<table border="1">
		<thead>
	    	<tr>
	    		<th style="border: 1px solid #ddd !important">No.</th>
	    		<th style="border: 1px solid #ddd !important">APIS Data</th>
	    	</tr>
		</thead>
		<tbody>
			@foreach($travellers as $traveller)
			<tr>
	    		<td style="border: 1px solid #ddd !important">{{$i}}</td>
	    		<td style="border: 1px solid #ddd !important; font-size: 10;">SRDOCSSVHK1-P-MAS-@if($traveller->passportno){{$traveller->passportno}}@else<a href="#" style="color: red;">Please Update Passport</a>@endif-MAS-@if($traveller->dateofbirth){{strtoupper(\Carbon::parse($traveller->dateofbirth)->format('dMy'))}}@else<a href="#" style="color: red;">Please Update Date of Birth</a>@endif-@if($traveller->gender){{$traveller->gender}}@else<a href="#" style="color: red;">Please Update Gender</a>@endif-@if($traveller->passportexpiry){{strtoupper(\Carbon::parse($traveller->passportexpiry)->format('dMy'))}}@else<a href="#" style="color: red;">Please Update Passport Expiry Date</a>@endif-@if($traveller->nameandsurname){{str_replace(' ', '', strtoupper($traveller->nameandsurname))}}@else<a href="#" style="color: red;">Please Update First Name</a>@endif/@if($traveller->last_name){{str_replace(' ', '', strtoupper($traveller->last_name))}}@else<a href="#" style="color: red;">Please Update Last Name</a>@endif/P{{$i++}}</td>
	    	</tr>
	    	@endforeach
		</tbody>
	</table>

</body>
</html>