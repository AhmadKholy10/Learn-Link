<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'description'];

    public function students(){
        return $this -> belongsToMany(Student::class, 'student_course');
    }

    public function gradeItems(){
        return $this->hasMany(GradeItem::class, 'course_id', 'id');
    }
}
