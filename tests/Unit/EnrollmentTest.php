<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->getJson(route('index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'json' => [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'course_id',
                            'user_id',
                            'status',
                            'created_at',
                            'updated_at',
                            'user' => [
                                'id',
                                'name',
                                'email'
                            ],
                            'course' => [
                                'id',
                                'title'
                            ]
                        ]
                    ]
                ],
                'message'
            ]);

    }

    public function testFilteredEnrollments()
    {
        $enrollment = Enrollment::with(['user', 'course'])->first();

        $response = $this->get(route('index', [
            'courseName' => $enrollment->course->title,
            'user' => $enrollment->user->email,
            'sort' => 'created_at',
            'order' => 'desc'
        ]));

        // Assert
        $response->assertStatus(200);
        $response->assertJsonFragment(
            [
                'id' => $enrollment->id,
                'status' => $enrollment->status,
                'course_id' => $enrollment->course_id,
                'user_id' => $enrollment->user_id,
                'created_at' => $enrollment->created_at,
                'updated_at' => $enrollment->updated_at,
                'user' => [
                    'id' => $enrollment->user->id,
                    'name' => $enrollment->user->name,
                    'email' => $enrollment->user->email
                ],
                'course' => [
                    'id' => $enrollment->course->id,
                    'title' => $enrollment->course->title
                ]
            ]
        );
    }

    public function testCreate()
    {

        $response = $this->get(route('create'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'json' => [
                    'courses' => [
                        '*' => [
                            'id',
                            'title',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ],
                'message'
            ]);
    }

    public function testShow()
    {
        $enrollment = Enrollment::with(['user', 'course'])->first();

        $response = $this->get(route('show', ['enrollment' => $enrollment->id]));

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'json' => [
                    'id' => $enrollment->id,
                    "course_id" => $enrollment->course->id,
                    'status' => $enrollment->status,
                    'created_at' => $enrollment->created_at->toISOString(),
                    'updated_at' => $enrollment->updated_at->toISOString(),
                    'user' => [
                        'id' => $enrollment->user->id,
                        'name' => $enrollment->user->name,
                        'email' => $enrollment->user->email
                    ],
                    'course' => [
                        'id' => $enrollment->course->id,
                        'title' => $enrollment->course->title
                    ],
                ],
                'message' => 'Success'
            ]);
    }

    public function testStoreEnrollment()
    {

        $enrollment = [
            'user_name' => 'test',
            'user_email' => Str::random(5) . '@test.com',
            'course_id' => 15,
            'status' => 1
        ];

        $response = $this->postJson(route('store'), $enrollment);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'status',
            'json' => [
                'id',
                'course_id',
                'user_id',
                'status',
                'created_at',
                'updated_at'
            ],
            'message'
        ]);

    }

    public function testStoreEnrollmentValidation()
    {
        $response = $this->postJson(route('store'), []);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'user_name', 'user_email', 'course_id', 'status'
        ]);
    }

    public function testUpdateEnrollment()
    {
        $enrollment = Enrollment::inRandomOrder()->first();

        $response = $this->putJson(route('update', $enrollment), [
            'status' => 1
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'json' => [
                'id',
                'user_id',
                'course_id',
                'status',
                'created_at',
                'updated_at'
            ],
            'message'
        ]);

        $enrollment->refresh();
        $this->assertEquals(1, $enrollment->status);
    }

    public function testUpdateEnrollmentValidation()
    {
        $enrollment = Enrollment::inRandomOrder()->first();

        $response = $this->putJson(route('update', $enrollment), [
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors([
            'status'
        ]);
    }

    public function testUpdateNonExistentEnrollment()
    {
        // Send a PUT request to update a non-existent enrollment
        $response = $this->putJson('/api/enrollment/999999', [
            'status' => 1
        ]);

        // Assert that the response has a status code of 404 (not found)
        $response->assertStatus(404);
    }

    public function testEnrollmentDeletion(): void
    {
        $enrollment = Enrollment::factory()->create();

        $response = $this->delete(route('delete', $enrollment));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('enrollments', ['id' => $enrollment->id]);
    }

    public function testDeleteNonExistentEnrollment()
    {
        $response = $this->delete(route('delete', ['enrollment' => 9999999999]));

        $response->assertStatus(404);
    }
}
