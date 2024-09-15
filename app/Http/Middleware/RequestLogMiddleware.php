<?php

namespace App\Http\Middleware;

use App\Models\RequestLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $logData = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'body' => $request->except(['password', 'token']),
            'ip_address' => $request->ip(),
        ];

        $log = RequestLog::create($logData);

        $response = $next($request);

        $log->update([
            'response' => [
                'status' => $response->status(),
                'content' => json_decode($response->getContent(), true)
            ]
        ]);

        return $response;
    }
}
