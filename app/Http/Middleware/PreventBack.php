<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Session;
//use Carbon\Carbon;

class PreventBack
{
    public function handle($request, Closure $next, $guard = null)
    {

        $response = $next($request);
        return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma','no-cache')
                        ->header('Expires','Mon, 01 Jan 2018 00:00:00 GMT');
    }

}
