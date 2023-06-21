<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Level;

class StudentController extends Controller
{
    public function getStudents(){
        $students = Student::all();
       return $students ;
   }

   public function store(){
    return Student::create([
        'full_name' => request('name'),
        'email' => request('email'),
        'date_of_birth' => request('date_of_birth'),
        'code' => request('code'),
        'level' => request('level')
    ]);
   }

   public function update(Student $student){
    $student->update([
        'full_name' => request('name'),
        'email' => request('email'),
        'date_of_birth' => request('date_of_birth'),
        'code' => request('code'),
        'level' => request('level')
    ]);

    return $student;
   }

   public function delete($id){
    $student = Student::find($id);
    $student->delete();
    return response()->noContent();
   }

   public function search(){
    $searchQuery = request('query');
    $students = Student::where('full_name','like',"%{$searchQuery}%")->get();
    return response()->json($students);
   }

   public function getLevels(){
       $levels = Level::all();
       return response()->json($levels);
   }

   public function filter($id){
       $students = Student::where('level_id', $id )->get();
       return response()->json($students);
   }
}
