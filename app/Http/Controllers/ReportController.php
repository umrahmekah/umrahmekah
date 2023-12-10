<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use PDF;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tourdates;
use App\Models\Tours;
use App\Models\Travelagents;

class ReportController extends Controller
{
    protected $data   = [];
    public $module          = 'tourdates';

    public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model = new Tourdates();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'tourdates',
            'return'     => self::returnUrl(),
        ];

        \App::setLocale(CNF_LANG);
        if (defined('CNF_MULTILANG') && CNF_MULTILANG == '1') {
            $lang = ('' != \Session::get('lang') ? \Session::get('lang') : CNF_LANG);
            \App::setLocale($lang);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
        $this->data['tours'] = Tours::where('owner_id', CNF_OWNER)->get();
        if ($request->pdf) {
            return \PDF::loadView('report.packages-report-pdf', $this->data)->stream();
        }
        // dd($this->data['tours']);
        return view('report.packages-report', $this->data);
    }

    public function getPackage(Request $request,$id)
    {
        $this->data['tour'] = Tours::where('owner_id', CNF_OWNER)->where('tourID', $id)->get()->first();

        if (!$this->data['tour']) {
            abort(404);
        }

        if ($request->pdf) {
            return PDF::loadView('report.package-report-pdf', $this->data)->stream();
        }

        return view('report.package-report', $this->data);
    }
    
    public function getBooking($id)
    {

        $this->data['tour_c'] = Tourdates::join('travellers', 'tour_date.owner_id', '=', 'travellers.owner_id')->join('bookings', 'tour_date.owner_id', '=', 'bookings.owner_id')->where('tour_code', $id)->where('travellers.owner_id', CNF_OWNER)->get()->first();
        
        ($this->data['tour_c']);
        if (!$this->data['tour_c']) {
            abort(404);
        }

        return view('report.bookinglist-report', $this->data);
    }

    public function getAgentlist(Request $request)
    {
        $this->data['agents'] = Travelagents::where('owner_id', CNF_OWNER)->get();
        if ($request->pdf) {
            return PDF::loadView('report.agent-report-pdf', $this->data)->stream();
        }

        return view('report.agent-report', $this->data);
    }

    public function getAgentdetail(Request $request)
    {
        $this->data['agents'] = Travelagents::where('owner_id', CNF_OWNER)->get();

        $this->data['results'] = Travelagents::where('owner_id', CNF_OWNER)->whereIn('travelagentID', $request->agent_ids)->get();

        if ($request->pdf) {
            return PDF::loadView('report.agent-detail-report-pdf', $this->data)->stream('Agent Detail Report.pdf');
        }

        return view('report.agent-detail-report', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
