@extends('layouts.app')

@section('content')

  <section class="content-header">
      <h1> {!! Lang::get('core.moduleconfiguration') !!}</h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('dashboard') }}"> Home</a></li>
        <li><a href="{{ url('mmb/module') }}"> Module</a></li>
        <li class="active">Basic Info  </li>
         <li class="active">{{ $row->module_title }}</li>
      </ol>
  </section>   

  <div class="content">

            @if(Session::has('message'))
               {{ Session::get('message') }}
            @endif
            @if($errors->all())
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            @endif
            <div class="box box-primary">
                <div class="box-header with-border"><h4> {{ $row->module_title }} <small>  :  {{ Lang::get('core.moduleconfigdescription') }} </small> </h4></div>
                    <div class="box-body ">

                    @include('mmb.module.tab',array('active'=>'config','type'=> $type))

                    <div class="col-md-6">
                        {!! Form::open(array('url'=>'mmb/module/saveconfig/'.$module_name, 'class'=>'form-horizontal ','id'=>'configA' , 'parsley-validate'=>'','novalidate'=>' ')) !!}
                            <input  type='hidden' name='module_id' id='module_id'  value='{{ $row->module_id }}'   />
                            <fieldset>
                                <legend> {{ Lang::get('core.moduleinfo') }} </legend>
                                <div class="form-group">
                                    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_mtitle') }} </label>
                                    <div class="col-md-8">
                                        <div class="input-group input-group-sm" style="margin:1px 0 !important;">
                                        <input  type='text' name='module_title' id='module_title' class="form-control " required="true" value='{{ $row->module_title }}'  />
                                        <span class="input-group-addon xlick bg-default btn-sm " >EN</span>
                                    </div>
                                        @if($config->lang =='true')
                                            <?php $lang = SiteHelpers::langOption();
                                            if($mmbconfig['cnf_multilang'] ==1) {
                                                foreach($lang as $l) { if($l['folder'] !='en') {?>
                                                    <div class="input-group input-group-sm" style="margin:1px 0 !important;">
                                                        <input name="language_title[<?php echo $l['folder'];?>]" type="text"   class="form-control" placeholder="Label for <?php echo $l['name'];?>"
                                                        value="<?php echo (isset($module_lang['title'][$l['folder']]) ? $module_lang['title'][$l['folder']] : '');?>" />
                                                        <span class="input-group-addon xlick bg-default btn-sm " ><?php echo strtoupper($l['folder']);?></span>
                                                    </div>
                                            <?php } } }?>
                                        @endif
                                     </div>
                                </div>

                                <div class="form-group">
                                    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_modnote') }}</label>
                                    <div class="col-md-8">
                                        <div class="input-group input-group-sm" style="margin:1px 0 !important;">
                                            <input  type='text' name='module_note' id='module_note'  value='{{ $row->module_note }}' class="form-control input-sm "  />
                                            <span class="input-group-addon xlick bg-default btn-sm " >EN</span>
                                        </div>
                                        @if($config->lang =='true')
                                            <?php $lang = SiteHelpers::langOption();
                                            if($mmbconfig['cnf_multilang'] ==1) {
                                                foreach($lang as $l) { if($l['folder'] !='en') {?>
                                                    <div class="input-group input-group-sm" style="margin:1px 0 !important;">
                                                        <input name="language_note[<?php echo $l['folder'];?>]" type="text"   class="form-control input-sm" placeholder="Note for <?php echo $l['name'];?>"
                                                        value="<?php echo (isset($module_lang['note'][$l['folder']]) ? $module_lang['note'][$l['folder']] : '');?>" />
                                                        <span class="input-group-addon xlick bg-default btn-sm " ><?php echo strtoupper($l['folder']);?></span>
                                                    </div>
                                            <?php } } }?>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.classcontroller') }} </label>
                                    <div class="col-md-8">
                                        <input  type='text' name='module_name' id='module_name' readonly="1"  class="form-control input-sm" required value='{{ $row->module_name }}'  />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.tablemaster') }}</label>
                                    <div class="col-md-8">
                                        <input  type='text' name='module_db' id='module_db' readonly="1"  class="form-control input-sm" required value='{{ $row->module_db}}'  />

                                    </div>
                                </div>

                                <div class="form-group" style="display:none;" >
                                    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.author') }} </label>
                                    <div class="col-md-8">
                                        <input  type='text' name='module_author' id='module_author' class="form-control input-sm"  readonly="1"  value='{{ $row->module_author }}'  />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="ipt" class=" control-label col-md-4"> {{ Lang::get('core.shortcode') }} </label>
                                    <div class="col-md-8 " >
                                                <b>{{ Lang::get('core.formshortcode') }} : </b><code><br /><?php echo "!!MmbHelpers|showForm|".$row->module_name."!!"; ?></code><br />
                                            <b>{{ Lang::get('core.tableshortcode') }} : </b><br />
                                            <code><?php echo htmlentities('<php>');?> use \App\Http\Controllers\<?php echo ucwords($row->module_name).'Controller;';?> <br />
                                            <?php echo ' echo '.ucwords($row->module_name).'Controller::display();'. htmlentities('</php>') ; ?></code>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="ipt" class=" control-label col-md-4"></label>
                                    <div class="col-md-8">
                                    <button type="submit" name="submit" class="btn btn-primary"> {{ Lang::get('core.updatemodule') }} </button>
                                     </div>
                                </div>
                            </fieldset>
                        {!! Form::close() !!}
                    </div>
                    @if($config->advance =='true')
                    <div class="col-sm-6 col-md-6">

                        @if($type !='report' && $type !='generic')
                            {!! Form::open(array('url'=>'mmb/module/savesetting/'.$module_name, 'class'=>'form-horizontal ' ,'id'=>'configB')) !!}
                                <input  type='text' name='module_id' id='module_id'  value='{{ $row->module_id }}'  style="display:none; " />
                                <fieldset>
                                    <legend> Module Setting </legend>

                                    <div class="form-group">
                                        <label for="ipt" class=" control-label col-md-4"> Grid Table Type </label>
                                        <div class="col-md-8">

                                            <select class="form-control input-sm" name="module_type">
                                                <?php if($row->module_type  =='addon') $row->module_type ='native'; ?>
                                                @foreach($cruds as $crud)
                                                    <option value="{{ $crud->type }}"
                                                    @if($crud->type == $row->module_type ) selected @endif
                                                        >{{ $crud->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="ipt" class=" control-label col-md-4"> Default Order  </label>
                                        <div class="col-md-8">
                                            <select class="select-alt" name="orderby">
                                                @foreach($tables as $t)
                                                    <option value="{{ $t['field'] }}"
                                                    @if($setting['orderby'] ==$t['field']) selected="selected" @endif
                                                    >{{ $t['label'] }}</option>
                                                @endforeach
                                            </select>
                                            <select class="select-alt" name="ordertype">
                                                <option value="asc" @if($setting['ordertype'] =='asc') selected="selected" @endif > Ascending </option>
                                                <option value="desc" @if($setting['ordertype'] =='desc') selected="selected" @endif > Descending </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="ipt" class=" control-label col-md-4"> Display Rows </label>
                                        <div class="col-md-8">
                                            <select class="select-alt" name="perpage">
                                                <?php $pages = array('10','20','30','50');
                                                foreach($pages as $page) {
                                                ?>
                                                <option value="<?php echo $page;?>"  @if($setting['perpage'] ==$page) selected="selected" @endif > <?php echo $page;?> </option>
                                                <?php } ?>
                                            </select>
                                            / Page
                                        </div>
                                    </div>

                                </fieldset>
                                @if($config->setting->method =='true')
                                    <fieldset>
                                        <legend> Form & View Setting </legend>
                                        <p> <i>You can switch this setting and applied to current module without have to rebuild </i></p>

                                        <div class="form-group">
                                            <label for="ipt" class=" control-label col-md-4"> Form Method </label>
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                <input type="radio" value="native" name="form-method" class="minimal-red"
                                                @if($setting['form-method'] == 'native') checked="checked" @endif
                                                /> New Page
                                                </label>
                                                <label class="radio-inline">
                                                <input type="radio" value="modal" name="form-method"  class="minimal-red"
                                                @if($setting['form-method'] == 'modal') checked="checked" @endif
                                                /> Modal
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="ipt" class=" control-label col-md-4"> View  Method </label>
                                            <div class="col-md-8">
                                                <label class="radio-inline">
                                                <input type="radio" value="native" name="view-method" class="minimal-red"
                                                @if($setting['view-method'] == 'native') checked="checked" @endif
                                                /> New Page
                                                </label>
                                                <label class="radio-inline">
                                                <input type="radio" value="modal" name="view-method" class="minimal-red"
                                                @if($setting['view-method'] == 'modal') checked="checked" @endif
                                                /> Modal
                                                </label>
                                                <label class="radio-inline">
                                                <input type="radio" value="expand" name="view-method" class="minimal-red"
                                                @if($setting['view-method'] == 'expand') checked="checked" @endif
                                                /> Expand Grid
                                                </label>

                                            </div>
                                        </div>

                                        <div class="form-group" >
                                            <label for="ipt" class=" control-label col-md-4"> Inline add / edit row </label>
                                            <div class="col-md-8">
                                                <label class="checkbox-inline">
                                                <input type="checkbox" value="true" name="inline" class="minimal-red"
                                                @if($setting['inline'] == 'true') checked="checked" @endif
                                                /> Yes  Allowed
                                                </label>

                                            </div>
                                        </div>
                                        <p class="alert alert-warning"> <strong> Important ! </strong> this setting only work with module where have <strong>Adavance </strong> Option</p>
                                    </fieldset>
                                @endif

                                <div class="form-group">
                                    <label for="ipt" class=" control-label col-md-4"></label>
                                    <div class="col-md-8">
                                        <button type="submit" name="submit" class="btn btn-primary"> Update Setting </button>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        @endif
                    </div>
                </div>
                @endif
                <div class="clr" style="clear:both;"></div>
            </div>

            <div class="clr clear"></div>
        </div>
    </div>

<script type="text/javascript">
	$(document).ready(function(){

		<?php echo MmbHelpers::sjForm('configA'); ?>
		<?php echo MmbHelpers::sjForm('configB'); ?>

	})
</script>	

@stop