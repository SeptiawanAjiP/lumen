<?php


$app->get('/{code}', 'VoucherController@get');

$app->post('register', 'AuthController@register');

$app->post('login', 'AuthController@login');


$app->group(['middleware' => 'auth:api'], function() use ($app){

	
	

});