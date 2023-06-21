<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGradeItem extends Model
{
    use HasFactory;
    protected $table = "student_grade_item";
    protected $fillable = ['id', 'student_id', 'grade_item_id', 'grade'];

    public function student(){
        return $this->belongsTo(Student::class, 'student_id','id');
    }
}
