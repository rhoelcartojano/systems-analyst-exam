<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessUtilitiesModel extends Model
{
    use HasFactory;

    protected $table = 'access_utilities';
    
    protected $fillable = [
        'employee_id',
        'access_utilities'
    ];

    protected $casts = [
        'access_utilities' => 'array'
    ];

    public function employee() {
        return $this->belongsTo(EmployeeModel::class);
    }
}
