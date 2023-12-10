<?php namespace App\Http\Controllers;

use App\Models\Piform;
use App\Models\Tours;
use App\Models\Tourcategories;
use App\Models\Tourdates;
use App\Models\Guide;
use App\Models\Hotels;
use App\Models\Suppliertypes;
use App\Models\Flightbooking;
use App\Models\Accomodation;
use App\Models\Ziarah;
use App\Models\Transportation;
use App\Models\LocalContact;
use App\Models\Travellers;
use App\Models\PiformRemark;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect, DB, Auth, Carbon, PDF ; 


class PiformController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'piform';
	static $per_page	= '100000';

	public function __construct()
	{
		
		parent::__construct();
		$this->model = new Piform();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = array();
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'piform',
			'return'	=> self::returnUrl()
			
		);
		
		
	}

	public function getIndex( Request $request )
	{
		// Make Sure users Logged 
		if(!\Auth::check()) 
			return redirect('user/login')->with('msgstatus', 'error')->with('messagetext','You are not login');

		$this->access = $this->model->validAccess($this->info['id'] , session('gid'));
		if($this->access['is_view'] ==0) 
			return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'id'); 
		$order = (!is_null($request->input('order')) ? $request->input('order') : 'asc');
		// End Filter sort and order for query 
		// Filter Search for query		
		$filter = '';	
		if(!is_null($request->input('search')))
		{
			$search = 	$this->buildSearch('maps');
			$filter = $search['param'];
			$this->data['search_map'] = $search['maps'];
		} 

		// $piforms = Piform::where('owner_id', CNF_OWNER)->paginate(25);
		
		$page = $request->input('page', 1);
		$params = array(
			'page'		=> $page ,
			'limit'		=> (!is_null($request->input('rows')) ? filter_var($request->input('rows'),FILTER_VALIDATE_INT) : static::$per_page ) ,
			'sort'		=> $sort ,
			'order'		=> $order,
			'params'	=> $filter,
			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
		);
		// Get Query 
		$results = $this->model->getRows( $params , session('uid') );

		foreach ($results['rows'] as $row) {
			$row->tourdate = Tourdates::find($row->tourdate_id);
		}
		
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('piform');
		
		$this->data['rowData']		= $results['rows'];
		// Build Pagination 
		$this->data['pagination']	= $pagination;
		// Build pager number and append current param GET
		$this->data['pager'] 		= $this->injectPaginate();	
		// Row grid Number 
		$this->data['i']			= ($page * $params['limit'])- $params['limit']; 
		// Grid Configuration 
		$this->data['tableGrid'] 	= $this->info['config']['grid'];
		$this->data['tableForm'] 	= $this->info['config']['forms'];
		$this->data['colspan'] 		= \SiteHelpers::viewColSpan($this->info['config']['grid']);		
		// Group users permission
		$this->data['access']		= $this->access;
		// Detail from master if any
		$this->data['fields'] =  \SiteHelpers::fieldLang($this->info['config']['grid']);
		// Master detail link if any 
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array()); 
				
		// Render into template
		return view('piform.index',$this->data);
	}	



	function getUpdate(Request $request, $id = null)
	{
		// Make Sure users Logged 
		if(!\Auth::check()) 
			return redirect('user/login')->with('msgstatus', 'error')->with('messagetext','You are not login');

		$this->access = $this->model->validAccess($this->info['id'] , session('gid'));
		if($id =='')
		{
			if($this->access['is_add'] ==0 )
			return redirect('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}	
		
		if($id !='')
		{
			if($this->access['is_edit'] ==0 )
			return redirect('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}				
				
		// $row = $this->model->find($id);
		// if($row)
		// {
		// 	$this->data['row'] =  $row;
		// } else {
		// 	$this->data['row'] = $this->model->getColumnTable('pi_forms'); 
		// }

		$piform = Piform::with(['accomodations'])->where('owner_id', CNF_OWNER)->where('id', $id)->get()->first();

		if ($piform) {
			$this->data['piform'] = $piform;
		}else{
			$this->data['piform'] = new Piform;
		}

		if ($request->tourdate) {
			$this->data['tourdate'] = Tourdates::where('owner_id', CNF_OWNER)->where('tourdateID', $request->tourdate)->get()->first();
		}else{
			$this->data['tourdate'] = null;
		}

		$this->data['fields'] =  \SiteHelpers::fieldLang($this->info['config']['forms']);

		// $supplier_types = Suppliertypes::where('owner_id', CNF_OWNER)->where('status', 1)->get();

		// $suppliers = [];

		// foreach ($supplier_types as $type) {
		// 	array_push($suppliers, $type->supplier_type);
		// 	foreach ($supplier_types->suppliers as $supplier) {
				
		// 	}
		// }

		$this->data['tours'] = Tours::where('owner_id', CNF_OWNER)->get();
		$this->data['tourcategories'] = Tourcategories::where('owner_id', CNF_OWNER)->get();
		$this->data['guides'] = Guide::where('owner_id', CNF_OWNER)->get();
		$this->data['hotels'] = Hotels::where('owner_id', CNF_OWNER)->where('status', 1)->get();
		$this->data['supplier_types'] = Suppliertypes::where('owner_id', CNF_OWNER)->where('status', 1)->get();
		
		$this->data['id'] = $id;
		return view('piform.form',$this->data);
	}	

	public function getShow( Request $request, $id = null)
	{
		// Make Sure users Logged 
		if(!\Auth::check()) 
			return redirect('user/login')->with('msgstatus', 'error')->with('messagetext','You are not login');

		$this->access = $this->model->validAccess($this->info['id'] , session('gid'));
		if($this->access['is_detail'] ==0) 
		return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
					
		$row = $this->model->getRow($id);
		$piform = Piform::find($id);
		$flight = $piform->tourdate->flight->where('status', 2)->first();
		if($row)
		{
			$booktours = $piform->tourdate->booktours;

			$adults = 0;
			$children = 0;
			$infants = 0;
			$total = 0;

			foreach ($booktours as $booktour) {
				$booking = $booktour->booking;
				if ($booking) {
					$bookrooms = $booking->bookRoom;
					if ($bookrooms) {
						foreach ($bookrooms as $room) {
							$travellers_id = explode(',', $room->travellers);
							foreach ($travellers_id as $id) {
								$traveller = Travellers::find($id);
								if ($traveller) {
									$age = Carbon::parse($traveller->dateofbirth)->age;
									$total++;
									if ($age > 12) {
										$adults++;
									}elseif ($age > 2) {
										$children++;
									}else{
										$infants++;
									}
								}
							}
						}
					}
				}
				
			}

			$pax = ['adults' => $adults,'children' => $children,'infants' => $infants, 'total' => $total];

			$this->data['row'] =  $row;
			$this->data['piform'] = $piform;
			$this->data['pax'] = $pax;
			$this->data['flight'] = $flight;
			$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
			$this->data['id'] = $id;
			$this->data['access']		= $this->access;
			$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array()); 
			$this->data['fields'] =  \SiteHelpers::fieldLang($this->info['config']['grid']);
			$this->data['prevnext'] = $this->model->prevNext($id);
			return view('piform.view',$this->data);
		} else {
			return Redirect::to('piform')->with('messagetext','Record Not Found !')->with('msgstatus','error');					
		}
	}

	function postCopy( Request $request)
	{
		// Make Sure users Logged 
		if(!\Auth::check()) 
			return redirect('user/login')->with('msgstatus', 'error')->with('messagetext','You are not login');

		$this->access = $this->model->validAccess($this->info['id'] , session('gid'));
		if($this->access['is_add'] ==0) 
			return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');


	    foreach(\DB::select("SHOW COLUMNS FROM pi_forms ") as $column)
        {
			if( $column->Field != 'id')
				$columns[] = $column->Field;
        }
		
		if(count($request->input('ids')) >=1)
		{
			$toCopy = implode(",",$request->input('ids'));
			$sql = "INSERT INTO pi_forms (".implode(",", $columns).") ";
			$sql .= " SELECT ".implode(",", $columns)." FROM pi_forms WHERE id IN (".$toCopy.")";
			\DB::select($sql);
			return Redirect::to('piform')->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
		} else {
		
			return Redirect::to('piform')->with('messagetext','Please select row to copy')->with('msgstatus','error');
		}	
		
	}		

	function postSave( Request $request)
	{
		// dd($request->all());
		// Make Sure users Logged 
		if(!\Auth::check()) 
			return redirect('user/login')->with('msgstatus', 'error')->with('messagetext','You are not login');

		$user = Auth::user();
		// $rules = $this->validateForm();
		// $validator = Validator::make($request->all(), $rules);	
		// if ($validator->passes()) {
		// 	$data = $this->validatePost( $request );
				
		// 	$id = $this->model->insertRow($data , $request->input('id'));
			
		// 	if(!is_null($request->input('apply')))
		// 	{
		// 		$return = 'piform/update/'.$id.'?return='.self::returnUrl();
		// 	} else {
		// 		$return = 'piform?return='.self::returnUrl();
		// 	}

		// 	// Insert logs into database
		// 	if($request->input('id') =='')
		// 	{
		// 		\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
		// 	} else {
		// 		\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
		// 	}

		// 	return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		// } else {

		// 	return Redirect::to('piform/update/'. $request->input('id'))->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
		// 	->withErrors($validator)->withInput();
		// }	

		DB::beginTransaction();

		if ($request->id) {
			$piform = Piform::find($request->id);
		}else{
			$piform = new Piform;
			$piform->pif_number = Piform::where('owner_id', CNF_OWNER)->count()+1;
			$piform->entry_by = $user->id;
			$piform->owner_id = CNF_OWNER;
		}

		$piform->tourdate_id = $request->tourdate_id;
		$piform->group_name = $request->group_name ?? null;
		$piform->leader_id  = $request->leader_id ?? null;
		$piform->save();

		$accom_ids = [];

		foreach ($request->accomodation as $accom) {
			if ($accom['id']) {
				$accomodation = $piform->accomodations->where('id', (integer)$accom['id'])->first();
			}else{
				$accomodation = new Accomodation;
				$accomodation->entry_by = $user->id;
				$accomodation->owner_id = CNF_OWNER;
				$accomodation->pi_form_id = $piform->id;
				$accomodation->booked_by = CNF_COMNAME;
			}

			$accomodation->hotel_id = $accom['hotel_id'];
			$accomodation->check_in = $accom['check_in'];
			$accomodation->check_out = $accom['check_out'];
			$accomodation->single = $accom['single'] ?? 0;
			$accomodation->double = $accom['double'] ?? 0;
			$accomodation->triple = $accom['triple'] ?? 0;
			$accomodation->quad = $accom['quad'] ?? 0;
			$accomodation->quint = $accom['quint'] ?? 0;
			$accomodation->sext = $accom['sext'] ?? 0;
			$accomodation->save();

			array_push($accom_ids, $accomodation->id);
		}

		foreach ($piform->accomodations as $accom) {
			if (!in_array($accom->id, $accom_ids)) {
				$accom->delete();
			}
		}

		$zia_ids = [];

		foreach ($request->ziarah as $zia) {
			if ($zia['id']) {
				$ziarah = $piform->ziarahs->where('id', (integer)$zia['id'])->first();
			}else{
				$ziarah = new Ziarah;
				$ziarah->entry_by = $user->id;
				$ziarah->owner_id = CNF_OWNER;
				$ziarah->pi_form_id = $piform->id;
			}

			$ziarah->city = $zia['city'];
			$ziarah->date = $zia['date'];
			$ziarah->time = $zia['time'];
			$ziarah->transport = $zia['transport'];
			$ziarah->save();

			array_push($zia_ids, $ziarah->id);
		}

		foreach ($piform->ziarahs as $zia) {
			if (!in_array($zia->id, $zia_ids)) {
				$zia->delete();
			}
		}

		$trans_id = [];

		foreach ($request->transportation as $trans) {
			if ($trans['id']) {
				$transportation = $piform->transportations->where('id', (integer)$trans['id'])->first();
			}else{
				$transportation = new Transportation;
				$transportation->entry_by = $user->id;
				$transportation->owner_id = CNF_OWNER;
				$transportation->pi_form_id = $piform->id;
			}

			$transportation->to = $trans['to'];
			$transportation->from = $trans['from'];
			$transportation->date = $trans['date'];
			$transportation->time = $trans['time'];
			$transportation->remarks = $trans['remarks'];
			$transportation->save();

			array_push($trans_id, $transportation->id);
		}

		foreach ($piform->transportations as $trans) {
			if (!in_array($trans->id, $trans_id)) {
				$trans->delete();
			}
		}

		$lc_ids = [];

		foreach ($request->local_contact as $lc) {
			if ($lc['id']) {
				$local_contact = $piform->localcontacts->where('id', (integer)$lc['id'])->first();
			}else{
				$local_contact = new LocalContact;
				$local_contact->entry_by = $user->id;
				$local_contact->owner_id = CNF_OWNER;
				$local_contact->pi_form_id = $piform->id;
			}

			$local_contact->name = $lc['name'];
			$local_contact->contact = $lc['contact'];
			$local_contact->save();

			array_push($lc_ids, $local_contact->id);
		}

		foreach ($piform->localcontacts as $lc) {
			if (!in_array($lc->id, $lc_ids)) {
				$lc->delete();
			}
		}

		$rem_ids = [];

		foreach ($request->remark as $rem) {
			if ($rem['id']) {
				$remark = $piform->remarks->where('id', (integer)$rem['id'])->first();
			}else{
				$remark = new PiformRemark;
				$remark->entry_by = $user->id;
				$remark->owner_id = CNF_OWNER;
				$remark->pi_form_id = $piform->id;
			}

			$remark->remark = $rem['remark'];
			$remark->save();

			array_push($rem_ids, $remark->id);
		}

		foreach ($piform->remarks as $rem) {
			if (!in_array($rem->id, $rem_ids)) {
				$rem->delete();
			}
		}

		DB::commit();

		if(!is_null($request->input('apply')))
		{
			$return = 'piform/update/'.$id.'?return='.self::returnUrl();
		} else {
			$return = 'piform?return='.self::returnUrl();
		}

		return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
	
	}	

	public function postDelete( Request $request)
	{
		// Make Sure users Logged 
		if(!\Auth::check()) 
			return redirect('user/login')->with('msgstatus', 'error')->with('messagetext','You are not login');

		$this->access = $this->model->validAccess($this->info['id'] , session('gid'));
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		// delete multipe rows 
		if(count($request->input('ids')) >=1)
		{
			$this->model->destroy($request->input('ids'));
			
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('piform')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('piform')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}	

	public static function display( )
	{
		$mode  = isset($_GET['view']) ? 'view' : 'default' ;
		$model  = new Piform();
		$info = $model::makeInfo('piform');

		$data = array(
			'pageTitle'	=> 	$info['title'],
			'pageNote'	=>  $info['note']
			
		);

		if($mode == 'view')
		{
			$id = $_GET['view'];
			$row = $model::getRow($id);
			if($row)
			{
				$data['row'] =  $row;
				$data['fields'] 		=  \SiteHelpers::fieldLang($info['config']['grid']);
				$data['id'] = $id;
				return view('piform.public.view',$data);
			} 

		} else {

			$page = isset($_GET['page']) ? $_GET['page'] : 1;
			$params = array(
				'page'		=> $page ,
				'limit'		=>  (isset($_GET['rows']) ? filter_var($_GET['rows'],FILTER_VALIDATE_INT) : 10 ) ,
				'sort'		=> 'id' ,
				'order'		=> 'asc',
				'params'	=> '',
				'global'	=> 1 
			);

			$result = $model::getRows( $params );
			$data['tableGrid'] 	= $info['config']['grid'];
			$data['rowData'] 	= $result['rows'];	

			$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
			$pagination = new Paginator($result['rows'], $result['total'], $params['limit']);	
			$pagination->setPath('');
			$data['i']			= ($page * $params['limit'])- $params['limit']; 
			$data['pagination'] = $pagination;
			return view('piform.public.index',$data);			
		}


	}

	function postSavepublic( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost(  $request );		
			 $this->model->insertRow($data , $request->input('id'));
			return  Redirect::back()->with('messagetext','<p class="alert alert-success">'.\Lang::get('core.note_success').'</p>')->with('msgstatus','success');
		} else {

			return  Redirect::back()->with('messagetext','<p class="alert alert-danger">'.\Lang::get('core.note_error').'</p>')->with('msgstatus','error')
			->withErrors($validator)->withInput();

		}	
	
	}	

	public function postGettour(Request $request)
	{
		$tours = Tours::where('owner_id', CNF_OWNER)->where('status', 1)->where('tourcategoriesID', $request->id)->get();

		return $tours;
	}

	public function postGetdate(Request $request)
	{
		$dates = Tourdates::where('owner_id', CNF_OWNER)->where('status', 1)->where('tourID', $request->id)->get();

		return $dates;
	}

	public function postGetflight(Request $request)
	{
		$flightbooking = Flightbooking::where('owner_id', CNF_OWNER)->where('status', 2)->where('tourdates_id', $request->id)->get()->first();

		if ($flightbooking) {
			$string = 
				'
				<table border="1">
					<tr>
						<th style="width: 100px;">Carrier</th>
						<th style="width: 100px;">From/To</th>
						<th style="width: 100px;">Date</th>
						<th style="width: 100px;">ETD</th>
						<th style="width: 100px;">ETA</th>
						<th style="width: 100px;">PNR</th>
					</tr>
					<tr>
						<td>'.$flightbooking->depart->flightDate->flight_company.'</td>
						<td>'.$flightbooking->depart->sector.'</td>
						<td>'.$flightbooking->departure_date.'</td>
						<td>'.$flightbooking->depart->dep_time.'</td>
						<td>'.$flightbooking->depart->arr_time.'</td>
						<td>'.$flightbooking->pnr.'</td>
					</tr>
					<tr>
						<td>'.$flightbooking->return->flightDate->flight_company.'</td>
						<td>'.$flightbooking->return->sector.'</td>
						<td>'.$flightbooking->return_date.'</td>
						<td>'.$flightbooking->return->dep_time.'</td>
						<td>'.$flightbooking->return->arr_time.'</td>
						<td>'.$flightbooking->pnr.'</td>
					</tr>
				</table>
				';
		}else{
			$string = 'No confirmed flight booking found. Flight booking schedule will be updated automatically once confirmed booking found.';
		}
		

		return $string;
	}

	public function getPdf( Request $request, $id = null)
	{
		// Make Sure users Logged 
		if(!\Auth::check()) 
			return redirect('user/login')->with('msgstatus', 'error')->with('messagetext','You are not login');

		$this->access = $this->model->validAccess($this->info['id'] , session('gid'));
		if($this->access['is_detail'] ==0) 
		return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
					
		$row = $this->model->getRow($id);
		$piform = Piform::find($id);
		$flight = $piform->tourdate->flight->where('status', 2)->first();
		if($row)
		{
			$booktours = $piform->tourdate->booktours;

			$adults = 0;
			$children = 0;
			$infants = 0;
			$total = 0;

			foreach ($booktours as $booktour) {
				$booking = $booktour->booking;
				if ($booking) {
					$bookrooms = $booking->bookRoom;
					if ($bookrooms) {
						foreach ($bookrooms as $room) {
							$travellers_id = explode(',', $room->travellers);
							foreach ($travellers_id as $id) {
								$traveller = Travellers::find($id);
								if ($traveller) {
									$age = Carbon::parse($traveller->dateofbirth)->age;
									$total++;
									if ($age > 12) {
										$adults++;
									}elseif ($age > 2) {
										$children++;
									}else{
										$infants++;
									}
								}
							}
						}
					}
				}
				
			}

			$pax = ['adults' => $adults,'children' => $children,'infants' => $infants, 'total' => $total];

			$this->data['row'] =  $row;
			$this->data['piform'] = $piform;
			$this->data['pax'] = $pax;
			$this->data['flight'] = $flight;
			$this->data['fields'] 		=  \SiteHelpers::fieldLang($this->info['config']['grid']);
			$this->data['id'] = $id;
			$this->data['access']		= $this->access;
			$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array()); 
			$this->data['fields'] =  \SiteHelpers::fieldLang($this->info['config']['grid']);
			$this->data['prevnext'] = $this->model->prevNext($id);
			// return view('piform.pdf',$this->data);
			return PDF::loadView('piform.pdf', $this->data)->stream('piform'.sprintf('%04d',$piform->pif_number).'.pdf');
		} else {
			return Redirect::to('piform')->with('messagetext','Record Not Found !')->with('msgstatus','error');					
		}
	}


}