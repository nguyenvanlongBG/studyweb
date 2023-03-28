<?php

namespace App\Http\Controllers;

use App\Services\ExamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    private ExamService $examService; 
    public function __construct(ExamService $examService)
    {
        $this->examService=$examService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function list(Request $request){
       return $this->examService->list($request->all());
    }
    public function doTest(Request $request){
       
       $this->examService->doTest($request);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createNew(Request $request)
    {
       return $this->examService->createNew($request->all());
    }
    public function do(Request $request)
    {
       return $this->examService->doExam($request);
    }
     public function mark( $idExam,Request $request)
    {
       return $this->examService->mark($idExam,$request);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    public function submit(Request $request){
        return  $this->examService->submit($request);
    }
    public function reportExam($id, Request $request){
        return $this->examService->reportExam($id,$request);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function history($idTest, Request $request)
    {
        return $this->examService->history($idTest, $request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
