<?php

namespace App\Models;
use App\Models\Tasks;
use Illuminate\Database\Eloquent\Model;
use Carbon;

class tourdates extends Mmb
{
    protected $table      = 'tour_date';
    protected $primaryKey = 'tourdateID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT tour_date.* FROM tour_date  ';
    }

    public static function queryWhere()
    {
        return '  WHERE tour_date.owner_id = ' . CNF_OWNER . ' AND tour_date.type = 1 AND tour_date.tourdateID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function flight()
    {
        return $this->hasMany(Flightbooking::class, 'tourdates_id');
    }

    public function piform()
    {
        return $this->hasOne(Piform::class, 'tourdate_id');
    }

    public function tour()
    {
        return $this->belongsTo(Tours::class, 'tourID');
    }

    public function tourcategory()
    {
        return $this->belongsTo(Tourcategories::class, 'tourcategoriesID');
    }

    public function booktours()
    {
        return $this->hasMany(Booktour::class, 'tourdateID');
    }
    
    public function tasks()
    {
        return $this->hasMany(Tasks::class, 'tour_date_id');

    }

    public function guide()
    {
        return $this->belongsTo(Guide::class, 'guideID');
    }

    public function getPaxAttribute()
    {
        $pax = 0;

        foreach ($this->booktours as $booktour) {
            $booking = $booktour->booking;
            if ($booking) {
                $pax += $booking->pax;
            }
        }

        return $pax;
    }

    public function getExistBookingNeedsAttentionAttribute()
    {
        $boolean = true;
        foreach ($this->booktours as $key => $booktour) {
            $booking = $booktour->booking;

            if ($booking) {
                $boolean &= $booking->settled;
            }
        }

        return !$boolean;
    }

    public function getCapacityAttribute()
    {
        $pax = $this->pax;

        return $this->total_capacity - $pax;
    }

    public function getNotStartYetAttribute()
    {
        $today = Carbon::today();
        $start = Carbon::parse($this->start);

        return $start > $today;
    }

    public function getTotalSalesAttribute()
    {
        $sales = 0;
        foreach ($this->booktours as $key => $booktour) {
            $booking = $booktour->booking;
            if ($booking) {
                $invoice = $booking->invoice;
                if ($invoice) {
                    $sales += $invoice->InvTotal ?? 0;
                }
            }
        }
        return $sales;
    }

    public function getTotalPaymentsAttribute()
    {
        $payments = 0;
        foreach ($this->booktours as $key => $booktour) {
            $booking = $booktour->booking;
            if ($booking) {
                $invoice = $booking->invoice;
                if ($invoice) {
                    foreach ($invoice->payments as $key => $payment) {
                        $payments += $payment->amount;
                    }
                }
            }
        }
        return $payments;
    }

    public function getOccupancyAttribute()
    {
        return ($this->pax/($this->total_capacity ? $this->total_capacity : 1))*100;
    }

    public function getTaskPercentageAttribute()
    {
        $completed_task = $this->tasks->where('status', 2)->count();

        $TotalTask = $this->tasks->count();

        $TaskPercentage = $TotalTask == 0 ? 0 :(($completed_task) / $TotalTask) * 100;

        return $TaskPercentage;
    }

    public function getTaskFractionAttribute()
    {
        $completed_task = $this->tasks->where('status', 2)->count();

        $TotalTask = $this->tasks->count();

        $TaskFraction = $completed_task.'/'.$TotalTask;

        return $TaskFraction;
    }

    public function getConpleteTaskAttribute()
    {
        return $this->tasks->where('status', 2);
    }
    
    public function getDiscountPriceAttribute()
    {
        $discountprice = [
            0 => 0,
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0
        ];
        
            if($this->discount != 0){
                if ($this->cost_single != 0) {
                    $discountprice[0] = $this->cost_single - $this->discount;
                }

                if($this->cost_double != 0){
                    $discountprice[1] = $this->cost_double - $this->discount;
                }

                if($this->cost_triple != 0){
                    $discountprice[2] = $this->cost_triple - $this->discount;
                }

                if($this->cost_quad != 0){
                    $discountprice[3] = $this->cost_quad - $this->discount;
                }

                if($this->cost_quint != 0){
                    $discountprice[4] = $this->cost_quint - $this->discount;
                }

                if($this->cost_sext != 0){
                    $discountprice[5] = $this->cost_sext - $this->discount;
                }
                
                if($this->cost_child != 0){
                    $discountprice[6] = $this->cost_child - $this->discount;
                }
                
                if($this->cost_child_wo_bed != 0){
                    $discountprice[7] = $this->cost_child_wo_bed - $this->discount;
                }
                
                if($this->cost_infant_wo_bed != 0){
                    $discountprice[8] = $this->cost_infant_wo_bed - $this->discount;
                }
            
            }
        
        return $discountprice;
    }

    public function getLowestPriceAttribute()
    {
        $price = 0;

        if ($this->cost_single) {
            $price = $this->cost_single;
        }
        if (($this->cost_double > 0 && $this->cost_double < $price) || ($price == 0)) {
            $price = $this->cost_single;
        }
        if (($this->cost_triple > 0 && $this->cost_triple < $price) || ($price == 0)) {
            $price = $this->cost_single;
        }
        if (($this->cost_quad > 0 && $this->cost_quad < $price) || ($price == 0)) {
            $price = $this->cost_single;
        }
        if (($this->cost_quint > 0 && $this->cost_quint < $price) || ($price == 0)) {
            $price = $this->cost_single;
        }
        if (($this->cost_sext > 0 && $this->cost_sext < $price) || ($price == 0)) {
            $price = $this->cost_single;
        }
    }
}
