<?php

namespace Modules\GatepassReceive\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Item\Entities\Item;
use Modules\Gatepass\Entities\GatepassItem;

class InternalReceiveItem extends Model
{
    use HasFactory;

    protected $guarded =[];

    public function itemsInfo()
    {
        return $this->belongsTo(GatepassItem::class, 'item_id')->with('itemInfo', 'unitInfo');
    }
    
}
