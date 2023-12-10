<?php
use \App\Http\Controllers\TourdatesController;
?>
<style>
* {
  box-sizing: border-box;
}

body {
  font-family:arial, sans-serif;
  font-size: 11px;
}

h1 {
  font-size: 12px;
  font-weight: normal;
}

table {
  width: 100%;
  display: table;
  border-collapse: collapse;
  text-align: left;
}

table td {
  padding: 2px;
  border: 1px solid #000000;
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
<table>
<tbody>
<tr>
<td align="left" colspan="9">
  {{ Lang::get('core.tourname') }}: {{ SiteHelpers::formatLookUp($row->tourID,'tourID','1:tours:tourID:tour_name') }}<br>
  {{ Lang::get('core.tourdate') }}: {{ SiteHelpers::TarihFormat($row->start)}} / {{ SiteHelpers::TarihFormat($row->end)}}<br>
  {{ Lang::get('core.tourcode') }}:{{ $row->tour_code}}<br>
  {{ Lang::get('core.guide') }}: {{ SiteHelpers::formatLookUp($row->guideID,'guideID','1:guides:guideID:name') }}<br>
  {{ Lang::get('core.totalpassenger') }}: {{ $tourdate->pax }} pax
</td>
</tr>
<tr>
<td></td>
<td colspan="3" align=center > {{ Lang::get('core.emergencydetails') }}</td>
<td colspan="3" align=center > {{ Lang::get('core.insurancedetails') }}</td>
<td colspan="2" align=center > {{ Lang::get('core.specialreq') }}</td>
</tr>
<tr>
<td style='width:20%'><strong>{{ Lang::get('core.passengerlist') }}</strong></td>
<td style='width:10%'><strong>{{ Lang::get('core.emergencycontact') }}</strong></td>
<td style='width:10%'><strong>{{ Lang::get('core.email') }}</strong></td>
<td style='width:10%'><strong>{{ Lang::get('core.phone') }}</strong></td>
<td style='width:10%'><strong>{{ Lang::get('core.company') }}</strong></td>
<td style='width:10%'><strong>{{ Lang::get('core.policyno') }}</strong> </td>
<td style='width:10%'><strong>{{ Lang::get('core.phone') }}</strong></td>
<td style='width:10%'><strong>{{ Lang::get('core.bedconfiguration') }}</strong></td>
<td style='width:10%'><strong>{{ Lang::get('core.dietaryreq') }}</strong></td>
</tr>
                        @foreach($tourdate->booktours as $booktour)
                          <?php $booking = $booktour->booking; ?>
                          @if($booking)
                            @foreach($booking->bookRoom as $room)
                        {!! TourdatesController::travelersDetailemergency($room->travellers) !!}
                        @endforeach
                      @endif
                    @endforeach

</tbody>
</table>