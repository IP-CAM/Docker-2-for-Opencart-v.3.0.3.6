<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $builder = \App\Product_description::query();

        if ($request->has('q')) {
        \Log::info('user query:' . $request->input('q')); 
            $queryString = $request->input('q');

            \Log::info('request data ' , [$queryString]);
                   $builder->where('name', 'LIKE', "%$queryString%");
        }
        $posts = $builder->orderBy('name')->limit(30)->get();
\Log::info('search result' ,[$posts] );
        return $this->response()->array($posts->toArray());
    }
}
