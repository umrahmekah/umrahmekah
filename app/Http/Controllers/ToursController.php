<?php

namespace App\Http\Controllers;

use App\Models\Tandc;
use App\Models\Tourcategories;
use App\Models\tourdates;
use App\Models\Tours;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Session;
use Redirect;
use Validator;

//test push

class ToursController extends Controller
{
    public $module          = 'tours';
    public static $per_page = '10000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model = new Tours();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'tours',
            'return'     => self::returnUrl(),
        ];

        \App::setLocale(CNF_LANG);
        if (defined('CNF_MULTILANG') && CNF_MULTILANG == '1') {
            $lang = ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG);
            \App::setLocale($lang);
        }
    }

    public function getIndex(Request $request)
    {
        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $sort  = (! is_null($request->input('sort')) ? $request->input('sort') : 'tourID');
        $order = (! is_null($request->input('order')) ? $request->input('order') : 'asc');
        // End Filter sort and order for query
        $filter = '';
        if (! is_null($request->input('search'))) {
            $search                   = $this->buildSearch('maps');
            $filter                   = $search['param'];
            $this->data['search_map'] = $search['maps'];
        }

        $page   = $request->input('page', 1);
        $params = [
            'page'   => $page,
            'limit'  => (! is_null($request->input('rows')) ? filter_var($request->input('rows'), FILTER_VALIDATE_INT) : static::$per_page),
            'sort'   => $sort,
            'order'  => $order,
            'params' => $filter,
            'global' => (isset($this->access['is_global']) ? $this->access['is_global'] : 0),
        ];
        // Get Query
        $results = $this->model->getRows($params);

        // Build pagination setting
        $page       = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
        $pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
        $pagination->setPath('tours');

        $this->data['rowData']    = $results['rows'];
        $this->data['pagination'] = $pagination;
        $this->data['pager']      = $this->injectPaginate();
        $this->data['i']          = ($page * $params['limit']) - $params['limit'];
        $this->data['tableGrid']  = $this->info['config']['grid'];
        $this->data['tableForm']  = $this->info['config']['forms'];
        $this->data['colspan']    = \SiteHelpers::viewColSpan($this->info['config']['grid']);
        // Group users permission
        $this->data['access'] = $this->access;
        // Detail from master if any
        $this->data['fields'] = \AjaxHelpers::fieldLang($this->info['config']['grid']);
        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
        // Render into template

        return view('tours.index', $this->data);
    }

    public function getUpdate(Request $request, $id = null)
    {
        if ('' == $id) {
            if (0 == $this->access['is_add']) {
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }

        if ('' != $id) {
            if (0 == $this->access['is_edit']) {
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }

        $row = $this->model->retrive($id);
        if ($row) {
            $this->data['row'] = $row;
            $count = Tours::where('owner_id', CNF_OWNER)->where('tour_name', 'LIKE', $row['tour_name'])->where('tourID', '<>', $id)->get()->count();
            if ($count > 0) {
                $this->data['name_error'] = true;
            }
        } else {
            if ('' == $id) {
                $this->data['row'] = $this->model->getColumnTable('tours');
                foreach ($this->data['row'] as $key => $old) {
                    if (gettype( old($key) ) === 'array') {
                        $this->data['row'][$key] = implode(old($key));
                    }else{
                        $this->data['row'][$key] = old($key) ?? "";
                    }
                }
                // dd($this->data['row']);
            } else {
                return Redirect::to('tours')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }
        $categories               = Tourcategories::where('owner_id', CNF_OWNER)->where('type', 1)->where('status', 1)->get();
        $this->data['categories'] = $categories;
        $this->data['fields']     = \AjaxHelpers::fieldLang($this->info['config']['forms']);

        $this->data['id'] = $id;

        return view('tours.form', $this->data);
    }

    public function getShow(Request $request, $id = null)
    {
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->getRow($id);
        if ($row) {
            $row->tour_description = str_replace('initial;', ';', $row->tour_description);
            $this->data['row']      = $row;
            $this->data['fields']   = \SiteHelpers::fieldLang($this->info['config']['grid']);
            $this->data['id']       = $id;
            $this->data['access']   = $this->access;
            $this->data['subgrid']  = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
            $this->data['fields']   = \AjaxHelpers::fieldLang($this->info['config']['grid']);
            $this->data['prevnext'] = $this->model->prevNext($id);

            $tourdetail = \DB::table('tour_detail')->where('tourID', $id)->orderBy('day', 'ASC')->get();
            $dayTree    = [];
            $first      = 0;
            foreach ($tourdetail as $td) {
                $dayTree[] = [
                    'tourdetailID'   => $td->tourdetailID,
                    'title'          => $td->title,
                    'day'            => $td->day,
                    'countryID'      => $td->countryID,
                    'cityID'         => $td->cityID,
                    'hotelID'        => $td->hotelID,
                    'siteID'         => $td->siteID,
                    'meal'           => $td->meal,
                    'optionaltourID' => $td->optionaltourID,
                    'description'    => $td->description,
                    'icon'           => $td->icon,
                    'image'          => $td->image,
                ];
                ++$first;
            }
            $this->data['dayTree'] = $dayTree;

            if (! is_null($request->input('pdf'))) {
                return \PDF::loadView('tours.pdf', $this->data)->stream('tours'.$id.'.pdf');
                // $html = view('tours.pdf', $this->data)->render();

                // return \PDF::loadHTML($html)->stream('tours'.$id.'.pdf');
            }

            return view('tours.view', $this->data);
        } else {
            return Redirect::to('tours')->with('messagetext', \Lang::get('core.norecord'))->with('msgstatus', 'error');
        }
    }

    public function postCopy(Request $request)
    {
        foreach (\DB::select('SHOW COLUMNS FROM tours ') as $column) {
            if ('tourID' != $column->Field  && 'views' != $column->Field) {
                $columns[] = $column->Field;
            }
        }

        if (count($request->input('ids')) >= 1) {
            $toCopy = implode(',', $request->input('ids'));
            $sql    = 'INSERT INTO tours (' . implode(',', $columns) . ') ';
            $sql .= ' SELECT ' . implode(',', $columns) . ' FROM tours WHERE tourID IN (' . $toCopy . ')';
            \DB::insert($sql);

            return Redirect::to('tours')->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('tours')->with('messagetext', \Lang::get('core.note_selectrow'))->with('msgstatus', 'error');
        }
    }

    public function postSave(Request $request)
    {
        // $temp = Tours::where('tour_name', 'LIKE', $request->tour_name)->where('owner_id', CNF_OWNER)->where('tourID', '<>', $request->tourID)->get()->first();
        // if ($temp) {
        //     return redirect()->back()
        //         ->with('messagetext', 'The tour name already exist')->with('msgstatus', 'error')->withInput($request->input());
        // }
        // dd($request->all());
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('tb_tours');

            $id = $this->model->insertRow($data, $request->input('tourID'));

            $tour = Tours::find($id);

            $tourdates = $tour->tourdates;

            foreach ($tourdates as $tourdate) {
                $tourdate->tourcategoriesID = $tour->tourcategoriesID;
                $tourdate->save();
            }

            $tour->flight        = $request->input('flight');
            $tour->transit       = $request->input('transit');
            $tour->baggage_limit = $request->input('baggage_limit');
            $tour->sector        = $request->input('sector');
            $tour->b2b           = $request->input('b2b');

            $tour->save();

            if (! is_null($request->input('apply'))) {
                $return = 'tours/update/' . $id . '?return=' . self::returnUrl();
            } else {
                $return = 'tours?return=' . self::returnUrl();
            }

            // Insert logs into database
            if ('' == $request->input('tourID')) {
                \SiteHelpers::auditTrail($request, 'New Data with ID ' . $id . ' Has been Inserted !');
            } else {
                \SiteHelpers::auditTrail($request, 'Data with ID ' . $id . ' Has been Updated !');
            }

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('tours/update/' . $request->input('tourID'))->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
                ->withErrors($validator)->withInput();
        }
    }

    public function postDelete(Request $request)
    {
        if (0 == $this->access['is_remove']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        // delete multipe rows
        if (count($request->input('ids')) >= 1) {
            $this->model->destroy($request->input('ids'));

            \SiteHelpers::auditTrail($request, 'ID : ' . implode(',', $request->input('ids')) . '  , Has Been Removed Successfully');
            // redirect
            return Redirect::to('tours')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('tours')
                ->with('messagetext', \Lang::get('core.note_noitemdeleted'))->with('msgstatus', 'error');
        }
    }

    public function display(Request $request)
    {
        $tour_list = \DB::table('tours')->get();
        $category  = \DB::table('def_tour_categories')
            ->join('tours', 'tours.tourcategoriesID', '=', 'def_tour_categories.tourcategoriesID')
            ->where('tours.status', '=', '1')
            ->where('def_tour_categories.type', 1)
            ->where('tours.owner_id', '=', CNF_OWNER)
            ->groupBy('def_tour_categories.tourcategoriesID')
            ->get(['def_tour_categories.*', \DB::raw('count(*) as category_count')]);

        $category = Tourcategories::where('status', 1)->where('owner_id', CNF_OWNER)->where('type', 1)->get()->filter( function ($query) {
            foreach ($query->tours as $key => $tour) {
                if ($tour->filteredTourdate->count() > 0) {
                    return true;
                }
            }
            return false;
        });

        Session::put('affiliate', app('request')->input('affiliate'));

        $mode  = isset($_GET['view']) ? 'view' : 'default';
        $model = new Tours();
        $info  = $model::makeInfo('tours');

        $data = [
            'pageTitle'    => \Lang::get('core.packages'),
            'pageNote'     => $info['note'],
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
            'isMobile'     => $this->isMobile() ? 1 : 0
        ];

        if ('view' == $mode) {
            $id = $_GET['view'];

            \DB::table('tours')->where('tourID', $_GET['view'])->update(['views' => \DB::raw('views+1')]);

            $tourdetail = \DB::table('tour_detail')->where('tourID', $id)->orderBy('day', 'ASC')->get();
            $tourdate   = \DB::table('tour_date')->select(DB::raw('tour_date.*,tours.tour_name,tours.transit'))
                ->join('tours', 'tours.tourID', '=', 'tour_date.tourID')
                ->where('tour_date.tourID', $id)->where('tour_date.status', '1')->where('tours.b2b', 0)
                ->orderBy('tour_date.start', 'ASC')
                ->get();

            $tourdate = Tourdates::where('tourID', $_GET['view'])->where('status', 1)->get()->filter( function ($query) {
                return $query->notStartYet && $query->capacity > 0;
            });

            //find min price for each room
            $minsingle = tourdates::select('cost_single')->where('tourID', $id)->where('cost_single', '!=', 0)->min('cost_single');
            $mindouble = tourdates::select('cost_double')->where('tourID', $id)->where('cost_double', '!=', 0)->min('cost_double');
            $mintriple = tourdates::select('cost_triple')->where('tourID', $id)->where('cost_triple', '!=', 0)->min('cost_triple');
            $minquad   = tourdates::select('cost_quad')->where('tourID', $id)->where('cost_quad', '!=', 0)->min('cost_quad');
            $minquint   = tourdates::select('cost_quint')->where('tourID', $id)->where('cost_quint', '!=', 0)->min('cost_quint');
            $minsext   = tourdates::select('cost_sext')->where('tourID', $id)->where('cost_sext', '!=', 0)->min('cost_sext');

            //get the most lowest price available
            $data['minprice'] = min($minsingle, $mindouble, $mintriple, $minquad, $minquint, $minsext);

            $dayTree  = [];
            $first    = 0;
            $tourName = '';
            foreach ($tourdetail as $td) {
                $dayTree[] = [
                    'tourdetailID'   => $td->tourdetailID,
                    'title'          => $td->title,
                    'day'            => $td->day,
                    'countryID'      => $td->countryID,
                    'cityID'         => $td->cityID,
                    'hotelID'        => $td->hotelID,
                    'siteID'         => $td->siteID,
                    'meal'           => $td->meal,
                    'optionaltourID' => $td->optionaltourID,
                    'description'    => $td->description,
                    'icon'           => $td->icon,
                    'image'          => $td->image,
                ];
                $tourName = $td->title;
                ++$first;
            }
            $data['dayTree'] = $dayTree;

            $tdate = [];
            $sec   = 0;
            foreach ($tourdate as $trd) {
                $tdate[] = [
                    'tourdateID'         => $trd->tourdateID,
                    'tourname'           => $trd->tour->tour_name,
                    'tourID'             => $trd->tourID,
                    'tour_code'          => $trd->tour_code,
                    'start'              => $trd->start,
                    'end'                => $trd->end,
                    'featured'           => $trd->tour->featured,
                    'definite_departure' => $trd->definite_departure,
                    'total_capacity'     => $trd->total_capacity,
                    'cost_single'        => $trd->cost_single,
                    'cost_double'        => $trd->cost_double,
                    'cost_triple'        => $trd->cost_triple,
                    'cost_quad'          => $trd->cost_quad,
                    'cost_quint'         => $trd->cost_quint,
                    'cost_sext'          => $trd->cost_sext,
                    'cost_child'         => $trd->cost_child,
                    'cost_depo'          => $trd->cost_deposit,
                    'currencyID'         => $trd->tour->currencyID,
                    'status'             => $trd->tour->status,
                    'transit'            => $trd->tour->transit,
                    'tourdate'           => $trd,
                    'discount'           => $trd->discount
                ];
                ++$sec;
            }
            $data['tdate'] = $tdate;

            $row = $model::getRow($id);
            if ($row) {
                $data['pageTitle'] = $row->tour_name;
                $data['row']       = Tours::find($row->tourID);
                $data['fields']    = \SiteHelpers::fieldLang($info['config']['grid']);
                $data['id']        = $id;
                $data['category']  = Tourcategories::where('tourcategoriesID', $row->tourcategoriesID)->where('type', 1)->first();
                $data['seat_available_total'] = 0;
                
                return view('layouts.' . CNF_THEME . '.tour.view', $data);
            }
        } else {
            $sort   = ((isset($_GET['sort'])) ? $_GET['sort'] : 'tourID');
            $order  = ((isset($_GET['order'])) ? $_GET['order'] : 'asc');
            $filter = '';
            if (isset($_GET['search'])) {
                //			$search = $buildSearch('maps');
			// $filter = $search['param'];
			// $data['search_map'] = $search['maps'];
            }

            $page   = isset($_GET['page']) ? $_GET['page'] : 1;
            $params = [
                'page'   => $page,
                'limit'  => (isset($_GET['limit'])) ? $_GET['limit'] : '10000',
                'sort'   => $sort,
                'order'  => $order,
                'params' => (isset($_GET['cat']) ? 'AND tourcategoriesID =' . $_GET['cat'] . ' AND status = 1' : ''.'AND status = 1'),
                'global' => 1,
            ];

            $tours = Tours::where('owner_id', CNF_OWNER)->where('type', 1)->where('status', 1);

            if ($request->cat) {
                $tours = $tours->where('tourcategoriesID', $request->cat);
            }

            $tours = $tours->orderBy($sort, $order)->get()->filter( function ($query) {
                return $query->filteredTourdate->count() > 0 && ($query->b2b != 1);
            });

            $result            = $model::getRows($params);
            $data['tableGrid'] = $info['config']['grid'];
            $data['rowData']   = $tours;
            $page              = $page >= 1 && false !== filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
            $pagination        = new Paginator($result['rows'], $result['total'], $params['limit']);
            $pagination->setPath('');
            $data['i']          = ($page * $params['limit']) - $params['limit'];
            $data['pagination'] = $pagination;
            $data['category']   = $category;
            $data['tour_list']  = $tour_list;
            $data['sort']       = $sort;
            $data['order']      = $order;
            $data['dates']      = $this->getDates();

            $tourdates = tourdates::select('tourID', 'start', 'end')->where('status', 1)->where('start', '>', \Carbon::today())->where('type', 1)->where('owner_id', CNF_OWNER)->get();

            // for ($i = 0; $i < sizeof($data['rowData']); ++$i) {
            //     $dates = $tourdates->where('tourID', $data['rowData'][$i]->tourID);
            //     $j     = 0;
            //     foreach ($dates as $tempdate) {
            //         ++$j;
            //         $data['rowData'][$i]->start[$j] = $tempdate->start;
            //         $data['rowData'][$i]->end[$j]   = $tempdate->end;
            //         if (3 == $j) {
            //             break;
            //         }
            //     }
            // }

            return view('layouts.' . CNF_THEME . '.tour.index', $data);
        }
    }

    public function tnc(Request $request)
    {
        $model = new Tandc();
        $info  = $model::makeInfo('tandc');
        $data  = [
            'pageTitle'    => \Lang::get('core.tandc'),
            'pageNote'     => $info['note'],
            'pageMetakey'  => CNF_METAKEY,
            'pageMetadesc' => CNF_METADESC,
            'pageLang'     => ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG),
            'homepage'     => 0,
            'pageImage'    => CNF_HEADERIMAGE,
        ];

        $tandc = Tandc::where('owner_id', CNF_OWNER)->where('tandcID', $request->id)->get()->first();
        $tour  = $request->package;
        if ($tandc) {
            $data['tandc'] = $tandc;
            $data['tour']  = $tour;

            return view('layouts.' . CNF_THEME . '.tour.tnc', $data);
        } else {
            return  '

<html>
<head>
    <title>Error Terms and Condition not found</title>
</head>
<body>
<script>
alert("Error: Terms and Condition not found");
window.history.back();
</script>
</body>
</html>

            ';
        }
    }

    public function postSavepublic(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('tours');
            $this->model->insertRow($data, $request->input('tourID'));

            return  Redirect::back()->with('messagetext', '<p class="alert alert-success">' . \Lang::get('core.note_success') . '</p>')->with('msgstatus', 'success');
        } else {
            return  Redirect::back()->with('messagetext', '<p class="alert alert-danger">' . \Lang::get('core.note_error') . '</p>')->with('msgstatus', 'error')
                ->withErrors($validator)->withInput();
        }
    }

    public function getTourdetail(Request $request, $tourID, $tourdetailID = 0)
    {
        $rest = \DB::table('tour_detail')->where('tourdetailID', $tourdetailID)->get();
        if (count($rest) >= 1) {
            $row               = $rest[0];
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = (object) [
                'tourdetailID'   => '',
                'tourID'         => $tourID,
                'day'            => '',
                'countryID'      => '',
                'cityID'         => '',
                'hotelID'        => '',
                'siteID'         => '',
                'meal'           => '',
                'optionaltourID' => '',
                'title'          => '',
                'description'    => '',
                'icon'           => '',
                'image'          => '',
            ];
        }

        return view('tours.tourdetail', $this->data);
    }

    public function postTourdetail(Request $request)
    {
        $data = [
            'tourID'         => $request->input('tourID'),
            'day'            => $request->input('day'),
            'countryID'      => $request->input('countryID'),
            'cityID'         => $request->input('cityID'),
            'hotelID'        => $request->input('hotelID'),
            'siteID'         => $request->input('siteID'),
            'meal'           => $request->input('meal'),
            'optionaltourID' => $request->input('optionaltourID'),
            'title'          => $request->input('title'),
            'description'    => $request->input('description'),
            'icon'           => $request->input('icon'),
            'image'          => $request->input('image'),
        ];
        if ('' == $request->input('tourdetailID')) {
            \DB::table('tour_detail')->insert($data);

            return Redirect::to('tours/show/' . $request->input('tourID'))
                ->with('messagetext', 'New day has been added !')->with('msgstatus', 'success');
        } else {
            \DB::table('tour_detail')->where('tourdetailID', $request->input('tourdetailID'))->update($data);

            return Redirect::to('tours/show/' . $request->input('tourID'))
                ->with('messagetext', 'Day has been updated !')->with('msgstatus', 'success');
        }
    }

    public function getTourdetaildelete(Request $request, $tourID, $tourdetailID = 0)
    {
        \DB::table('tour_detail')->where('tourdetailID', $tourdetailID)->where('tourID', $tourID)->delete();

        return Redirect::to('tours/show/' . $tourID)
            ->with('messagetext', 'Day has been deleted !')->with('msgstatus', 'success');
    }

    public static function placesToVisit($places = '')
    {
        $placestovisit = '';
        if ('' != $places) {
            $sql2 = \DB::table('def_sites')->whereIn('siteID', explode(',', $places))->get();
            foreach ($sql2 as $v2) {
                $placestovisit .= "<span class='label label-success'>" . $v2->site_name . '</span>&nbsp; ';
            }
        }

        return $placestovisit;
    }

    public static function optionalTours($optionals = '')
    {
        $optionalTours = '';
        if ('' != $optionals) {
            $sql3 = \DB::table('def_optional_tours')->whereIn('optionaltourID', explode(',', $optionals))->get();
            foreach ($sql3 as $v3) {
                $optionalTours .= "<span class='label label-primary'>" . $v3->optional_tour . '</span>&nbsp; ';
            }
        }

        return $optionalTours;
    }

    public static function whatsIncluded($inclusions = '')
    {
        $whatsIncluded = '';
        if ('' != $inclusions) {
            $sql4 = \DB::table('def_inclusions')->whereIn('inclusionID', explode(',', $inclusions))->get();
            foreach ($sql4 as $v4) {
                $whatsIncluded .= '<li>' . $v4->inclusion . '</li> ';
            }
        }

        return $whatsIncluded;
    }

    public function getDates()
    {
        $dates = tourdates::all();

        $dates = tourdates::select('start')->get();

        return $dates;
    }

    public function isMobile()
    {
        $uaFull = strtolower($_SERVER['HTTP_USER_AGENT']);
        $uaStart = substr($uaFull, 0, 4);

        $uaPhone = [ // use `= array(` if PHP<5.4
            '(android|bb\d+|meego).+mobile',
            'avantgo',
            'bada\/',
            'blackberry',
            'blazer',
            'compal',
            'elaine',
            'fennec',
            'hiptop',
            'iemobile',
            'ip(hone|od)',
            'iris',
            'kindle',
            'lge ',
            'maemo',
            'midp',
            'mmp',
            'mobile.+firefox',
            'netfront',
            'opera m(ob|in)i',
            'palm( os)?',
            'phone',
            'p(ixi|re)\/',
            'plucker',
            'pocket',
            'psp',
            'series(4|6)0',
            'symbian',
            'treo',
            'up\.(browser|link)',
            'vodafone',
            'wap',
            'windows ce',
            'xda',
            'xiino'
        ]; // use `);` if PHP<5.4

        $uaMobile = [ // use `= array(` if PHP<5.4
            '1207', 
            '6310', 
            '6590', 
            '3gso', 
            '4thp', 
            '50[1-6]i', 
            '770s', 
            '802s', 
            'a wa', 
            'abac|ac(er|oo|s\-)', 
            'ai(ko|rn)', 
            'al(av|ca|co)', 
            'amoi', 
            'an(ex|ny|yw)', 
            'aptu', 
            'ar(ch|go)', 
            'as(te|us)', 
            'attw', 
            'au(di|\-m|r |s )', 
            'avan', 
            'be(ck|ll|nq)', 
            'bi(lb|rd)', 
            'bl(ac|az)', 
            'br(e|v)w', 
            'bumb', 
            'bw\-(n|u)', 
            'c55\/', 
            'capi', 
            'ccwa', 
            'cdm\-', 
            'cell', 
            'chtm', 
            'cldc', 
            'cmd\-', 
            'co(mp|nd)', 
            'craw', 
            'da(it|ll|ng)', 
            'dbte', 
            'dc\-s', 
            'devi', 
            'dica', 
            'dmob', 
            'do(c|p)o', 
            'ds(12|\-d)', 
            'el(49|ai)', 
            'em(l2|ul)', 
            'er(ic|k0)', 
            'esl8', 
            'ez([4-7]0|os|wa|ze)', 
            'fetc', 
            'fly(\-|_)', 
            'g1 u', 
            'g560', 
            'gene', 
            'gf\-5', 
            'g\-mo', 
            'go(\.w|od)', 
            'gr(ad|un)', 
            'haie', 
            'hcit', 
            'hd\-(m|p|t)', 
            'hei\-', 
            'hi(pt|ta)', 
            'hp( i|ip)', 
            'hs\-c', 
            'ht(c(\-| |_|a|g|p|s|t)|tp)', 
            'hu(aw|tc)', 
            'i\-(20|go|ma)', 
            'i230', 
            'iac( |\-|\/)', 
            'ibro', 
            'idea', 
            'ig01', 
            'ikom', 
            'im1k', 
            'inno', 
            'ipaq', 
            'iris', 
            'ja(t|v)a', 
            'jbro', 
            'jemu', 
            'jigs', 
            'kddi', 
            'keji', 
            'kgt( |\/)', 
            'klon', 
            'kpt ', 
            'kwc\-', 
            'kyo(c|k)', 
            'le(no|xi)', 
            'lg( g|\/(k|l|u)|50|54|\-[a-w])', 
            'libw', 
            'lynx', 
            'm1\-w', 
            'm3ga', 
            'm50\/', 
            'ma(te|ui|xo)', 
            'mc(01|21|ca)', 
            'm\-cr', 
            'me(rc|ri)', 
            'mi(o8|oa|ts)', 
            'mmef', 
            'mo(01|02|bi|de|do|t(\-| |o|v)|zz)', 
            'mt(50|p1|v )', 
            'mwbp', 
            'mywa', 
            'n10[0-2]', 
            'n20[2-3]', 
            'n30(0|2)', 
            'n50(0|2|5)', 
            'n7(0(0|1)|10)', 
            'ne((c|m)\-|on|tf|wf|wg|wt)', 
            'nok(6|i)', 
            'nzph', 
            'o2im', 
            'op(ti|wv)', 
            'oran', 
            'owg1', 
            'p800', 
            'pan(a|d|t)', 
            'pdxg', 
            'pg(13|\-([1-8]|c))', 
            'phil', 
            'pire', 
            'pl(ay|uc)', 
            'pn\-2', 
            'po(ck|rt|se)', 
            'prox', 
            'psio', 
            'pt\-g', 
            'qa\-a', 
            'qc(07|12|21|32|60|\-[2-7]|i\-)', 
            'qtek', 
            'r380', 
            'r600', 
            'raks', 
            'rim9', 
            'ro(ve|zo)', 
            's55\/', 
            'sa(ge|ma|mm|ms|ny|va)', 
            'sc(01|h\-|oo|p\-)', 
            'sdk\/', 
            'se(c(\-|0|1)|47|mc|nd|ri)', 
            'sgh\-', 
            'shar', 
            'sie(\-|m)', 
            'sk\-0', 
            'sl(45|id)', 
            'sm(al|ar|b3|it|t5)', 
            'so(ft|ny)', 
            'sp(01|h\-|v\-|v )', 
            'sy(01|mb)', 
            't2(18|50)', 
            't6(00|10|18)', 
            'ta(gt|lk)', 
            'tcl\-', 
            'tdg\-', 
            'tel(i|m)', 
            'tim\-', 
            't\-mo', 
            'to(pl|sh)', 
            'ts(70|m\-|m3|m5)', 
            'tx\-9', 
            'up(\.b|g1|si)', 
            'utst', 
            'v400', 
            'v750', 
            'veri', 
            'vi(rg|te)', 
            'vk(40|5[0-3]|\-v)', 
            'vm40', 
            'voda', 
            'vulc', 
            'vx(52|53|60|61|70|80|81|83|85|98)', 
            'w3c(\-| )', 
            'webc', 
            'whit', 
            'wi(g |nc|nw)', 
            'wmlb', 
            'wonu', 
            'x700', 
            'yas\-', 
            'your', 
            'zeto', 
            'zte\-'
        ]; // use `);` if PHP<5.4

        $isPhone = preg_match('/' . implode('|', $uaPhone) . '/i', $uaFull);
        $isMobile = preg_match('/' . implode('|', $uaMobile) . '/i', $uaStart);

        if($isPhone || $isMobile) {
            return true;
        } else {
            return false;
        }
    }
}
