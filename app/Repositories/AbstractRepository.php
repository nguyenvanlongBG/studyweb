<?php
namespace App\Repositories;
abstract class AbstractRepository implements RepositoryInterface{
    protected $model;
    public function __construct()
    {
        $this->setModel();
    }
abstract public function model();
public function setModel(){
    $this->model=app()->make($this->model());
}
public function builder(){
    $this->model->newQuery();
}
public function appplyConditions(array $where){
    foreach($where as $field => $value ){
        if( is_array($value)){
            list($field,$condition,$val)=$value;
            $this->model=$this->model->where($field,$condition, $val);
        }else{
            $this->model=$this->model->where($field, '=', $value);
        }
    }
}
public function all(){
    $this->model->all();
}
public function find($id, $columns=['*'], $relations=[]){
return $this->model->with($relations)->find($id, $columns);
}

public function create(array $attributes){
        return $this->model->create($attributes);
}
public function update($id, array $attributes){
    $result=$this->find($id);
    if($result){
        $result->update($attributes);
        return  $result;
    }
    return false;
}
public function delete($id){
    $result=$this->find($id);
    if($result){
        $result->delete();
        return true;
    }
    return false;
}

public function findWhere($where, $columns = ['*'], $relations = [])
{
    $this->appplyConditions($where);
    $model=$this->builder->select($columns)->with($relations)->get();
    $this->setModel();
    return $model;           
}
}
?>