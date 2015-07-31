<?php

class SocialController extends BaseController {

    public static $name = 'social';
    public static $group = 'accounts';
    public static $entity = 'social';
    public static $entity_name = 'Работа с социальными сетями';

    /****************************************************************************/
    public static function returnRoutes() {
        $class = __CLASS__;
        Route::post('social-signin', ['as' => 'signin.ulogin', 'uses' => $class . '@postUlogin']);
    }

    public static function returnShortCodes() {
    }

    public static function returnActions() {
    }

    public static function returnInfo() {
    }

    public static function returnMenu() {
    }

    /****************************************************************************/
    public function __construct() {
    }

    /****************************************************************************/
    public function postUlogin() {

        $_user = json_decode(file_get_contents('http://ulogin.ru/token.php?token=' . Input::get('token') . '&host=' . $_SERVER['HTTP_HOST']), true);
        $validate = Validator::make([], []);
        if (isset($_user['error'])):
            return Redirect::to('/#popup=enter');
        endif;
        if ($check = Ulogin::where('identity', '=', $_user['identity'])->first()):
            Auth::loginUsingId($check->user_id, true);
            return Redirect::to(AuthAccount::getGroupStartUrl());
        elseif (isset($_user['email']) && User::where('email', $_user['email'])->exists()):
            $userID = User::where('email', $_user['email'])->pluck('id');
            self::createULogin($userID, $_user);
            Auth::loginUsingId($userID, TRUE);
            return Redirect::to(AuthAccount::getGroupStartUrl());
        else:
            $rules = array('network' => 'required|max:255', 'identity' => 'required|max:255|unique:ulogin',
                'email' => 'required|unique:ulogin|unique:users');
            $validate = Validator::make($_user, $rules);
            if ($validate->passes()):
                return Redirect::to('/#popup=reg')
                    ->with('token', Input::get('token'))
                    ->with('email', $_user['email'])
                    ->with('identity', $_user['identity'])
                    ->with('profile', $_user['profile'])
                    ->with('first_name', $_user['first_name'])
                    ->with('last_name', $_user['last_name'])
                    ->with('city', $_user['city'])
                    ->with('uid', $_user['uid'])
                    ->with('photo_big', $_user['photo_big'])
                    ->with('photo', $_user['photo'])
                    ->with('network', $_user['network'])
                    ->with('verified_email', $_user['verified_email']);
            else:
                return Redirect::to('/#popup=enter');
            endif;
        endif;
    }

    private function createULogin($userID, $_user) {

        $ulogin = new Ulogin();
        $ulogin->user_id = $userID;
        $ulogin->network = $_user['network'];
        $ulogin->identity = $_user['identity'];
        $ulogin->email = isset($_user['email']) ? $_user['email'] : '';
        $ulogin->first_name = $_user['first_name'];
        $ulogin->last_name = $_user['last_name'];
        $ulogin->photo = $_user['photo'];
        $ulogin->photo_big = $_user['photo_big'];
        $ulogin->profile = $_user['profile'];
        $ulogin->access_token = isset($_user['access_token']) ? $_user['access_token'] : '';
        $ulogin->country = isset($_user['country']) ? $_user['country'] : '';
        $ulogin->city = isset($_user['city']) ? $_user['city'] : '';
        $ulogin->save();

        return $ulogin;
    }

    /****************************************************************************/
}