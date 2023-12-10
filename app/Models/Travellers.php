<?php

namespace App\Models;

use Carbon;
use App\User;
use App\Library\SiteHelpers;
use Illuminate\Support\Facades\Lang;

class travellers extends Mmb
{
    protected $table      = 'travellers';
    protected $primaryKey = 'travellerID';
    protected $fillable   = [
        'nameansurname',
          'email',
          'phone',
          'address',
          'city',
          'countryID',
          'passportno',
          'dateofbirth',
          'passportissue',
          'passportexpiry',
          'passportcountry',
    ];

    public $tempBooking = null;
    public $tempRoom = null;

    public function __construct()
    {
        parent::__construct();
    }

    public static function querySelect()
    {
        return '  SELECT travellers.* FROM travellers  ';
    }

    public static function queryWhere()
    {
        return '  WHERE travellers.owner_id = ' . CNF_OWNER . ' AND travellers.travellerID IS NOT NULL ';
    }

    public static function queryGroup()
    {
        return '  ';
    }

    public function mahram()
    {
      return $this->hasOne(Travellers::class, 'mahram_id');
    }

    public function country()
    {
      return $this->belongsTo(Countries::class, 'countryID');
    }

    public function passportCountry()
    {
      return $this->belongsTo(Countries::class, 'passportcountry');
    }

    public function nationalityCountry()
    {
      return $this->belongsTo(Countries::class, 'nationality');
    }

    public function getFullnameAttribute()
    {
      return $this->nameandsurname.' '.$this->last_name;
    }

    public function getFullAddressAttribute()
    {
      $address = $this->address.', '.$this->city.', ';
      if ($this->country) {
        $address .= $this->country->country_name;
      }
      return $address;
    }

    public function getAgeAttribute()
    {
      return Carbon::parse($this->dateofbirth)->age;
    }

    public function getMahramRelationshipAttribute()
    {
      $mahram_relation_map = [
        '0' => lang::get('core.is_mahram'),
        '1' => 'Father',
        '2' => 'Husband',
        '3' => 'Brother',
        '4' => 'Uncle',
        '5' => 'Grandfather',
        '6' => 'Foster father',
        '7' => 'Stepfather',
      ];
      return $mahram_relation_map[$this->mahram_relation];
    }

    public function getUserAttribute()
    {
      $user = User::where('owner_id', CNF_OWNER)->where('email', $this->email)->get()->first();

      return $user;
    }
    
    
    public function getGenderLanguageAttribute()
    {
      if ($this->gender === 'M') {
        return 'core.male';
      }elseif ($this->gender === 'F') {
        return 'core.female';
      }else{
        return 'core.nogender';
      }
    }
}
