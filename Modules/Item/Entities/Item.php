<?php

namespace Modules\Item\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Modules\Unit\Entities\Unit;
use Modules\ItemDescription\Entities\ItemDescription;

class Item extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function itemDescription()
    {
        return $this->hasMany(ItemDescription::class, 'item_id', 'id');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by')->with('department', 'userSource');
    }

    public function unitInfo()
    {
        return $this->belongsTo(Unit::class ,'unit_id','id');
    }

    public function scopeActive($query)
    {
        return $query->whereStatus(1);
    }

    public function scopeFilter($query, array $filters = []): Builder
    {
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name']. '%');
        }

        if (isset($filters['status'])) {
            $query->whereStatus($filters['status']);
        }

        if (isset($filters['unit_id'])) {
            $query->whereUnitId($filters['unit_id']);
        }

        return $query;
    }
    
}
