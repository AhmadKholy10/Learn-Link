<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['full_name', 'code', 'date_of_birth', 'level', 'email', 'level_id'] ; 
    
    public function scopeSelection($query){
        return $query -> select('id', 'full_name','code', 'date_of_birth', 'level', 'email', 'level_id');
    }

    // public function courses(){
    //     return $this -> belongsToMany(Course::class, 'student_course');
    // }

    // public function gradeItems(){
    //     return $this -> belongsToMany(GradeItem::class, 'student_grade_item', 'student_id', 'grade_item_id');
    // }

    public function studentGradeItems(){
        return $this->hasMany(StudentGradeItem::class, 'student_id', 'id');
    }
}
