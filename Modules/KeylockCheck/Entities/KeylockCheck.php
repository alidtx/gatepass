<?php

namespace Modules\KeylockCheck\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Kyslik\ColumnSortable\Sortable;

class KeylockCheck extends Model
{
    use HasFactory, Sortable;

   
}
