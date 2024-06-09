<?php

namespace Modules\GatepassReceive\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Gatepass\Entities\Gatepass;
use Modules\Gatecheck\Entities\Gatecheck;
use Modules\Location\Entities\Location;
use Kyslik\ColumnSortable\Sortable;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class InternalReceive extends Model
{
    use HasFactory, SoftDeletes, Sortable;

    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded =[];

    public function receivedItems()
    {
        return $this->hasMany(InternalReceiveItem::class, 'internal_receive_id')->with('itemsInfo');
    }
    
    public function gatepassCheckInfo()
    {
        return $this->belongsTo(Gatecheck::class, 'gatepass_check_id')->with('releasedUser');
    }

    public function toLocationInfo()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
    
    // Define a scope for filtering records based on the current month
    public function scopeCurrentMonth($query)
    {
        return $query->whereBetween('received_date_time', [
            now()->startOfMonth(),
            now()->endOfMonth(),
        ]);
    }

    // Define a scope for filtering records based on custom start and end dates
    public function scopeCustomDateRange($query, $fromDate, $toDate)
    {
        // return $query->whereBetween('received_date_time', [$fromDate, $toDate]);
        return $query->whereDate('received_date_time', '>=', $fromDate)
            ->whereDate('received_date_time', '<=', $toDate);
    }
}
