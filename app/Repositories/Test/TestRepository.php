<?php  
namespace App\Repositories\Test;

use App\Models\Test;
use App\Repositories\AbstractRepository;

class TestRepository extends AbstractRepository{
    public function model()
    {
       return Test::class;
    }
  
    public function list()
    {
        
    }
    public function listUser($idClass)
    {
       
        
    }
  
}

?>