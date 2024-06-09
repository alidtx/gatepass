<?php

namespace Modules\Departments\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Kyslik\ColumnSortable\Sortable;

class Department extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [];
    
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
