<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemplateSettingController extends Controller
{
    public function __construct()
    {
        $this->data = [
            'pageTitle' => \Lang::get('core.t_template_setting'),
            'pageNote'  => \Lang::get('core.t_template_setting_note'),
            'active'    => 'template-settings',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return view('core.template-settings.edit', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function getEdit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function putUpdate(Request $request, $id)
    {
    }
}
