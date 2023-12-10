@extends('layouts.app')
@section('content')
  <script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>
  <section class="content-header">
    <h1> {{ SiteHelpers::formatLookUp($tourdate->tourcategoriesID,'tourcategoriesID','1:def_tour_categories:tourcategoriesID:tourcategoryname') }}</h1>
  </section>

  <div class="box-header with-border">
    <div class="box-header-tools pull-left" >
      <a href="{{ url('tourdates/show/'.$tourdate->tourdateID) }}" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-left fa-2x"></i></a>
      {{-- <a href="{{ url('tourdates/exportroomlist/'.$tourdate->tourdateID)}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.export_room_list') }}"><i class="fa fa-file-excel-o fa-lg text-green"></i> {{ Lang::get('core.export_room_list') }}</a> --}}
    </div>	
  </div>


  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body box-profile">
        <div class="row">
          <div class="col-md-4">
            <table>
              <tr>
                <th>{{ Lang::get('core.package') }}</th>
                <td>&nbsp: &nbsp{{ $tourdate->tour->tour_name }}</td>
              </tr>
              <tr>
                <th>{{ Lang::get('core.date') }}</th>
                <td>&nbsp: &nbsp{{ \Carbon::parse($tourdate->start)->format('d M Y') }} - {{ \Carbon::parse($tourdate->end)->format('d M Y') }}</td>
              </tr>
              <tr>
                <th>Pax</th>
                <td>&nbsp: &nbsp{{ $tourdate->pax }} pax</td>
              </tr>
              
            </table>
          </div>
          <div class="col-md-4">
            <b>{{ Lang::get('core.room_information') }}</b><br>
            @if($room_count[1])
              {{ Lang::get('core.single') }} : {{$room_count[1]}} {{ Lang::get('core.room') }}<br>
            @endif
            @if($room_count[2])
              {{ Lang::get('core.double') }} : {{$room_count[2]}} {{ Lang::get('core.room') }}<br>
            @endif
            @if($room_count[3])
              {{ Lang::get('core.triple') }} : {{$room_count[3]}} {{ Lang::get('core.room') }}<br>
            @endif
            @if($room_count[4])
              {{ Lang::get('core.quad') }} : {{$room_count[4]}} {{ Lang::get('core.room') }}<br>
            @endif
            @if($room_count[5])
              {{ Lang::get('core.quint') }} : {{$room_count[5]}} {{ Lang::get('core.room') }}<br>
            @endif
            @if($room_count[6])
              {{ Lang::get('core.sext') }} : {{$room_count[6]}} {{ Lang::get('core.room') }}<br>
            @endif
          </div>
          <div class="col-md-4">
            <a target="_blank" class="btn btn-default tips pull-right" title="{{ Lang::get('core.generate_room_list') }}" onclick="saveToPdf();"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.generate_room_list') }}</a>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6" id="arrangement_container">
            <?php $num = 1; ?>
            @foreach($rooms as $room)
              <p>{{ App\Models\Bookroom::ROOM_TYPE_MAP[$room['room_type']] }} Room</p>
              <ul class="list-group" data-amount="{{ $num }}" id="room_{{ $num++ }}" data-value="{{ $room['room_type'] }}">
                @foreach($room['travellers'] as $traveller)
                  <li class="list-group-item" traveller-value="{{ $traveller->travellerID }}" booking-value="{{ $traveller->tempBooking->bookingsID }}">{{ $traveller->tempBooking->bookingno }} - {{ $traveller->fullname }} ({{ Lang::get($traveller->genderLanguage) }}) <span class="pull-right">({{ $traveller->tempRoom->roomTypeName }})</span></li>
                @endforeach
              </ul>
              <br>
            @endforeach
          </div>
          <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Finalized room arrangement
                </div>
                <div class="panel-body">
                    @foreach($room_arrangements as $ra)
                      <a href="{{ url('tourdates/roomarrangement/'.$ra->id) }}" target="_blank">{{ \Carbon::parse($ra->created_at)->format('d M Y') }} - {{ $ra->entry->fullName }}.pdf</a><br>
                    @endforeach
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Instruction
                </div>
                <div class="panel-body">
                    <ol>
                      <li>The room are auto arranged. Please check the room arrangement.</li>
                      <li>Rearrange the room accordingly.</li>
                      <li>After the room have been arranged, click generate room list button above.</li>
                      <li>Once the arrangement have been saved, the room list will be available to download above.</li>
                    </ol>
                    <span style="font-weight: bold;">The arrangement in this page will be reset after you have saved or you leave this page.</span>
                </div>
            </div>
            
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            @if($tourdate->cost_single)
              <button type="button" class="btn btn-default btn-sm" onclick="addRoom(1)">
                <i class="fa fa-plus"></i> {{ Lang::get('core.single') }}
              </button>
            @endif
            @if($tourdate->cost_double)
              <button type="button" class="btn btn-default btn-sm" onclick="addRoom(2)">
                <i class="fa fa-plus"></i> {{ Lang::get('core.double') }}
              </button>
            @endif
            @if($tourdate->cost_triple)
              <button type="button" class="btn btn-default btn-sm" onclick="addRoom(3)">
                <i class="fa fa-plus"></i> {{ Lang::get('core.triple') }}
              </button>
            @endif
            @if($tourdate->cost_quad)
              <button type="button" class="btn btn-default btn-sm" onclick="addRoom(4)">
                <i class="fa fa-plus"></i> {{ Lang::get('core.quad') }}
              </button>
            @endif
            @if($tourdate->cost_quint)
              <button type="button" class="btn btn-default btn-sm" onclick="addRoom(5)">
                <i class="fa fa-plus"></i> {{ Lang::get('core.quint') }}
              </button>
            @endif
            @if($tourdate->cost_sext)
              <button type="button" class="btn btn-default btn-sm" onclick="addRoom(6)">
                <i class="fa fa-plus"></i> {{ Lang::get('core.sext') }}
              </button>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  
  <script>

    var roomType = [
      "{{ App\Models\Bookroom::ROOM_TYPE_MAP[1] }}",
      "{{ App\Models\Bookroom::ROOM_TYPE_MAP[2] }}",
      "{{ App\Models\Bookroom::ROOM_TYPE_MAP[3] }}",
      "{{ App\Models\Bookroom::ROOM_TYPE_MAP[4] }}",
      "{{ App\Models\Bookroom::ROOM_TYPE_MAP[5] }}",
      "{{ App\Models\Bookroom::ROOM_TYPE_MAP[6] }}"
    ];

    $(document).ready(()=>{
      $('.list-group').sortable({
        group: 'shared'
      });
    });

    var roomArr = [], travellers = [], room = [], roomlist = null, items = null;

    var roomType = [
      "{{ App\Models\Bookroom::ROOM_TYPE_MAP[1] }} Room",
      "{{ App\Models\Bookroom::ROOM_TYPE_MAP[2] }} Room",
      "{{ App\Models\Bookroom::ROOM_TYPE_MAP[3] }} Room",
      "{{ App\Models\Bookroom::ROOM_TYPE_MAP[4] }} Room",
      "{{ App\Models\Bookroom::ROOM_TYPE_MAP[5] }} Room",
      "{{ App\Models\Bookroom::ROOM_TYPE_MAP[6] }} Room"
    ];

    function saveToPdf() {
      roomArr = [];
      let num = $('.list-group:last').attr('data-amount');
      num++;
      for (let i = 1; i < num; i++) {
        travellers = [];
        room = [];
        roomlist = document.getElementById("room_"+i);
        items = document.querySelectorAll("#room_"+i+" li");
        for (let j = 0; j < items.length; j++) {
          travellers.push({traveller:items[j].getAttribute("traveller-value"),booking:items[j].getAttribute("booking-value")});
        }
        room = {room_type: roomlist.getAttribute("data-value"),
                travellers: travellers}
        roomArr.push(room);
      }
      console.log(roomArr);

      let form = document.createElement("form");
      form.setAttribute("method", "post");
      form.setAttribute("action", "{{ url('tourdates/roomarrange/'.$tourdate->tourdateID) }}");

      let hiddenField = document.createElement("input");
      hiddenField.setAttribute("type", "hidden");
      hiddenField.setAttribute("name", "_token");
      hiddenField.setAttribute("value", "{{ csrf_token()}}");

      form.appendChild(hiddenField);

      let field = document.createElement("input");
      field.setAttribute("type", "hidden");
      field.setAttribute("name", "rooms");
      field.setAttribute("value", JSON.stringify(roomArr));

      form.appendChild(field);

      document.body.appendChild(form);
      form.submit();
    }

    function addRoom(type) {
      let num = $('.list-group:last').attr('data-amount');
      num++;

      let string = '<p>'+roomType[type-1]+'</p><ul class="list-group" data-amount="'+num+'" id="room_'+num+'" data-value="'+type+'"></ul><br>';

      $('#arrangement_container').append(string);

      $('.list-group').sortable({
        group: 'shared'
      });
    }
  </script>

@endsection