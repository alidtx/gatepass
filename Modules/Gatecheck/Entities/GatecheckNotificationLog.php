<?php

namespace Modules\Gatecheck\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class GatecheckNotificationLog extends Model
{
    use HasFactory, Sortable;

    protected $fillable = ['gatecheck_id', 'sent_at'];
    
}
