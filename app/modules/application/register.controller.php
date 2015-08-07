<?php

class RegisterController extends BaseController {

    public static $name = 'registration';
    public static $group = 'accounts';
    public static $entity = 'registration';
    public static $entity_name = 'Регистрация пользователей';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'guest', 'prefix' => ''), function () use ($class) {
            Route::post('registration', array('before' => 'csrf', 'as' => 'signup-participant',
                'uses' => $class . '@signup'));
            Route::get('registration/activation/{activate_code}', array('as' => 'signup-activation',
                'uses' => $class . '@activation'));
        });
        Route::group(array('before' => '', 'prefix' => 'api'), function () use ($class) {
            Route::post('registration', array('as' => 'api.signup.participant',
                'uses' => $class . '@apiSignup'));
        });
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

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl(),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }

    /****************************************************************************/
    public function apiSignup() {

        $json_request = array('status' => FALSE, 'responseText' => '');
        $validator = Validator::make(Input::all(), Accounts::$api_rules);
        if ($validator->passes()):
            $token = Input::get('token');
            if ($token != md5(Input::get('email') . Config::get('app.key'))):
                $json_request['responseText'] = 'Неверный токен.';
                return Response::json($json_request, 200);
            endif;
            if (User::where('email', Input::get('email'))->exists() == FALSE):
                $password = rand(1111, 9999);
                $vk_id = Input::get('vk_id');
                $inst_id = Input::get('inst_id');
                $social = array(
                    'https:://vk.com/' . is_numeric($vk_id) ? 'id' . $vk_id : $vk_id,
                    'https://instagram.com/' . $inst_id
                );

                $user = new User;
                $user->group_id = 4;
                $user->active = 1;
                $user->load_video = 1;
                $user->name = Input::get('name');
                $user->email = Input::get('email');
                $user->phone = Input::get('phone');
                $user->social = json_encode($social);
                $user->way = Input::get('way');
                $user->password = Hash::make($password);
                $user->photo = '';
                $user->thumbnail = '';
                $user->temporary_code = Str::random(24);
                $user->code_life = myDateTime::getFutureDays(5);
                $user->save();

                Mail::send('emails.auth.signup', array('account' => $user, 'password' => $password,
                    'verified_email' => FALSE), function ($message) {
                    $message->from(Config::get('mail.from.address'), Config::get('mail.from.name'));
                    $message->to(Input::get('email'))->subject('Регистрация в конкурсе талантов A-GEN (Поколение А)');
                });
                $json_request['responseText'] = Lang::get('interface.SIGNUP.success');
                $json_request['status'] = TRUE;
            else:
                $json_request['responseText'] = Lang::get('interface.SIGNUP.email_exist');
            endif;
        else:
            $json_request['responseText'] = Lang::get('interface.SIGNUP.fail');
        endif;
        return Response::json($json_request, 200);
    }

    /****************************************************************************/

    public function signup() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'redirect' => FALSE);
        if (Request::ajax()):
            $validator = Validator::make(Input::all(), Accounts::$rules);
            if ($validator->passes()):
                if (User::where('email', Input::get('email'))->exists() == FALSE):
                    $password = rand(1111, 9999);
                    $post = Input::all();
                    $post['password'] = Hash::make($password);
                    if ($account = self::getRegisterAccount($post)):
                        Mail::send('emails.auth.signup', array('account' => $account, 'password' => $password,
                            'verified_email' => $post['verified_email']), function ($message) {
                            $message->from(Config::get('mail.from.address'), Config::get('mail.from.name'));
                            $message->to(Input::get('email'))->subject('Регистрация в конкурсе талантов A-GEN (Поколение А)');
                        });
                        self::createULogin($account->id, $post);
                        Auth::loginUsingId($account->id, TRUE);
                        $json_request['redirect'] = URL::to(AuthAccount::getGroupStartUrl());
                        $json_request['responseText'] = Lang::get('interface.SIGNUP.success');
                        $json_request['status'] = TRUE;
                    endif;
                else:
                    $json_request['responseText'] = Lang::get('interface.SIGNUP.email_exist');
                endif;
            else:
                $json_request['responseText'] = Lang::get('interface.SIGNUP.fail');
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request, 200);
    }

    public function activation($temporary_key = '') {

        if ($account = User::where('active', 0)->where('temporary_code', $temporary_key)->where('code_life', '>=', time())->first()
        ):
            $account->code_life = 0;
            $account->temporary_code = '';
            $account->active = 1;
            $account->save();
            $account->touch();
            Auth::login($account, TRUE);
            return Redirect::to(AuthAccount::getGroupStartUrl());
        else:
            return Redirect::to('/')->with('message.status', 'error')->with('message.text', 'Код активации не действителен.');
        endif;
    }

    /**************************************************************************/
    private function getRegisterAccount($post = NULL) {

        $user = new User;
        if (!is_null($post)):
            $user->group_id = Group::where('name', 'participant')->pluck('id');
            $user->name = $post['name'];
            $user->surname = '';
            $user->email = $post['email'];
            $user->active = $post['verified_email'] == 1 ? 1 : 0;

            $user->location = $post['location'];
            $user->age = $post['age'];
            $user->phone = $post['phone'];
            $user->social = !empty($post['social']) ? json_encode($post['social']) : json_encode(array());

            $user->password = $post['password'];
            $user->photo = '';
            $user->thumbnail = '';
            $user->temporary_code = Str::random(24);
            $user->code_life = myDateTime::getFutureDays(5);
            $user->save();
            $user->touch();
            return $user;
        endif;
        return FALSE;
    }

    private function createULogin($user_id, $post) {

        $ulogin = new Ulogin();

        if (!is_null($post) && isset($post['network']) && !empty($post['network'])):
            $ulogin->user_id = $user_id;
            $ulogin->network = $post['network'];
            $ulogin->identity = $post['identity'];
            $ulogin->email = $post['email'];
            $ulogin->first_name = $post['name'];
            $ulogin->last_name = '';
            $ulogin->nickname = '';
            $ulogin->city = $post['location'];
            $ulogin->photo = $post['photo'];
            $ulogin->photo_big = $post['photo_big'];
            $ulogin->profile = $post['profile'];
            $ulogin->uid = $post['uid'];
            $ulogin->access_token = $post['token'];
            $ulogin->verified_email = $post['verified_email'];
            $ulogin->token_secret = '';

            $ulogin->bdate = '';
            $ulogin->sex = 0;

            $ulogin->save();
            $ulogin->touch();
            return $ulogin;
        endif;
        return FALSE;
    }
}