<?php
namespace App\Repositories;
interface RepositoryInterface{
    /**
     * Get all
     * @return mixed
     */
 public function all();
/**
 * Get all 
 */
public function find($id, $columns=['*'],$relations=[] );
/**
 * Get one
 * @param array $id
 * @param array $columns
 * @param array $relations
 * @return mixed
 */
public function findWhere($where, $columns=['*'],$relations=[] );
/**
 * Find Where
 * @param array where
 * @param array $columns
 * @param array $relations
 * @return mixed
 */
public function create(array $attributes);
/**
 * Create
 
 * @param array $attributes
 * @return mixed
 */
public function update($id, array $attributes);
/**
 * Update
 * @param int $id
 * @param array $attributes
 * @return mixed
 */
public function delete($id);
/**
 * Delete
 * @param int $id
 * @return mixed
 */




}
?>
