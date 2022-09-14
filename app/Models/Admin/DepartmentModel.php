<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    use HasFactory;

    protected $table = 'departments';
    
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    public function employee() {
        return $this->hasMany(EmployeeModel::class);
    }
}
