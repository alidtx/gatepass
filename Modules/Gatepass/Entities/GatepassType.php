<?php

namespace Modules\Gatepass\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

class GatepassType extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by')->with('department', 'userSource');
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

        return $query;
    }

}
