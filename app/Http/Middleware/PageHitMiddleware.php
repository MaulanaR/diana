<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class PageHitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //cek session bahasa ada apa kaga
        if(in_array($request->segment(1), array('id','en'))){
          Session::remove('bahasa');
          Session::put('bahasa', $request->segment(1));
          // $request->session()->put('bahasa', $request->segment(1));
        }else{
          if(!Session::has('bahasa')){
            Session::put('bahasa', 'id');
          }
        }
        //save ke tabel t_visitors
        $browser = null;
        if( preg_match('/MSIE (\d+\.\d+);/', $request->header('user-agent')) ) {
            $browser = "Internet Explorer";
          } else if (preg_match('/Chrome[\/\s](\d+\.\d+)/', $request->header('user-agent')) ) {
            $browser = "Chrome";
          } else if (preg_match('/Edge\/\d+/', $request->header('user-agent')) ) {
            $browser = "Edge";
          } else if ( preg_match('/Firefox[\/\s](\d+\.\d+)/', $request->header('user-agent')) ) {
            $browser = "Firefox";
          } else if ( preg_match('/OPR[\/\s](\d+\.\d+)/', $request->header('user-agent')) ) {
            $browser = "Opera";
          } else if (preg_match('/Safari[\/\s](\d+\.\d+)/', $request->header('user-agent')) ) {
            $browser = "Safari";
          }
        
        $insert = [
            'url'          => $request->url(),
            'fullurl'      => $request->fullUrl(),
            'ip'           => $request->ip(),
            'date'         => Carbon::now()->toDateTimeString(),
            'user_agent'   => $request->header('user-agent'),
            'browser'      => $browser,
        ];

        DB::table('t_visitors')->insert($insert);
        return $next($request);
    }
}
