<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');

    }

    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');

    }

    public function scopeFilterByStatus($query, $status)
    {
        if (isset($status)) {
            return $query->where('status', $status);
        }
    }

    public function scopeFilterByCourse($query, $courseName)
    {
        if (isset($courseName)) {
            return $query->whereHas('course', function ($query) use ($courseName) {
                $query->where('title', 'like', '%' . $courseName . '%');
            });
        }
    }

    public function scopeFilterByUser($query, $user)
    {
        if (isset($user)) {
            return $query->whereHas('user', function ($query) use ($user) {
                $query->where('name', 'like', '%' . $user . '%')
                    ->orWhere('email', 'like', '%' . $user . '%');
            });
        }
    }


    public function scopeSortBy($query, $field, $direction = 'asc')
    {
        if (isset($field)) {

            if ($field === 'course_title') {
//                return $query->whereHas('course', function ($query) use ($direction) {
//                    $query->orderBy('title', $direction);
//                });
                return $query->select('enrollments.*')
                    ->join('courses', 'courses.id', '=', 'enrollments.course_id')
                    ->orderBy('courses.title', $direction);
            } elseif ($field === 'status') {
                return $query->orderBy('status', $direction);
            } elseif ($field === 'created_at') {
                return $query->orderBy('created_at', $direction);
            } elseif ($field === 'updated_at') {
                return $query->orderBy('updated_at', $direction);
            }
            return $query;
        }
    }

}
