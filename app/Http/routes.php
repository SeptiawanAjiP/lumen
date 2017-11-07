<?php


$app->get('/{code}', 'Controller@get');




$app->group(['middleware' => 'auth:api'], function() use ($app){

		
		
});
