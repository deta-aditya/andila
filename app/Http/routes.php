<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['namespace' => 'Web'], function () {
	
	Route::get('docs/{category}/{page}', 'DocsController@read')   	    ->name('web.docs.read');
    
    Route::group(['middleware' => 'guest'], function () {

        Route::get('/', 'HomeController@root')                              ->name('web.default');
        Route::get('home', 'HomeController@welcome')                        ->name('web.home');
        Route::get('about', 'HomeController@about')                         ->name('web.about');
        Route::get('login', 'HomeController@login')                         ->name('web.login');
        Route::post('login', 'AuthController@login')                        ->name('web.auth.login');

        Route::get('docs/index', 'DocsController@index')                    ->name('web.docs.index');

	});

	Route::group(['middleware' => 'auth'], function () {

		Route::get('dashboard', 'DashboardController@index')				->name('web.dashboard.index');
		Route::get('logout', 'AuthController@logout')						->name('web.auth.logout');

		Route::get('users', 'UserController@index')							->name('web.users.index');
		Route::get('users/create', 'UserController@create')					->name('web.users.create');
		Route::get('users/{user}/edit', 'UserController@edit')				->name('web.users.edit');
		Route::post('users', 'UserController@preindex')						->name('web.users.preindex');

		Route::get('stations', 'StationController@index')					->name('web.stations.index');
		Route::get('stations/create', 'StationController@create')			->name('web.stations.create');
		Route::get('stations/{station}', 'StationController@show')			->name('web.stations.show');
		Route::get('stations/{station}/edit', 'StationController@edit')		->name('web.stations.edit');
		Route::post('stations', 'StationController@preindex')				->name('web.stations.preindex');

		Route::get('agents', 'AgentController@index')						->name('web.agents.index');
		Route::get('agents/create', 'AgentController@create')				->name('web.agents.create');
		Route::get('agents/{agent}', 'AgentController@show')				->name('web.agents.show');
		Route::get('agents/{agent}/edit', 'AgentController@edit')			->name('web.agents.edit');
		Route::post('agents', 'AgentController@preindex')					->name('web.agents.preindex');

		Route::get('subagents', 'SubagentController@index')					->name('web.subagents.index');
		Route::get('subagents/create', 'SubagentController@create')			->name('web.subagents.create');
		Route::get('subagents/{subagent}', 'SubagentController@show')		->name('web.subagents.show');
		Route::get('subagents/{subagent}/edit', 'SubagentController@edit')	->name('web.subagents.edit');
		Route::post('subagents', 'SubagentController@preindex')				->name('web.subagents.preindex');

		Route::get('schedules', 'ScheduleController@index')					->name('web.schedules.index');
		Route::get('schedules/{schedule}', 'ScheduleController@show')		->name('web.schedules.show');

		Route::get('orders', 'OrderController@index')						->name('web.orders.index');
		Route::get('orders/{schedule}/download', 'OrderController@download')->name('web.orders.download');

		Route::get('subschedules', 'SubscheduleController@index')			->name('web.subschedules.index');

		Route::get('reports', 'ReportController@index')						->name('web.reports.index');
		Route::post('reports/query', 'ReportController@prequery')			->name('web.reports.prequery');

	});
});

//////////////////////////////
//	ANDILA REST API ROUTES  //
//////////////////////////////

Route::group(['prefix' => 'api', 'middleware' => 'api', 'namespace' => 'Api'], function () {

	Route::group(['prefix' => 'v0'], function () {

		Route::post('auth',										'UserController@authenticate')			->name('api.v0.users.auth');

		Route::group(['middleware' => 'user.access'], function () {

			Route::get('users',		 							'UserController@index')					->name('api.v0.users.index');
			Route::get('users/{user}', 							'UserController@show')					->name('api.v0.users.show');
			Route::get('users/{user}/inbox', 					'UserController@inbox')					->name('api.v0.users.inbox');
			Route::get('users/{user}/outbox', 					'UserController@outbox')				->name('api.v0.users.outbox');
			Route::get('users/{user}/draftbox',					'UserController@draftbox')				->name('api.v0.users.draftbox');
			Route::get('users/{user}/attachments',				'UserController@attachments')			->name('api.v0.users.attachments');
			Route::get('users/{user}/activities', 				'UserController@activities')			->name('api.v0.users.activities');
			Route::get('users/{user}/notifications',			'UserController@notifications')			->name('api.v0.users.notifications');
			Route::post('users',		 						'UserController@admin')					->name('api.v0.users.admin');
			Route::put('users/{user}',	 						'UserController@update')				->name('api.v0.users.update');
			Route::delete('users/{user}',	 					'UserController@destroy')				->name('api.v0.users.destroy');

			Route::get('stations', 								'StationController@index')				->name('api.v0.stations.index');
			Route::get('stations/{station}', 					'StationController@show')				->name('api.v0.stations.show');
			Route::get('stations/{station}/schedules',			'StationController@schedules')			->name('api.v0.stations.schedules'); 
			Route::post('stations/{station}/schedules',			'StationController@singleSchedule')		->name('api.v0.stations.singleSchedule'); 
			Route::post('stations', 							'StationController@single')				->name('api.v0.stations.single');
			Route::put('stations/{station}', 					'StationController@update')				->name('api.v0.stations.update');
			Route::delete('stations/{station}',					'StationController@destroy')			->name('api.v0.stations.destroy');

			Route::get('agents', 								'AgentController@index')				->name('api.v0.agents.index');
			Route::get('agents/{agent}', 						'AgentController@show')					->name('api.v0.agents.show');
			Route::get('agents/{agent}/schedules', 				'AgentController@schedules')			->name('api.v0.agents.schedules');
			Route::get('agents/{agent}/subschedules',			'AgentController@subschedules')			->name('api.v0.agents.subschedules');
			Route::get('agents/{agent}/subagents', 				'AgentController@subagents')			->name('api.v0.agents.subagents');
			Route::post('agents/{agent}/subagents',				'AgentController@singleSubagent')		->name('api.v0.agents.singleSubagent');
			Route::post('agents/{agent}/activate', 				'AgentController@activate')				->name('api.v0.agents.activate');
			Route::post('agents/{agent}/deactivate', 			'AgentController@deactivate')			->name('api.v0.agents.deactivate');
			Route::post('agents/deactivate',					'AgentController@deactivates')			->name('api.v0.agents.deactivates');
			Route::post('agents/activate', 						'AgentController@activates')			->name('api.v0.agents.activates');
			Route::post('agents', 								'AgentController@single')				->name('api.v0.agents.single');
			Route::put('agents/{agent}', 						'AgentController@update')				->name('api.v0.agents.update');
			Route::delete('agents/{agent}',						'AgentController@destroy')				->name('api.v0.agents.destroy');

			Route::get('subagents', 							'SubagentController@index')				->name('api.v0.subagents.index');
			Route::get('subagents/{subagent}', 					'SubagentController@show')				->name('api.v0.subagents.show');
			Route::post('subagents/{subagent}/activate', 		'SubagentController@activate')			->name('api.v0.subagents.activate');
			Route::post('subagents/{subagent}/deactivate', 		'SubagentController@deactivate')		->name('api.v0.subagents.deactivate');
			Route::post('subagents/deactivate',					'SubagentController@deactivates')		->name('api.v0.subagents.deactivates');
			Route::post('subagents/activate', 					'SubagentController@activates')			->name('api.v0.subagents.activates');
			Route::post('subagents',		 					'SubagentController@single')			->name('api.v0.subagents.single');
			Route::put('subagents/{subagent}', 					'SubagentController@update')			->name('api.v0.subagents.update');
			Route::delete('subagents/{subagent}',				'SubagentController@destroy')			->name('api.v0.subagents.destroy');

			Route::get('schedules', 							'ScheduleController@index')				->name('api.v0.schedules.index');
			Route::get('schedules/{schedule}', 					'ScheduleController@show')				->name('api.v0.schedules.show');
			Route::get('schedules/{schedule}/subschedules', 	'ScheduleController@subschedules')		->name('api.v0.schedules.subschedules');
			Route::post('schedules/{schedule}/orders',			'ScheduleController@singleOrder')		->name('api.v0.schedules.singleOrder');
			Route::post('schedules', 							'ScheduleController@single')			->name('api.v0.schedules.single');
			Route::post('schedules/batch', 						'ScheduleController@multiple')			->name('api.v0.schedules.multiple');
			Route::delete('schedules/{schedule}',				'ScheduleController@destroy')			->name('api.v0.schedules.destroy');

			Route::get('subschedules', 							'SubscheduleController@index')			->name('api.v0.subschedules.index');
			Route::get('subschedules/{subschedule}',			'SubscheduleController@show')			->name('api.v0.subschedules.show');
			Route::post('subschedules/{subschedule}/reports',	'SubscheduleController@singleReport')	->name('api.v0.subschedules.singleReport');

			Route::get('orders', 								'OrderController@index')				->name('api.v0.orders.index');
			Route::get('orders/{order}', 						'OrderController@show')					->name('api.v0.orders.show');
			Route::get('orders/{order}/subschedules', 			'OrderController@subschedules')			->name('api.v0.orders.subschedules');
			Route::post('orders/{order}/accept', 				'OrderController@accept')				->name('api.v0.orders.accept');
			Route::post('orders', 								'OrderController@single')				->name('api.v0.orders.single');

			Route::get('reports', 								'ReportController@index')				->name('api.v0.reports.index');
			Route::get('reports/{report}',						'ReportController@show')				->name('api.v0.reports.show');
			Route::post('reports/{report}/complete', 			'ReportController@complete')			->name('api.v0.reports.complete');
			Route::post('reports/complete',			 			'ReportController@completes')			->name('api.v0.reports.completes');
			Route::post('reports/batch', 						'ReportController@multiple')			->name('api.v0.reports.multiple');
			Route::post('reports',								'ReportController@single')				->name('api.v0.reports.single');

			Route::get('messages/{message}',					'MessageController@show')				->name('api.v0.messages.show');
			Route::get('messages/{message}/attachments',		'MessageController@attachments')		->name('api.v0.messages.attachments');
			Route::post('messages/{message}/attachments',		'MessageController@multipleAttachment')	->name('api.v0.messages.multipleAttachment');
			Route::post('messages/{message}/send',				'MessageController@send')				->name('api.v0.messages.send');
			Route::post('messages/{message}/read',				'MessageController@read')				->name('api.v0.messages.read');
			Route::post('messages/send',						'MessageController@singleSend')			->name('api.v0.messages.singleSend');
			Route::post('messages/draft',						'MessageController@singleDraft')		->name('api.v0.messages.singleDraft');
			Route::put('messages/{message}',					'MessageController@update')				->name('api.v0.messages.update');

			Route::get('id/provinces',							'IndonesiaController@provinces')		->name('api.v0.reports.provinces');
			Route::get('id/regencies',							'IndonesiaController@regencies')		->name('api.v0.reports.regencies');
			Route::get('id/districts',							'IndonesiaController@districts')		->name('api.v0.reports.districts');
			Route::get('id/subdistricts',						'IndonesiaController@subdistricts')		->name('api.v0.reports.subdistricts');

		});

		Route::group(['middleware' => 'dev:1'], function () {
			//
		});

		Route::group(['middleware' => 'dev:0'], function () {
			//
		});
	});

});
