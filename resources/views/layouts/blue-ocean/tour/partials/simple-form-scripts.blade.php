@push('scripts')
<script>
    // to assign event to the first traveller
    summaryCollapse("summary_fullname1");

    var num = 1;
    var no_of_person = {!! (!empty($no_of_person)) ? $no_of_person : 0 !!};

    //var total_price = '<?php echo $tourdatedetail['cost_price'];?>'*num;
    //document.getElementById('total_price1').innerHTML = '{{CURRENCY_SYMBOLS}} '+total_price;
    document.getElementById('total_jemaah1').innerHTML=num;

    // to add traveller if 'no_of_person' available
    if (no_of_person > 1) {
        for (var index_person = 1 ; index_person < no_of_person ; index_person++) {
            addTraveller();
        }
    }

    function passData() {
        for (var i = 1; i <= num; i++) {
            if (document.getElementById('nameandsurname'+i) != null) {
                document.getElementById('mahram'+i).innerHTML = document.getElementById('nameandsurname'+i).value;
                // document.getElementById('passport'+i).innerHTML = document.getElementById('nameandsurname'+i).value;
            }
        }
    }

    function validationJemaah() {
        var nameandsurnames = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("nameandsurname"+i)&&document.getElementById("nameandsurname"+i).value!==""&&document.getElementById("nameandsurname"+i).value!==null) {
                boolean = true;
            }
            nameandsurnames.push(boolean);

        }

        var nameBoolean = true;
        for (var i = 0; i < nameandsurnames.length; i++) {
            nameBoolean = nameBoolean&&nameandsurnames[i];
        }

        var lastnames = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("last_name"+i)&&document.getElementById("last_name"+i).value!==""&&document.getElementById("last_name"+i).value!==null) {
                boolean = true;
            }
            lastnames.push(boolean);

        }

        var lastnameBoolean = true;
        for (var i = 0; i < lastnames.length; i++) {
            lastnameBoolean = lastnameBoolean&&lastnames[i];
        }
        
        var emails = [];
        for (var i = 1; i <= 1; i++) {
            var boolean = false;
            if (document.getElementById("email"+i)&&document.getElementById("email"+i).value!==""&&document.getElementById("email"+i).value!==null) {
                boolean = true;
            }
            emails.push(boolean);
        }

        var emailBoolean = true;
        for (var i = 0; i < emails.length; i++) {
            emailBoolean = emailBoolean&&emails[i];
        }

        var phones = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("phone"+i)&&document.getElementById("phone"+i).value!==""&&document.getElementById("phone"+i).value!==null) {
                boolean = true;
            }
            phones.push(boolean);
        }

        var phoneBoolean = true;
        for (var i = 0; i < phones.length; i++) {
            phoneBoolean = phoneBoolean&&phones[i];
        }

        var genders = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("gender"+i)&&document.getElementById("gender"+i).value!=="0"&&document.getElementById("gender"+i).value!==null) {
                boolean = true;
            }
            genders.push(boolean);
        }

        var genderBoolean = true;
        for (var i = 0; i < genders.length; i++) {
            genderBoolean = genderBoolean&&genders[i];
        }

        // var addresss = [];
        // for (var i = 1; i <= num; i++) {
        //     var boolean = false;
        //     if (document.getElementById("address"+i)&&document.getElementById("address"+i).value!==""&&document.getElementById("address"+i).value!==null) {
        //         boolean = true;
        //     }
        //     addresss.push(boolean);
        // }

        var addressBoolean = true;
        // for (var i = 0; i < addresss.length; i++) {
        //     addressBoolean = addressBoolean&&addresss[i];
        // }

        // var countryIDs = [];
        // for (var i = 1; i <= num; i++) {
        //     var boolean = false;
        //     if (document.getElementById("countryID"+i)&&document.getElementById("countryID"+i).value!==""&&document.getElementById("countryID"+i).value!==null) {
        //         boolean = true;
        //     }
        //     countryIDs.push(boolean);
        // }

        var countryIDBoolean = true;
        // for (var i = 0; i < countryIDs.length; i++) {
        //     countryIDBoolean = countryIDBoolean&&countryIDs[i];
        // }

        var nric = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("nric"+i)&&document.getElementById("nric"+i).value!==""&&document.getElementById("nric"+i).value!==null) {
                boolean = true;
            }
            nric.push(boolean);
        }

        var nricBoolean = true;
        for (var i = 0; i < nric.length; i++) {
            nricBoolean = nricBoolean&&nric[i];
        }

        return nameBoolean&&emailBoolean&&phoneBoolean&&addressBoolean&&countryIDBoolean&&nricBoolean&&lastnameBoolean&&genderBoolean;

    }

    function validationBooking() {
        return true;
    }

    function validationPassport() {
        return true;
    }

    function bookingCheck() {
        if(!validationJemaah()){
            alert("Please fill in all the required information!");
            event.stopImmediatePropagation();

            return false;
        }

        return true;
    }

    function passportCheck() {
        var boolean = validationJemaah() && validationBooking();
        if(!(document.getElementById("adult_count")&&document.getElementById("adult_count").value!==""&&document.getElementById("adult_count").value>0)){
            alert("Please fill in all the required information!");
            event.stopImmediatePropagation();

            return false;
        }

        return true;
    }

    function summaryCheck() {
        var boolean = validationJemaah() && validationPassport() && validationBooking();
        if(!boolean){
            alert("Please fill in all the required information!");
            event.stopImmediatePropagation();

            return false;
        }

        return true;
    }

    function togglePaymentStep(active_detail) {
        $('.tab-pane').filter(':not(' + active_detail + ')').hide();
        $('.tab-pane').filter(':not(' + active_detail + ')').css('opacity', 0);

        $(active_detail).show();
        $(active_detail).css('opacity', 1);

        $('div.payment__icon').removeClass('payment__icon-active');

        if (!($(active_detail + '-tab div.payment__icon').hasClass('payment__icon-complete'))) {
            $(active_detail + '-tab div.payment__icon').addClass('payment__icon-active');
        }

        togglePaymentDetail(active_detail);
    }

    function togglePaymentDetail(active_detail) {
        if (active_detail == '#booking-summary') {
            $('.payment__detail').removeClass('hidden');
        } else {
            $('.payment__detail').addClass('hidden');
        }
    }
    
    function continueTraveller() {
        // document.getElementById('booking-detail-tab').click();

        var valid = bookingCheck();

        if (valid) {
            passData();
            addMahram();

            $('#jemaah-detail-tab div.payment__icon').addClass('payment__icon-complete');

            togglePaymentStep('#booking-detail');
        }
    }

    function continueMahram() {
        // document.getElementById('passport-detail-tab').click();

        var valid = passportCheck();

        if (valid) {
            passData();
            copyForms();
            calculateroom();

            $('#booking-detail-tab div.payment__icon').addClass('payment__icon-complete');

            togglePaymentStep('#booking-summary');
        }
    }

    function continuePassport() {
        // document.getElementById('booking-summary-tab').click();

        var valid = summaryCheck();

        if (valid) {
            passData();
            copyForms();
            calculateroom();

            $('#passport-detail-tab div.payment__icon').addClass('payment__icon-complete');

            togglePaymentStep('#booking-summary');
        }
    }

    function backMahram() {
        // document.getElementById('jemaah-detail-tab').click();

        $('div.payment__icon').removeClass('payment__icon-active');

        togglePaymentStep('#jemaah-detail');
    }

    function backPassport() {
        // document.getElementById('booking-detail-tab').click();

        $('div.payment__icon').removeClass('payment__icon-active');

        togglePaymentStep('#booking-detail');
    }

    function backSummary() {
        // document.getElementById('passport-detail').click();

        $('div.payment__icon').removeClass('payment__icon-active');

        togglePaymentStep('#booking-detail');
    }

    function countryName(id,i){
        var country=[];
        axios.get("/country_name/"+id)
            .then(response => {
                return response.data;
            }).catch(e => {
                console.log(e);
            }).then(data => {
                country=data;
                document.getElementById(i).innerHTML = country['country_name'];
            }).catch(e => {
                console.log(e);
            });
    }

    function copyForms() {

        for (var i = 1; i <= num; i++) {
            if (document.getElementById("nameandsurname"+i) != null) {
                document.getElementById("summary_fullname"+i).innerHTML = document.getElementById("nameandsurname"+i).value;
            }

            if (document.getElementById("last_name"+i) != null) {
                document.getElementById("summary_lastname"+i).innerHTML = document.getElementById("last_name"+i).value;
            }

            if (document.getElementById("gender"+i) != null) {
                document.getElementById("summary_gender"+i).innerHTML = getGenderDesc(document.getElementById("gender"+i).value);
            }

            if (document.getElementById("email"+i) != null) {
                document.getElementById("summary_email"+i).innerHTML = document.getElementById("email"+i).value;
            }

            if (document.getElementById("nric"+i) != null) {
                document.getElementById("summary_nric"+i).innerHTML = document.getElementById("nric"+i).value;
            }

            if (document.getElementById("phone"+i) != null) {
                document.getElementById("summary_phoneno"+i).innerHTML = document.getElementById("phone"+i).value;
            }

            if (document.getElementById("address"+i) != null) {
                document.getElementById("summary_addresstext"+i).innerHTML = document.getElementById("address"+i).value;
            }

            if (document.getElementById("countryID"+i) != null) {
                countryName(document.getElementById("countryID"+i).value,"summary_countryID"+i);
            }

            if (document.getElementById("adult_count").value != null) {
                document.getElementById('summary_adult_number').innerHTML = document.getElementById("adult_count").value;
            }else{
                document.getElementById('summary_adult_number').innerHTML = 0;
            }

            if (document.getElementById("child_count").value != null && document.getElementById("child_count").value != "")  {
                document.getElementById('summary_child_number').innerHTML = document.getElementById("child_count").value;
            }else{
                document.getElementById('summary_child_number').innerHTML = 0;
            }

            if (document.getElementById("infant_count").value != null && document.getElementById("infant_count").value != "") {
                document.getElementById('summary_infant_number').innerHTML = document.getElementById("infant_count").value;
            }else{
                document.getElementById('summary_infant_number').innerHTML = 0;
            }
        }
    }

    function getGenderDesc (gender) {
        if (gender == 'M') {
            return '{{ Lang::get('core.male') }}';
        } else if (gender == 'F') {
            return '{{ Lang::get('core.female') }}';
        }
    }

    function getRoomName (type) {
        if (type == 1) {
            return '{{ Lang::get('core.roomtype_1') }}';
        } else if (type == 2) {
            return '{{ Lang::get('core.roomtype_2') }}';
        } else if (type == 3) {
            return '{{ Lang::get('core.roomtype_3') }}';
        } else if (type == 4) {
            return '{{ Lang::get('core.roomtype_4') }}';
        } else if (type == 5) {
            return '{{ Lang::get('core.roomtype_5') }}';
        } else if (type == 6) {
            return '{{ Lang::get('core.roomtype_6') }}';
        } else if (type == 7) {
            return '{{ Lang::get('core.roomtype_7') }}';
        } else if (type == 8) {
            return '{{ Lang::get('core.roomtype_8') }}';
        } else if (type == 9) {
            return '{{ Lang::get('core.roomtype_9') }}';
        }
    }

    function getMahramRelationName (type) {
        if (type == 1) {
            return '{{ Lang::get('core.mahram_relation_1') }}';
        } else if (type == 2) {
            return '{{ Lang::get('core.mahram_relation_2') }}';
        } else if (type == 3) {
            return '{{ Lang::get('core.mahram_relation_3') }}';
        } else if (type == 4) {
            return '{{ Lang::get('core.mahram_relation_4') }}';
        } else if (type == 5) {
            return '{{ Lang::get('core.mahram_relation_5') }}';
        } else if (type == 6) {
            return '{{ Lang::get('core.mahram_relation_6') }}';
        } else if (type == 7) {
            return '{{ Lang::get('core.mahram_relation_7') }}';
        } else if (type == 8) {
            return '{{ Lang::get('core.mahram_not_required') }}';
        }
    }

    function addMahram(){
        $('.mahramname').each(function () {
            var mahram_selected = $(this).val();

            $(this).empty().append('<option value="0">{{ Lang::get("core.mahramsurname") }} *</option>');

            for (var index_mahram = 1; index_mahram <= num; index_mahram++){
                var newopt = $("#nameandsurname" + index_mahram).val();
                $('<option>').val(newopt).text(newopt).appendTo($(this));

                if (mahram_selected == newopt) {
                    $(this).val(newopt);
                }
            }
        });
    }

    function summaryCollapse(id) {
        var coll = document.getElementById(id);
        if(null != coll) {
            coll.addEventListener("click",function(){
                this.classList.toggle("activesummary");
                var content = this.nextElementSibling;
                if (content.style.maxHeight){
                    content.style.maxHeight = null;
                } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                }
            });
        }
    }

    function submitButton(){
        @if(Auth::check())
            document.forms["booking_form"].submit();
        @else
            $(document).on("submit", "form#booking_form", function(e){
                e.preventDefault();
                e.stopPropagation();
                return false;
            });

            var data = {treveller_password:document.getElementById("email1").value};
            axios.post("/bookpackage/checkcredential",data)
                .catch(function(error){
                    console.log(error);
                }).then(function(data){
                    if(data){
                        toggleSignInModal(data);
                    } else{
                        if (confirm("You don't have an account. Please click Ok if you want us to create an account for you.")) {
                            document.forms["booking_form"].submit();
                        }
                    }
                }).catch(function(e){
                    console.log(e);
                });
        @endif
    }

    function toggleSignInModal(flag) {
        if (flag) {
            // Show Modal
            $('#signInModal').modal('show');

            $("#signin-email").val($('#email1').val());
        } else {
            // Hide Modal
            $('#signInModal').modal('hide');

            $("#signin-email").val('');
            $("#signin-password").val('');
        }
    }

    function toggleTerms() {
        $('#termsModal').modal('show');
    }

    function toggleError() {
        $('#errorModal').modal('show');
    }

    @if(Session::has('failed'))
        toggleError();
    @endif

    function agreeTerms(value) {
        let button = document.getElementById("proceedButton");
        if (value) {
            button.disabled = false;
        }else{
            button.disabled = true;
        }
    }

    function proceedSignIn(){
        var signin_email = $("#signin-email").val();
        var signin_password = $("#signin-password").val();
        var credential = {email:signin_email, password:signin_password};

        if(signin_password != "") {
            axios.post("/bookpackage/checklogin", credential)
                .catch(e=>{
                    console.log(e);
                }).then(data=>{
                    console.log(data);
                    if(data){
                        // Hide modal
                        toggleSignInModal(false);

                        document.forms["booking_form"].submit();
                    } else {
                        alert("Login Failed. Please Check Your Credentials");
                    }
                }).catch(e=>{
                    console.log(e);
                });
        }
    }

    function calculateroom(){

        let adult_count = 0;

        if (document.getElementById("adult_count").value != null && document.getElementById("adult_count").value != "" && document.getElementById("adult_count").value > 0) {
            adult_count = document.getElementById("adult_count").value;
        }

        let child_count = 0;
        
        if (document.getElementById("child_count").value != null && document.getElementById("child_count").value != "" && document.getElementById("child_count").value > 0) {
            child_count = document.getElementById("child_count").value;
        }

        let infant_count = 0;
        
        if (document.getElementById("infant_count").value != null && document.getElementById("infant_count").value != "" && document.getElementById("infant_count").value > 0) {
            infant_count = document.getElementById("infant_count").value;
        }

        let total_count = +adult_count + +child_count + +infant_count;

        document.getElementById("total_jemaah1").innerHTML = total_count;

        var total_deposit = {{$tourdatedetail->cost_deposit ?? 0}}*total_count;

        document.getElementById('total_deposit1').innerHTML = '{{CURRENCY_SYMBOLS}} '+total_deposit;
        document.getElementById('totaldeposit').value = total_deposit;

    }
</script>
@endpush