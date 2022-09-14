<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EmployeeModel extends Model
{
    use HasFactory;

    protected $table = 'employees';
    
    protected $fillable = [
        'department_id',
        'name',
        'email',
        'password'
    ];

    public function department() {
        return $this->belongsTo(DepartmentModel::class);
    }

    public function access_utility() {
        return $this->hasOne(AccessUtilitiesModel::class, 'employee_id');
    }
}
