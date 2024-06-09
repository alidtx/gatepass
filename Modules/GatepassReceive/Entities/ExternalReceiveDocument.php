<?php

namespace Modules\GatepassReceive\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExternalReceiveDocument extends Model
{
    use HasFactory;

    protected $guarded =[];
    
    public function getDocumentAttribute($value):mixed
    {
        return $value ? asset('/').$value : $value;
    }
}
