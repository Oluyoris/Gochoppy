<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Send email notification to a user
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'type'    => 'required|in:order_update,withdrawal,general',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::find($request->user_id);

        if (!$user->email) {
            return response()->json([
                'success' => false,
                'message' => 'User has no email address.',
            ], 400);
        }

        // Queue email (async)
        Mail::to($user->email)->queue(new OrderNotification(
            $request->title,
            $request->message,
            $user
        ));

        return response()->json([
            'success' => true,
            'message' => 'Email notification queued successfully',
        ]);
    }
}