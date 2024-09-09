<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\StudentModel;
use App\Repositories\StudentRepositoryInterface;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    protected $studentrepointerface;

    public function __construct(StudentRepositoryInterface $studentrepointerface)
    {
        $this->studentrepointerface = $studentrepointerface;

    }

    //retrieve all students
    public function index()
    {
        $students = $this->studentrepointerface->getAllStudents();

        if($students->isEmpty()){
        $response = [
            'status' => false,
            'message' => 'No data available',
            'data' => []
        ];

        return response()->json($response,404);
       };

        return response()->json([
            'status' => true,
            'message' => 'Students retrieved successfully',
            'total_data'=>$students->count(),
            'data' => $students
        ], 200);
    }


    //create the students
    public function store(StoreStudentRequest $request)
    {
        // Create a new student record
        $student = $this->studentrepointerface->createStudent($request->validated());

        // Return a success response
        $response = [
            "status" => true,
            "message" => "Student Created Successfully.",
            "data" => $student
        ];
        return response()->json($response,201);
    }


    //to show specific id data
    public function show($id)
    {
        if (!is_numeric($id)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid ID format. ID must be numeric.',
                'data' => []
            ], 400); // 400 Bad Request for invalid input
        }
        $student = $this->studentrepointerface->getStudentById($id);

            if($student){
                return response()->json([
                    'status' => true,
                    'message' => 'Student found successfully',
                    'data' => $student
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => "No data found for Id: {$id}",
                    'data' => []
                ], 200);
            }
       
    }


     //to delete specific id data
     public function destroy($id)
     {
        if (!is_numeric($id)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid ID format. ID must be numeric.',
                'data' => []
            ], 400); // 400 Bad Request for invalid input
        }
        $studentinfo = $this->studentrepointerface->getStudentById($id);
        $student = $this->studentrepointerface->deleteStudent($id);

            if($student){
                return response()->json([
                    'status' => true,
                    'message' => 'Student deleted successfully',
                    'data' => $studentinfo
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => "No data found for Id: {$id}",
                    'data' => []
                ], 200);
            }
     }


    //update the student
    public function update(UpdateStudentRequest $request, $id)
    {
        if (!is_numeric($id)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid ID format. ID must be numeric.',
                'data' => []
            ], 400); // 400 Bad Request for invalid input
        }

        $student = $this->studentrepointerface->getStudentById($id);

        if(!$student){
            return response()->json([
                'status' => false,
                'message' => "No data found for Id: {$id}",
                'data' => []
            ], 200);
         
        } 
        
       // Validate and update the student record
       $validatedData = $request->validated();
       $student->update($validatedData);

        // Return a success response
        return response()->json([
            'status' => true,
            'message' => 'Student updated successfully',
            'data' => $student
        ], 200);
    }

    

    //search students based on name,age,email and address
    public function search(Request $request)
    {
        $students = $this->studentrepointerface->searchStudents($request);
        
        // Check if any results were found
        if ($students->isEmpty()) {
            // Build the response for no data found
            $response = [
                'status' => false,
                'message' => 'No data found for the given criteria',
                'data' => []
            ];
            return response()->json($response, 404);
        }
        
        // Build the response with the results
        $response = [
            'status' => true,
            'message' => 'Students retrieved successfully',
            'total_data' => $students->count(),
            'data' => $students
        ];

         return response()->json($response, 200);

    }


}