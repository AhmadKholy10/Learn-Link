<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\GradeItem;
use App\Models\StudentGradeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index(){
        $courses = Course::all();
       return $courses ;
   }

   public function search(){
    $searchQuery = request('query');
    $courses = Course::where('name','like',"%{$searchQuery}%")->get();
    return response()->json($courses);
   }

   public function getStudents($course_id){
   $query = DB::select("SELECT students.full_name as student_name ,students.code as student_code, grade_items.name as grade_item_name, grade_items.max_degree as grade_item_max, student_grade_item.grade as grade  FROM `courses` 
   join grade_items ON courses.id = grade_items.course_id
   join student_grade_item  ON grade_items.id = student_grade_item.grade_item_id
   join students ON students.id = student_grade_item.student_id
   WHERE courses.id = ".$course_id);
    
    $student_grades = [];
    foreach($query as $entry){
     $student_code = $entry->student_code;
     $student_name = $entry->student_name;
     $grade_item_name = $entry->grade_item_name;
     $grade = $entry->grade;
    if(!isset($student_grades[$student_code])){
        $student_grades[$student_code] = ['student_code'=> $student_code, 'student_name' => $student_name]; 
    }
    $student_grades[$student_code][$grade_item_name] = $grade;
    }
    $grades_array = [];
    foreach($student_grades as $student_grade => $data){
        $grades_array[] = $data ;
    }
     return response()->json($grades_array);
   }

}
