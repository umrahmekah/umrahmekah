<?php

namespace App\Models;

use Carbon;
use App\User;
use App\Library\SiteHelpers;
use Illuminate\Support\Facades\Lang;

class tasks extends Mmb
{
    protected $table      = 'task';
    protected $primaryKey = 'id';
    protected $fillable   = [
          'task_name',
          'description',
          'owner_id',
          'due_date',
          'assigner_id',
          'assigned_id',
          'entry_by',
          'tour_date_id',
          'status',
    ];
    
    public function owners()
    {
        return $this->belongsTo(Owners::class, 'id');
    }
    
    public function users()
    {
        return $this->belongsTo(User::class, 'id');
    }
    
    public function tourdates()
    {
        return $this->belongsTo(Tourdates::class, 'tourdateID');
    }

    public function assignedByUser()
    {
      return $this->belongsTo(User::class, 'assigner_id');
    }

    public function assignedToUser()
    {
      return $this->belongsTo(User::class, 'assigned_id');
    }
}
