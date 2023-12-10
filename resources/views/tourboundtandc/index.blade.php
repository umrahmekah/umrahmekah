@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
    <section class="content-header">
      <h1>  {{ Lang::get('core.tandc') }}</h1>
    </section>

  <div class="content">
<div class="col-md-12">

<div class="box box-primary">
  <div class="box-header with-border">
            @include( 'mmb/toolbarmain')
  </div>
  <div class="box-body">

   {!! Form::open(array('url'=>'tourboundtandc/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
   <div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
    <table class="table table-striped table-bordered " id="{{ $pageModule }}Table">
        <thead>
      <tr>
        <th class="number"> No </th>
        <th> <input type="checkbox" class="checkall" /></th>
        <th width="50" style="width: 50px;">{{ Lang::get('core.btn_action') }}</th>
                <th><?php echo Lang::get('core.title') ;?></th> 
        <th><?php echo Lang::get('core.created') ;?></th>         
                <th><?php echo Lang::get('core.updated') ;?></th> 
        <th width="30"><?php echo Lang::get('core.status') ;?></th>         
        </tr>
        </thead>

        <tbody>
            @foreach ($rowData as $row)
                <tr>
          <td width="30"> {{ ++$i }} </td>
          <td width="50"><input type="checkbox" class="ids minimal-red" name="ids[]" value="{{ $row->tandcID }}" />  </td>
          <td>

              @if($access['is_detail'] ==1)
              <a href="{{ url('tourboundtandc/show/'.$row->tandcID.'?return='.$return)}}" class="tips" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-eye fa-2x"></i> </a>
              @endif
              @if($access['is_edit'] ==1)
              <a  href="{{ url('tourboundtandc/update/'.$row->tandcID.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-pencil fa-2x"></i> </a>
              @endif

          </td>
                    <td>{{$row->title}}</td>
                    <td>{{ SiteHelpers::TarihFormat($row->created_at)}}</td>
                    <td>{{ SiteHelpers::TarihFormat($row->updated_at)}}</td>
                    <td>{!! GeneralStatus::Status($row->status) !!}</td>
            
            </tr>

            @endforeach

        </tbody>

    </table>
  <input type="hidden" name="md" value="" />
  </div>
  {!! Form::close() !!}
  </div>
</div>
</div>
</div><div style="clear: both;"></div> 
<script>
$(document).ready(function(){

  $('.do-quick-search').click(function(){
    $('#MmbTable').attr('action','{{ url("tourboundtandc/multisearch")}}');
    $('#MmbTable').submit();
  });

  $('input[type="checkbox"],input[type="radio"]').iCheck({
    checkboxClass: 'icheckbox_square-red',
    radioClass: 'iradio_square-red',
  });

  $('#{{ $pageModule }}Table .checkall').on('ifChecked',function(){
    $('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('check');
  });

  $('.copy').click(function() {
    var total = $('input[class="ids"]:checkbox:checked').length;
    if(confirm('are u sure Copy selected rows ?'))
    {
        $('#MmbTable').attr('action','{{ url("tourboundtandc/copy")}}');
        $('#MmbTable').submit();// do the rest here
    }
  })

});
</script>
<style>
.table th , th { text-align: none !important;  }
.table th.right { text-align:right !important;}
.table th.center { text-align:center !important;}

</style>

<script>
  $(function () {
    $('#{{ $pageModule }}Table').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "language": datatableLang.{{ config('app.locale') }}
    });
  });
</script>

@stop
