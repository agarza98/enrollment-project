<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{

    public function index(Request $request)
    {
        try {

            $enrollments = Enrollment::with(['user:id,name,email', 'course:id,title'])
                ->filterByStatus($request->input('status'))
                ->filterByCourse($request->input('courseName'))
                ->filterByUser($request->input('user'))
                ->sortBy($request->input('sort'), $request->input('order'))
                ->paginate(20);
            return response()->json([
                    'status' => 200,
                    'json' => $enrollments,
                    'message' => 'Success'
                ]
                , 200);

        } catch (\Exception $exception) {
            return response()->json([
                    'status' => 500,
                    'message' => $exception->getMessage()
                ]
                , 500);
        }

    }

    public function create()
    {
        try {
            $courses = Course::all();
            $data = [
                'courses' => $courses,
            ];
            return response()->json([
                    'status' => 200,
                    'json' => $data,
                    'message' => 'Success'
                ]
                , 200);
        } catch (\Exception $exception) {
            return response()->json([
                    'status' => 500,
                    'message' => $exception->getMessage()
                ]
                , 500);
        }
    }

    public function show($enrollment)
    {
        try {
            $enrollment = Enrollment::with(['user', 'course'])->where('id', $enrollment)->first();
            return response()->json([
                    'status' => 200,
                    'json' => $enrollment,
                    'message' => 'Success'
                ]
                , 200);
        } catch (\Exception $exception) {
            return response()->json([
                    'status' => 500,
                    'message' => $exception->getMessage()
                ]
                , 500);
        }
    }

    public function store(StoreEnrollmentRequest $request)
    {
        try {
            $request->validated();

            $user = User::create([
                'name' => $request->get('user_name'),
                'email' => $request->get('user_email'),
            ]);
            $enrollment = Enrollment::create([
                'course_id' => $request->get('course_id'),
                'user_id' => $user->id,
                'status' => $request->get('status')
            ]);

            return response()->json([
                    'status' => 201,
                    'json' => $enrollment,
                    'message' => 'Enrollment created'
                ]
                , 201);

        } catch (\Exception $exception) {
            return response()->json([
                    'status' => 500,
                    'message' => $exception->getMessage()
                ]
                , 500);
        }

    }

    public function update(UpdateEnrollmentRequest $request, Enrollment $enrollment)
    {
        try {

            $request->validated();

            $enrollment->update([
                'status' => $request->get('status')
            ]);


            return response()->json([
                    'status' => 200,
                    'json' => $enrollment,
                    'message' => 'Enrollment updated'
                ]
                , 200);

        } catch (\Exception $exception) {
            return response()->json([
                    'status' => 500,
                    'message' => $exception->getMessage()
                ]
                , 500);
        }
    }

    public function delete(Enrollment $enrollment)
    {
        try {
            $enrollment->delete();
            return response()->json([
                    'status' => 204,
                    'message' => 'Enrollment deleted'
                ]
                , 204);

        } catch (\Exception $exception) {
            return response()->json([
                    'status' => 500,
                    'message' => $exception->getMessage()
                ]
                , 500);

        }
    }
}
