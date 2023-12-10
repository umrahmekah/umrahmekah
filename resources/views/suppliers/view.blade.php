@if($setting['view-method'] =='native')
	<div class="box box-primary">
		<div class="box-header with-border">

			<div class="box-header-tools pull-left" >
				@if($access['is_add'] ==1)
					<a href="{{ url('suppliers/update/'.$id.'?return='.$return) }}" class="tips btn btn-default btn-xs " title="{{ Lang::get('core.btn_edit') }}" onclick="ajaxViewDetail('#suppliers',this.href); return false; "><i class="fa  fa-pencil fa-2x"></i></a>
				@endif
				<a href="{{ url('suppliers/show/'.$id.'?&print=true&return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_print') }}" onclick="ajaxPopupStatic(this.href); return false;"><i class="fa  fa-print"></i></a>
			</div>

			<div class="box-header-tools pull-right " >
				<a href="{{ ($prevnext['prev'] != '' ? url('cars/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips" onclick="ajaxViewDetail('#cars',this.href); return false; "><i class="fa fa-arrow-left fa-2x"></i>  </a>
				<a href="{{ ($prevnext['next'] != '' ? url('cars/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips" onclick="ajaxViewDetail('#cars',this.href); return false; "> <i class="fa fa-arrow-right fa-2x"></i>  </a>
				<a href="javascript:void(0)" class="collapse-close tips btn btn-default btn-xs" onclick="ajaxViewClose('#{{ $pageModule }}')">
					<i class="fa fa-remove"></i></a>
			</div>
		</div>

		<div class="box-body">
			@endif

			<table class="table  table-bordered" >
				<tbody>

				<tr>
					<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.suppliername') }}</strong></td>
					<td>{{ SiteHelpers::formatLookUp($row->supplierID,'supplierID','1:def_supplier:supplierID:name') }} </td>

				</tr>

				<tr>
					<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.suppliertype') }}</strong></td>
					<td>{{ SiteHelpers::formatLookUp($row->suppliertypeID,'suppliertypeID','1:def_supplier_type:suppliertypeID:supplier_type') }} </td>

				</tr>

				<tr>
					<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.phone') }}</strong></td>
					<td>{{ $row->phone}} </td>

				</tr>

				<tr>
					<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.email') }}</strong></td>
					<td>{{ $row->email }}</td>

				</tr>

				<tr>
					<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.address') }}</strong></td>
					<td>{{ $row->address}} </td>

				</tr>

				{{-- <tr>
					<td width='30%' class='label-view text-right'><strong>{{Lang::get('core.startdate')}}</strong></td>
					<td>{{ $row->start_date}} </td>

				</tr> --}}

				{{-- <tr>
					<td width='30%' class='label-view text-right'><strong>{{Lang::get('core.expdate')}}</strong></td>
					<td>{{ $row->expired_date}} </td>

				</tr> --}}

				<tr>
					<td width='30%' class='label-view text-right'>
						{{-- @if($row->created_at!=$row->updated_at)
							<strong>{{Lang::get('core.updateby')}}</strong>
						@else
							<strong>{{Lang::get('core.createby')}}</strong>
						@endif --}}
					</td>

					<td>
						@if(isset($row->entry_by))
							{{ SiteHelpers::formatLookUp($row->entry_by,'entry_by','1:tb_users:id:first_name') }}
						@else
							{{ SiteHelpers::formatLookUp($row->owner_id,'owner_id','1:tb_owners:id:name') }}
						@endif
					</td>

				</tr>

				{{--<tr>--}}
					{{--<td width='30%' class='label-view text-right'><strong>{{Lang::get('core.dailyrate')}}</strong></td>--}}
					{{--<td>{{ $row->dayrate}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</td>--}}

				{{--</tr>--}}

				{{--<tr>--}}
					{{--<td width='30%' class='label-view text-right'><strong>{{Lang::get('core.weeklyrate')}}</strong></td>--}}
					{{--<td>{{ $row->weekrate}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</td>--}}

				{{--</tr>--}}

				{{--<tr>--}}
					{{--<td width='30%' class='label-view text-right'><strong>{{Lang::get('core.monthlyrate')}}</strong></td>--}}
					{{--<td>{{ $row->monthrate}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</td>--}}

				{{--</tr>--}}

				{{--<tr>--}}
					{{--<td width='30%' class='label-view text-right'><strong>{{Lang::get('core.airportpickup')}}</strong></td>--}}
					{{--<td>{!! SiteHelpers::formatRows($row->airportpickup,$fields['airportpickup'],$row ) !!} </td>--}}

				{{--</tr>--}}

				{{--<tr>--}}
					{{--<td width='30%' class='label-view text-right'><strong>{{Lang::get('core.similarcars')}}</strong></td>--}}
					{{--<td>{{ $row->similarcars}} </td>--}}

				{{--</tr>--}}

				{{--<tr>--}}
					{{--<td width='30%' class='label-view text-right'><strong>{{Lang::get('core.images')}}</strong></td>--}}
					{{--<td>{!! SiteHelpers::formatRows($row->images,$fields['images'],$row ) !!} </td>--}}

				{{--</tr>--}}

				{{--<tr>--}}
					{{--<td width='30%' class='label-view text-right'><strong>{{Lang::get('core.availableextras')}}</strong></td>--}}
					{{--<td>{{ SiteHelpers::formatLookUp($row->availableextras,'availableextras','1:def_car_extras:carsextrasID:name') }} </td>--}}

				{{--</tr>--}}

				{{--<tr>--}}
					{{--<td width='30%' class='label-view text-right'><strong>{{Lang::get('core.status')}}</strong></td>--}}
					{{--<td>{!! GeneralStatus::Status($row->status) !!} </td>--}}

				{{--</tr>--}}

				</tbody>
			</table>



			@if($setting['form-method'] =='native')
		</div>
	</div>
@endif

<script>
    $(document).ready(function(){

    });
</script>