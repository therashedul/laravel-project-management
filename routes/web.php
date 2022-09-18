<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;

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

Route::get('/', function () {
    return view('welcome');
});


// Auth::routes();
Auth::routes(['register' => false]);  // Can`t registrition with out login

Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom'); 
Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom'); 
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');

Route::group(['prefix' => 'admin', 'as' => 'admin.','middleware' => ['auth','admin','priventBackHistory']], function() {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
    // Users
    Route::get('users', [App\Http\Controllers\AdminController::class, 'user'])->name('users');
    Route::get('/users.create',  [App\Http\Controllers\AdminController::class, 'usercreate'])->name('users.create');
    Route::post('users.store', [App\Http\Controllers\AdminController::class, 'userstore'])->name('users.store');
    Route::get('users.show.{id}', [App\Http\Controllers\AdminController::class, 'usershow'])->name('users.show');
    Route::get('users.edit.{id}', [App\Http\Controllers\AdminController::class, 'useredit'])->name('users.edit');
    Route::get('users.publish.{id}', [App\Http\Controllers\AdminController::class, 'userpublish'])->name('users.publish');
    Route::get('users.unpublish.{id}', [App\Http\Controllers\AdminController::class, 'userunpublish'])->name('users.unpublish');
    Route::patch('users.update.{id}', [App\Http\Controllers\AdminController::class, 'userupdate'])->name('users.update');
    Route::delete('/users.destroy.{id}', [App\Http\Controllers\AdminController::class, 'userdestroy'])->name('users.destroy');

    Route::post('/users.upload', [App\Http\Controllers\AdminController::class, 'usersupload'])->name('users.upload');    
    Route::get('/users.fetch', [App\Http\Controllers\AdminController::class, 'usersfetch'])->name('users.fetch');
    Route::get('/users.delete', [App\Http\Controllers\AdminController::class, 'usersuploaddelete'])->name('users.delete');
    Route::post('/users.search', [App\Http\Controllers\AdminController::class, 'userssearch'])->name('users.search'); 
    // Admin role
    Route::get('/roles', [App\Http\Controllers\AdminController::class, 'role'])->name('roles');
    Route::get('/roles.create', [App\Http\Controllers\AdminController::class, 'rolecreate'])->name('roles.create');
    Route::post('/roles.store', [App\Http\Controllers\AdminController::class, 'rolestore'])->name('roles.store');
    Route::get('/roles.show.{id}', [App\Http\Controllers\AdminController::class, 'roleshow'])->name('roles.show');
    Route::get('/roles.edit.{id}', [App\Http\Controllers\AdminController::class, 'roleedit'])->name('roles.edit');
    Route::patch('/roles.update.{id}', [App\Http\Controllers\AdminController::class, 'roleupdate'])->name('roles.update');
    Route::delete('/roles.destroy.{id}', [App\Http\Controllers\AdminController::class, 'roledelete'])->name('roles.destroy');
    
    // Media
    Route::get('/media', [App\Http\Controllers\AdminController::class, 'media'])->name('media');
    Route::post('/media.upload', [App\Http\Controllers\AdminController::class, 'mediaupload'])->name('media.upload');
    Route::get('/media.fetch', [App\Http\Controllers\AdminController::class, 'mediafetch'])->name('media.fetch');
    Route::get('/media.delete', [App\Http\Controllers\AdminController::class, 'mediauploaddelete'])->name('media.delete');
    Route::post('/media.search', [App\Http\Controllers\AdminController::class, 'mediasearch'])->name('media.search'); 
      
    // Project
    Route::get('/projects', [App\Http\Controllers\AdminController::class, 'projects'])->name('projects');
    Route::get('/projects.create', [App\Http\Controllers\AdminController::class, 'projectscreate'])->name('projects.create');
    Route::post('/projects.store', [App\Http\Controllers\AdminController::class, 'projectsstore'])->name('projects.store');
    Route::post('/projects.upload', [App\Http\Controllers\AdminController::class, 'projectsupload'])->name('projects.upload');
    Route::get('/projects.fetch', [App\Http\Controllers\AdminController::class, 'projectsfetch'])->name('projects.fetch');
    Route::get('/projects.delete', [App\Http\Controllers\AdminController::class, 'uploaddelete'])->name('projects.delete');
    // Route::get('/permissions.show.{id}', [App\Http\Controllers\AdminController::class, 'permissionshow'])->name('permissions.show');
    Route::get('/projects.edit.{id}', [App\Http\Controllers\AdminController::class, 'projectsedit'])->name('projects.edit');
    Route::patch('/projects.update.{id}', [App\Http\Controllers\AdminController::class, 'projectsupdate'])->name('projects.update');
    Route::delete('/projects.destroy.{id}', [App\Http\Controllers\AdminController::class, 'projectsdelete'])->name('projects.destroy');
    Route::get('/projects.imagedestroy.{id}', [App\Http\Controllers\AdminController::class, 'projectsimagedelete'])->name('projects.imagedestroy');
    Route::post('/projects.search', [App\Http\Controllers\AdminController::class, 'projectssearch'])->name('projects.search');
    Route::post('/projects.imagesearch', [App\Http\Controllers\AdminController::class, 'projectsimagesearch'])->name('projects.imageSearch');
    
    // Documents
    Route::get('/documents', [App\Http\Controllers\AdminController::class, 'documents'])->name('documents');
    Route::get('/documents.create', [App\Http\Controllers\AdminController::class, 'documentscreate'])->name('documents.create');
    Route::post('/documents.store', [App\Http\Controllers\AdminController::class, 'documentsstore'])->name('documents.store');
    Route::post('/documents.upload', [App\Http\Controllers\AdminController::class, 'documentsupload'])->name('documents.upload');
    Route::get('/documents.fetch', [App\Http\Controllers\AdminController::class, 'documentsfetch'])->name('documents.fetch');
    Route::get('/documents.delete', [App\Http\Controllers\AdminController::class, 'documentsuploaddelete'])->name('documents.delete');
    Route::get('/documents.edit.{id}', [App\Http\Controllers\AdminController::class, 'documentsedit'])->name('documents.edit');
    Route::patch('/documents.update.{id}', [App\Http\Controllers\AdminController::class, 'documentsupdate'])->name('documents.update');
    Route::delete('/documents.destroy.{id}', [App\Http\Controllers\AdminController::class, 'documentsdelete'])->name('documents.destroy');
    Route::get('/documents.filedestroy.{id}', [App\Http\Controllers\AdminController::class, 'documentsfiledelete'])->name('documents.filedestroy');
    Route::post('/documents.search', [App\Http\Controllers\AdminController::class, 'documentssearch'])->name('documents.search');
    Route::post('/documents.filesearch', [App\Http\Controllers\AdminController::class, 'documentfilessearch'])->name('documents.filesearch');
    Route::delete('/documents.destroy.{id}', [App\Http\Controllers\AdminController::class, 'documentsdelete'])->name('documents.destroy'); // Documents
    
    //Task
    Route::get('/tasks', [App\Http\Controllers\AdminController::class, 'tasks'])->name('tasks');
    Route::get('/tasks.create', [App\Http\Controllers\AdminController::class, 'taskscreate'])->name('tasks.create');
    Route::post('/tasks.store', [App\Http\Controllers\AdminController::class, 'tasksstore'])->name('tasks.store');
    Route::get('/tasks.edit.{id}', [App\Http\Controllers\AdminController::class, 'tasksedit'])->name('tasks.edit');
    Route::get('/tasks.show.{id}', [App\Http\Controllers\AdminController::class, 'tasksshow'])->name('tasks.show');
    Route::patch('/tasks.update.{id}', [App\Http\Controllers\AdminController::class, 'tasksupdate'])->name('tasks.update');
    Route::get('/tasks.searchdestroy.{id}', [App\Http\Controllers\AdminController::class, 'searchdestroy'])->name('tasks.searchdestroy');
    Route::post('/tasks.search', [App\Http\Controllers\AdminController::class, 'taskssearch'])->name('tasks.search');
    Route::delete('/tasks.destroy.{id}', [App\Http\Controllers\AdminController::class, 'tasksdelete'])->name('tasks.destroy');    
    Route::get('/tasts.profile.{id}', [App\Http\Controllers\AdminController::class, 'tasksprofile'])->name('tasts.profile');
    
    // Admin permission
    Route::get('/permissions', [App\Http\Controllers\AdminController::class, 'permission'])->name('permissions');
    Route::get('/permissions.create', [App\Http\Controllers\AdminController::class, 'permissioncreate'])->name('permissions.create');
    Route::post('/permissions.store', [App\Http\Controllers\AdminController::class, 'permissionstore'])->name('permissions.store');
    Route::get('/permissions.show.{id}', [App\Http\Controllers\AdminController::class, 'permissionshow'])->name('permissions.show');
    Route::get('/permissions.edit.{id}', [App\Http\Controllers\AdminController::class, 'permissionedit'])->name('permissions.edit');
    Route::patch('/permissions.update.{id}', [App\Http\Controllers\AdminController::class, 'permissionupdate'])->name('permissions.update');
    Route::delete('/permissions.destroy.{id}', [App\Http\Controllers\AdminController::class, 'permissiondelete'])->name('permissions.destroy');
    Route::post('/permissions.search', [App\Http\Controllers\AdminController::class, 'permissionsearch'])->name('permissons.search');
    Route::get('/permissions.permissiondelete.{id}', [App\Http\Controllers\AdminController::class, 'deletepermission'])->name('permissions.permissiondelete');  

});

Route::group(['prefix' => 'executive', 'as'=>'executive.', 'middleware' => ['auth','executive','priventBackHistory']], function() {
    Route::get('/', [App\Http\Controllers\ExecutiveController::class, 'index'])->name('executive');
    // Users
    Route::get('users', [App\Http\Controllers\ExecutiveController::class, 'user'])->name('users');
    Route::get('/users.create',  [App\Http\Controllers\ExecutiveController::class, 'usercreate'])->name('users.create');
    Route::post('users.store', [App\Http\Controllers\ExecutiveController::class, 'userstore'])->name('users.store');
    Route::get('users.show.{id}', [App\Http\Controllers\ExecutiveController::class, 'usershow'])->name('users.show');
    Route::get('users.edit.{id}', [App\Http\Controllers\ExecutiveController::class, 'useredit'])->name('users.edit');
    Route::get('users.publish.{id}', [App\Http\Controllers\ExecutiveController::class, 'userpublish'])->name('users.publish');
    Route::get('users.unpublish.{id}', [App\Http\Controllers\ExecutiveController::class, 'userunpublish'])->name('users.unpublish');
    Route::patch('users.update.{id}', [App\Http\Controllers\ExecutiveController::class, 'userupdate'])->name('users.update');
    Route::delete('/users.destroy.{id}', [App\Http\Controllers\ExecutiveController::class, 'userdestroy'])->name('users.destroy');
    // Admin role
    Route::get('/roles', [App\Http\Controllers\ExecutiveController::class, 'role'])->name('roles');
    Route::get('/roles.create', [App\Http\Controllers\ExecutiveController::class, 'rolecreate'])->name('roles.create');
    Route::post('/roles.store', [App\Http\Controllers\ExecutiveController::class, 'rolestore'])->name('roles.store');
    Route::get('/roles.show.{id}', [App\Http\Controllers\ExecutiveController::class, 'roleshow'])->name('roles.show');
    Route::get('/roles.edit.{id}', [App\Http\Controllers\ExecutiveController::class, 'roleedit'])->name('roles.edit');
    Route::patch('/roles.update.{id}', [App\Http\Controllers\ExecutiveController::class, 'roleupdate'])->name('roles.update');
    Route::delete('/roles.destroy.{id}', [App\Http\Controllers\ExecutiveController::class, 'roledelete'])->name('roles.destroy');
   
   // Media
    Route::get('/media', [App\Http\Controllers\ExecutiveController::class, 'media'])->name('media');
    Route::post('/media.upload', [App\Http\Controllers\ExecutiveController::class, 'mediaupload'])->name('media.upload');
    Route::get('/media.fetch', [App\Http\Controllers\ExecutiveController::class, 'mediafetch'])->name('media.fetch');
    Route::get('/media.delete', [App\Http\Controllers\ExecutiveController::class, 'mediauploaddelete'])->name('media.delete');
    Route::post('/media.search', [App\Http\Controllers\ExecutiveController::class, 'mediasearch'])->name('media.search'); 
      
    // Project
    Route::get('/projects', [App\Http\Controllers\ExecutiveController::class, 'projects'])->name('projects');
    Route::get('/projects.create', [App\Http\Controllers\ExecutiveController::class, 'projectscreate'])->name('projects.create');
    Route::post('/projects.store', [App\Http\Controllers\ExecutiveController::class, 'projectsstore'])->name('projects.store');
    Route::post('/projects.upload', [App\Http\Controllers\ExecutiveController::class, 'projectsupload'])->name('projects.upload');
    Route::get('/projects.fetch', [App\Http\Controllers\ExecutiveController::class, 'projectsfetch'])->name('projects.fetch');
    Route::get('/projects.delete', [App\Http\Controllers\ExecutiveController::class, 'uploaddelete'])->name('projects.delete');
    // Route::get('/permissions.show.{id}', [App\Http\Controllers\ExecutiveController::class, 'permissionshow'])->name('permissions.show');
    Route::get('/projects.edit.{id}', [App\Http\Controllers\ExecutiveController::class, 'projectsedit'])->name('projects.edit');
    Route::patch('/projects.update.{id}', [App\Http\Controllers\ExecutiveController::class, 'projectsupdate'])->name('projects.update');
    Route::delete('/projects.destroy.{id}', [App\Http\Controllers\ExecutiveController::class, 'projectsdelete'])->name('projects.destroy');
    Route::get('/projects.imagedestroy.{id}', [App\Http\Controllers\ExecutiveController::class, 'projectsimagedelete'])->name('projects.imagedestroy');
    Route::post('/projects.search', [App\Http\Controllers\ExecutiveController::class, 'projectssearch'])->name('projects.search');
    Route::post('/projects.imagesearch', [App\Http\Controllers\ExecutiveController::class, 'projectsimagesearch'])->name('projects.imageSearch');
    
    // Documents
    Route::get('/documents', [App\Http\Controllers\ExecutiveController::class, 'documents'])->name('documents');
    Route::get('/documents.create', [App\Http\Controllers\ExecutiveController::class, 'documentscreate'])->name('documents.create');
    Route::post('/documents.store', [App\Http\Controllers\ExecutiveController::class, 'documentsstore'])->name('documents.store');
    Route::post('/documents.upload', [App\Http\Controllers\ExecutiveController::class, 'documentsupload'])->name('documents.upload');
    Route::get('/documents.fetch', [App\Http\Controllers\ExecutiveController::class, 'documentsfetch'])->name('documents.fetch');
    Route::get('/documents.delete', [App\Http\Controllers\ExecutiveController::class, 'documentsuploaddelete'])->name('documents.delete');
    // Route::get('/permissions.show.{id}', [App\Http\Controllers\ExecutiveController::class, 'permissionshow'])->name('permissions.show');
    Route::get('/documents.edit.{id}', [App\Http\Controllers\ExecutiveController::class, 'documentsedit'])->name('documents.edit');
    Route::patch('/documents.update.{id}', [App\Http\Controllers\ExecutiveController::class, 'documentsupdate'])->name('documents.update');
    Route::delete('/documents.destroy.{id}', [App\Http\Controllers\ExecutiveController::class, 'documentsdelete'])->name('documents.destroy');
    Route::get('/documents.filedestroy.{id}', [App\Http\Controllers\ExecutiveController::class, 'documentsfiledelete'])->name('documents.filedestroy');
    Route::post('/documents.search', [App\Http\Controllers\ExecutiveController::class, 'documentssearch'])->name('documents.search');
    Route::post('/documents.filesearch', [App\Http\Controllers\ExecutiveController::class, 'documentfilessearch'])->name('documents.filesearch');
    Route::delete('/documents.destroy.{id}', [App\Http\Controllers\ExecutiveController::class, 'documentsdelete'])->name('documents.destroy');
    
    // Admin permission
    Route::get('/permissions', [App\Http\Controllers\ExecutiveController::class, 'permission'])->name('permissions');
    Route::get('/permissions.create', [App\Http\Controllers\ExecutiveController::class, 'permissioncreate'])->name('permissions.create');
    Route::post('/permissions.store', [App\Http\Controllers\ExecutiveController::class, 'permissionstore'])->name('permissions.store');
    Route::get('/permissions.show.{id}', [App\Http\Controllers\ExecutiveController::class, 'permissionshow'])->name('permissions.show');
    Route::get('/permissions.edit.{id}', [App\Http\Controllers\ExecutiveController::class, 'permissionedit'])->name('permissions.edit');
    Route::patch('/permissions.update.{id}', [App\Http\Controllers\ExecutiveController::class, 'permissionupdate'])->name('permissions.update');
    Route::delete('/permissions.destroy.{id}', [App\Http\Controllers\ExecutiveController::class, 'permissiondelete'])->name('permissions.destroy');
    Route::post('/permissions.search', [App\Http\Controllers\ExecutiveController::class, 'permissionsearch'])->name('permissons.search');
    Route::get('/permissions.permissiondelete.{id}', [App\Http\Controllers\ExecutiveController::class, 'deletepermission'])->name('permissions.permissiondelete');

});
Route::group(['prefix' => 'developer','middleware' => ['auth','developer','priventBackHistory']], function() {
    Route::get('/', [App\Http\Controllers\DeveloperController::class, 'index'])->name('developer');
   
    Route::get('users', [App\Http\Controllers\DeveloperController::class, 'user'])->name('developer.users');
    Route::get('/users.create',  [App\Http\Controllers\DeveloperController::class, 'usercreate'])->name('developer.users.create');
    Route::post('users.store', [App\Http\Controllers\DeveloperController::class, 'userstore'])->name('developer.users.store');
    Route::get('users.show.{id}', [App\Http\Controllers\DeveloperController::class, 'usershow'])->name('developer.users.show');
    Route::get('users.edit.{id}', [App\Http\Controllers\DeveloperController::class, 'useredit'])->name('developer.users.edit');
    Route::get('users.publish.{id}', [App\Http\Controllers\DeveloperController::class, 'userpublish'])->name('developer.users.publish');
    Route::get('users.unpublish.{id}', [App\Http\Controllers\DeveloperController::class, 'userunpublish'])->name('developer.users.unpublish');
    Route::patch('users.update.{id}', [App\Http\Controllers\DeveloperController::class, 'userupdate'])->name('developer.users.update');
    Route::delete('/users.destroy.{id}', [App\Http\Controllers\DeveloperController::class, 'userdestroy'])->name('developer.users.destroy');
    
    // developer role
    Route::get('/roles', [App\Http\Controllers\DeveloperController::class, 'role'])->name('developer.roles');
    Route::get('/roles.create', [App\Http\Controllers\DeveloperController::class, 'rolecreate'])->name('developer.roles.create');
    Route::post('/roles.store', [App\Http\Controllers\DeveloperController::class, 'rolestore'])->name('developer.roles.store');
    Route::get('/roles.show.{id}', [App\Http\Controllers\DeveloperController::class, 'roleshow'])->name('developer.roles.show');
    Route::get('/roles.edit.{id}', [App\Http\Controllers\DeveloperController::class, 'roleedit'])->name('developer.roles.edit');
    Route::patch('/roles.update.{id}', [App\Http\Controllers\DeveloperController::class, 'roleupdate'])->name('developer.roles.update');
    Route::delete('/roles.destroy.{id}', [App\Http\Controllers\DeveloperController::class, 'roledelete'])->name('developer.roles.destroy');
    // developer permission
    Route::get('/permissions', [App\Http\Controllers\DeveloperController::class, 'permission'])->name('developer.permissions');
    Route::get('/permissions.create', [App\Http\Controllers\DeveloperController::class, 'permissioncreate'])->name('developer.permissions.create');
    Route::post('/permissions.store', [App\Http\Controllers\DeveloperController::class, 'permissionstore'])->name('developer.permissions.store');
    Route::get('/permissions.show.{id}', [App\Http\Controllers\DeveloperController::class, 'permissionshow'])->name('developer.permissions.show');
    Route::get('/permissions.edit.{id}', [App\Http\Controllers\DeveloperController::class, 'permissionedit'])->name('developer.permissions.edit');
    Route::patch('/permissions.update.{id}', [App\Http\Controllers\DeveloperController::class, 'permissionupdate'])->name('developer.permissions.update');
    Route::delete('/permissions.destroy.{id}', [App\Http\Controllers\DeveloperController::class, 'permissiondelete'])->name('developer.permissions.destroy');

    // Media
    Route::get('/media', [App\Http\Controllers\DeveloperController::class, 'media'])->name('developer.media');
    Route::post('/media.upload', [App\Http\Controllers\DeveloperController::class, 'mediaupload'])->name('developer.media.upload');    
    Route::get('/media.fetch', [App\Http\Controllers\DeveloperController::class, 'mediafetch'])->name('developer.media.fetch');
    Route::get('/media.delete', [App\Http\Controllers\DeveloperController::class, 'mediauploaddelete'])->name('developer.media.delete');
    Route::post('/media.search', [App\Http\Controllers\DeveloperController::class, 'mediasearch'])->name('developer.media.search'); 
       
    // Project
    Route::get('/projects', [App\Http\Controllers\DeveloperController::class, 'projects'])->name('developer.projects');
    Route::get('/projects.create', [App\Http\Controllers\DeveloperController::class, 'projectscreate'])->name('developer.projects.create');
    Route::post('/projects.store', [App\Http\Controllers\DeveloperController::class, 'projectsstore'])->name('developer.projects.store');
    Route::post('/projects.upload', [App\Http\Controllers\DeveloperController::class, 'projectsupload'])->name('developer.projects.upload');
   
    Route::get('/projects.fetch', [App\Http\Controllers\DeveloperController::class, 'projectsfetch'])->name('developer.projects.fetch');
    Route::get('/projects.delete', [App\Http\Controllers\DeveloperController::class, 'uploaddelete'])->name('developer.projects.delete');
    // Route::get('/permissions.show.{id}', [App\Http\Controllers\DeveloperController::class, 'permissionshow'])->name('developer.permissions.show');
    Route::get('/projects.edit.{id}', [App\Http\Controllers\DeveloperController::class, 'projectsedit'])->name('developer.projects.edit');
    Route::patch('/projects.update.{id}', [App\Http\Controllers\DeveloperController::class, 'projectsupdate'])->name('developer.projects.update');
    Route::delete('/projects.destroy.{id}', [App\Http\Controllers\DeveloperController::class, 'projectsdelete'])->name('developer.projects.destroy');
    Route::post('/projects.search', [App\Http\Controllers\DeveloperController::class, 'projectssearch'])->name('developer.projects.search');

     // Documents
    Route::get('/documents', [App\Http\Controllers\DeveloperController::class, 'documents'])->name('developer.documents');
    Route::get('/documents.create', [App\Http\Controllers\DeveloperController::class, 'documentscreate'])->name('developer.documents.create');
    Route::post('/documents.store', [App\Http\Controllers\DeveloperController::class, 'documentsstore'])->name('developer.documents.store');
    Route::post('/documents.upload', [App\Http\Controllers\DeveloperController::class, 'documentsupload'])->name('developer.documents.upload');
  
    Route::get('/documents.fetch', [App\Http\Controllers\DeveloperController::class, 'documentsfetch'])->name('developer.documents.fetch');
    Route::get('/documents.delete', [App\Http\Controllers\DeveloperController::class, 'documentsuploaddelete'])->name('developer.documents.delete');
    // Route::get('/permissions.show.{id}', [App\Http\Controllers\DeveloperController::class, 'permissionshow'])->name('developer.permissions.show');
    Route::get('/documents.edit.{id}', [App\Http\Controllers\DeveloperController::class, 'documentsedit'])->name('developer.documents.edit');
    Route::patch('/documents.update.{id}', [App\Http\Controllers\DeveloperController::class, 'documentsupdate'])->name('developer.documents.update');
    Route::delete('/documents.destroy.{id}', [App\Http\Controllers\DeveloperController::class, 'documentsdelete'])->name('developer.documents.destroy');
    Route::post('/documents.search', [App\Http\Controllers\DeveloperController::class, 'documentssearch'])->name('developer.documents.search');

     //Task
    Route::get('/tasks', [App\Http\Controllers\DeveloperController::class, 'tasks'])->name('developer.tasks');
    Route::get('/tasks.create', [App\Http\Controllers\DeveloperController::class, 'taskscreate'])->name('developer.tasks.create');
    Route::post('/tasks.store', [App\Http\Controllers\DeveloperController::class, 'tasksstore'])->name('developer.tasks.store');
    Route::get('/tasks.edit.{id}', [App\Http\Controllers\DeveloperController::class, 'tasksedit'])->name('developer.tasks.edit');
    Route::get('/tasks.show.{id}', [App\Http\Controllers\DeveloperController::class, 'tasksshow'])->name('developer.tasks.show');
    Route::patch('/tasks.update.{id}', [App\Http\Controllers\DeveloperController::class, 'tasksupdate'])->name('developer.tasks.update');
    Route::get('/tasks.searchdestroy.{id}', [App\Http\Controllers\DeveloperController::class, 'searchdestroy'])->name('developer.tasks.searchdestroy');
    Route::post('/tasks.search', [App\Http\Controllers\DeveloperController::class, 'taskssearch'])->name('developer.tasks.search');
    Route::delete('/tasks.destroy.{id}', [App\Http\Controllers\DeveloperController::class, 'tasksdelete'])->name('developer.tasks.destroy');
});

Route::group(['middleware' => ['auth']], function() {
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);	
});
// Route::get('/executive', [App\Http\Controllers\ExecutiveController::class, 'index'])->name('executive')->middleware('executive');

// Route::get('/developer', [App\Http\Controllers\DeveloperController::class, 'index'])->name('developer')->middleware('developer');

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::group(['middleware' => ['auth',]], function() {
//     Route::get('users', 'App\Http\Controllers\UserController@index')->name('users.index');
//     Route::get('/create', 'App\Http\Controllers\UserController@create')->name('users.create');
//     Route::post('store', 'App\Http\Controllers\UserController@store')->name('users.store');
//     Route::get('users.{id}', 'App\Http\Controllers\UserController@show')->name('users.show');
//     Route::get('edit.{id}', 'App\Http\Controllers\UserController@edit')->name('users.edit');
//     Route::patch('users.update.{id}', 'App\Http\Controllers\UserController@update')->name('users.update');
//     Route::delete('destroy.{id}', 'App\Http\Controllers\UserController@destroy')->name('users.destroy');
// });


