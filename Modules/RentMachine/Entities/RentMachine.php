<?php

namespace Modules\RentMachine\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Kyslik\ColumnSortable\Sortable;

class RentMachine extends Model
{
    use HasFactory, Sortable;

   
}
