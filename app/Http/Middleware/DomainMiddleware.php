<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Support\Facades\Auth;

class DomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $domain = $request->getHost();

        $owner = DB::table('tb_owners')
            ->where('domain', $domain)
            ->orWhere('subdomain', $domain)
            ->first();

        if ($owner) {
            define('CNF_OWNER', $owner->id);
            define('CNF_DOMAIN', $domain);
            define('CNF_SUBDOMAIN', $owner->subdomain);
            define('CNF_APPNAME', 'Oomrah');
            define('CNF_APPDESC', 'Umrah Tour Management');
            define('CNF_COMNAME', $owner->name);
            define('CNF_ADDRESS', $owner->address);
            define('CNF_TEL', $owner->telephone);
            define('CNF_EMAIL', $owner->email);
            define('CNF_FACEBOOK', $owner->facebook);
            define('CNF_TWITTER', $owner->twitter);
            define('CNF_INSTAGRAM', $owner->instagram);
            define('CNF_TRIPADVISOR', $owner->tripdavisor);
            define('CNF_TAGLINE', $owner->tagline);
            define('CNF_DESCRIPTION', $owner->description);
            define('CNF_TEMPCOLOR', $owner->template_color);
            define('CNF_METAKEY', $owner->meta_keyword);
            define('CNF_METADESC', $owner->meta_description);
            define('CNF_GROUP', $owner->group);
            define('CNF_ACTIVATION', $owner->activation);
            define('CNF_MAINTENANCE', $owner->maintenance);
            define('CNF_SHOWHELP', $owner->show_help);
            define('CNF_SHOWTESTIMONIAL', $owner->show_testimonial);
            define('CNF_SHOWTOUR', $owner->show_tour);
            define('CNF_MULTILANG', $owner->multi_language);
            define('CNF_AVAILLANG', $owner->avail_language);
            define('CNF_LANG', $owner->default_language);
            define('CNF_CURRENCY', $owner->default_currency);
            define('CNF_REGIST', $owner->registration);
            define('CNF_FRONT', $owner->front);
            define('CNF_THEME', $owner->theme);
            define('CNF_BOOKINGFORM', $owner->booking_form);
            define('CNF_RECAPTCHA', $owner->captcha);
            define('CNF_RECAPTCHAPUBLICKEY', $owner->name);
            define('CNF_RECAPTCHAPRIVATEKEY', $owner->name);
            define('CNF_MODE', $owner->mode);
            define('CNF_LOGO', $owner->logo);
            define('CNF_HEADERIMAGE', $owner->header_image);
            define('CNF_ALLOWIP', $owner->allow_ip);
            define('CNF_RESTRICIP', $owner->restrict_ip);
            define('CNF_MAIL', 'swift');
            define('CNF_DATE', $owner->date);
            define('CNF_APIKEY', 'AIzaSyAft8so1yFrI1CTA7HbKHOxSwP5HOjN_A8');
            define('CNF_BILLPLZAPIKEY', $owner->billplz_api_key);
            define('CNF_BILLPLZSIGNATUREKEY', $owner->billplz_signature_key);
            define('CNF_BILLPLZCOLLECTIONID', $owner->billplz_collection_id);
            define('CNF_CALENDARID', $owner->google_calendar);
            define('CNF_ANALYTICS', $owner->google_analytics);

            define('CNF_PAYMENT_GATEWAY_ID', $owner->payment_gateway_id);
            define('CNF_PAYMENT_GATEWAY_DATA', $owner->payment_gateway_data);
            define('CURRENCY_SYMBOLS', DB::table('def_currency')->where('currencyID', $owner->default_currency)->first()->symbol);

            // set Google Analytic ID based on tenant.
            config([
                'laravel-analytics.view_id' => $owner->google_analytics,
            ]);

            template_configurations();

            $lang = session('lang');
            $getLang = '';

            if($lang == 'my') {
                $getLang = 'ms';
            } else {
                $getLang = $lang;
            }

            \Carbon::setLocale($getLang);
        } else {
            return abort(404);
        }

        if (Auth::check()) {
            $user       = Auth::user();
            $sub_domain = $request->getHost();
        }

        return $next($request);
    }
}
