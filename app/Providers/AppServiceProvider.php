<?php

namespace App\Providers;

use App\Notification;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('notificationsCount', function () {
            $countNote = Notification::where('user_id', Auth::user()->id)->where('status', 'new')->orderby('id', 'desc')->count();
            return $countNote;
        });
        $this->app->singleton('item_count_cart', function () {
            $item_count_cart = DB::table('tbl_cart')->where('user_id', Auth::user()->id)->where('status', 'recommended')->count();
            Log::info($item_count_cart);
            return $item_count_cart;
        });
        $this->app->singleton('item_count_cart_responsive', function () {
            $item_count_cart_responsive = DB::table('tbl_cart')->where('user_id', Auth::user()->id)->where('status', 'recommended')->count();
            return $item_count_cart_responsive;
        });
        $this->app->singleton('notificationsPopup', function () {
            $toastShow = Notification::where('user_id', Auth::user()->id)->where('status', 'new')->where('toast', 0)->orderby('id', 'desc')->get();
            Notification::where('user_id', Auth::user()->id)->update(['toast' => 1]);
            return $toastShow;
        });
        $this->app->singleton('sessionTimeCounter', function () {

            // if(Auth::user()->type=='patient')
            // {
            //     $sessionUpTime=Session::where('patient_id',Auth::user()->id)->where('status','doctor joined')->first();
            //     $time=date('H:i:s',strtotime($sessionUpTime->updated_at));
            //     $nowTime=Carbon::now()->format('H:i:s')->addMinutes(15);
            //     $time1 = new DateTime($time);
            //     $time2 = new DateTime($nowTime);
            //     $interval = $time1->diff($time2);

            // }
            // else if(Auth::user()->type=='doctor')
            // {
            //     $toastShow=Session::where('doctor_id',Auth::user()->id)->where('status','doctor joined')->first();

            //     Notification::where('user_id',Auth::user()->id)->update(['toast'=>1]);
            //     return $toastShow;
            // }

        });
        $this->app->singleton('getNote', function () {

            $getNotes = Notification::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
            if ($getNotes != null || $getNotes != '') {
                foreach ($getNotes as $getNote) {
                    $time = $getNote->created_at;
                    $timediff = $time->diff(new DateTime());
                    $seconds = $timediff->s;
                    $mint = $timediff->i;
                    $hours = $timediff->h;
                    $day = $timediff->d;
                    $month = $timediff->m;
                    $year = $timediff->y;
                    if ($year > 0) {$getNote->created_at = $year . " Year Ago";} else if ($month > 0) {$getNote->created_at = $month . " Month Ago";} else if ($day > 0) {$getNote->created_at = $day . " Day Ago";} else if ($hours > 0) {$getNote->created_at = $hours . " Hour Ago";} else if ($mint > 0) {$getNote->created_at = $mint . " Minutes Ago";} else { $getNote->created_at = $seconds . " Seconds Ago";}
                }
            }
            return $getNotes;
        });

        $this->app->singleton('DocStatus', function () {
            $Doc_Status = Auth::user()->status;
            return $Doc_Status;
        });

        Schema::defaultStringLength(191);
        Blade::directive('convert', function ($money) {
            return "<?php echo number_format($money, 2); ?>";
        });
        //View::share('shahzaib', '03432881510');
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $url = url()->current();
                if ($user->user_type == 'doctor') {
                    $count = DB::table('user_auth_activity')->where('user_id', $user->id)->count();
                    if ($count == 0) {
                        DB::table('user_auth_activity')->insert([
                            'user_id' => $user->id,
                            'url' => $url,
                            'status' => '1',
                            'expired_time' => Carbon::now()->addMinutes(15),
                            'created_at' => NOW(),
                            'updated_at' => NOW(),
                        ]);
                    } else {
                        DB::table('user_auth_activity')->where('user_id', $user->id)->update([
                            'user_id' => $user->id,
                            'url' => $url,
                            'status' => '1',
                            'expired_time' => Carbon::now()->addMinutes(15),
                            'created_at' => NOW(),
                            'updated_at' => NOW(),
                        ]);
                    }
                }
            }
        });
    }
}
