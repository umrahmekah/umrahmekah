<?php

namespace App\Models;
use Carbon, DB;

class tours extends Mmb
{
    protected $table      = 'tours';
    protected $primaryKey = 'tourID';

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT tours.* FROM tours ';
    }

    public static function queryWhere()
    {
        return '  WHERE tours.owner_id = ' . CNF_OWNER . ' AND tours.type = 1 AND tours.tourID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function category()
    {
        return $this->belongsTo(Tourcategories::class, 'tourcategoriesID');
    }

    public function tourdates()
    {
        return $this->hasMany(Tourdates::class, 'tourID');
    }

    public function tandc()
    {
        return $this->belongsTo(Tandc::class, 'policyandterms');
    }

    public function bookTours()
    {
        return $this->hasMany(Booktour::class, 'tourID');
    }

    public function getFlightsAttribute()
    {
        if ($this->flight === 'Choose your Air Line') {
            return null;
        }
        $flight = DB::table('flights')
                            ->where('iata',$this->flight)
                            ->first();
        return $flight;
    }

    public function getMinPriceAttribute()
    {
        $tourdates = $this->filteredTourdate;

        $min_price = 0;

        foreach ($tourdates as $key => $tourdate) {
            if (($tourdate->cost_single > 0 && $tourdate->cost_single < $min_price) || !$min_price) {
                $min_price = $tourdate->cost_single;
            }

            if (($tourdate->cost_double > 0 && $tourdate->cost_double < $min_price) || !$min_price) {
                $min_price = $tourdate->cost_double;
            }

            if (($tourdate->cost_triple > 0 && $tourdate->cost_triple < $min_price) || !$min_price) {
                $min_price = $tourdate->cost_triple;
            }

            if (($tourdate->cost_quad > 0 && $tourdate->cost_quad < $min_price) || !$min_price) {
                $min_price = $tourdate->cost_quad;
            }

            if (($tourdate->cost_quint > 0 && $tourdate->cost_quint < $min_price) || !$min_price) {
                $min_price = $tourdate->cost_quint;
            }

            if (($tourdate->cost_sext > 0 && $tourdate->cost_sext < $min_price) || !$min_price) {
                $min_price = $tourdate->cost_sext;
            }
        }

        return $min_price;
    }

    public function getFilteredTourdateAttribute()
    {
        $tourdates = $this->tourdates->filter( function ($query) {
            return $query->notStartYet && $query->capacity > 0;
        });

        return $tourdates;
    }

    public function getTotalSalesAttribute()
    {
        $sales = 0;
        foreach ($this->bookTours as $key => $booktour) {
            $booking = $booktour->booking;
            if ($booking) {
                $invoice = $booking->invoice;
                if ($invoice) {
                    $sales += $invoice->InvTotal;
                }
            }
        }
        return $sales;
    }

    public function getYtdSalesAttribute()
    {
        $sales = 0;
        // $tourdates = $this->tourdates->where('start', '>=', Carbon::now()->startOfYear())->where('start', '<=', Carbon::now()->endOfYear());
        $tourdates = $this->tourdates->filter( function ($query) {
            $start = Carbon::parse($query->start)->format('Y');
            $yearStart = Carbon::now()->format('Y');
            return $start == $yearStart;
        });
        foreach ($tourdates as $key => $tourdate) {
            foreach ($tourdate->booktours as $key => $booktour) {
                $booking = $booktour->booking;
                if ($booking) {
                    $invoice = $booking->invoice;
                    if ($invoice) {
                        $sales += $invoice->InvTotal;
                    }
                }
            }
        }
            
        return $sales;
    }

    public function getTotalPaymentsAttribute()
    {
        $payments = 0;
        foreach ($this->bookTours as $key => $booktour) {
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

    public function getYtdPaymentsAttribute()
    {
        $payments = 0;
        // $tourdates = $this->tourdates->where('start', '>=', Carbon::now()->startOfYear())->where('start', '<=', Carbon::now()->endOfYear());
        $tourdates = $this->tourdates->filter( function ($query) {
            $start = Carbon::parse($query->start)->format('Y');
            $yearStart = Carbon::now()->format('Y');
            return $start == $yearStart;
        });
        foreach ($tourdates as $key => $tourdate) {
            foreach ($tourdate->booktours as $key => $booktour) {
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
        }
            
        return $payments;
    }

    public function getTotalCapacityAttribute()
    {
        $capacity = 0;
        foreach ($this->tourdates as $key => $tourdate) {
            $capacity += $tourdate->total_capacity;
        }

        return $capacity;
    }

    public function getTotalPaxAttribute()
    {
        $pax = 0;
        foreach ($this->tourdates as $key => $tourdate) {
            $pax += $tourdate->pax;
        }
        return $pax;
    }

    public function getYtdCapacityAttribute()
    {
        $capacity = 0;
        // $tourdates = $this->tourdates->where('start', '>=', Carbon::now()->startOfYear())->where('start', '<=', Carbon::now()->endOfYear());
        $tourdates = $this->tourdates->filter( function ($query) {
            $start = Carbon::parse($query->start)->format('Y');
            $yearStart = Carbon::now()->format('Y');
            return $start == $yearStart;
        });
        foreach ($tourdates as $key => $tourdate) {
            $capacity += $tourdate->total_capacity;
        }

        return $capacity;
    }

    public function getYtdPaxAttribute()
    {
        $pax = 0;
        // $tourdates = $this->tourdates->where('start', '>=', Carbon::now()->startOfYear())->where('start', '<=', Carbon::now()->endOfYear());
        $tourdates = $this->tourdates->filter( function ($query) {
            $start = Carbon::parse($query->start)->format('Y');
            $yearStart = Carbon::now()->format('Y');
            return $start == $yearStart;
        });
        foreach ($tourdates as $key => $tourdate) {
            $pax += $tourdate->pax;
        }
        return $pax;
    }

    public function getOccupancyAttribute()
    {
        return ($this->totalPax/($this->totalCapacity?$this->totalCapacity:1))*100;
    }

    public function getYtdOccupancyAttribute()
    {
        return ($this->ytdPax/($this->ytdCapacity?$this->ytdCapacity:1))*100;
    }
}
