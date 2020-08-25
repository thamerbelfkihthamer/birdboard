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

Route::get('/', function () {
    return redirect('login');
});


Route::group(['middleware' => 'auth'], function(){

	Route::group(['prefix' => '/projects'], function(){

		Route::get('', 'ProjectsController@index')->name('projects.index');
		Route::post('', 'ProjectsController@store')->name('projects.store');
	    Route::get('/create', 'ProjectsController@create')->name('projects.create');
		Route::get('/{project}', 'ProjectsController@show')->name('projects.show');
		Route::get('/edit/{id}', 'ProjectsController@edit')->name('projects.edit');
		Route::patch('/update/{project}', 'ProjectsController@update')->name('projects.update');
        Route::delete('/{project}', 'ProjectsController@delete')->name('projects.delete');
        Route::post('/invitation/{project}', 'ProjectInvitationsController@store')->name('project.invite');

        Route::post('/{project}/tasks', 'ProjectTasksController@store')->name('tasks.store');
	});

	Route::group(['prefix' => '/tasks'], function(){

		Route::patch('/{task}', 'ProjectTasksController@update')->name('tasks.update');
		Route::patch('/delete/{task}', 'ProjectTasksController@delete')->name('tasks.delete');
	});

    Route::get('/home', 'HomeController@index')->name('home');
});


Auth::routes();

