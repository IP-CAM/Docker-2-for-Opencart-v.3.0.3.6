<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Authorization, content-type');
header('Access-Control-Allow-Origin: *');




        App::setLocale('ar');

        

  app('Dingo\Api\Auth\Auth')->extend('jwt', function ($app) {
                   return new Dingo\Api\Auth\Provider\JWT($app['Tymon\JWTAuth\JWTAuth']);
  });

  
  Route::get('/newtask', 'NewTask@show');  
  
  Route::get('/taskDetails/{id}', 'NewTask@details');    

  /*Route::get('/', function () {
    die('404 not found');
  });*/

# Initialize Dingo routing
  $api = app('Dingo\Api\Routing\Router');

# Routes version 1
  $api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {


    
    $api->get('/', function () {
     return base_path();
        // die('404 not found');
    });
    
    // reviews 
    $api->get('/review/{id}' , 'ProductController@get_review');
    $api->post('/review_add' ,'ProductController@add_review');

    // related items
    $api->get('/related_items/{id}' , 'ProductController@get_relate');

    // Regsiter
    $api->post('/newtask', 'NewTask@store');


    // categories api
    $api->get('/cats', 'CategoryController@show');

    // Regsiter
    $api->post('/register', 'Register@create');

    // Login
    $api->post('/login', 'Login@create');
    $api->post('/login/token', 'Login@create_auth');
    
    
    // search
    $api->post('/search', 'SearchController@show');

    //profile
    $api->get('/profile', 'Profile@show');
    $api->put('/profile', 'Profile@update');


    //profile/address
    $api->resource('/profile/address', 'AddressController');
 
    //profile/order
    $api->resource('/profile/order', 'OrderController');
 
    //profile/wishlist
    $api->resource('/profile/wishlist', 'WishlistController');


    //profile/cart
    $api->get('/profile/cart/{code}', 'CartController@index');

    $api->post('/profile/cart/{code}', 'CartController@store');
    $api->put('/profile/cart/{code}', 'CartController@update');

    $api->get('/profile/cart/', 'CartController@index2');

    $api->resource('/profile/cart/', 'CartController');
    // $api->post('/profile/cart' , 'CartGuestController');


      $api->delete('/profile/cart/remove/{cart}', 'CartController@remove');
      $api->get('/profile/cart/promo/{code}', 'CartController@promocode');

    
    // product 
    $api->get('/product/latest' , 'ProductController@get_latest');
    $api->get('/product/special' , 'ProductController@get_special');
    $api->get('/product/cat/{id}' , 'ProductController@cat_products');
    $api->get('/product/{id}' , 'ProductController@product');



    // banner
    $api->get('/banners' , 'BannerController@index');
    $api->get('/flashes' , 'ProductController@get_flash');
    
  
    // misc
    $api->get('/misc/init' , 'MiscController@init');
    $api->get('/misc/country_zones/{id}' , 'MiscController@country_zones');

    $api->get('/home' ,  'MiscController@home');

    $api->get('/main/cats' , 'CategoryController@MainCats');
    $api->get('/Article/List' , 'ArticleController@getArt');
    $api->get('/Article/{id}' , 'ArticleController@getList');
    $api->get('/AllArticle' , 'ArticleController@AllArt');
    $api->get('/Recipe/{id}' , 'ArticleController@Recipe');


  
  });
