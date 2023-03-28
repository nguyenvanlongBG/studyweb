<?php
namespace App\Services;
use App\Models\ItemSubject;


class SubjectService extends BaseService
{
    public function __construct(){

    }
    public function createItems($request){

    }
    public function getItems($subject_id){
        $items= ItemSubject::where('subject_id', '=', $subject_id)->get()->makeHidden(['subject_id','created_at', 'updated_at']);
        if($items){
            return $this->sendResponse($items, "Successfully");
        }else{
            return $this->sendError(null, "Fail");
        }
       
    }
}

