<?php

namespace App\Http\Controllers;

use App\Services\BaseService;
use App\Services\SubjectService;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    private SubjectService $subjectService;
    private BaseService $baseService;
     public function __construct(SubjectService $subjectService, BaseService $baseService)
    {
        $this->subjectService=$subjectService;
        $this->baseService=$baseService;
    }
    public function items($subject_id){
        return $this->subjectService->getItems($subject_id);
    }
}
