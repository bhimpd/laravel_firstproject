<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\StudentModel;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    //retrieve all students
    public function index()
    {
        $students = StudentModel::all();
        if($students->isEmpty()){
        $response = [
            'status' => false,
            'message' => 'No data available',
            'data' => []
        ];

        return response()->json($response,404);
       };

       $total = StudentModel::all()->count(); //fetch total number of data in the table
        return response()->json([
            'status' => true,
            'message' => 'Students retrieved successfully',
            'total_data'=>$total,
            'data' => $students
        ], 200);
    }


    //create the students
    public function store(StoreStudentRequest $request)
    {
        // Create a new student record
        $student = StudentModel::create($request->validated());
        dd($student->get());
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
            $student =StudentModel::find($id);

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
            $studentinfo =StudentModel::find($id);
            $student =StudentModel::destroy($id);

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

        $student =StudentModel::find($id);

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
        // Get search parameters from the query string
        $name = $request->query('name');
        $age = $request->query('age');
        $address = $request->query('address');
        $email = $request->query('email');

        // Build the query based on the search parameters
        $query = StudentModel::query();
        
       if($name){
            $query->where('name','LIKE',"%{$name}%");
        }

        if ($age) {
            $query->where('age', $age);
        }
    
        if ($address) {
            $query->where('address', 'LIKE', "%{$address}%");
        }

        if ($email) {
            $query->where('email', 'LIKE', "%{$email}%");
        }

        // dd($query->toSql(), $query->getBindings());  if we like to see the query, we need to call toSql and getBinging methods
        
        // Execute the query and get the results
        $students = $query->get();

        // dd($students);  to see the output

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