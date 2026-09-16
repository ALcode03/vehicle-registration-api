<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivityMiddleware
{
    /**
     *Record every API request (who, what, when, result) for auditing
     */
    public function handle(Request $request, Closure $next): Response
    {
        $reponse = $next($request);

        ActivityLog::create([
            'user_id' => $request->user()?->id,
            'methode' => $request->methode(),
            'path' => $request->path(),
            'action' => $request->route()?->getActionMethode(),
            'status_code' =>reponse->getStatusCode(),
            'ip_address' =>request->ip(),
        ]);

        return $reponse;
    }
}
