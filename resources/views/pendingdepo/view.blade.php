@include('layouts.modern.header')

<div class="main-wraper bg-grey-2 padd-90">
	<div class="container">
		<ul class="list-breadcrumb clearfix">
			<li><a class="color-grey link-dr-blue" href="{{ url('') }}">{{ Lang::get('core.home') }}</a> /</li>
			<li><a class="color-grey link-dr-blue" href="{{ url('package') }}">{{ Lang::get('core.unpaiddeposit') }}</a></li>
		</ul>
		<div class="row">
			<div class="col-xs-12 col-md-8">
				<div class="accordion style-5 ">
					@foreach($travellerdetail as $traveller)
						<div class="acc-panel features">
							<div class="acc-title"><span class="acc-icon"></span>{{ $traveller->nameandsurname }}</div>
							<div class="acc-body" style="display: none;">
								<h5>{{ Lang::get('core.jemaahdetail') }}</h5>
								<p style="color: #333;">
									{{ Lang::get('core.email') }}: <b>{{ $traveller->email }}</b><br>
									{{ Lang::get('core.phone') }}: <b>{{ $traveller->phone }}</b><br>
									{{ Lang::get('core.address') }}: <b>{{ $traveller->address }}</b><br>
								</p>
							</div>
						</div>
					@endforeach
				</div>
			</div>
			<div class="col-sx-12 col-md-4">
				<div class="may-interested padd-90">
					<div class="row">
						<div class="hotel-item">
							<div class="radius-top">
								<img class="img-responsive img-full" src="@if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.$tourdetail->tourimage) && $tourdetail->tourimage !='')
								{{ asset('uploads/images/'.CNF_OWNER.'/'.$tourdetail->tourimage)}}
								@else
								{{ asset('mmb/images/tour-noimage.jpg')}}
								@endif " alt="">
								{{--<div class="price price-s-1">$273</div>--}}
							</div>
							<div class="detail-block clearfix bg-dr-blue">
								<div class="details-desc">
									<h4><b>{{ $tourdetail->tour_name }}</b></h4>
									<p>Umrah Category : <b>{{ $tourdetail->tourcategoryname }}</b></p>
									<p>Umrah Date : <b>{{ $tourdetail->start }} - {{ $tourdetail->end }}</b></p>
									<p>Total Jemaah : <b>{{ $bookingdetail->totaltravellers }} Jemaah</b></p>
									<p>Deposit pending : <b>{{CURRENCY_SYMBOLS}}{{ $totalpendingdeposit }}</b></p>
								</div>
								<input type="hidden" id="totalpendingdeposit" name="totalpendingdeposit" value="{{ $totalpendingdeposit }}">
								<div>
									<a href="#" onclick="paypendingdeposit('{{ $bookingno }}');" class="c-button b-40 bg-white hv-transparent">Pay Deposit</a>
									<a href="#" class="c-button b-40 bg-white hv-transparent">Delete Booking</a>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

	</div>
</div>

<script>
    function paypendingdeposit(id){
        $.ajax({
            type:'get',
            datatype:'html',
            success:function(data){
                console.log('success');
                window.location.href = "{{url('/pendingdepo/pay/deposit')}}/"+id;
            },
            error:function(){
            }
        })
    }
</script>


@include('layouts.modern.footer')