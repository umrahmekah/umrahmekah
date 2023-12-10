@extends('layouts.app') @section('content')
<section class="content-header">
    <h1>{{Lang::get('core.travellers')}}</h1>
</section>

<div class="box-header with-border">
    <div class="box-header-tools pull-left">
        <a href="{{ url('travellers?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-left fa-2x"></i></a>
        @if($access['is_add'] ==1)
        <a href="{{ url('travellers/update/'.$id.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa  fa-pencil fa-2x"></i> </a>
        <a href="{{ url('createbooking/update?travellerID='.$id)}}" title="{{Lang::get('core.newbooking')}}" class="tips text-blue"><i class="fa fa-suitcase fa-2x"></i></a>        
        <a href="{{ url('invoice/update?travellerID='.$id)}}" title="{{Lang::get('core.addnewinvoice')}}" class="tips text-yellow"><i class="fa fa-file-text fa-2x"></i></a>        
        <a href="{{ url('payments/update?travellerID='.$id)}}" title="{{Lang::get('core.addnewpayment')}}" onclick="MmbModal(this.href,'{{ Lang::get('core.addnewpayment') }}'); return false;" class="tips text-green"><i class="fa fa-cc-visa fa-2x"></i></a>        
        <a href="{{ url('travellersfiles/update?travellerID='.$id)}}" title="{{Lang::get('core.addnewfile')}}" onclick="MmbModal(this.href,'{{ Lang::get('core.addnewfile') }}'); return false;" class="tips text-red"><i class="fa fa-paperclip fa-2x"></i></a> @endif
        <a href="{{ url('travellersnote/update?travellerID='.$id)}}" title="{{Lang::get('core.addnewnote')}}" onclick="MmbModal(this.href,'{{Lang::get('core.addnewnote')}}'); return false;" class="tips text-purple"><i class="fa fa-sticky-note fa-2x"></i></a>
        <a  href="{{ url( $pageModule .'/pdf/'.$id) }}" target="_blank" class="tips text-red" title="PDF"><i class="fa fa-file-pdf-o fa-2x"></i> </a> 
        
    </div>
    <div class="box-header-tools pull-right ">
        <a href="{{ ($prevnext['prev'] != '' ? url('travellers/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips" title="{{Lang::get('core.previous')}}"><i class="fa fa-arrow-left fa-2x"></i>  </a> 
        <a href="{{ ($prevnext['next'] != '' ? url('travellers/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips" title="{{Lang::get('core.next')}}"> <i class="fa fa-arrow-right fa-2x"></i> </a>
        @if(Session::get('gid') == 1) @endif
    </div>
</div>

<div class="col-md-3">
    <div class="box box-primary">
        <div class="box-body box-profile">
            <div class="text-center">         
                @if(file_exists('./uploads/images/'.CNF_OWNER.'/'.$row->image) && $row->image !='')
                    <img class="img-circle" width="100" height="100" src=" {{ asset('./uploads/images/'.CNF_OWNER.'/'.$row->image) }}" />                 
                @else
                    <img class="img-circle" width="100" height="100" src=" {{ asset('/uploads/images/no-image-person.png') }}" />   
                @endif
            </div>
            <h3 class="profile-username text-center">{{ $row->nameandsurname}}</h3>
            {{--@if(!empty($mahram))--}}
                {{--<h3 class="profile-username text-center">{{ lang::get('core.mahram') }}: {{ $mahram->nameandsurname }}</h3>--}}
            {{--@endif--}}

            <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                    <b>{{Lang::get('core.age')}}</b> <a class="pull-right">{{ \Carbon::parse($row->dateofbirth)->age }}</a>
                </li>
                <li class="list-group-item">
                    <b>{{Lang::get('core.nric')}}</b> <a class="pull-right">{{ $row->NRIC}}</a>
                </li>
                <li class="list-group-item">
                    <b>{{Lang::get('core.email')}}</b> <a href="mailto:{{ $row->email}}" class="pull-right">{{ $row->email}}</a>
                </li>
                <li class="list-group-item">
                    <b>{{Lang::get('core.phone')}}</b> <a class="pull-right">{{ $row->phone}} </a>
                </li>
                <li class="list-group-item">
                    <b>{{Lang::get('core.country')}}</b> <a class="pull-right">@if($travellerCountry){{ $travellerCountry->country_name }} @endif </a>
                </li>
                <li class="list-group-item">
                    <b>{{Lang::get('core.city')}}</b> <a class="pull-right">{{ $row->city }}</a>
                </li>
                <li class="list-group-item">
                    <b>{{Lang::get('core.address')}}</b>
                </li>
                <li class="list-group-item">
                <a>{{ $row->address}} </a>
                </li>
                <li class="list-group-item">
                    <b>{{Lang::get('core.nationality')}}</b> <a class="pull-right">@if($nationality){{ $nationality->country_name }} @endif</a>
                </li>
                <li class="list-group-item">
                    <b>{{Lang::get('core.interests')}}</b>
                </li>
                <li class="list-group-item">
                    <a>{{ $row->interests}}</a>
                </li>
                <li class="list-group-item">
                    <b>{{Lang::get('core.status')}}</b> <a class="pull-right">{!! GeneralStatus::Status($row->status) !!}
</a>
                </li>
                
            </ul>
        </div>
    </div>
</div>

<div class="col-md-9">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab"><i class="fa fa-square- text-green" aria-hidden="true"></i>
 {{Lang::get('core.travellersdetails')}}</a></li>
            <li><a href="#tab_2" data-toggle="tab"><i class="fa fa-square- text-green" aria-hidden="true"></i>
 {{Lang::get('core.bookings')}}</a></li>
            <li><a href="#tab_3" data-toggle="tab"><i class="fa fa-square- text-green" aria-hidden="true"></i>
 {{Lang::get('core.invoices')}}</a></li>
            <li><a href="#tab_4" data-toggle="tab"><i class="fa fa-square- text-green" aria-hidden="true"></i>
 {{Lang::get('core.payments')}}</a></li>
            <li><a href="#tab_6" data-toggle="tab"><i class="fa fa-square- text-green" aria-hidden="true"></i>
 {{Lang::get('core.m_files')}}</a></li>
            <li><a href="#tab_5" data-toggle="tab"><i class="fa fa-square- text-green" aria-hidden="true"></i>
 {{Lang::get('core.notes')}}</a></li>
            <li><a href="#tab_7" data-toggle="tab"><i class="fa fa-square- text-green" aria-hidden="true"></i>
{{Lang::get('core.checklist')}}</a></li>
            <li><a href="#tab_8" data-toggle="tab"><i class="fa fa-square- text-green" aria-hidden="true"></i>
{{Lang::get('core.jemaahlist')}}</a></li>

        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                <div class="col-md-12">
                    <div class="box-header">
                        <h3 class="box-title with-border">{{Lang::get('core.passport')}}</h3>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>{{Lang::get('core.dateofbirth')}}</b> <a class="pull-right">{{ SiteHelpers::TarihFormat($row->dateofbirth)}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{Lang::get('core.dateofissue')}}</b><a class="pull-right">{{ SiteHelpers::TarihFormat($row->passportissue)}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{Lang::get('core.passportcountry')}}</b><a class="pull-right">@if($passportCountry){{ $passportCountry->country_name }}@endif</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>{{Lang::get('core.passportno')}} </b> <a class="pull-right">{{ $row->passportno}} </a>
                            </li>
                            <li class="list-group-item">
                                <b>{{Lang::get('core.dateofexpiry')}} </b> <a class="pull-right">{{ SiteHelpers::TarihFormat($row->passportexpiry)}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{Lang::get('core.passportplaceissue')}}</b><a class="pull-right">{{ $row->passport_place_made }}</a>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box-header">
                        <h3 class="box-title with-border">{{Lang::get('core.mahram_detail')}}</h3>
                    </div>
                    @if(!empty($mahram))
                        <div class="col-md-6">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>{{Lang::get('core.mahram')}}</b> <a class="pull-right" href="{{ url('travellers/show/'.$row->mahram_id.'?return='.$return) }}">{{ $mahram->nameandsurname }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>{{Lang::get('core.relation')}}</b>
                                    <a class="pull-right">
                                        @if($row->mahram_relation==1)
                                            {{ lang::get('core.mahram_relation_1') }}
                                        @elseif($row->mahram_relation==2)
                                            {{ lang::get('core.mahram_relation_2') }}
                                        @elseif($row->mahram_relation==3)
                                            {{ lang::get('core.mahram_relation_3') }}
                                        @elseif($row->mahram_relation==4)
                                            {{ lang::get('core.mahram_relation_4') }}
                                        @elseif($row->mahram_relation==5)
                                            {{ lang::get('core.mahram_relation_5') }}
                                        @elseif($row->mahram_relation==6)
                                            {{ lang::get('core.mahram_relation_6') }}
                                        @elseif($row->mahram_relation==7)
                                            {{ lang::get('core.mahram_relation_7') }}
                                        @else
                                            {{ lang::get('core.mahram_relation_8') }}
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="col-md-6">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>{{Lang::get('core.mahram')}}</b> <a class="pull-right">{{ lang::get('core.is_mahram') }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>{{Lang::get('core.relation')}}</b>
                                    <a class="pull-right">{{ lang::get('core.is_mahram') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            {{ lang::get('core.reminder') }}
                        </div>
                    @endif

                </div>               

                <div class="col-md-6">
                    <div class="box-header">
                        <h3 class="box-title with-border">{{Lang::get('core.emergencycontactdetails')}}</h3>
                    </div>
                    <div class="col-md-12">

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>{{Lang::get('core.emergencycontact')}}</b> <a class="pull-right">{{ $row->emergencycontactname}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{Lang::get('core.email')}}</b><a href="mailto:{{$row->emergencycontactemail}}" class="pull-right">{{ $row->emergencycontactemail}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{Lang::get('core.phone')}}</b><a class="pull-right">{{ $row->emergencycontanphone}} </a>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box-header">
                        <h3 class="box-title with-border">{{Lang::get('core.insurancedetails')}}</h3>
                    </div>
                    <div class="col-md-12">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>{{Lang::get('core.insurancecompany')}}</b> <a class="pull-right">{{ $row->insurancecompany}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{Lang::get('core.insurancepolicyno')}}</b><a class="pull-right">{{ $row->insurancepolicyno}} </a>
                            </li>
                            <li class="list-group-item">
                                <b>{{Lang::get('core.insurancecompanyphone')}}</b> <a class="pull-right">{{ $row->insurancecompanyphone}} </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box-header">
                        <h3 class="box-title with-border">{{Lang::get('core.specialreq')}}</h3>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>{{Lang::get('core.bedconfiguration')}}</b> <a class="pull-right">{!! SiteHelpers::formatRows($row->bedconfiguration,$fields['bedconfiguration'],$row ) !!}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>{{Lang::get('core.dietaryreq')}}</b><a class="pull-right">{{ $row->dietaryrequirements}}</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <div style="clear: both;"></div>

            </div>
            <div class="tab-pane" id="tab_2">
                <table id="bookings" class="table  table-striped">
                    <thead>
                        <tr>
                            <th>{{Lang::get('core.bookingno')}}</th>
                            <th>{{Lang::get('core.tour')}}</th>
                            <th>{{Lang::get('core.hotel')}}</th>
                            <th>{{Lang::get('core.flight')}}</th>
                            <th>{{Lang::get('core.car')}}</th>
                            <th>{{Lang::get('core.extraservices')}}</th>
                            <th>{{Lang::get('core.bookingdate')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($book as $bk)
                        <tr>
                            <td>
                                <a href="{{ url('createbooking/show/'.$bk['bookingsID'])}}" target="_blank">{{ $bk['bookingno'] }}</a>
                            </td>
                            <td>{!! SiteHelpers::Tour($bk['tour']) !!}</td>
                            <td>{!! SiteHelpers::Hotel($bk['hotel']) !!}</td>
                            <td>{!! SiteHelpers::Flight($bk['flight']) !!}</td>
                            <td>{!! SiteHelpers::Car($bk['car']) !!}</td>
                            <td>{!! SiteHelpers::Extraservices($bk['extraservices']) !!}</td>
                            <td>{{ SiteHelpers::TarihFormat($bk['created_at'])}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>


            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_3">
                <table id="invoices" class="table  table-striped">
                    <thead>
                        <tr>
                            <th>{{Lang::get('core.invoiceno')}}</th>
                            <th>{{Lang::get('core.bookingno')}}</th>
                            <th>{{Lang::get('core.issuedate')}}</th>
                            <th>{{Lang::get('core.status')}}</th>
                            <th>{{Lang::get('core.duedate')}}</th>
                            <th>{{Lang::get('core.amount')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invo as $in)
                        <?php $payment=\DB::table('invoice_payments')->where('invoiceID', $in['invoiceID'] )->sum('amount'); ?>
                        <tr>
                            <td>
                                <a href="{{ url('invoice/show/'.$in['invoiceID'])}}" target="_blank">{{ $in['invoiceID'] }}</a>
                            </td>
                            <td><a href="{{ url('createbooking/show/'.$in['bookingID'])}}" target="_blank">{{ SiteHelpers::formatLookUp($in['bookingID'],'bookingID','1:bookings:bookingsID:bookingno') }}</a></td>
                            <td>{{ SiteHelpers::TarihFormat($in['DateIssued'])}}</td>
                            <td>{!! InvoiceStatus::Payments( $payment , $in['InvTotal']) !!}</td>
                            <td>{!! InvoiceStatus::paymentstatus($in['DueDate'],$in['status']) !!}</td>
                            <td>{{ SiteHelpers::formatLookUp($in['currency'],'currencyID','1:def_currency:currencyID:currency_sym') }} {{ $in['InvTotal'] }} </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <div class="tab-pane" id="tab_4">
                <table id="payments" class="table  table-striped">
                    <thead>
                        <tr>
                            <th>{{Lang::get('core.invoiceno')}}</th>
                            <th>{{Lang::get('core.amount')}}</th>
                            <th>{{Lang::get('core.paymenttype')}}</th>
                            <th>{{Lang::get('core.notes')}}</th>
                            <th>{{Lang::get('core.paymentdate')}}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pay as $pt)
                        <tr>
                            <td><a href="{{ url('invoice/show/'.$pt['invoiceID'])}}" target="_blank">
                            {{ $pt['invoiceID'] }}</a></td>
                            <td>{{ SiteHelpers::formatLookUp($pt['currency'],'currencyID','1:def_currency:currencyID:currency_sym|symbol') }} {{ $pt['amount'] }} </td>
                            <td>{{\DB::table('def_payment_types')->where('paymenttypeID', $pt['payment_type'])->first()->payment_type}}</td>
                            <td>{{ $pt['notes'] }}</td>
                            <td>{{ SiteHelpers::TarihFormat($pt['payment_date'])}}</td>
                            <td>@if($access['is_remove'] =='1')
                                <a href="{{ url('payments/delete/'.$pt['invoicePaymentID'])}}" class=" pull-right" data-toggle="confirmation" data-title="{{Lang::get('core.rusure')}}" data-content="{{ Lang::get('core.youwanttodeletethis') }}"><i class="fa fa-trash-o fa-2x text-red"></i></a> @endif @if($access['is_edit'] =='1')
                                <a href="{{ url('payments/update/'.$pt['invoicePaymentID'].'/?travellerID='.$id)}}" onclick="MmbModal(this.href,'{{Lang::get('core.editpayment')}}'); return false;" class=" pull-right tips" title="{{Lang::get('core.editpayment')}}"><i class="fa fa-pencil fa-2x text-navy"></i></a> @endif </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="tab_5">
                <table id="notes" class="table  table-striped">
                    <thead>
                        <tr>
                            <th style="width:10px;"></th>
                            <th>{{Lang::get('core.title')}}</th>
                            <th>{{Lang::get('core.note')}}</th>
                            <th style="width:60px;">{{Lang::get('core.date')}}</th>
                            <th style="width:40px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tnotes as $tn)
                        <tr>
                            <td><a class="text-{{ $tn['style'] }} tips" title="{{ $tn['style'] }}" href="#"><i class="fa fa-square fa-2x"></i></a></td>
                            <td>{{ $tn['title'] }}</td>
                            <td>{{ $tn['note'] }}</td>
                            <td>{{ SiteHelpers::TarihFormat($tn['created_at'])}}</td>
                            <td>@if($access['is_remove'] =='1')
                                <a href="{{ url('travellers/notedelete/'.$tn['travellerID'].'/'.$tn['travellers_noteID'])}}" class=" pull-right" data-toggle="confirmation" data-title="{{Lang::get('core.rusure')}}" data-content="{{ Lang::get('core.youwanttodeletethis') }}"><i class="fa fa-trash-o fa-2x text-red"></i></a> @endif @if($access['is_edit'] =='1')
                                <a href="{{ url('travellersnote/update/'.$tn['travellers_noteID'].'/?travellerID='.$id)}}" onclick="MmbModal(this.href,'{{Lang::get('core.editnote')}}'); return false;" class=" pull-right tips" title="{{ Lang::get('core.editnote') }}"><i class="fa fa-pencil fa-2x text-navy"></i></a> @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="tab_6">
                <table id="files" class="table  table-striped">
                    <thead>
                        <tr>
                            <th style="width:60px;">{{Lang::get('core.m_files')}}</th>
                            <th style="width:100px;">{{Lang::get('core.filetype')}}</th>
                            <th>{{Lang::get('core.remarks')}}</th>
                            <th style="width:60px;">{{Lang::get('core.date')}}</th>
                            <th style="width:40px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($file as $fl)
                        <tr>
                            <td>{!! SiteHelpers::showUploadedFile($fl['file'],'/uploads/files/'.CNF_OWNER.'/') !!}</td>
                            <td>@if($fl['file_type']==1) Passport @elseif ($fl['file_type']==2) ID Card @elseif ($fl['file_type']==3) Photo @elseif ($fl['file_type']==4) Other Documents  @endif</td>
                            <td>{{ $fl['remarks'] }}</td>
                            <td>{{ SiteHelpers::TarihFormat($fl['created_at'])}}</td>
                            <td>@if($access['is_remove'] =='1')
                                <a href="{{ url('travellers/filedelete/'.$fl['travellerID'].'/'.$fl['fileID'])}}" class="pull-right" data-toggle="confirmation" data-title="{{Lang::get('core.rusure')}}" data-content="{{ Lang::get('core.youwanttodeletethis') }}"><i class="fa fa-trash-o fa-2x text-red "></i></a> @endif @if($access['is_edit'] =='1')
                                <a href="{{ url('travellersfiles/update/'.$fl['fileID'].'/?travellerID='.$id)}}" onclick="MmbModal(this.href,'{{Lang::get('core.editnote')}}'); return false;" class=" pull-right tips" title="{{ Lang::get('core.editfile') }}"><i class="fa fa-pencil fa-2x text-navy"></i></a> @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane" id="tab_7">
                {{--<legend>{{Lang::get('core.docchecklist')}}</legend>--}}
                <div class="box-header">
                    <h3 class="box-title with-border">{{Lang::get('core.docchecklist')}}</h3>
                </div>
                {{--view if mahram is husband--}}
                @if($row->mahram_relation=='1')
                    <div class="row">
                        <div class="col-md-2 col-xs-4">1</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijilnikah') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==1)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">2</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.IDcard') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==2)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>

                {{--view checklist if mahram is bapa--}}
                @elseif($row->mahram_relation=='2')
                    <div class="row">
                        <div class="col-md-2 col-xs-4">1</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijilnikah') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==1)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">2</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijillahir') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==5)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">3</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijillahirmahram') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==7)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>

                {{--view checklist if mahram abang/adik lelaki--}}
                @elseif($row->mahram_relation=='3')
                    <div class="row">
                        <div class="col-md-2 col-xs-4">1</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijillahir') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==1)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">2</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijillahirmahram') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==7)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">3</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijilmatibapa') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==8)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">4</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.suratkebenaran') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==9)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>

                    {{--view checklist if mahram bapa saudara--}}
                @elseif($row->mahram_relation=='4')
                    <div class="row">
                        <div class="col-md-2 col-xs-4">1</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijillahir') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==5)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">2</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.IDcardbapa') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==3)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">3</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijillahirbapa') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==6)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">4</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijillahirmahram') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==7)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">5</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.suratkebenaran') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==9)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>

                    {{--view if mahram datuk sebelah bapa--}}
                @elseif($row->mahram_relation=='5')
                    <div class="row">
                        <div class="col-md-2 col-xs-4">1</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijillahir') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==5)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">2</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijillahirbapa') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==6)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">3</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.IDcardbapa') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==3)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">4</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.IDcarddatuk') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==4)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">5</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.suratkebenaran') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==9)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                @elseif($row->mahram_relation=='6')
                    <div class="row">
                        <div class="col-md-2 col-xs-4">1</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijillahir') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==5)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">2</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.suratcerai') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==11)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">3</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijilnikah') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==1)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">4</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.suratkebenaran') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==9)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>

                    {{--checklist if mahram is bapa angkat--}}
                @elseif($row->mahram_relation=='7')
                    <div class="row">
                        <div class="col-md-2 col-xs-4">1</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijillahir') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==5)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">2</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.suratakuananakangkat') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==10)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-4">3</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.sijilnikah') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==1)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <br>
                    {{--@elseif($row->mahram_relation=='8')--}}
                    {{--@elseif($row->mahram_relation=='9')--}}
                @else
                    <div class="row">
                        <div class="col-md-2 col-xs-4">1</div>
                        <div class="col-md-7 col-xs-4">{{ lang::get('core.IDcard') }}</div>
                        <div class="col-md-3 col-xs-4">
                            @if($row->travellerID)
                                @foreach($file as $fl)
                                    @if($fl['file_type']==2)
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-check-circle text-green tips" title="'.Lang::get('core.fr_mactive').'"></i></div>
                                    @elseif($fl['file_type']=='')
                                        <div class="col-md-2 col-xs-2"><i class="fa fa-fw fa-2x fa-close text-red tips" title="'.Lang::get('core.cancelled').'"></i></div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif

            </div>

            <div class="tab-pane" id="tab_8">
                @if(count($mahram_list)>0)
                    <table id="bookings" class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{Lang::get('core.name')}}</th>
                                <th>{{Lang::get('core.mahram_relation')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mahram_list as $mlist)
                                <tr>
                                    <td>

                                        <a href="{{ url('travellers/show/'. $mlist->travellerID.'?return=') }}">{{ $mlist->nameandsurname }}</a>

                                    </td>
                                    <td>
                                        @if($mlist->mahram_relation==1)
                                            {{ lang::get('core.anak') }}
                                        @elseif($mlist->mahram_relation==2)
                                            {{ lang::get('core.isteri') }}
                                        @elseif($mlist->mahram_relation==3)
                                            {{ lang::get('core.kakak') }}
                                        @elseif($mlist->mahram_relation==4)
                                            {{ lang::get('core.anaksaudara') }}
                                        @elseif($mlist->mahram_relation==5)
                                            {{ lang::get('core.cucu') }}
                                        @elseif($mlist->mahram_relation==6)
                                            {{ lang::get('core.anakangkat') }}
                                        @elseif($mlist->mahram_relation==7)
                                            {{ lang::get('core.anaktiri') }}

                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                @else
                    {{ lang::get('core.nomahram') }}
                @endif


            </div>
        </div>
    </div>
</div>

<div style="clear: both;"></div>

<script>
    $(function() {
        $("#bookings,#invoices,#payments,#notes,#files").DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": false
        });
            
            $('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("travellers/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		

    });
    
        


</script>

<script type="text/javascript">
    $(function() {
        $('.editItem').click(function() {
            $('.displayItem').hide();
            $('.displayEdit').show();
        });
        $('.closeItem').click(function() {
            $('.displayItem').show();
            $('.displayEdit').hide();
        });
        
    $('[data-toggle=confirmation]').confirmation({
    rootSelector: '[data-toggle=confirmation]',
    container: 'body'
  });

    })

</script>

@stop
