<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    protected $table = "posts";

    public function files()
    {
        return $this->hasMany(\App\File::class, 'post' , 'id');
    }

    public function cat(){
        return $this->hasMany(\App\serv_deps::class, 'id' , 'servdep')->take(1);
    }

    public function comment()
    {
        return $this->hasMany(\App\Comment::class, 'post' , 'id')->count();
    }
    public function review()
    {
        return $this->hasMany(\App\review::class, 'post' , 'id')->count();
    }

    public function country(){
         return $this->hasMany(\App\Countries::class, 'id' , 'country')->take(1);
    }
    public function owner(){
         return $this->hasOne(\App\User::class, 'id' , 'add_by')->take(1);
    }

    public function area(){
         return $this->hasMany(\App\Areas::class, 'id' , 'area')->take(1);
    }

    public function cities(){
         return $this->hasMany(\App\City::class, 'id' , 'city')->take(1);
    }

   public function getTimeoutAttribute($value)
    {
        return date('d-m-Y' , $value);
    }

    public function photo() {
    }



    // queiries
    public static function getLatest($limit = 20) {
       return Post::where('pin_post','0')->orderBy('created_at', 'desc')->limit($limit)->get();
    }
    public static function getPinned() {
       return Post::where('pin_post','1')->orderBy('created_at', 'desc')->get();
    }
    public static function getCatPosts($id){
        return Post::where('servdep' , $id)->orderBy('super_post_end' , 'desc')->orderBy('created_at' , 'desc')->get();
    }
}
