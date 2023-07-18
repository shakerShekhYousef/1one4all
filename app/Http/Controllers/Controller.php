<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Info(
 *    title="Your super  ApplicationAPI",
 *    version="1.0.0",
 * ),
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Token based Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="apiAuth",
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($result, $message)
    {
        if (!is_null($result))
            $response = [
                'success' => true,
                'data'    => $result,
                'message' => $message,
            ];
        else
            $response = [
                'success' => true,
                'message' => $message,
            ];


        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    public function createnotification($data)
    {
        try {
            $notification = Notification::create([
                'player_id' => $data['player_id'],
                'trainer_id' => $data['trainer_id'],
                'request_id' => $data['request_id'],
                'notification_type' => $data['notification_type'],
            ]);
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }
}
