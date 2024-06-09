<?php

namespace Modules\Gatepass\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Item\Entities\Item;
use Modules\Unit\Entities\Unit;
use Modules\ItemDescription\Entities\ItemDescription;

class GatepassItem extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    public function itemInfo()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id')->with('unitInfo');
    }

    public function unitInfo()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
