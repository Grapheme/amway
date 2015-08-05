<?php

class ModeratorController extends BaseController {

    public static $name = 'moderator';
    public static $group = 'application';

    /****************************************************************************/
    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        if (Auth::check() && Auth::user()->group_id == 3):
            Route::group(array('before' => '', 'prefix' => 'admin'), function () use ($class) {
                Route::get('participants', array('as' => 'moderator.participants',
                    'uses' => $class . '@participantsList'));
                Route::post('participant/{user_id}/save', array('before' => 'csrf',
                    'as' => 'moderator.participants.save', 'uses' => $class . '@participantsSave'));
            });
            Route::get('participants/{user_id}/status/{status_number}', array('as' => 'moderator.participants.status',
                'uses' => $class . '@participantsSetStatus'));
        endif;
    }

    /****************************************************************************/
    public function __construct() {

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,
        );
        View::share('module', $this->module);
    }

    /****************************************************************************/
    public static function returnInfo() {
    }

    public static function returnMenu() {
    }

    public static function returnActions() {
    }

    /****************************************************************************/

    public function participantsList() {

        if ($counts_all = (new User())->select(DB::raw('status, COUNT(*) AS count'))->where('group_id', 4)->groupBy('status')->get()):
            $temp = $counts = array();
            foreach ($counts_all as $count):
                $temp[$count->status] = $count->count;
            endforeach;
            $counts = $temp;
        endif;
        $counts = (array)$counts;
        $filter_status = Input::get('filter_status') ?: '0';
        $users = Accounts::where('group_id', 4)->orderBy('created_at','DESC')->where('status', $filter_status)->with('ulogin')->paginate(20);
        return View::make($this->module['tpl'] . 'participants', compact('users', 'filter_status', 'counts'));
    }

    public function participantsSave($user_id) {

        if ($user = User::where('id', $user_id)->first()):
            if (Input::has('top_week_video')):
                DB::table('users')->update(array('top_week_video' => 0));
            endif;
            $user->in_main_page = Input::has('in_main_page') ? 1 : 0;
            $user->winner = Input::has('winner') ? 1 : 0;
            $user->top_week_video = Input::has('top_week_video') ? 1 : 0;
            $user->top_video = Input::has('top_video') ? 1 : 0;
            $user->save();
        endif;
        return Redirect::back();

    }

    public function participantsSetStatus($user_id, $status) {

        if ($user = User::where('id', $user_id)->first()):
            $user->status = (int)$status;
            $user->save();
        endif;
        return Redirect::back();

    }
    /****************************************************************************/
}