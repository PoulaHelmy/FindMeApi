<?php

use Illuminate\Support\Facades\Route;

Route::get('/home', 'HomeController@index')->name('home');


/* For Password reset operations*/
Route::group(['middleware' => 'api', 'prefix' => 'password'], function () {
    Route::post('create', 'API\PasswordResetController@create');
    Route::get('find/{token}', 'API\PasswordResetController@find');
    Route::post('reset', 'API\PasswordResetController@reset');
});


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'API\Passport@login');
    Route::post('signup', 'API\Passport@signup');
    Route::get('signup/activate/{token}', 'API\Passport@signupActivate');

    Route::group(['middleware' => 'auth:api'], function () {


        /*************************** Passport USer Routes **************************/
        Route::get('user', 'API\Passport@details');
        Route::post('update/password', 'API\Passport@updatePassword');
        Route::get('logout', 'API\Passport@logout');
        Route::post('update', 'API\Passport@update');
        Route::get('userdata/{id}', 'API\Passport@UserData');

        /*********************************** -- ***************************************/

        /*************************** Items Routes **************************/
        Route::resource('items', 'API\Items');
        Route::post('getitembyname', 'API\Items@getItemByName');
        Route::resource('items/values', 'API\ItemValues');
        Route::get('items/markasreturned/{id}', 'API\Items@markAsReturned');

        /*********************************** -- ***************************************/


        /*************************** Questions Routes **************************/
        Route::post('items/questions', 'API\Questions@store');
        Route::put('items/questions/{id}', 'API\Questions@update');
        Route::get('items/questions/{id}', 'API\Questions@show');
        /*********************************** -- ***************************************/


        /*************************** Requests Routes **************************/
        Route::resource('requests', 'API\ItemsRequests');
        Route::post('requests/change/status', 'API\ItemsRequests@changeStatus');
        Route::post('requests/incoming', 'API\ItemsRequests@incoming_requests');
        /*********************************** -- ***************************************/


        /*************************** Notifications Routes **************************/
        Route::get('notifications/all', 'API\UsersNotifications@index');
        Route::get('notifications/markread/{id}', 'API\UsersNotifications@markNotificationAsRedaed');
        /*********************************** -- ***************************************/

        /*************************** Chat  Routes **************************/
        Route::get('chat/all/{id}', 'API\ChatController@getChat');
        Route::post('chat/sendmsg', 'API\ChatController@storeMsg');
        Route::post('chat/allmsgs', 'API\ChatController@getAllMsgs');

        /*********************************** Matching Items ***************************************/
        Route::get('matching/items', 'Api\MatchingItems@main');
        /*********************************** -- ***************************************/

        /*************************** Persons  Routes **************************/
        Route::post('persons/faces', 'API\Items@uploadPersonFaces');
        /*********************************** -- ***************************************/
        // Route::get('items/upoptions/{id}','API\Items@getAllItemOptions');
    });
});

/* --------------------------------------------------------------------------------- */
/* Get All Inputs Id's Realted to a subcat */
Route::get('subcatsinputs/{id}', 'API\Admin\SubCategoryAPI@all_subcatsids');

/* Cruds  Routes */
Route::resource('categories', 'API\Admin\CategoryApi');
Route::resource('subcategories', 'API\Admin\SubCategoryAPI');
Route::resource('inputs', 'API\Admin\InputsAPI');

/* Filters Routes */
Route::get('filter/categories', 'API\Admin\CategoryApi@indexWithFilter');
Route::get('filter/inputs', 'API\Admin\InputsAPI@indexWithFilter');
Route::get('filter/subcategories', 'API\Admin\SubCategoryAPI@indexWithFilter');
Route::get('filter/items', 'API\Items@indexWithFilter');
Route::get('filter/requests', 'API\ItemsRequests@indexWithFilter');
Route::get('filter/users', 'API\Users@indexWithFilter');

/* Get All Inputs Id's Realted to a subcat && get ALL Inputs Values For this Item */
Route::post('subcatalldata', 'API\Admin\SubCategoryAPI@all_items_subcats_data');

/* Get All subCAts  Realted to a cat */
Route::get('catsubcats/{id}', 'API\Admin\CategoryApi@all_subCatsData');

/* Relations ships  Routes */
Route::post('subcategories/inputs', 'API\Admin\SubCategoryAPI@subcats_inputs');

/* Matching Items */
Route::get('matching', 'API\Items@matching');
//Route::get('matching2', 'API\Admin\AdminItems@matching');

/* Algolia search Items */
Route::get('testpola/{q}', 'API\ItemsFilters@myFilter');

/* --------------------------------------------------------------------------------- */
/* ---------------------------------Admin Routes--------------------------------------- */

Route::get('allusers', 'API\Users@getAllUsers');
Route::get('lastusers', 'API\Users@getLastUsers');
Route::get('allitems', 'API\Admin\AdminItems@allItems');
Route::get('lastitems', 'API\Admin\AdminItems@lastItems');
Route::get('lastpersons', 'API\Admin\AdminItems@lastPersons');
Route::get('summerydata', 'API\Admin\ChartsController@summeryData');
Route::get('allrequests', 'API\Admin\AdminRequests@allrequests');
Route::get('allresults', 'API\Admin\ChartsController@allResults');
Route::get('allsummery', 'API\Admin\ChartsController@allResultsSummery');
Route::post('activateuser', 'API\Passport@signupActivate2');
Route::get('getitem/{id}', 'API\Admin\AdminItems@getitem');
Route::get('getreq/{id}', 'API\Admin\AdminRequests@getrequest');
Route::get('getuser/{id}', 'API\Users@adminGetUserData');















/* --------------------------------------------------------------------------------- */


// Route::get('pola', 'API\Users@getAllUsers');


// Route::get('filter/items','API\Items@indexWithFilter');

// Route::get('email', 'API\EmailController@sendEmail');


// Route::get('/debug-sentry', function () {
//     throw new Exception('My first Sentry error!');
// });


//Route::resource('requests','API\ItemsRequests');


//Route::get('pola',function (){
//    $row=App\Models\Input::find(47);
//
//    if(!$row)
//        dd('No Row');
//    foreach($row->optionsValidators as $valid){
//        $xx=inputValidator::find($valid->id);
//        echo '<br>'.$valid->id.' : '.$xx->delete().'<br>';
//    }
////    foreach($row->optionsInputs() as $option){
////        inputOption::destroy($option->id);
////    }
//});


//Route::post('items/images','API\ImagesController@uploadImages');
