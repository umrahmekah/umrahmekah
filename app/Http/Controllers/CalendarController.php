<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Redirect;
use Validator;

class CalendarController extends Controller
{
    public $module          = 'calendar';
    public static $per_page = '100000';

    protected $layout = 'layouts.main';
    protected $data   = [];

    public function __construct()
    {
        $this->beforeFilter('csrf', ['on' => 'post']);
        $this->model = new Calendar();

        $this->info   = $this->model->makeInfo($this->module);
        $this->access = $this->model->validAccess($this->info['id']);

        $this->data = [
            'pageTitle'  => $this->info['title'],
            'pageNote'   => $this->info['note'],
            'pageModule' => 'calendar',
            'return'     => self::returnUrl(),
        ];
    }

    public function getIndex(Request $request)
    {
        if (0 == $this->access['is_view']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        // Group users permission
        $this->data['access'] = $this->access;
        // Detail from master if any

        // Master detail link if any
        $this->data['subgrid'] = (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : []);
        // Render into template
        return view('calendar.index', $this->data);
    }

    public function getJsondata(Request $request)
    {
        if (is_null($request->get('start')) || is_null($request->get('end'))) {
            die('Please provide a date range.');
        }

        $results = $this->model->getRows($params = []);
        $data    = [];
        foreach ($results['rows'] as $row) {
            $data[] = [
                'tourdateID'       => $row->tourdateID,
                'tourcategoriesID' => $row->tourcategoriesID,
                'tourID'           => $row->tourID,
                'tour_code'        => $row->tour_code,
                'start'            => $row->start,
                'title'            => $row->tour_code,
                'color'            => $row->color,
                'end'              => $row->end,
            ];
        }

        return json_encode($data);
    }

    public function getUpdate(Request $request, $tourdateID = null)
    {
        if ('' == $tourdateID) {
            if (0 == $this->access['is_add']) {
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }

        if ('' != $tourdateID) {
            if (0 == $this->access['is_edit']) {
                return Redirect::to('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            }
        }

        $row = $this->model->retrive($tourdateID);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('tour_date');
        }

        $this->data['tourdateID'] = $tourdateID;

        return view('calendar.form', $this->data);
    }

    public function getShow($tourdateID = null)
    {
        if (0 == $this->access['is_detail']) {
            return Redirect::to('dashboard')
                ->with('messagetext', Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }

        $row = $this->model->getRow($tourdateID);
        if ($row) {
            $this->data['row'] = $row;
        } else {
            $this->data['row'] = $this->model->getColumnTable('tour_date');
        }

        $this->data['tourdateID'] = $tourdateID;
        $this->data['access']     = $this->access;

        return view('calendar.view', $this->data);
    }

    public function postSave(Request $request)
    {
        $rules     = $this->validateForm();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $data = $this->validatePost('tour_date');

            $tourdateID = $this->model->insertRow($data, $request->input('tourdateID'));

            if (! is_null($request->input('apply'))) {
                $return = 'calendar/update/' . $tourdateID . '?return=' . self::returnUrl();
            } else {
                $return = 'calendar?return=' . self::returnUrl();
            }

            // Insert logs into database
            if ('' == $request->input('tourdateID')) {
                \SiteHelpers::auditTrail($request, 'New Data with ID ' . $tourdateID . ' Has been Inserted !');
            } else {
                \SiteHelpers::auditTrail($request, 'Data with ID ' . $tourdateID . ' Has been Updated !');
            }

            return Redirect::to($return)->with('messagetext', \Lang::get('core.note_success'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('calendar')->with('messagetext', \Lang::get('core.note_error'))->with('msgstatus', 'error')
            ->withErrors($validator)->withInput();
        }
    }

    public function postSavedrop(Request $request)
    {
        $data = $this->validatePost('tour_date');
        $ID   = $this->model->insertRow($data, $request->get('tourdateID'));

        return 'success';
    }

    public function postDelete(Request $request)
    {
        if (0 == $this->access['is_remove']) {
            return Redirect::to('dashboard')
                ->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
        }
        // delete multipe rows
        if (count($request->input('tourdateID')) >= 1) {
            $this->model->destroy($request->input('tourdateID'));

            \SiteHelpers::auditTrail($request, 'ID : ' . implode(',', $request->input('tourdateID')) . '  , Has Been Removed Successfully');
            // redirect
            return Redirect::to('calendar')
                ->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus', 'success');
        } else {
            return Redirect::to('calendar')
                ->with('messagetext', \Lang::get('core.note_noitemdeleted'))->with('msgstatus', 'error');
        }
    }
}
