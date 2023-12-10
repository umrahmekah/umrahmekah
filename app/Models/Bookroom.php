<?php

namespace App\Models;

class bookroom extends Mmb
{
    protected $table      = 'book_room';
    protected $primaryKey = 'roomID';

    const ROOM_TYPE_SINGLE = 1;
    const ROOM_TYPE_DOUBLE = 2;
    const ROOM_TYPE_TRIPLE = 3;
    const ROOM_TYPE_QUADDRUPLE = 4;
    const ROOM_TYPE_QUINTUPLE = 5;
    const ROOM_TYPE_SEXTUPLE = 6;
    const ROOM_TYPE_CHILDREN_WITH_BED = 7;
    const ROOM_TYPE_CHILDREN_WITHOUT_BED = 8;
    const ROOM_TYPE_INFANT = 9;
    const ROOM_TYPE_MAP = [
        1 => 'Single Bed',
        2 => 'Double Bed',
        3 => 'Triple Bed',
        4 => 'Four Bed',
        5 => 'Five Bed',
        6 => 'Six Bed',
        7 => 'Children With Bed',
        8 => 'Children Without Bed',
        9 => 'Infant'
    ];
    const ROOM_TYPE_MAP_MALAY = [
        1 => 'Seorang',
        2 => 'Berdua',
        3 => 'Bertiga',
        4 => 'Berempat',
        5 => 'Berlima',
        6 => 'Berenam',
        7 => 'Kanak-kanak berkatil',
        8 => 'Kanak-kanak tanpa katil',
        9 => 'Bayi'
    ];
    const ROOM_TYPE_MAP_LANG = [
        1 => 'single',
        2 => 'double',
        3 => 'triple',
        4 => 'quad',
        5 => 'quint',
        6 => 'sext'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT book_room.* FROM book_room  ';
    }

    public static function queryWhere()
    {
        return '  WHERE  book_room.owner_id = ' . CNF_OWNER . ' AND book_room.roomID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function childRoom()
    {
        return $this->hasMany(Bookroom::class, 'parent_id');
    }

    public function booking()
    {
        return $this->belongsTo(Createbooking::class, 'bookingID');
    }

    public function getChildrenRoomAttribute()
    {
        foreach ($this->childRoom as $key => $room) {
            if (in_array($room->roomtype, [7,8])) {
                return $room;
            }
        }
        return null;
    }

    public function getInfantRoomAttribute()
    {
        foreach ($this->childRoom as $key => $room) {
            if (in_array($room->roomtype, [9])) {
                return $room;
            }
        }
        return null;
    }
    public function getTravellerListAttribute()
    {
        $traveller_ids = explode(',', $this->travellers);

        $travellers = Travellers::whereIn('travellerID', $traveller_ids)->get();

        foreach ($travellers as $key => $traveller) {
            $traveller->tempRoom = $this;
        }

        return $travellers;
    }

    public function getTravellerListWithChildAttribute()
    {
        $travellers = $this->travellerList->all();

        foreach ($this->childRoom as $key => $room) {
            $traveller_child = $room->travellerList->all();
            $travellers = array_merge($travellers, $traveller_child);
        }

        return $travellers;
    }

    public function getRoomTypeNameAttribute()
    {
        return self::ROOM_TYPE_MAP[$this->roomtype];
    }

    public function getRoomTypeNameMalayAttribute()
    {
        return self::ROOM_TYPE_MAP_MALAY[$this->roomtype];
    }

    
}
