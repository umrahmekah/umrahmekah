@include('layouts.modern.header')

<style>
	div div div div p{
		margin-bottom: 10px !important;
	}
</style>

<div class="list-wrapper bg-grey-2">
	<div class="container">
		<ul class="list-breadcrumb clearfix">
			<li><a class="color-grey link-dr-blue" href="{{ url('') }}">{{ Lang::get('core.home') }}</a> /</li>
			<li><a class="color-grey link-dr-blue" href="{{ url('package') }}">{{ Lang::get('core.unpaiddeposit') }}</a></li>
		</ul>
		<div class="row">

			<div class="col-xs-12 col-sm-8 col-md-9">
				<div class="list-content clearfix">
					<div class="list-item-entry">
                        @foreach($listpending as $pending)
                            <div class="hotel-item style-8 bg-white">
                                <div class="table-view">
                                    <div class="radius-top cell-view">
                                        <img class="img-responsive img-full" src="@if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.$pending->tourimage) && $pending->tourimage !='')
                                        {{ asset('uploads/images/'.CNF_OWNER.'/'.$pending->tourimage)}}
                                        @else
                                        {{ asset('mmb/images/tour-noimage.jpg')}}
                                        @endif " alt="">
                                        <div class="price price-s-3 red tt">{{ Lang::get('core.unpaiddeposit') }}</div>
                                    </div>
                                    <div class="title hotel-middle clearfix cell-view">

                                        <h4><b>{{ $pending->tour_name }}</b></h4>
                                        <span class="f-14 color-dark-2 grid-hidden">{{ Lang::get('core.umrahpackageprice') }} : {{CURRENCY_SYMBOLS}}{{ $pending->balance }}</span>
                                        <div>
                                            <p class="f-14">{{ Lang::get('core.tourdates') }} : {{ $pending->start }}-{{ $pending->end }}</p>
                                        </div>

                                    </div>
                                    <div class="title hotel-right bg-dr-blue clearfix cell-view">
                                        <input type="hidden" id="totalpendingdeposit" name="totalpendingdeposit" value="{{ $pending->cost_deposit*$pending->totaltravellers }}">
                                        <div class="hotel-person color-white">{{ Lang::get('core.unpaiddeposit') }} <span>{{CURRENCY_SYMBOLS}}{{ $pending->cost_deposit*$pending->totaltravellers }}</span></div>

                                        <div style="margin-bottom: 10px">
                                            <button class="c-button b-40 bg-white color-dark-2 hv-dark-2-o grid-hidden" onclick="viewpendingbooking('{{ $pending->bookingno }}');">{{ Lang::get('core.detailbooking') }}</button>
                                        </div>
                                        <div style="margin-bottom: 10px">
                                            <button class="c-button b-40 bg-white color-dark-2 hv-dark-2-o grid-hidden" onclick="ondeletebooking('{{ $pending->bookingno }}');">{{ Lang::get('core.cancelbooking') }}</button>
                                        </div>
                                        <div>
                                            <button class="c-button b-40 bg-white color-dark-2 hv-dark-2-o grid-hidden" onclick="paypendingdeposit('{{ $pending->bookingno }}');">{{ Lang::get('core.paydeposit') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    //ajax for delete pending booking if customer cancel the booking
    function ondeletebooking(id){
        $.ajax({
            type: "POST",
            url: '{{url("/pendingdepo/delete")}}?id='+id,
            success: function(response){
                window.location.href = response.url;
            },
            dataType: 'JSON'
        });
    }

    //ajax for view pending booking
    function viewpendingbooking(id){
        $.ajax({
            type:'get',
            datatype:'html',
            success:function(data){
                console.log('success');
                window.location.href = "{{url('/pendingdepo/view')}}/"+id;
            },
            error:function(){
            }
        })
    }

    //ajax for pay pending booking
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