<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierService extends Model
{
    protected $table      = 'def_supplier_services';
    protected $primaryKey = 'id';

    protected $fillable = [
    	'name',
    	'description',
    	'start_date',
    	'end_date',
    	'price',
    	'min_quantity',
    	'max_quantity',
    	'document',
    	'supplier_id',
    	'status',
    	'entry_by',
    	'owner_id'
    ];

    protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at'];

    public function supplier()
    {
    	return $this->belongsTo(Suppliers::class, 'supplier_id');
    }

    public function getDocumentUrlAttribute()
    {
    	return url('/suppliers/downloaddocument/'.$this->id.'?file='.$this->document.'&an_id='.$this->supplier_id);
    }

    public function getStatusLabelAttribute()
    {
    	if (\Carbon::today() > $this->end_date) {
    		return '<span class="label label-danger">Expired</span>';
    	}
        if ($this->status == 0) {
            return '<span class="label label-danger">Not Active</span>';
        }else{
            return '<span class="label label-success">Active</span>';
        }
    }
}
