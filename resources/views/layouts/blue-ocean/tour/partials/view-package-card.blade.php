<style>
table, th, td {
  font-size: 12px;
  color: gray;
  padding-bottom: 8px;
}
</style>
<?php
    #-------------GET MINIMUM PRICE-------------#

    if ($td['cost_single'] > 0) {
        if (is_null($min_price)) {
            $min_price = $td['cost_single'];
        } elseif ($td['cost_single'] < $min_price) {
            $min_price = $td['cost_single'];
        }
    }
    if ($td['cost_double'] > 0 && $td['cost_double'] < $min_price) {
        if (is_null($min_price)) {
            $min_price = $td['cost_double'];
        } elseif ($td['cost_double'] < $min_price) {
            $min_price = $td['cost_double'];
        }
    }
    if ($td['cost_triple'] > 0 && $td['cost_triple'] < $min_price) {
        if (is_null($min_price)) {
            $min_price = $td['cost_triple'];
        } elseif ($td['cost_triple'] < $min_price) {
            $min_price = $td['cost_triple'];
        }
    }
    if ($td['cost_quad'] > 0 && $td['cost_quad'] < $min_price) {
        if (is_null($min_price)) {
            $min_price = $td['cost_quad'];
        } elseif ($td['cost_quad'] < $min_price) {
            $min_price = $td['cost_quad'];
        }
    }
    if ($td['cost_quint'] > 0 && $td['cost_quint'] < $min_price) {
        if (is_null($min_price)) {
            $min_price = $td['cost_quint'];
        } elseif ($td['cost_quint'] < $min_price) {
            $min_price = $td['cost_quint'];
        }
    }
    if ($td['cost_sext'] > 0 && $td['cost_sext'] < $min_price) {
        if (is_null($min_price)) {
            $min_price = $td['cost_sext'];
        } elseif ($td['cost_sext'] < $min_price) {
            $min_price = $td['cost_sext'];
        }
    }

    #-------------GET MINIMUM PRICE ENDS-------------#

    $seat_booked     = \GeneralStatus::tourCapacity($td['tourdateID'], $td['total_capacity']);
    $seat_available  = intval($td['total_capacity']) - $seat_booked;
    $seat_percentage = intval(100 * $seat_booked / intval($td['total_capacity']));
    @$seat_available_total += $seat_available;
?>

<?php
 #-------------GET PRICE AFTER DISCOUNT-------------#

    $discountprice = [
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0
        ];

    
    if ($td['cost_single'] != 0) {
        $discountprice[0] = $td['cost_single'] - $td['discount'];
    }

    if($td['cost_double'] != 0){
        $discountprice[1] = $td['cost_double'] - $td['discount'];
    }

    if($td['cost_triple'] != 0){
        $discountprice[2] = $td['cost_triple'] - $td['discount'];
    }

    if($td['cost_quad'] != 0){
        $discountprice[3] = $td['cost_quad'] - $td['discount'];
    }

    if($td['cost_quint'] != 0){
        $discountprice[4] = $td['cost_quint'] - $td['discount'];
    }

    if($td['cost_sext'] != 0){
        $discountprice[5] = $td['cost_sext'] - $td['discount'];
    }
    
    #-------------GET PRICE AFTER DISCOUNT ENDS-------------#

?>


<div class="card m-2 col-xs-12 col-sm-12 col-md-5">
  <div class="card-body">
    <h5 align="center" class="card-title mb5">{{ $td['tourname'] }}</h5>
    <p align="center" class="card-text mb5" style="color: black;">
        {{ SiteHelpers::TarihFormat($td['start']) }} - {{ SiteHelpers::TarihFormat($td['end']) }}
    </p>
      <table width="107%">
          <tr>
          <th></th>
          <th></th>
          <th></th>
          </tr>
          
          <tr>
          <td>Deposit</td>
          <td></td>
          <td align="center">{{CURRENCY_SYMBOLS}}{{ $td['cost_depo'] }}/pax</td>
          </tr>
          
          <tr>
          <td>{{ Lang::get('core.capacity') }}</td>
          <td></td>
          <td align="center">{{ $td['tourdate']->capacity.'/'.$td['total_capacity'] }}</td>
          </tr>
          
          <tr>
          <td>{{ Lang::get('core.transit') }}</td>
          <td></td>
          <td align="center">{{ $td['transit'] }}</td>
          </tr>
          
          <tr>
          <td>{{ Lang::get('core.singleroom') }}</td>
          @if($td['discount'] && $td['cost_single']!= 0)
          <td align="right"><strike style="color:red"><span style="color:gray">{{CURRENCY_SYMBOLS}}{{ number_format($td['cost_single']) }}</span></strike></td>
          @else 
          <td></td>
          @endif
          @if($td['cost_single']== 0)
           <td align="center">{{ Lang::get('core.notavailable') }}</td>
          @else
            <td align="center">{{CURRENCY_SYMBOLS}}{{$discountprice[0]}}</td>
          @endif
          </tr>
          
          <tr>
          <td >{{ Lang::get('core.doubleroom') }}</td>
          @if($td['discount'] && $td['cost_double'] != 0)
          <td align="right"><strike style="color:red"><span style="color:gray">{{CURRENCY_SYMBOLS}}{{ number_format($td['cost_double']) }}</span></strike></td>
          @else
            <td></td>
          @endif
            @if($td['cost_double']== 0)
           <td align="center">{{ Lang::get('core.notavailable') }}</td>
            @else
            <td align="center">{{CURRENCY_SYMBOLS}} {{$discountprice[1]}}</td>
            @endif 
          </tr>
          
          <tr>
            <td>{{ Lang::get('core.tripleroom') }}</td>
            @if($td['discount'] && $td['cost_triple'] != 0)
            <td align="right"><strike style="color:red"><span style="color:gray">{{CURRENCY_SYMBOLS}}{{ number_format($td['cost_triple']) }}</span></strike></td>
            @else
            <td></td>
            @endif
            @if($td['cost_triple']== 0)
            <td align="center">{{ Lang::get('core.notavailable') }}</td>
            @else
            <td align="center">{{CURRENCY_SYMBOLS}}{{$discountprice[2]}}</td>
            @endif
          </tr>
          
          <tr>
            <td>{{ Lang::get('core.quadroom') }}</td>
            @if($td['discount'] && $td['cost_quad'] != 0)
            <td align="right"><strike style="color:red"><span style="color:gray">{{CURRENCY_SYMBOLS}}{{ number_format($td['cost_quad']) }}</span></strike></td>
            @else
            <td></td>
            @endif
            @if($td['cost_quad']== 0)
            <td align="center">{{ Lang::get('core.notavailable') }}</td>
            @else
            <td align="center">{{CURRENCY_SYMBOLS}}{{$discountprice[3]}}</td>
            @endif
          </tr>
          
          <tr>
          <td>{{ Lang::get('core.quintroom') }}</td>
          @if($td['discount'] && $td['cost_quint'] != 0)
            <td align="right"><strike style="color:red"><span style="color:gray">{{CURRENCY_SYMBOLS}}{{ number_format($td['cost_quint']) }}</span></strike></td>
          @else
            <td></td>
          @endif
          @if($td['cost_quint']== 0)
            <td align="center">{{ Lang::get('core.notavailable') }}</td>
          @else
            <td align="center">{{CURRENCY_SYMBOLS}}{{$discountprice[4]}}</td>
          @endif
          </tr>
          
          <tr>
          <td>{{ Lang::get('core.sextroom') }}</td>
          @if(($td['discount']) && ($td['cost_sext'] != 0))
              <td align="right"><strike style="color:red"><span style="color:gray">{{CURRENCY_SYMBOLS}}{{ number_format($td['cost_sext']) }}</span></strike></td>
              
          @else
              <td></td>
          @endif
          @if($td['cost_sext']== 0)
            <td align="center">{{ Lang::get('core.notavailable') }}</td>
          @else
            <td align="center">{{CURRENCY_SYMBOLS}}{{$discountprice[5]}}</td>
          @endif
          </tr>
          
          <tr>
          <td></td>
          <td></td>
          <td></td>
          </tr>

    
    </table>
    
    {{-- <div class="col-xs-12 col-sm-12"> --}}
    @if(CNF_BOOKINGFORM == 1)
        <a href="booknow?tourID={{ $td['tourID'] }}&tourdateID={{ $td['tourdateID'] }}" class="btn btn-primary btn-block">
            <span>{{ Lang::get('core.book') }}</span>
        </a>
    @else
        <a href="booknowsimple?tourID={{ $td['tourID'] }}&tourdateID={{ $td['tourdateID'] }}" class="btn btn-primary btn-block">
            <span>{{ Lang::get('core.book') }}</span>
        </a>
    @endif
    {{-- </div> --}}
  </div>
</div>