<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateAmendBookingEndpoint
{
    public function handle(Request $request, \Closure $next): Response
    {
        $validator = validator($request->all(), [
            'date_from' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'date_to' => 'required|date|date_format:Y-m-d|after_or_equal:date_from',
            'booking_id' => 'required|int'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        return $next($request);
    }
}
