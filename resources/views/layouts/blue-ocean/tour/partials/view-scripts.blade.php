<script>
    var date_tour = new Array();
    var tour_roomtype = new Array();

    <?php 
        if (!empty($tdate)) {
            foreach ($tdate as $tour_record) {
                $label_single = ($tour_record['cost_single']) ? ' '.CURRENCY_SYMBOLS . $tour_record['cost_single'] : ' ' . strtolower(Lang::get('core.notavailable'));
                $label_double = ($tour_record['cost_double']) ? ' '.CURRENCY_SYMBOLS . $tour_record['cost_double'] : ' ' . strtolower(Lang::get('core.notavailable'));
                $label_triple = ($tour_record['cost_triple']) ? ' '.CURRENCY_SYMBOLS . $tour_record['cost_triple'] : ' ' . strtolower(Lang::get('core.notavailable'));
                $label_quad = ($tour_record['cost_quad']) ? ' '.CURRENCY_SYMBOLS . $tour_record['cost_quad'] : ' ' . strtolower(Lang::get('core.notavailable'));
                $label_quint = ($tour_record['cost_quint']) ? ' '.CURRENCY_SYMBOLS . $tour_record['cost_quint'] : ' ' . strtolower(Lang::get('core.notavailable'));
                $label_sext = ($tour_record['cost_sext']) ? ' '.CURRENCY_SYMBOLS . $tour_record['cost_sext'] : ' ' . strtolower(Lang::get('core.notavailable'));

                echo 'date_tour[' . $tour_record['tourdateID'] . '] = ' . $tour_record['tourID'] .';';

                echo 'tour_roomtype[\'' . $tour_record['tourdateID'] . '-1\'] = \'' . \Lang::get('core.roomtype_1') . ' ' . $label_single. '\';';
                echo 'tour_roomtype[\'' . $tour_record['tourdateID'] . '-2\'] = \'' . \Lang::get('core.roomtype_2') . ' ' . $label_double . '\';';
                echo 'tour_roomtype[\'' . $tour_record['tourdateID'] . '-3\'] = \'' . \Lang::get('core.roomtype_3') . ' ' . $label_triple . '\';';
                echo 'tour_roomtype[\'' . $tour_record['tourdateID'] . '-4\'] = \'' . \Lang::get('core.roomtype_4') . ' ' . $label_quad . '\';';
                echo 'tour_roomtype[\'' . $tour_record['tourdateID'] . '-5\'] = \'' . \Lang::get('core.roomtype_5') . ' ' . $label_quint . '\';';
                echo 'tour_roomtype[\'' . $tour_record['tourdateID'] . '-6\'] = \'' . \Lang::get('core.roomtype_6') . ' ' . $label_sext . '\';';
            }
        }
    ?>

    function tourDateChange(dateid) {
        updateRoomTypeOption(dateid);
        clearPersonOption();

        if (dateid) {
            $('#tour-id').val(date_tour[dateid]);
        } else {
            $('#tour-id').val('');
        }
    }

    function tourRoomTypeChange(type) {
        updatePersonOption(type);
    }

    function tourPersonChange(person) {
        if (person || person > 0) {
            $('.booking__section').show();

            updateButtonUrl();
        } else {
            $('.booking__section').hide();
        }
    }
    
    function clearRoomTypeOption() {
        $('#tour-roomtype').empty();
    }
    
    function clearPersonOption() {
        $('#tour-person').empty();
    }

    function updateRoomTypeOption(key){
        clearRoomTypeOption();
        $('#tour-roomtype').append($('<option>', { value : '' }).text('{{ Lang::get('core.choose_option', ['name' => Lang::get('core.roomtype')]) }}'));
        
        var roomtype_1 = (tour_roomtype[key + '-' + 1]) ? tour_roomtype[key + '-' + 1] : '';
        var roomtype_2 = (tour_roomtype[key + '-' + 2]) ? tour_roomtype[key + '-' + 2] : '';
        var roomtype_3 = (tour_roomtype[key + '-' + 3]) ? tour_roomtype[key + '-' + 3] : '';
        var roomtype_4 = (tour_roomtype[key + '-' + 4]) ? tour_roomtype[key + '-' + 4] : '';
        var roomtype_5 = (tour_roomtype[key + '-' + 5]) ? tour_roomtype[key + '-' + 5] : '';
        var roomtype_6 = (tour_roomtype[key + '-' + 6]) ? tour_roomtype[key + '-' + 6] : '';

        $('#tour-roomtype').append($('<option' + ((roomtype_1.includes('{!! strtolower(Lang::get('core.notavailable')) !!}')) ? ' disabled' : '') + '>', { value : 1 }).text(roomtype_1));
        $('#tour-roomtype').append($('<option' + ((roomtype_2.includes('{!! strtolower(Lang::get('core.notavailable')) !!}')) ? ' disabled' : '') + '>', { value : 2 }).text(roomtype_2));
        $('#tour-roomtype').append($('<option' + ((roomtype_3.includes('{!! strtolower(Lang::get('core.notavailable')) !!}')) ? ' disabled' : '') + '>', { value : 3 }).text(roomtype_3));
        $('#tour-roomtype').append($('<option' + ((roomtype_4.includes('{!! strtolower(Lang::get('core.notavailable')) !!}')) ? ' disabled' : '') + '>', { value : 4 }).text(roomtype_4));
        $('#tour-roomtype').append($('<option' + ((roomtype_5.includes('{!! strtolower(Lang::get('core.notavailable')) !!}')) ? ' disabled' : '') + '>', { value : 5 }).text(roomtype_5));
        $('#tour-roomtype').append($('<option' + ((roomtype_6.includes('{!! strtolower(Lang::get('core.notavailable')) !!}')) ? ' disabled' : '') + '>', { value : 6 }).text(roomtype_6));
    }

    function updatePersonOption(type){
        clearPersonOption();
        $('#tour-person').append($('<option>', { value : '' }).text('{!! Lang::get('core.choose_option', ['name' => Lang::get('core.no_of_person')]) !!}'));        

        $('#tour-person').append($('<option>', { value : 1 }).text(1));
        $('#tour-person').append($('<option>', { value : 2 }).text(2));
        $('#tour-person').append($('<option>', { value : 3 }).text(3));
        $('#tour-person').append($('<option>', { value : 4 }).text(4));
        $('#tour-person').append($('<option>', { value : 5 }).text(5));
        $('#tour-person').append($('<option>', { value : 6 }).text(6));
        $('#tour-person').append($('<option>', { value : 7 }).text(7));
        $('#tour-person').append($('<option>', { value : 8 }).text(8));
        $('#tour-person').append($('<option>', { value : 9 }).text(9));
        $('#tour-person').append($('<option>', { value : 10 }).text(10));
    }

    function updateButtonUrl() {
        var tour_id = $('#tour-id').val();
        var tour_date = $('#tour-date').val();
        var tour_roomtype = $('#tour-roomtype').val();
        var tour_person = $('#tour-person').val();
        @if(CNF_BOOKINGFORM == 1)
        var url_string = 'booknow?tourID=' + tour_id + '&tourdateID=' + tour_date + '&numOfPerson=' + tour_person;
        @else
        var url_string = 'booknowsimple?tourID=' + tour_id + '&tourdateID=' + tour_date + '&numOfPerson=' + tour_person;
        @endif
        $('#btn-proceed-booking').attr('href', url_string);
    }
</script>