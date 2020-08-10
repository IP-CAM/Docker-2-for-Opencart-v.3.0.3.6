<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * @Resource("posts", uri="/posts")
 */
class PostController extends Controller
{
    /**
    * Show latest posts
    *
    * Get a JSON representation of latest posts
    *
    * @Get("/")
    * @Response(200, body={"data":"Array<Post>"} )
    */
    public function index()
    {
        $pinned_posts = \App\Post::getPinned();
        $latests_posts = \App\Post::getLatest(20);
        
        return
        $this->response()->array(
            [
                'latest' => $latests_posts,
                'pinned' => $pinned_posts,
            ]);
    }


     /**
    * Show latest posts
    *
    * Get a JSON representation of latest posts by cat {id}
    *
    * @Response(200, body={"data":"Array<Post>"} )
    * @Get("/cat/{id}")
    */
    public function showCat($id)
    {
        $catPosts = \App\Post::getCatPosts($id);
        if (count($catPosts) > 0) {
            return $this->response()->array($catPosts->toArray());
        } else {
            return $this->response->errorNotFound();
        }
    }



    /**
    * Show Post Details
    *
    * Get a JSON representation of Post Details by post {id}
    *
    * @Post("/")
    * @Request("id=:number", contentType="application/x-www-form-urlencoded")
    *   @Response(200, body={"data":"Post[info+comment+files]"})
    */
    public function show(Request $request)
    {
        $id = $request->input('id', '1');
        $post = \App\Post::with(['files' , 'cat' , 'country' , 'cities' , 'area' , 'owner'])->findOrFail($id);
        if (count($post) == 1) {
            return $this->response()->array($post->toArray());
        } else {
            return $this->response()->errorNotFound();
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $images = $request->input('images');
        
        $imgArray = [];
        $imgArray = explode(',data:image/jpeg;base64', $images);

        if ($request->uid > 0) {
            $type_ads = 'user';
        } else {
            $type_ads = 'visitor';
        }
        $post = new \App\Post();
        $post->title = $request->title;
        $post->content = $request->desc;
        $post->youtube = 0 ;
        $post->keywords = 0 ;
        $post->views = 0;
        $post->add_by = $request->uid;
        $post->type_post = 1;
        $post->type_ads = $type_ads ;
        $post->price = 0 ;
        $post->rent_time = 0 ;
        $post->servdep = $request->cat;
        $post->country = 4 ;
        $post->area      = 45;
        $post->city      = 8;
        $post->active = 1;
        $post->photo = 0 ;
        $post->super_post = 0;
        $post->super_post_end = 0;
        $post->pin_post = 0;
        $post->pin_start = 0;
        $post->pin_end = 0;
        $post->comment = 0 ;
        $post->name = $request->username;
        $post->mobile = $request->phone;
        $post->email = $request->email;
        $post->address = 0 ;
        $post->timeout = time() + (60*60*24*360);
        $post->timeout_num = 0;
        $post->save();
        if (count($imgArray) > 0) {
            $images = $this->handle64Images($imgArray);
            foreach ($images as $key => $img) {
                $file = new \App\File();
                $file->uid = 0;
                $file->slide = 0;
                $file->post = $post->id;
                $file->cat_id =  0;
                $file->news_id =  0;
                $file->invite_id = 0;
                $file->file =  $img['file'];
                $file->path =  $img['path'];
                $file->ext =  'jpg';
                $file->mimtype =  'image/jpeg';
                $file->name =  $img['file'];
                $file->size =  $img['size'];
                $file->success =  0;
                $file->add_date =  time();
                $file->tmp_id = 0;
                $file->save();
            }
            $post->photo = $images[0]['path']. $images[0]['file'];
            $post->save();
        }
        return $this->response()->array(
            $post->toArray()
        );
    }

    private function handle64Images($imgArray)
    {
        $storage = Storage::disk('custom');
        $op = [];
        foreach ($imgArray as $key => $value) {
            // file path
            $public = '';
            $folder = 'posts/'.time().rand(10, 99).'/';
            $fileName = time().rand(100, 999) . '.jpg';
            $fullPath = $public . $folder . $fileName;

            //file contnet
            $value = str_replace('data:image/jpeg;base64', '', $value);
            $value = str_replace('[removed]', '', $value);
            $value = str_replace(',', '', $value);
            $data = base64_decode($value);

            // save file
            $storage->put($fullPath, $data );

            \Log::info($fullPath);

            $op[] = [
                'path' => $folder,
                'file' => $fileName,
                'size' => time(),
            ];
        }
        return $op;
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
