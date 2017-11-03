<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
	/**
     * Validate the given request with the given rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return void
     */
    public function validate(\Illuminate\Http\Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
        	$message = implode(', ',
    						array_values(
    							array_map(
    								function($v){
    									return implode(', ', $v);
    								}, $validator->errors()->getMessages()
    							)
    						)
        				);


        	throw new ValidationException($validator, $this->buildFailedValidationResponse(
	            $request, ["status" => "fail", "message" => $message]
	        ));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function buildFailedValidationResponse(\Illuminate\Http\Request $request, array $errors)
    {
        if (isset(static::$responseBuilder)) {
            return call_user_func(static::$responseBuilder, $request, $errors);
        }

        return new JsonResponse($errors, 200);
    }

    public function sendNotif($email, $data){
        $receiver = app('db')->table('fire_account')->where('email', $email)->select('token')->get();
        return $this->pushNotif($receiver, $data);
    }

    public function buildPayload(){
        
    }

    public function pushNotif($receiver, $data){
        $receiver = $receiver->pluck('token');
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . env('FIREBASE_API'),
            'Content-Type: application/json'
        );

        $ch = curl_init();
        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);
        
        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (sizeof($receiver)==1) {
           $fields = array(
                'to' => $receiver[0],
                'notification' => [
                    'body' => 'Tes',
                    'title' => 'Se',
                    'icon' => null
                ]
            );
        } else {
            $fields = array(
                'registration_ids' => $receiver,
                'notification' => [
                    'body' => 'Tes',
                    'title' => 'Se',
                    'icon' => null
                ]
            );
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);

        return response()->json($result);
    }
}
