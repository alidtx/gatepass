<?php

namespace Modules\ItemDescription\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Modules\Item\Entities\Item;

class ItemDescription extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function itemInfo()
    {
        return $this->belongsTo(Item::class ,'item_id','id')->with('unitInfo');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by')->with('department', 'userSource');
    }

    public function scopeFilter($query, array $filters = []): Builder
    {
        if (isset($filters['item_id'])) {
            $query->whereItemId($filters['item_id']);
        }

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name']. '%');
        }

        return $query;
    }

    public function scopeActive($query)
    {
        return $query->whereStatus(1);
    }
}
