<?php

namespace App\Http\Controllers;

use Analytics;
use App\Models\Booktour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;;
use Spatie\Analytics\Period;
use App\Models\Tourdates;
use App\Models\Tours;
use App\Models\Travelagents;
use App\Models\Travellers;
use App\Models\Createbooking;
use App\Models\Bookroom;
use App\Models\Tasks;
use App\Http\Requests;
use DB, HijriDate, Hijri;
use DateTime;
use App\Http\Resources\Post as PostResource;
use App\Models\Post;

class DashboardController extends Controller
{
    public $module          = 'tourdates';

    public function __construct()
    {
        $this->model = new Tourdates();

        parent::__construct();
        $this->data = [
            'pageTitle' => CNF_COMNAME,
            'pageNote'  => 'Welcome to Dashboard',
            'pageModule' => 'tourdates',
            'return'     => self::returnUrl(),
        ];
    }
    

    public function getIndex(Request $request)
    {   
        $user = Auth::user();

        if ($user->group_id == 6) {
            return redirect('/createbooking');
        }

        $toursB = Tourdates::where('owner_id', CNF_OWNER)->where('start', '>=', Carbon::now())->orderBy('start', 'asc')->paginate(6);
        $this->data['toursB'] = $toursB;

        $tasks = Tasks::where('owner_id','=', CNF_OWNER)->count();
        $this->data['tasks'] = $tasks;

        $taskcount = Tasks::join('tour_date', 'tour_date.tourdateID', '=', 'task.tour_date_id')->where('task.owner_id', '=', CNF_OWNER)
                ->where('task.tour_date_id', '=', 'tour_date.tourdateID')
                ->count();
        $this->data['taskcount'] = $taskcount;

        $ongoing_task = \DB::table('task')
                ->where('owner_id', '=', CNF_OWNER)
                ->where('status', 0)
                ->count();

        $completed_task = \DB::table('task')
                ->where('owner_id', '=', CNF_OWNER)
                ->where('status', 2)
                ->count();

        $TotalTask = \DB::table('task')->where('owner_id','=', CNF_OWNER)->count();

        $this->data['TotalTask'] = $TotalTask;
        $this->data['completed_task'] = $completed_task;
        $this->data['ongoing_task'] = $ongoing_task;

        /*$months = [
             '01' => 'January',
             '02' => 'February',
             '03' => 'March',
             '04' => 'April',
             '05' => 'May',
             '06' => 'June',
             '07' => 'July',
             '08' => 'August',
             '09' => 'September',
             '10' => 'October',
             '11' => 'November',
             '12' => 'December'
        ];

        $this->data['months'] = $months;


        if($request->has('cari'))
        {
            $search = Tourdates::where('tour_code', 'LIKE', '%'.$request->cari.'%')->get();
        } else {
            $search = Tourdates::all();

        }

        $this->data['search'] = $search;*/

        return view('dashboard.index', $this->data);
    }

    //public function index(Request $request)
    //{
        //$search = $request->get('search');
        //$post = DB::table('tour_date')->where('tour_code', 'like', '%'.search.'%')->paginate(6);
        //$this->data['post'] = $post;


        //return view('dashboard.index', $this->data);
    //}
    
    /*
    public function getDashboard()
    {
    }
    */
}
