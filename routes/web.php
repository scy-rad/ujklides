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

use Illuminate\Http\Request;
use app\Uploades;

Route::get('/', function () {
    return view('welcome');
});


Route::group([
    'middleware' => 'roles',                        // uiruchom middlewara roles - nazwa zdefiniowana w Kernel.php
    'roles' => ['Admin', 'moderator']                              // i dostęp tylko dla tych użytkownikó (tablica)
], function() {
    Route::resource('pages', 'PagesController');
//    Route::resource('fittings', 'FittingController');


Route::resource('/ajaxData','AjaxDataController');



/////////////////////////////////////////////////////////
//   R O O M S
/////////////////////////////////////////////////////////

//Route::resource('/ajaxData','AjaxDataController');

Route::get('rooms', [
    'uses' => 'RoomController@index',
    'as' => 'rooms.index'
]);

Route::get('room/{room}', [
    'uses' => 'RoomController@show',
    'as' => 'rooms.show'
]);

Route::get('room/storages/{room}', [
    'uses' => 'RoomController@showstorages',
    'as' => 'rooms.showstorages'
]);
Route::get('room/inventory/{room}', [
    'uses' => 'RoomController@showinventory',
    'as' => 'rooms.showinventory'
]);
Route::post('ajax-inventory', 'RoomController@store_inventory');



/////////////////////////////////////////////////////////
//   I T E M
/////////////////////////////////////////////////////////

Route::get('itemtypes/{item_type}', [
    'uses' => 'ItemTypeController@index',
    'as' => 'itemtypes.index'
]);

Route::get('itemtypes/groups/{item_type}', [
    'uses' => 'ItemTypeController@showgroup',
    'as' => 'itemtypes.showgroups'
]);

Route::get('itemtypes/items/{item_type}', [
    'uses' => 'ItemTypeController@showitem',
    'as' => 'itemtypes.showitems'
]);

Route::get('itemgroups/items/{item_group}', [
    'uses' => 'ItemGroupController@showitem',
    'as' => 'itemgroups.showitems'
]);


Route::get('items/{item_group}', [
    'uses' => 'ItemController@index',
    'as' => 'items.index'
]);

Route::get('item/show/{item}', [
    'uses' => 'ItemController@show',
    'as' => 'items.show'
]);

Route::get('item/doc/{item}/{doc}', [
    'uses' => 'ItemController@doc',
    'as' => 'items.doc'
]);

Route::get('item/gal/{item}/{gal}', [
    'uses' => 'ItemController@gal',
    'as' => 'items.gal'
]);

Route::get('item/fil/{item}/{id_what}', [
    'uses' => 'ItemController@fil',
    'as' => 'items.fil'
]);

Route::put('item/{item}', [
    'uses' => 'ItemController@update',
    'as' => 'item.update'
]);

/*
Route::get('item/fault/{item}/{id_what}', [
    'uses' => 'ItemController@fault',
    'as' => 'items.fault'
]);
*/











Route::get('fault/create/{item_id}', [
    'uses' => 'FaultController@create',
    'as' => 'fault.create'
]);

Route::post('fault/store', [
    'uses' => 'FaultController@store',
    'as' => 'fault.store'
]);

Route::get('fault/{fault_id}', [
    'uses' => 'FaultController@show',
    'as' => 'fault.show'
]);
Route::get('faults/{item_id}', [
    'uses' => 'FaultController@showall',
    'as' => 'fault.showall'
]);

Route::get('fault/edit/{fault_id}', [
    'uses' => 'FaultController@edit',
    'as' => 'fault.edit'
]);

Route::get('fault/close/{fault_id}', [
    'uses' => 'FaultController@close',
    'as' => 'fault.close'
]);


Route::put('fault', [
    'uses' => 'FaultController@update',
    'as' => 'fault.update'
]);

Route::delete('fault/{fault_id}', [
    'uses' => 'FaultController@destroy',
    'as' => 'fault.delete'
]);




Route::get('review/{review_id}', [
    'uses' => 'ReviewController@show',
    'as' => 'review.show'
]);
Route::get('review/edit/{review_id}', [
    'uses' => 'ReviewController@edit',
    'as' => 'review.edit'
]);
Route::put('review/update', [
    'uses' => 'ReviewController@update',
    'as' => 'review.update'
]);
Route::get('review/close/{review_id}', [
    'uses' => 'ReviewController@tryclose',
    'as' => 'review.tryclose'
]);
Route::put('review/close', [
    'uses' => 'ReviewController@close',
    'as' => 'review.close'
]);


Route::resource('/ManItem','ManItemController');

Route::get('mansimmeds', [
    'uses' => 'ManSimmedController@index',
    'as' => 'mansimmeds.index'
]);

Route::get('mansimmeds/groups', [
    'uses' => 'ManSimmedController@groups',
    'as' => 'mansimmeds.groups'
]);

Route::get('mansimmeds/changes', [
    'uses' => 'ManSimmedController@changes',
    'as' => 'mansimmeds.changes'
]);

Route::get('mansimmeds/showdeleted', [
    'uses' => 'ManSimmedController@showdeleted',
    'as' => 'mansimmeds.showdeleted'
]);

Route::post('mansimmeds/import_file', [
    'uses' => 'ManSimmedController@import_file',
    'as' => 'mansimmeds.import_file'
]);

Route::post('mansimmeds/import_check', [
    'uses' => 'ManSimmedController@import_check',
    'as' => 'mansimmeds.import_check'
]);

Route::post('mansimmeds/import_reread', [
    'uses' => 'ManSimmedController@import_reread',
    'as' => 'mansimmeds.import_reread'
]);

Route::post('mansimmeds/import_complement', [
    'uses' => 'ManSimmedController@import_complement',
    'as' => 'mansimmeds.import_complement'
]);

Route::post('mansimmeds/import_append', [
    'uses' => 'ManSimmedController@import_append',
    'as' => 'mansimmeds.import_append'
]);

Route::post('mansimmeds/impanalyze', [
    'uses' => 'ManSimmedController@impanalyze',
    'as' => 'mansimmeds.impanalyze'
]);

Route::post('mansimmeds/import_noremove', [
    'uses' => 'ManSimmedController@import_noremove',
    'as' => 'mansimmeds.import_noremove'
]);

Route::post('mansimmeds/mark_import', [
    'uses' => 'ManSimmedController@markimport',
    'as' => 'mansimmeds.markimport'
]);


Route::get('mansimmeds/import', [
    'uses' => 'ManSimmedController@doimport',
    'as' => 'mansimmeds.doimport'
]);

Route::post('mansimmeds/impclear', [
    'uses' => 'ManSimmedController@clearimport',
    'as' => 'mansimmeds.impclear'
]);

Route::post('mansimmeds/clear_import_tmp', [
    'uses' => 'ManSimmedController@clear_import_tmp',
    'as' => 'mansimmeds.clear_import_tmp'
]);

Route::get('mansimmeds/csv', [
    'uses' => 'ManSimmedController@generate_csv',
    'as' => 'mansimmeds.csv'
]);

Route::get('mansimmeds/send-mail', [
    'uses' => 'ManSimmedController@sendMail',
    'as' => 'mansimmeds.sendMail'
]);


/////////////////////////////////////////////////////////
//   W O R K   T I M E
/////////////////////////////////////////////////////////

Route::get('worktime/month', [
    'uses' => 'WorkTimeController@month_data',
    'as' => 'worktime.month'
]);

Route::get('worktime/day/{day}/{user}', [
    'uses' => 'WorkTimeController@day_data',
    'as' => 'worktime.day_data'
]);

Route::get('worktime/statistics', [
    'uses' => 'WorkTimeController@statistics',
    'as' => 'worktime.statistics'
]);

Route::get('worktime/statpertech', [
    'uses' => 'WorkTimeController@statpertech',
    'as' => 'worktime.statpertech'
]);


Route::post('worktime/save', [
    'uses' => 'WorkTimeController@save_data',
    'as' => 'worktime.save_data'
]);


/////////////////////////////////////////////////////////
//   L I B R A R I E S
/////////////////////////////////////////////////////////

Route::get('libraries/subjects', [
    'uses' => 'LibrariesController@list_subjects',
    'as' => 'libraries.subjects'
]);
Route::post('libraries/save_subject', [
    'uses' => 'LibrariesController@save_subject',
    'as' => 'libraries.save_subject'
]);


Route::get('libraries/workmonths', [
    'uses' => 'LibrariesController@list_workmonths',
    'as' => 'libraries.workmonths'
]);
Route::post('libraries/save_workmonth', [
    'uses' => 'LibrariesController@save_workmonth',
    'as' => 'libraries.save_workmonth'
]);

Route::get('libraries/params', [
    'uses' => 'LibrariesController@params_show',
    'as' => 'libraries.params_show'
]);
Route::post('libraries/params_save', [
    'uses' => 'LibrariesController@params_save',
    'as' => 'libraries.params_save'
]);

Route::get('libraries/rooms', [
    'uses' => 'LibrariesController@list_rooms',
    'as' => 'libraries.rooms'
]);
Route::post('libraries/save_room', [
    'uses' => 'LibrariesController@save_room',
    'as' => 'libraries.save_room'
]);

Route::get('libraries/student_groups', [
    'uses' => 'LibrariesController@list_student_groups',
    'as' => 'libraries.student_groups'
]);
Route::post('libraries/save_student_group', [
    'uses' => 'LibrariesController@save_student_group',
    'as' => 'libraries.save_student_group'
]);

Route::get('libraries/user_titles', [
    'uses' => 'LibrariesController@list_user_titles',
    'as' => 'libraries.user_titles'
]);
Route::post('libraries/save_user_title', [
    'uses' => 'LibrariesController@save_user_title',
    'as' => 'libraries.save_user_title'
]);


/////////////////////////////////////////////////////////
//   U S E R
/////////////////////////////////////////////////////////




Route::get('userprofile', 'UserController@profile');

Route::get('users/{type}', 'UserController@users');


Route::post('/userprofile', 'UserController@update_avatar');

Route::get('user/{user_id}', [
    'uses' => 'UserController@userprofile',
    'as' => 'user.profile'
]);
Route::post('user/addrole', [
    'uses' => 'UserController@add_role',
    'as' => 'user.add_role'
]);
Route::post('user/removerole', [
    'uses' => 'UserController@remove_role',
    'as' => 'user.remove_role'
]);
Route::post('user/changeemail', [
    'uses' => 'UserController@change_email',
    'as' => 'user.change_email'
]);
Route::post('user/changephone', [
    'uses' => 'UserController@change_phone',
    'as' => 'user.change_phone'
]);
Route::post('user/changepassword', [
    'uses' => 'UserController@change_password',
    'as' => 'user.change_password'
]);
Route::post('user/changestatus', [
    'uses' => 'UserController@change_status',
    'as' => 'user.change_status'
]);

Route::put('user/changeabout', [
    'uses' => 'UserController@change_about',
    'as' => 'user.change_about'
]);
Route::put('user/updatepersonal', [
    'uses' => 'UserController@update_personal',
    'as' => 'user.update_personal'
]);


//Route::get('/changePassword','HomeController@showChangePasswordForm');

Route::get('/changePasswordForm', [
    'uses' => 'HomeController@showChangePasswordForm',
    'as' => 'changePasswordForm'
]);
Route::post('/changePassword','HomeController@changePassword')->name('changePassword');

Route::post('ajax/user-notify', 'UserController@ajax_update_notify');

























Route::get('gallery/{id}', [
    'uses' => 'GalleryController@show',
    'as' => 'galleries.show'
]);

Route::get('pliki/{plik_type}', [
    'uses' => 'PlikController@index',
    'as' => 'pliks.index'
]);

Route::get('plik/{plik_id}', [
    'uses' => 'PlikController@show',
    'as' => 'pliks.show'
]);


/////////////////////////////////////////////////////////
//   S C E N A R I O
/////////////////////////////////////////////////////////


Route::get('scenarios', [
    'uses' => 'ScenarioController@index',
    'as' => 'scenarios.index'
]);

Route::get('scenario/create', [
    'uses' => 'ScenarioController@create',
    'as' => 'scenarios.create'
]);

Route::post('scenario/store', [
    'uses' => 'ScenarioController@store',
    'as' => 'scenarios.store'
]);

Route::get('scenario/{scenario}', [
    'uses' => 'ScenarioController@show',
    'as' => 'scenarios.show'
]);

Route::get('scenario/edit/{scenario}', [
    'uses' => 'ScenarioController@edit',
    'as' => 'scenarios.edit'
]);


Route::put('scenario/{scenario}', [
    'uses' => 'ScenarioController@update',
    'as' => 'scenarios.update'
]);

Route::delete('scenario/{scenario}', [
    'uses' => 'ScenarioController@destroy',
    'as' => 'scenarios.delete'
]);











/////////////////////////////////////////////////////////
//   S I M M E D
/////////////////////////////////////////////////////////


Route::get('simmeds', [
    'uses' => 'SimmedController@index',
    'as' => 'simmeds.index'
]);


Route::post('simmedsplaner', [
    'uses' => 'SimmedController@plane',
    'as' => 'simmeds.plane'
]);

Route::get('simmedsplane', [
    'uses' => 'SimmedController@plane',
    'as' => 'simmeds.plane'
]);

Route::get('scheduler/{sch_date}', [
    'uses' => 'SimmedController@scheduler',
    'as' => 'simmeds.scheduler'
]);

Route::get('timetable', [
    'uses' => 'SimmedController@timetable',
    'as' => 'simmeds.timetable'
]);

Route::post('timetable', [
    'uses' => 'SimmedController@timetable',
    'as' => 'simmeds.timetable'
]);

Route::get('simmed/create', [
    'uses' => 'SimmedController@create',
    'as' => 'simmeds.create'
]);

Route::post('simmed/store', [
    'uses' => 'SimmedController@store',
    'as' => 'simmeds.store'
]);

Route::get('simmed/{simmed}/{filtr}', [
    'uses' => 'SimmedController@show',
    'as' => 'simmeds.show'
]);

Route::get('simmededit/{simmed}', [
    'uses' => 'SimmedController@edit',
    'as' => 'simmeds.edit'
]);
Route::get('simmedcopy/{simmed}', [
    'uses' => 'SimmedController@copy',
    'as' => 'simmeds.copy'
]);

Route::put('simmed', [
    'uses' => 'SimmedController@update',
    'as' => 'simmeds.update'
]);

Route::delete('simmed/{simmed}', [
    'uses' => 'SimmedController@destroy',
    'as' => 'simmeds.delete'
]);

Route::put('simmed_descript', [
    'uses' => 'SimmedController@descript_update',
    'as' => 'simmeds.descript_update'
]);


//Route::post('simmed/AjaxSavePlane', [
//    'uses' => 'SimmedController@ajaxsaveplane',
//    'as' => 'simmeds.ajaxsaveplane'
//]);

/*
Route::post('simmed/ajaxgetplane', 'SimmedController@ajaxgetplane');
//Route::post('simmed/ajaxsaveplane', 'SimmedController@ajaxsaveplane');

Route::post('simmed/ajaxsaveplane', [
    'uses' => 'SimmedController@ajaxsaveplane',
    'as' => 'simmed.ajaxsaveplane'
]);
*/


Route::post('simmed/ajaxsavetechnician', 'SimmedController@ajaxsavetechnician');
Route::post('simmed/ajaxtechnicianchar', 'SimmedController@ajaxtechnicianchar');
//Route::post('mansimmeds/ajaxchangestatus', 'SimmedController@ajaxtechnicianchar');
Route::post('mansimmeds/ajaxchangestatus', 'ManSimmedController@ajaxchangestatus');






/////////////////////////////////////////////////////////
//   D O C S
/////////////////////////////////////////////////////////


Route::get('docs', [
    'uses' => 'DocsController@index',
    'as' => 'docs.index'
]);

Route::get('docs/create', [
    'uses' => 'DocsController@create',
    'as' => 'docs.create'
]);

Route::post('docs/store', [
    'uses' => 'DocsController@store',
    'as' => 'docs.store'
]);

Route::get('docs/edit/{doc}', [
    'uses' => 'DocsController@edit',
    'as' => 'docs.edit'
]);

Route::put('docs/{doc}', [
    'uses' => 'DocsController@update',
    'as' => 'docs.update'
]);

Route::delete('docs/{doc}', [
    'uses' => 'DocsController@destroy',
    'as' => 'docs.delete'
]);








/////////////////////////////////////////////////////////
//   P A G E S
/////////////////////////////////////////////////////////

Route::get('pages', [
    'uses' => 'PagesController@index',
    'as' => 'pages.index'
]);

Route::get('pages/create', [
    'uses' => 'PagesController@create',
    'as' => 'pages.create'
]);

Route::post('pages/store', [
    'uses' => 'PagesController@store',
    'as' => 'pages.store'
]);

Route::get('pages/edit/{page}', [
    'uses' => 'PagesController@edit',
    'as' => 'pages.edit'
]);

Route::put('pages/{page}', [
    'uses' => 'PagesController@update',
    'as' => 'pages.update'
]);

Route::delete('pages/{page}', [
    'uses' => 'PagesController@destroy',
    'as' => 'pages.delete'
]);



/////////////////////////////////////////////////////////
//   O T H E R S
/////////////////////////////////////////////////////////






Route::post('/upload', function(Request $request){

echo $request->get('fileplace');

switch ($request->get('fileplace'))
{
    case 'device':
        //return back()
        //->with('success','przesłano plik....');
        break;
    default:
        return back()
        ->with('danger','Nieprawidłowe wywołanie uploadu.');
}

 //   dd($request->hasFile('photofile'));
  
//echo $request->get('fileplace');
//    dd($request->all());
//    dd(request()->all());

//$zz = 'public/' . $request->get('fileplace');/
//echo $zz;

//$request->photofile->store('public/img/'. $request->get('fileplace'), 'public');
//$request->photofile->storeAs('public/img/'. $request->get('fileplace'), $request->file('photofile')->getClientOriginalName());

//$path = $request->file('photofile')->store('public/img');
$path = $request->file('photofile')->storeAs('img/'. $request->get('fileplace'), $request->file('photofile')->getClientOriginalName());


$url = Storage::url('img/'. $request->get('fileplace') .'/'. $request->file('photofile')->getClientOriginalName());
echo '<hr>';
echo $url;
echo '<img src="'.$url.'">';

$url = public_path().'/img/'. $request->get('fileplace') .'/'. $request->file('photofile')->getClientOriginalName();
echo '<hr>';
echo $url;
echo '<img src="'.$url.'">';

$url = storage_path().'/img/'. $request->get('fileplace') .'/'. $request->file('photofile')->getClientOriginalName();
echo '<hr>';
echo $url;
echo '<img src="'.$url.'">';

echo '<hr>';
echo $request->file('photofile')->getClientOriginalName();
dd($request->file('photofile'));
echo '<hr>';
    dd($request->all());

//    return back()
//    ->with('success','Superancko przesłano plik....');


/*    if ($request->file('photofile')->IsValid){
       echo 'valid'; 
    }
    else
        echo 'invalid';
*/
});


});





Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/contact', [
    'uses' => 'ContactUsFormController@createForm'
]);