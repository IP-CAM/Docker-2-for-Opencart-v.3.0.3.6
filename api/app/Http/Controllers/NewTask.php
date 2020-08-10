<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tasks;

class NewTask extends Controller
{
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
    public function create()
    {
        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => ['required'  ],
            'phone' => ['required' ],
            'content' => ['required' ],
            'taskLat' => ['required'],
            'taskLng' => ['required'],
            
        ];

        $payload = app('request')->only('title', 'phone',
         'content', 'taskLat', 'taskLng');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('تحقق من الحقول المستخدمه.', $validator->errors());
        } 
    else{
        $task = new Tasks;
        $task->title = $request->title;
        $task->phone = $request->phone;
        $task->content = $request->content;
        $task->taskLat = $request->taskLat;
        $task->taskLng = $request->taskLng;
        $task->save();
        return $task;
    }

        
        
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
       $task = Tasks::orderby('id' , 'desc')->get();
       return view('tasks.index', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function details($id)
    {
        $task = Tasks::find($id);
        return view('tasks.details', compact('task'));
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
