<?php

namespace App\Models;

class createbooking extends Mmb
{
    protected $table      = 'bookings';
    protected $primaryKey = 'bookingsID';
    protected $dates = ['created_at', 'updated_at'];

    const SOURCE_TYPE_CALL = 1;
    const SOURCE_TYPE_WALK_IN = 2;
    const SOURCE_TYPE_AGENT = 3;
    const SOURCE_TYPE_FACEBOOK = 4;
    const SOURCE_TYPE_INSTAGRAM = 5;
    const SOURCE_TYPE_TWITTER = 6;
    const SOURCE_TYPE_MATTA = 7;
    const SOURCE_TYPE_EVENT = 8;
    const SOURCE_TYPE_ONLINE = 9;
    const SOURCE_TYPE_MAP = [
        1 => 'Call',
        2 => 'Walk-in',
        3 => 'Agent',
        4 => 'Facebook',
        5 => 'Instagram',
        6 => 'Twitter',
        7 => 'Matta Event',
        8 => 'Company Event',
        9 => 'Online Booking' // do not show in form. for website booking only
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT bookings.* FROM bookings  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  bookings.owner_id = ' . CNF_OWNER . ' AND bookings.type = 1 AND bookings.bookingsID IS NOT NULL';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function bookTour()
    {
        return $this->hasOne(Booktour::class, 'bookingID');
    }

    public function bookRoom()
    {
        return $this->hasMany(Bookroom::class, 'bookingID');
    }

    public function traveller()
    {
        return $this->belongsTo(Travellers::class, 'travellerID');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'bookingID');
    }

    public function entryByUser()
    {
        return $this->belongsTo('App\User', 'entry_by');
    }

    public function checklist()
    {
        return $this->hasOne(BookingChecklist::class, 'booking_id');
    }

    public function getBookingDateAttribute()
    {
        $date = \Carbon::parse($this->created_at)->format('l jS F Y');

        return $date;
    }

    public function getPaxNumberAttribute()
    {
        return $this->adult_number + $this->child_number + $this->infant_number;
    }

    public function getPaxRoomAttribute()
    {
        $rooms = $this->bookRoom;

        $pax = 0;

        foreach ($rooms as $room) {
            foreach ($room->travellerList as $traveller) {
                if ($traveller) {
                    $pax += 1;
                }
            }
        }

        return $pax;
    }

    public function getPaxPerRoomTypeAttribute()
    {
        $pax = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 0,
            9 => 0
        ];

        $rooms = $this->bookRoom;

        foreach ($rooms as $room) {
            $pax[$room->roomtype] += $room->travellerList->count();
        }

        return $pax;
    }

    public function getPaxAttribute()
    {
        $pax = $this->paxRoom;

        if (!$pax) {
            $pax += $this->paxNumber;
        }

        return $pax;
    }

    public function getSettledAttribute()
    {
        $validation = false;

        if ($this->adultCount == $this->adult_number && $this->childCount == $this->child_number && $this->infantCount == $this->infant_number) {
            $validation = true;
        }

        if ($this->paxNumber == 0 || $this->dismissed || $validation) {
            return true;
        }else{
            return false;
        }
    }

    public function getAdultCountAttribute()
    {
        $rooms = $this->bookRoom->filter( function ($query) {
            return !in_array($query->roomtype, [7,8,9]);
        });

        $count = 0;

        foreach ($rooms as $key => $room) {
            $count += $room->travellerList->count();
        }

        return $count;
    }

    public function getChildCountAttribute()
    {
        $rooms = $this->bookRoom->filter( function ($query) {
            return in_array($query->roomtype, [7,8]);
        });

        $count = 0;

        foreach ($rooms as $key => $room) {
            $count += $room->travellerList->count();
        }

        return $count;
    }

    public function getInfantCountAttribute()
    {
        $rooms = $this->bookRoom->filter( function ($query) {
            return in_array($query->roomtype, [9]);
        });

        $count = 0;

        foreach ($rooms as $key => $room) {
            $count += $room->travellerList->count();
        }

        return $count;
    }

    public function getCommissionsAttribute()
    {
        $user = $this->entryByUser;

        if ($user) {
            $agent = Travelagents::where('owner_id', CNF_OWNER)->where('email', $user->email)->get()->first();
        }else{
            $agent = Travelagents::where('owner_id', CNF_OWNER)->where('affiliatelink', $this->affiliatelink)->get()->first();
        }

        if (!$agent) {
            return 0;
        }

        $commission = $this->pax * ($agent->commissionrate ?? 0);

        return $commission;
    }

    public function getPackageAttribute()
    {
        $booktour = $this->bookTour;
        if ($booktour) {
            $tour = $booktour->tour;
            $tourdate = $booktour->tourdate;
            if ($tour && $tourdate) {
                return $tour->tour_name.' ('.$tourdate->tour_code.')';
            }
        }

        return null;
    }

}
