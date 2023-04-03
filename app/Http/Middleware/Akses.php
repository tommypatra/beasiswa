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
        $session = session()->all();
        $auth = auth()->user();

        // $route = explode("-", $request->route()->uri);
        // $hakakses = \MyApp::hakakses("/" . $route[0]);
        // if (!$hakakses['r'])
        //     $allowAkses = false;

        // if ($allowAkses && isset($route[1])) {
        //     if ($route[1] == "update" && !$hakakses['u'])
        //         $allowAkses = false;
        //     elseif ($route[1] == "delete" && !$hakakses['d'])
        //         $allowAkses = false;
        //     elseif ($route[1] == "create" && (!$hakakses['c'] && !$hakakses['u']))
        //         $allowAkses = false;
        //     elseif ($route[1] == "upload" && (!$hakakses['u']))
        //         $allowAkses = false;
        // }

        if (!$allowAkses) {
            if ($request->ajax()) {
                $retval = array("status" => false, "messages" => ["akses ditolak"]);
                return response()->json($retval);
            } else {
                return redirect()->route('login')->with('error', 'Opps, access denied');
            }
        }

        $request->merge(array("hakakses" => $hakakses));
        return $next($request);
    }
}
