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

				{!! Form::open(array('url'=>'creditpackage/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
				<div class="col-md-12">
					<fieldset><legend> Credit Transactions</legend>

						{!! Form::hidden('id', $row['id']) !!}{!! Form::hidden('owner_id', $row['owner_id']) !!}{!! Form::hidden('currency', CNF_CURRENCY) !!}{!! Form::hidden('country', 1) !!}

						<div class="form-group  " >
										<label for="Package Name" class=" control-label col-md-4 text-left"> Package Name </label>
										<div class="col-md-6">
										  <input  type='text' name='package_name' id='package_name' value='{{ $row['package_name'] }}' 
						     class='form-control input-sm ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Credit" class=" control-label col-md-4 text-left"> Credit </label>
										<div class="col-md-6">
										  <input  type='text' name='credit' id='credit' value='{{ $row['credit'] }}' 
						     class='form-control input-sm ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Amount" class=" control-label col-md-4 text-left"> Amount </label>
										<div class="col-md-6">
										  <input  type='text' name='amount' id='amount' value='{{ $row['amount'] }}' 
						     class='form-control input-sm ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Active" class=" control-label col-md-4 text-left"> {{ Lang::get('core.status') }} <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  
					<label class='radio radio-inline'>
					<input type='radio' name='active' value ='0' required @if($row['active'] == '0') checked="checked" @endif > {{ Lang::get('core.fr_minactive') }} </label>
					<label class='radio radio-inline'>
					<input type='radio' name='active' value ='1' required @if($row['active'] == '1') checked="checked" @endif > {{ Lang::get('core.fr_mactive') }} </label> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>		
					</fieldset>
				</div>


				<div style="clear:both"></div>


				<div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">
						<button type="submit" name="apply" class="btn btn-success " > {{ Lang::get('core.sb_apply') }}</button>
						<button type="submit" name="submit" class="btn btn-primary " > {{ Lang::get('core.sb_save') }}</button>
						<button type="button" onclick="location.href='{{ URL::to('creditpackage?return='.$return) }}' " class="btn btn-danger  ">  {{ Lang::get('core.sb_cancel') }} </button>
					</div>

				</div>

				{!! Form::close() !!}
			</div>
		</div>
	</div>

	<script type="text/javascript">
        $(document).ready(function() {

            {{--$("#owner_id").jCombo("{!! url('creditpackage/comboselect?filter=tb_owners:id:name') !!}",--}}
                {{--{  selected_value : '{{ $row["owner_id"] }}' });--}}

            $("#currency").jCombo("{!! url('creditpackage/comboselect?filter=def_currency:currencyID:currency_name') !!}",
                {  selected_value : '{{ $row["currency"] }}' });


            $('.removeMultiFiles').on('click',function(){
                var removeUrl = '{{ url("creditpackage/removefiles?file=")}}'+$(this).attr('url');
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
@stop