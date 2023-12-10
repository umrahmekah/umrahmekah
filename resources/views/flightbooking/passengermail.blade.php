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