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
        'role_id',
        'level',
        'point',
        'asset'
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
       return $this->hasMany(Post::class);
    }
    public function exams()
    {
       return $this->hasMany(Exam::class);
    }
    public function answerNormals()
    {
       return $this->hasMany(AnswerNormal::class);
    }
    public function roles()
    {
       return $this->hasMany(Role::class);
    }

      public function groups()
    {
       return $this->belongsToMany(Group::class, 'group_users', 'user_id','group_id');
    }
       public function transactions()
    {
       return $this->hasMany(Transaction::class);
    }
    public function answerTests()
    {
       return $this->hasMany(AnswerQuestionTest::class);
    }
  public function userTests()
    {
       return $this->belongsToMany(UserTest::class);
    }
    public function Message()
    {
       return $this->hasMany(Message::class);
    }
       public function Request()
    {
       return $this->hasMany(Request::class);
    }   
       public function notifications()
    {
       return $this->belongsToMany(Notification::class, 'notification_users', 'user_id','notification_id');
    }
        public function missions()
    {
       return $this->belongsToMany(Mission::class, 'mission_users', 'user_id','mission_id');
    }
      public function comments()
    {
       return $this->hasMany(Comment::class);
    }
       public function confidentials()
    {
       return $this->hasMany(Confidential::class);
    }
     public function reacts()
    {
       return $this->hasMany(React::class, 'react_users');
    }
     public function questionCompetitions()
    {
       return $this->belongsToMany(QuestionCompetition::class);
    }
    public function events(){
      $this->belongsToMany(Event::class);
    }
    
}
