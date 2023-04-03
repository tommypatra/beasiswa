<?php

namespace App\Http\Middleware;

use Closure;

class AllowHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allowSite = true;
        $siteReg = \MyApp::allowheader();
        if (!$siteReg['status']) {
            $allowSite = false;
        }
        if (!$allowSite) {
            if ($request->ajax()) {
                $retval = array("status" => false, "messages" => ["akses ditolak"]);
                return response()->json($retval);
            } else {
                return redirect()->route('login')->with('error', 'Opps, access denied');
            }
        }
        return $next($request);
    }
}
