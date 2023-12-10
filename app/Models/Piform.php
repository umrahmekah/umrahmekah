<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Travellers;
use Carbon;

class piform extends Mmb  {
	
	protected $table = 'pi_forms';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT pi_forms.* FROM pi_forms  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE pi_forms.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	
	public function accomodations()
	{
		return $this->hasMany(Accomodation::class, 'pi_form_id');
	}

	public function ziarahs()
	{
		return $this->hasMany(Ziarah::class, 'pi_form_id');
	}

	public function transportations()
	{
		return $this->hasMany(Transportation::class, 'pi_form_id');
	}

	public function localContacts()
	{
		return $this->hasMany(LocalContact::class, 'pi_form_id');
	}

	public function remarks()
	{
		return $this->hasMany(PiformRemark::class, 'pi_form_id');
	}

	public function tourdate()
	{
		return $this->belongsTo(Tourdates::class, 'tourdate_id');
	}

	public function leader()
	{
		return $this->belongsTo(Guide::class, 'leader_id');
	}

	public function getPaxAttribute()
	{
		$booktours = $this->tourdate->booktours;

		$adults = 0;
		$children = 0;
		$infants = 0;

		foreach ($booktours as $booktour) {
			$bookrooms = $booktour->booking->bookRoom;
			foreach ($bookrooms as $room) {
				$travellers_id = explode(',', $room->travellers);
				foreach ($travellers_id as $id) {
					$traveller = Travellers::find($id);
					if ($traveller) {
						$age = Carbon::parse($traveller->dateofbirth)->age;
						if ($age > 12) {
							$adults++;
						}elseif ($age > 2) {
							$children++;
						}else{
							$infants++;
						}
					}
				}
			}
		}

		$pax = ['adults' => $adults,'children' => $children,'infants' => $infants,];

		return $pax;
	}
}
