<?php  
namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\AbstractRepository;

class UserRepository extends AbstractRepository{
    public function model()
    {
       return User::class;
    }
  
    public function list()
    {
       
    }
  
}

?>