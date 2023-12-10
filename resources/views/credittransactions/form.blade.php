@extends('layouts.app')

@section('content')

	<section class="content-header">
		<h1> {{ $pageTitle }} </h1>
	</section>

	<div class="content">

		<div class="box box-primary">
			<div class="box-header with-border">
				<div class="box-header-tools pull-left" >
					<a href="{{ url($pageModule.'?return='.$return) }}" class="tips"  title="{{ Lang::get('core.btn_back') }}" ><i class="fa  fa-arrow-left fa-2x"></i></a>
				</div>
			</div>
			<div class="box-body">

				<ul class="parsley-error-list">
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>

				{!! Form::open(array('url'=>'credittransactions/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
				<div class="col-md-12">
					<fieldset><legend> Credit Transactions</legend>

						{{--<div class="form-group  ">--}}
							{{--<label for="Owner Id" class=" control-label col-md-4 text-left"> Owner </label>--}}
							{{--<div class="col-md-6">--}}
								{{--<select name='owner_id' rows='5' id='owner_id' class='select2 '   ></select>--}}
							{{--</div>--}}
							{{--<div class="col-md-2">--}}

							{{--</div>--}}
						{{--</div>--}}
						{!! Form::hidden('id', $row['id']) !!}{!! Form::hidden('owner_id', $row['owner_id']) !!}{!! Form::hidden('transaction_id', $row['transaction_id']) !!}
						<div class="form-group  " >
							<label for="Agency" class=" control-label col-md-4 text-left"> Agency </label>
							<div class="col-md-6">
								<select name='agency' rows='5' id='agency' class='select2 '   ></select>
							</div>
							<div class="col-md-2">

							</div>
						</div>


						<div class="form-group  " >
							<label for="Amount Paid" class=" control-label col-md-4 text-left"> Amount Paid </label>
							<div class="col-md-6">
								<input  type='text' name='amount_paid' id='amount_paid' value='{{ $row['amount_paid']? $row['amount_paid']:0}}'
										class='form-control input-sm '/>
							</div>
							<div class="col-md-2">

							</div>
						</div>

						<div class="form-group  " >
							<label for="Credit Request" class=" control-label col-md-4 text-left"> Credit Request </label>
							<div class="col-md-6">
								<input  type='text' name='credit_request' id='credit_request' value='{{ $row['credit_request'] }}'
										class='form-control input-sm ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>

						<div class="form-group  " >
							<label for="Transaction Date" class=" control-label col-md-4 text-left" hidden> Transaction Date </label>
							<div class="col-md-6">
								<input  type='hidden' name='transaction_date' id='transaction_date' value='{{ $row['transaction_date'] }}'
											class='form-control input-sm ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>

						{!! Form::hidden('created_at', $row['created_at']) !!}

						<div class="form-group  " >
							<label for="Payment Gateway Id" class=" control-label col-md-4 text-left"> Payment Gateway </label>
							<div class="col-md-6">
								<select name='payment_gateway_id' rows='5' id='payment_gateway_id' class='select2 '   ></select>
							</div>
							<div class="col-md-2">

							</div>
						</div>

						{{--{!! Form::hidden('credit', $row['credit']) !!}--}}

						<div class="form-group  " >
							<label for="Currency" class=" control-label col-md-4 text-left"> Currency </label>
							<div class="col-md-6">
								<select name='currency' rows='5' id='currency' class='select2 '   ></select>
							</div>
							<div class="col-md-2">

							</div>
						</div>
					</fieldset>
				</div>


				<div style="clear:both"></div>


				<div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">
						<button type="submit" name="apply" class="btn btn-success " > {{ Lang::get('core.sb_apply') }}</button>
						<button type="submit" name="submit" class="btn btn-primary " > {{ Lang::get('core.sb_save') }}</button>
						<button type="button" onclick="location.href='{{ URL::to('credittransactions?return='.$return) }}' " class="btn btn-danger  ">  {{ Lang::get('core.sb_cancel') }} </button>
					</div>

				</div>

				{!! Form::close() !!}
			</div>
		</div>
	</div>

	<script type="text/javascript">
        $(document).ready(function() {

            {{--$("#owner_id").jCombo("{!! url('credittransactions/comboselect?filter=tb_owners:id:name') !!}",--}}
                {{--{  selected_value : '{{ $row["owner_id"] }}' });--}}

            $("#agency").jCombo("{!! url('credittransactions/comboselect?filter=tb_owners:id:name') !!}",
                {  selected_value : '{{ $row["agency"] }}' });

            $("#payment_gateway_id").jCombo("{!! url('credittransactions/comboselect?filter=payment_gateways:id:gateway_name') !!}",
                {  selected_value : '{{ $row["payment_gateway_id"] }}' });

            $("#currency").jCombo("{!! url('credittransactions/comboselect?filter=def_currency:currencyID:currency_name') !!}",
                {  selected_value : '{{ $row["currency"] }}' });


            $('.removeMultiFiles').on('click',function(){
                var removeUrl = '{{ url("credittransactions/removefiles?file=")}}'+$(this).attr('url');
                $(this).parent().remove();
                $.get(removeUrl,function(response){});
                $(this).parent('div').empty();
                return false;
            });

        });

        var currentTime = new Date();
        var month = currentTime.getMonth() + 1;
        var day = currentTime.getDate();
        var year = currentTime.getFullYear();
        document.getElementById('transaction_date').value = year + "-" + month + "-" + day;

        $(document).on('change','.qty', function(){
            $('.qty2').val($(this).val());
        });

	</script>
	<script>
        var total ='{{ $row['credit_request'] }}';
        $(document).ready(function () {
            $('input[name="credit_request"]').val(total);

            $('#amount_paid').on('input', function () {
                total = 0;
                $('#amount_paid').each(function () {
                    var amountInfo = parseInt($(this).val());
                    amountInfo = (amountInfo) ? amountInfo : 0; //Check if number otherwise set to 0
                    total = amountInfo;
                });
                $('input[name="credit_request"]').val(total);
            });
        });
	</script>
@stop