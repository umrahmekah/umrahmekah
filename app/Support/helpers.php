<?php

if (! function_exists('owner')) {
    /**
     * Get Current Owner Based on Domain.
     */
    function owner()
    {
        return Cache::remember('owner.' . request()->getHost(), config('cache.duration'), function () {
            return \App\Models\Owners::domain(request()->getHost())->first();
        });
    }
}

if (! function_exists('has_template_settings')) {
    /**
     * Determine either current template use has settings or not.
     */
    function has_template_settings()
    {
        return view()->exists('core.template-settings.partials.' . str_slug(CNF_THEME, '-'));
    }
}

if (! function_exists('recache_template_configurations')) {
    function recache_template_configurations()
    {
        Cache::forget('template_configuration.' . request()->getHost());
        template_configurations();
    }
}

if (! function_exists('template_configurations')) {
    /**
     * Get Template Configuration.
     */
    function template_configurations()
    {
        $template_path         = 'templates.' . owner()->theme;
        $default_configuration = config($template_path);

        if (empty($default_configuration)) {
            return null;
        }

        $configuration = Cache::remember(
            'template_configuration.' . request()->getHost(),
            config('cache.duration'), function () use ($default_configuration) {
                $owner = owner();

                return \App\Models\Template\Config::forOwner()->firstOrCreate([
                    'owner_id'      => $owner->id,
                    'template_name' => $owner->theme,
                ], [
                    'config'        => $default_configuration,
                ]);
            });

        if ($configuration) {
            config([
                'templates.' . owner()->theme => $configuration->config,
            ]);
        }

        return config('templates.' . owner()->theme);
    }
}

if (! function_exists('min_price')) {
    function min_price($row)
    {
        $min_price = 0;

        if ($row->cost_single > 0) {
            $min_price = $row->cost_single;
        }

        if ($row->cost_double > 0 && $row->cost_double < $min_price) {
            $min_price = $row->cost_double;
        }

        if ($row->cost_triple > 0 && $row->cost_triple < $min_price) {
            $min_price = $row->cost_triple;
        }

        if ($row->cost_quad > 0 && $row->cost_quad < $min_price) {
            $min_price = $row->cost_quad;
        }

        return $min_price;
    }
}

if (! function_exists('top_destinations')) {
    function top_destinations()
    {
        return DB::table('book_tour')
            ->where('book_tour.owner_id', owner()->id)
            ->join('tours', 'book_tour.tourID', '=', 'tours.tourID')
            ->select(
                DB::raw('count(book_tour.tourID) as tour_count'), 'tours.*'
            )
            ->groupBy('book_tour.tourID')
            ->limit(3)
            ->get();
    }
}

if (! function_exists('owner_upload_path')) {
    /**
     * Upload path for owner.
     * 
     * @param  string $type type of upload - files / images / users
     * @return string
     */
    function owner_upload_path($type = 'files')
    {
        $upload_path = base_path('public/uploads/' . $type . '/' . owner()->id);

        if(! file_exists($upload_path)) {
            mkdir($upload_path);
        }

        return $upload_path;
    }
}

if (! function_exists('owner_upload_uri')) {
    /**
     * Upload URI for owner.
     * 
     * @param  string $type type of upload - files / images / users
     * @return string
     */
    function owner_upload_uri($type = 'files')
    {
        return url('uploads/' . $type . '/' . owner()->id);
    }
}

if (! function_exists('slug_file_name')) {
    /**
     * Convert file name to slug.
     * 
     * @param  string $name      File name.
     * @param  string $extension File extension.
     * @return string            Sluggable file name.
     */
    function slug_file_name($name, $extension)
    {
        return str_slug(basename($name, $extension), '-') . '.' . $extension;
    }
}

if (! function_exists('moneyFormat')) {
    function moneyFormat($amount)
    {
        return number_format($amount, 2, '.', ',');
    }
}