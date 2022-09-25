<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'level',
        'point',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function posts()
    {
        $this->hasMany(Post::class);
    }
    public function exams()
    {
        $this->hasMany(Exam::class);
    }
    public function answerNormal()
    {
        $this->hasMany(AnswerNormal::class);
    }
    public function roles()
    {
        $this->hasMany(Role::class);
    }
    public function classroomUser()
    {
        $this->hasMany(ClassroomUser::class);
    }
    public function answerTest()
    {
        $this->hasMany(AnswerTest::class);
    }
    public function classrooms()
    {
        $this->belongsToMany(Classroom::class, 'classroom_users');
    }
}
