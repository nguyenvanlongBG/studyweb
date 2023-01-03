<?php

namespace App\Http\Controllers;

use App\Services\AnswerService;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    
    private AnswerService $answerService;
    public function __construct(AnswerService $answerService){
     $this->answerService=$answerService;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->answerNormalService->create($request);
    }
     public function list($id){
    // dd($request->id);
    
      return  $this->answerService->listByIdQuestion($id);
    }
    public function listByIdQuestion(Request $request){
    // dd($request->id);
      return  $this->answerService->listByIdQuestion($request->id);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showNormalAnswer($id)
    {
        return $this->answerService->showNormalAnswer($id);
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
    public function update(Request $request, $id)
    {
        //
    }

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
