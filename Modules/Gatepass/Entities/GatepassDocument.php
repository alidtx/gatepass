<?php

namespace Modules\Gatepass\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GatepassDocument extends Model
{
    use HasFactory;

    protected $fillable = ['document_name', 'document'];

    public function getDocumentAttribute($value):mixed
    {
        return $value ? asset('/').$value : $value;
    }
}
