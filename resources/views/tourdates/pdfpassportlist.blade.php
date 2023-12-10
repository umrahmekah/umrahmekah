<?php
use \App\Http\Controllers\TourdatesController;
?>
<style>
* {
  box-sizing: border-box;
}

body {
  font-family:sans-serif;
  font-size: 12px;
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
                @if($total==0)
{{ Lang::get('core.nobookingmade') }}
                @else
                            <h2 align="center">{{ Lang::get('core.passportlist') }}
</h2>

                <table>
                <tbody>

                <tr>
                  <td align="left" style="padding-left: 5px; vertical-align: top;">{{ CNF_COMNAME }}<br>{{ CNF_TEL }}<br>{{ CNF_EMAIL }}</td>
<td align="left" colspan="7" style="padding-left: 5px; vertical-align: top;">{{ Lang::get('core.tourname') }}: {{ SiteHelpers::formatLookUp($row->tourID,'tourID','1:tours:tourID:tour_name') }}<br>{{ Lang::get('core.tourdate') }}: {{ SiteHelpers::TarihFormat($row->start)}} / {{ SiteHelpers::TarihFormat($row->end)}}<br>{{ Lang::get('core.tourcode') }}:{{ $row->tour_code}}<br>{{ Lang::get('core.guide') }}: {{ SiteHelpers::formatLookUp($row->guideID,'guideID','1:guides:guideID:name') }}</td>

</tr>
                <tr style="background:#eeeeee; font-weight:bold; ">
<td style='width:25%'>{{ Lang::get('core.passengerlist') }}</td>
<td style='width:5%'><strong>{{ Lang::get('core.gender') }}</strong></td>
<td style='width:15%'><strong>{{ Lang::get('core.passportno') }}</strong></td>
<td style='width:15%'><strong>{{ Lang::get('core.passportoffice') }}</strong></td>
<td style='width:15%'><strong>{{ Lang::get('core.nationality') }}</strong></td>
<td style='width:15%'><strong>{{ Lang::get('core.dateofbirth') }}</strong></td>
<td style='width:15%'><strong>{{ Lang::get('core.dateofissue') }}</strong></td>
<td style='width:15%'><strong>{{ Lang::get('core.dateofexpiry') }}</strong></td>
</tr>
                    @foreach($tourdate->booktours as $booktour)
                      <?php $booking = $booktour->booking; ?>
                      @if($booking)
                        @foreach($booking->bookRoom as $room)
                          {!! TourdatesController::travelersDetailpassport($room->travellers) !!}
                        @endforeach
                      @endif
                    @endforeach
              </tbody>
                </table>
            @endif
<table>
<tbody>
</tbody>
</table>