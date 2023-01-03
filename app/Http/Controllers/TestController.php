<?php

namespace App\Http\Controllers;

use App\Services\TestService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    private TestService $testService;
    public function __construct(TestService $testService)
    {
        $this->testService=$testService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        return $this->testService->list($request);
    }
public function list(Request $request)
    {
        
        // if($request->params)
        //  dd("OK List");
        // dd($request);
        return $this->testService->list($request);
        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // dd($request);
        $this->testService->create($request);
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
    public function show($id)
    {
        // dd($request->input('idTest'));
      return $this->testService->show($id);
    }
    public function listQuestionTestDo($id, Request $request)
    {
        // dd($request->input('idTest'));
        // dd($id);
        // dd($request);
            return $this->testService->listQuestionDo($id, $request);
       
      
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function nummericalQuestion($id,Request $request){
        if($request->type==1){
              return $this->testService->nummericalQuestionDo($id, $request);
        }else{
            if($request->type==2){
                return $this->testService->nummericalQuestionUpdate($id, $request);
            }else{
                return $this->testService->nummericalQuestionHistory($id, $request);
            }
        }
       
    }

    public function edit($id)
    {
        //
    }
public function updateTest($id,Request $request){
       return $this->testService->update($id, $request);
}   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listQuestionTestUpdate( $id, Request $request)
    {
        return $this->testService->listQuestionUpdate($id,  $request->current_page);
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
