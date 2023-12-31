{!! Form::open(array('url'=>'home/proccess/5', 'id'=>'formconfiguration','class'=>'form-vertical' ,'files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}	

<div class="form-group  col-md-12" style="display:none">
					<label for="ipt" class="  "> TourName  </label>
                {!! Form::hidden('tourname', SiteHelpers::formatLookUp(app('request')->input('view'),'tour_name','1:tours:tourID:tour_name') ) !!}					

		</div>

		<div class="form-group  col-md-12" >
					<label for="ipt" class="  "> Traveller Name  </label>
				{!! Form::text('namesurname','',array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!}
		</div>

		<div class="form-group  col-md-12" >
					<label for="ipt" class="  "> Email  </label>
				{!! Form::text('email','',array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true', 'parsley-type'=>'email'   )) !!}
		</div>

		<div class="form-group  col-md-12" >
					<label for="ipt" class="  "> Country  </label>
				<select name='country' rows='5' id='country' class='form-control '   ></select>
		</div>

		<div class="form-group  col-md-12" >
					<label for="ipt" class="  "> Tour Date  </label>
				
				<div class="input-group m-b" style="width:250px !important;">
                    <input name='tourdate' class="form-control" type="date" value="2011-08-19" id="tourdate">
            </div>
		</div>

                <div class="col-md-3 text-sm text-uppercase">Adult  </div>
                <div class="col-md-3 mb-3">
		<div class="input-group input-group-quantity input-group-sm" data-toggle="quantity">
                    <span class="input-group-btn">
                      <input type="button" value="-" class="btn btn-secondary quantity-down" field="adult" />
                    </span>
                   <input type="text" name="adult" value="0" class="quantity form-control" />
                    <span class="input-group-btn">
                      <input type="button" value="+" class="btn btn-secondary quantity-up" field="adult" />
                    </span>
		</div>
		</div>
                <div class="col-md-3 text-sm text-uppercase">Child  </div>
                <div class="col-md-3 mb-3">

        <div class="input-group input-group-quantity input-group-sm" data-toggle="quantity">
                    <span class="input-group-btn">
                      <input type="button" value="-" class="btn btn-secondary quantity-down" field="child" />
                    </span>
                  <input type="text" name="child" value="0" class="quantity form-control" />
                    <span class="input-group-btn">
                      <input type="button" value="+" class="btn btn-secondary quantity-up" field="child" />
                    </span>
		</div>
		</div>
		
		<div class="form-group col-md-12" >					
				<button type="submit" name="submit" class="btn btn-primary"> Submit </button>
		</div>

{!! Form::close() !!}

<div style="clear: both;"></div>
<script type="text/javascript">
	
		$("#country").jCombo("{!! url('post/comboselect?filter=def_country:country_name:country_name') !!}",
		{  selected_value : '' });
		
</script>