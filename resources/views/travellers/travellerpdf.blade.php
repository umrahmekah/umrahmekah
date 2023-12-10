<html>
    
<head>
<meta charset="utf-8">
<title>{{$traveller->nameandsurname}}</title>
    
<style type="text/css"> 
    
body,td,th, {
	font-family: Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif;
	font-style: normal;
	font-size: 13px;
	color: #393939;
}
.title { font-family: 'Montserrat', sans-serif; color:#0087C3; font-size: 16px;}
    thead:before, thead:after,
    tbody:before, tbody:after,
    tfoot:before, tfoot:after
    {
        display: none;
    }
    
body{
width:100%;
height:100%;  
position:absolute;
top:0;
left:0;
margin: 0;
}
    
nav {
  float: left;
  width: 20%;
  height: 100%; 
  background: #ccc;
  padding: 20px;
}
    
.vl {
  border-left: 6px solid blue;
  height: 100px;
  position: absolute;
  left: 50%;
}
    
table, th, td{
  border: 1px;
  border-style: groove;
}
    
th{
  background-color: #D3D3D3;
}
      
</style>
    
</head>
 
    <nav>
        <div>
        
            @if(file_exists('./uploads/images/'.CNF_OWNER.'/'.$traveller->image) && $traveller->image !='')
                <img width=150 src="{{ asset('./uploads/images/'.CNF_OWNER.'/'.$traveller->image) }}">
            @else
                <img width=150 src=" {{ asset('/uploads/images/no-image-person.png') }}" />
            @endif
            
        </div>
        
        <h3 align="center">Personal Details</h3>
       <a> 
           <b>Name: </b><p>{{ $traveller->nameandsurname}}</p>
       </a>
        
       <a> 
           <b>{{Lang::get('core.age')}}: </b><p>{{ \Carbon::parse($traveller->dateofbirth)->age }}</p>
       </a>
        <a> 
           <b>{{Lang::get('core.nationality')}}: </b><p>{{ $nationality->country_name }}</p>
       </a>
       <a>
        <b>{{Lang::get('core.phone')}}: </b> <p>{{$traveller->phone}} </p>
       </a>
       <a>
           <b>{{Lang::get('core.email')}}: </b> <p>{{ $traveller->email}}</p>
       </a>
       <a>
           <b>{{Lang::get('core.address')}}: </b><p>{{ $traveller->address}}, {{ $traveller->city }}, {{$traveller->country_name}} </p>
       </a>
       <a>
        <b>{{Lang::get('core.interests')}}: </b><p>{{ $traveller->interests}}</p>
       </a> 
   </nav>
    
<body>
    <div style="margin-left:38%; padding-top:2%">
        <table style="width: 112%; border-style: none">
            <tr>
				<td align="right" style="font-size: 20px; vertical-align: bottom; border-style: none"><strong>{{ CNF_COMNAME }}</strong></td>
            </tr>
            <tr>
				<td align="right" style="border-style: none">{{ CNF_ADDRESS }}</td>
            </tr>

            <tr>
				<td align="right" style="border-style: none">{{ CNF_TEL }}</td>
            </tr>

            <tr>
                <td align="right" style="border-style: none">{{ CNF_EMAIL }}</td>
            </tr>
        </table>
    </div>
    
    <div style="margin-left:31%">
        <table style="width:112%; border-style: hidden;">
           <tr>
           <th style="border-style: hidden; background-color: white;"><hr style="color: #ccc"></th> 
           </tr>
         </table> 
    </div>
    
    <div style="margin-left:32%; padding-top:3%">
        <table style="width:108%; border-style: hidden;">
            <tr>
				<td align="center" style="font-size: 23px; vertical-align: bottom; border-style: none; text-transform: uppercase;"><strong>{{ $traveller->nameandsurname}}</strong></td>
            </tr>
        </table>
    </div>
    
    <!--PASSPORT!-->
         <div style="margin-left:32%; margin-top:3%;">
        <table style="width:108%; border-style: hidden;">
        <tr>
        <th align="center">{{Lang::get('core.passport')}}</th>
        </tr>
        <tr><td>
        <table style="width:100%; border-style: hidden;">
           <tr>
           <th style="border-style: hidden; background-color: white;"></th> 
           <th style="border-style: hidden; background-color: white;"></th>
           </tr>
           <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.passportno')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ $traveller->passportno}}</td>
           </tr>
            <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.dateofexpiry')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ SiteHelpers::TarihFormat($traveller->passportexpiry)}}</td>
           </tr>
            <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.passportplaceissue')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ $traveller->passport_place_made }}</td>
           </tr>
            <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.dateofbirth')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ SiteHelpers::TarihFormat($traveller->dateofbirth)}}</td>
           </tr>
            <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.dateofissue')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ SiteHelpers::TarihFormat($traveller->passportissue)}}</td>
           </tr>
            <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.passportcountry')}}</b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{$passportCountry->country_name}}</td>
           </tr>
        
         </table>
            </td>
            </tr>
        </table>  
    </div>
    
    <!--MAHRAM DETAILS-->
    <div style="margin-top:3%">
    @if($traveller->mahram_id != 0)
    <div style="margin-left:32%">
        <table style="width:108%; border-style: hidden;">
        <tr>
        <th align="center">Mahram Details</th>
        </tr>
        <tr><td>
        <table style="width:100%; border-style: hidden;">
           <tr>
           <th style="border-style: hidden; background-color: white;"></th> 
           <th style="border-style: hidden; background-color: white;"></th>
           </tr>
           <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.mahram')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ $mahram->nameandsurname }}</td>
           </tr>
            <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.relation')}}</b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right">
            @if($traveller->mahram_relation==1)
                                            {{ lang::get('core.mahram_relation_1') }}
                                        @elseif($traveller->mahram_relation==2)
                                            {{ lang::get('core.mahram_relation_2') }}
                                        @elseif($traveller->mahram_relation==3)
                                            {{ lang::get('core.mahram_relation_3') }}
                                        @elseif($traveller->mahram_relation==4)
                                            {{ lang::get('core.mahram_relation_4') }}
                                        @elseif($traveller->mahram_relation==5)
                                            {{ lang::get('core.mahram_relation_5') }}
                                        @elseif($traveller->mahram_relation==6)
                                            {{ lang::get('core.mahram_relation_6') }}
                                        @elseif($traveller->mahram_relation==7)
                                            {{ lang::get('core.mahram_relation_7') }}
                                        @else
                                            {{ lang::get('core.mahram_relation_8') }}
                                        @endif
            </td>
           </tr>
         </table>
            </td>
            </tr>
        </table>  
    </div>
    @else
     <div style="margin-left:32%">
        <table style="width:108%; border-style: hidden;">
        <tr>
        <th align="center">Mahram Details</th>
        </tr>
        <tr><td>
        <table style="width:100%; border-style: hidden;">
           <tr>
           <th style="border-style: hidden; background-color: white;"></th> 
           <th style="border-style: hidden; background-color: white;"></th>
           </tr>
           <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.mahram')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ lang::get('core.is_mahram') }}</td>
           </tr>
            <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.relation')}}</b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ lang::get('core.is_mahram') }}</td>
           </tr>
         </table>
            </td>
            </tr>
        </table>  
    </div>
    </div>
    @endif
    
    <!--EMERGENCY CONTACT DETAILS!-->
    <div style="margin-top:3%">
    <div style="margin-left:32%">
        <table style="width:108%; border-style: hidden;">
        <tr>
        <th align="center">{{Lang::get('core.emergencycontactdetails')}}</th>
        </tr>
        <tr><td>
        <table style="width:100%; border-style: hidden;">
           <tr>
           <th style="border-style: hidden; background-color: white;"></th> 
           <th style="border-style: hidden; background-color: white;"></th>
           </tr>
           <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.emergencycontact')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ $traveller->emergencycontactname}}</td>
           </tr>
            <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.phone')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ $traveller->emergencycontanphone}}</td>
           </tr>
            <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.email')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ $traveller->emergencycontactemail}}</td>
           </tr>
         </table>
            </td>
            </tr>
        </table>  
    </div>
    </div>
    
    <!--INSURANCE DETAILS!-->
    <div style="margin-top:3%">
    <div style="margin-left:32%">
        <table style="width:108%; border-style: hidden;">
        <tr>
        <th align="center">{{Lang::get('core.insurancedetails')}}</th>
        </tr>
        <tr><td>
        <table style="width:100%; border-style: hidden;">
           <tr>
           <th style="border-style: hidden; background-color: white;"></th> 
           <th style="border-style: hidden; background-color: white;"></th>
           </tr>
           <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.insurancecompany')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ $traveller->insurancecompany}}</td>
           </tr>
            <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.insurancepolicyno')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ $traveller->insurancepolicyno}}</td>
           </tr>
            <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.insurancecompanyphone')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right">{{ $traveller->insurancecompanyphone}}</td>
           </tr>
         </table>
            </td>
            </tr>
        </table>  
    </div>
    </div>
    
    <!--SPECIAL REQUIREMENTS!-->
    <div style="margin-top:3%">
    <div style="margin-left:32%">
        <table style="width:108%; border-style: hidden;">
        <tr>
        <th align="center">{{Lang::get('core.specialreq')}}</th>
        </tr>
        <tr><td>
        <table style="width:100%; border-style: hidden;">
           <tr>
           <th style="border-style: hidden; background-color: white;"></th> 
           <th style="border-style: hidden; background-color: white;"></th>
           </tr>
           <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.dietaryreq')}} </b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ $traveller->dietaryrequirements}}</td>
           </tr>
            <tr>
            <td style="border-style: hidden"><b>{{Lang::get('core.bedconfiguration')}}</b></td>
            <td style="border-style: hidden; padding-right:5%;" align="right"> {{ $traveller->bedconfiguration}}</td>
           </tr>
         </table>
            </td>
            </tr>
        </table>  
    </div>
    </div>
    
</body>
</html>
