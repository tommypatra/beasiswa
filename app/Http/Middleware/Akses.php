<?php

namespace App\Http\Middleware;

use Closure;

class Akses
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
        $hakakses = [];
        $allowAkses = true;
        $allowSite = true;

        $session = session()->all();
        $auth = auth()->user();

        $siteReg = \MyApp::allowheader();
        if (!$siteReg['status']) {
            $allowSite = false;
        }

        // $route = explode("-", $request->route()->uri);
        // $hakakses = \MyApp::hakakses("/" . $route[0]);
        // if (!$hakakses['r'])
        //     $allow = false;

        // if ($allow && isset($route[1])) {
        //     if ($route[1] == "update" && !$hakakses['u'])
        //         $allow = false;
        //     elseif ($route[1] == "delete" && !$hakakses['d'])
        //         $allow = false;
        //     elseif ($route[1] == "create" && (!$hakakses['c'] && !$hakakses['u']))
        //         $allow = false;
        //     elseif ($route[1] == "upload" && (!$hakakses['u']))
        //         $allow = false;
        // }

        if (!$allowSite || !$allowAkses) {
            if ($request->ajax()) {
                $retval = array("status" => false, "messages" => ["akses ditolak"]);
                return response()->json($retval);
            } else {
                return redirect()->route('login')->with('error', 'Opps, access denied');
            }
        }

        $request->merge(array("hakakses" => $hakakses, "vauth" => $auth, "vsession" => $session));
        return $next($request);
    }
}
