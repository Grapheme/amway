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

        $users = Accounts::where('group_id', 4)->paginate(20);
        return View::make($this->module['tpl'] . 'participants', compact('users'));
    }

    public function participantsSave($user_id) {

        if ($user = User::where('id', $user_id)->first()):
            $user->in_main_page = Input::has('in_main_page') ? 1 : 0;
            $user->winner = Input::has('winner') ? 1 : 0;
            $user->top_week_video = Input::has('top_week_video') ? 1 : 0;
            $user->save();
        endif;
        return Redirect::back();

    }

    /****************************************************************************/
}