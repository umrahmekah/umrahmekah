@extends('layouts.app')
@section('content')
    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
        <li  class="active"> {{ $pageTitle }} </li>
      </ol>
    </section>

  <div class="content">   
    <div class="box box-primary">
      <div class="box-header with-border">

      </div>
      <div class="box-body "> 

      </div>
     </div>
  </div> 
	
@stop