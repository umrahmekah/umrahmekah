<?php

namespace App\Http\Controllers\TemplateSetting\BlueOcean;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Redirect;

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
            return Redirect::back()
                ->with('messagetext', 'Template settings not exist.')
                ->with('msgstatus', 'success');
        }

        $inputs = collect($request->input());
        $inputs->pull('section_name');
        $inputs = $inputs->map(function ($item, $key) {
            return ('on' == $item) ? true : $item;
        });

        $inputs = $this->handleFileUploads($request, $section_name, $inputs);

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

        return Redirect::to('core/template-settings')
                ->with('messagetext', 'Template settings updated.')
                ->with('msgstatus', 'success');
    }

    private function handleFileUploads(Request $request, $section_name, $inputs)
    {
        if($request->hasFile('upload')) {
            $this->validate($request, [
                'upload.*' => 'image',
            ]);

            foreach ($request->upload as $key => $file) {
                $destinationPath = owner_upload_path('images');
                $filename = slug_file_name(
                        $file->getClientOriginalName(), 
                        $file->getClientOriginalExtension()
                    );
                $url = $file->move($destinationPath, $filename);
                $url = owner_upload_uri('images') . '/' . basename($url);
                $inputs = $inputs->toArray();
                
                if('section-one' == $section_name) {
                    $inputs['photos'][$key]['url'] = url($url);
                }

                if('section-six' == $section_name) {
                    $inputs['background-image'] = url($url);
                }

                if('footer' == $section_name) {
                    $inputs['background_image'] = url($url);
                }
            }
        }

        return $inputs;
    }
}
