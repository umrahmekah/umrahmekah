<?php

namespace App\Http\Controllers\API\TemplateSetting\BlueOcean;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

class SectionController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $section_name
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $section_name = $request->section_name;
        $config       = \App\Models\Template\Config::forOwner()->first();

        $conf = $config->config;

        if (! isset($conf['settings'][$section_name])) {
            return response()->json([
                'message' => 'Unknown Section',
            ], 404);
        }

        $inputs = collect($request->input());
        $inputs->pull('section_name');
        $inputs = $inputs->map(function ($item, $key) {
            return ('on' == $item) ? true : $item;
        });

        if ('section-seven' == $section_name) {
            $conf['settings'][$section_name]['enabled'] = (isset($inputs['enabled']) && in_array($inputs['enabled'], ['1', 'on'])) ? true : false;
            $conf['settings'][$section_name]['reasons'] = $inputs['reasons'];
        } elseif ('section-one' == $section_name) {
            $conf['settings'][$section_name]['photos'] = $inputs['photos'];
        } elseif ('section-two' == $section_name) {
            $conf['settings'][$section_name]['enabled']  = (isset($inputs['enabled']) && in_array($inputs['enabled'], ['1', 'on'])) ? true : false;
            $conf['settings'][$section_name]['features'] = $inputs['features'];
        } elseif ('section-five' == $section_name) {
            $conf['settings'][$section_name]['enabled']              = (isset($inputs['enabled']) && in_array($inputs['enabled'], ['1', 'on'])) ? true : false;
            $conf['settings'][$section_name]['activities']['first']  = $inputs['activities']['first'];
            $conf['settings'][$section_name]['activities']['second'] = $inputs['activities']['second'];
        } elseif ('footer' == $section_name) {
            $conf['settings'][$section_name]['top_destination']['enabled']          = (isset($inputs['enabled']) && in_array($inputs['enabled'], ['1', 'on'])) ? true : false;
            $conf['settings'][$section_name]['top_destination']['background_image'] = $inputs['background_image'];
        } else {
            $conf['settings'][$section_name] = $inputs;
        }

        $status  = true;
        $message = 'Template Setting for ' . title_case(str_replace('-', ' ', $section_name)) . ' Updated.';

        try {
            $config->update([
                'config' => $conf,
            ]);

            recache_template_configurations();
        } catch (Exception $e) {
            $status = false;
            Log::error($e->getMessage());
            $message = 'Unable to update template Setting for ' . title_case($section_name) . '.';
        }

        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $section_name
     *
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        $section_name = $request->section_name;
        $status       = true;
        $message      = 'Template Setting for ' . title_case(str_replace('-', ' ', $section_name)) . ' has been reset.';

        // ugly way to get default config, since the template config get overwritten in DomainMiddleware
        $default_configuration = include config_path('templates/' . owner()->theme . '.php');

        if (! $default_configuration) {
            return response()->json([
                'message' => 'Unknown Section',
            ], 404);
        }

        $config = \App\Models\Template\Config::forOwner()->first();
        $conf   = $config->config;

        if (! isset($conf['settings'][$section_name])) {
            return response()->json([
                'message' => 'Unknown Section',
            ], 404);
        }

        try {
            $conf['settings'][$section_name] = $default_configuration['settings'][$section_name];

            $config->update([
                'config' => $conf,
            ]);

            recache_template_configurations();
        } catch (Exception $e) {
            $status = false;
            Log::error($e->getMessage());
            $message = 'Unable to reset template Setting for ' . title_case($section_name) . '.';
        }

        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }
}
