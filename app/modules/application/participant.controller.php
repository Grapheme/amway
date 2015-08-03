<?php

class ParticipantController extends BaseController {

    public static $name = 'participant';
    public static $group = 'accounts';
    public static $entity = 'participant';
    public static $entity_name = 'Участники';

    /****************************************************************************/

    public static function returnRoutes() {

        $class = __CLASS__;

        Route::group(array('before' => 'user.auth', 'prefix' => 'participant'), function () use ($class) {
            Route::get('profile', array('as' => 'profile.edit', 'uses' => $class . '@profileEdit'));

            Route::post('profile/avatar/upload', array('before' => 'csrf', 'as' => 'profile.avatar.upload',
                'uses' => $class . '@profileAvatarUpdate'));
            Route::delete('profile/avatar/delete', array('before' => 'csrf', 'as' => 'profile.avatar.delete',
                'uses' => $class . '@profileAvatarDelete'));

            Route::post('profile/video/upload', array('as' => 'profile.video.upload',
                'uses' => $class . '@profileVideoUpdate'));
        });
        Route::post('video/youtube', array('as' => 'profile.video.youtube',
            'uses' => $class . '@setYoutubeVideo'));
        Route::post('participant/{user_id}/set-like', array('as' => 'participant.public.set.like', 'uses' => $class . '@setLike'));
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

    public function profileEdit() {

        $page_data = array(
            'page_title' => 'Личный кабинет',
            'page_description' => '',
            'page_keywords' => '',
            'profile' => Accounts::where('id', Auth::user()->id)->with('ulogin')->first(),
        );
        return View::make(Helper::acclayout('profile'), $page_data);
    }

    public function profileAvatarUpdate() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'image' => '', 'redirect' => FALSE);
        if (Request::ajax()):
            if ($uploaded = AdminUploadsController::createImageInBase64String('photo')):
                $user = Auth::user();
                $user->photo = @$uploaded['main'];
                $user->thumbnail = @$uploaded['thumb'];
                $user->save();
                $user->touch();
                $json_request['image'] = asset($user->photo);
                $json_request['status'] = TRUE;
            else:
                $json_request['responseText'] = Lang::get('interface.DEFAULT.fail');
            endif;
        else:
            return Redirect::back();
        endif;
        return Response::json($json_request, 200);
    }

    public function profileAvatarDelete() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'redirect' => FALSE);
        if (Request::ajax()):
            $user = Auth::user();
            if (File::exists(public_path($user->photo))):
                File::delete(public_path($user->photo));
            endif;
            if (File::exists(public_path($user->thumbnail))):
                File::delete(public_path($user->thumbnail));
            endif;
            $user->photo = '';
            $user->thumbnail = '';
            $user->save();
            $user->touch();
            $json_request['status'] = TRUE;
        else:
            return Redirect::back();
        endif;
        return Response::json($json_request, 200);
    }

    public function profileVideoUpdate() {

        $json_request = array('status' => FALSE, 'responseText' => '', 'redirect' => FALSE);
        if (Input::hasFile('video')):
            $path = Config::get('site.uploads_video_dir') . '/';
            if ($uploaded_file_path = AdminUploadsController::getUploadedFile('video', $path)):
                if (Auth::user()->load_video && File::exists(public_path(Auth::user()->local_video))):
                    File::delete(public_path(Auth::user()->local_video));
                endif;
                Auth::user()->load_video = 1;
                Auth::user()->local_video = $uploaded_file_path;
                Auth::user()->local_video_date = Carbon::now();
                Auth::user()->video = '';
                Auth::user()->save();
                $file_info = array('user_id' => Auth::user()->id, 'file_path' => public_path($uploaded_file_path),
                    'end' => "\n\r");
                File::append(public_path($path . 'readme.txt'), implode(';', $file_info));
                $json_request['responseText'] = 'Файл успешно загружен.';
                $json_request['status'] = TRUE;
            endif;
        endif;
        return Response::json($json_request, 200);
    }

    public function setYoutubeVideo() {

        $validator = Validator::make(Input::all(), array('user_id' => 'required|integer', 'video' => 'required'));
        if ($validator->passes()):
            if ($user = User::where('id', Input::get('user_id'))->where('load_video', 1)->first()):
                $user->video = Input::get('video');
                $user->save();
                $user->touch();
                return Response::make('', 200);
            endif;
        endif;
        App::abort(404);
    }
    /****************************************************************************/
    public function setLike($user_id){

        $json_request = array('status' => FALSE, 'count' => 0);
        if (Request::ajax()):
            if ($user = Accounts::where('group_id', 4)->first()):
                self::incrementLikePost($user);
                $json_request['status'] = TRUE;
                $json_request['count'] = ParticipantLikes::where('participant_id', $user_id)->count();
            endif;
        else:
            return Redirect::back();
        endif;
        return Response::json($json_request, 200);
    }

    private function incrementLikePost($user) {

        if (ParticipantLikes::where('participant_id', $user->id)->where('user_id', Auth::user()->id)->exists() === FALSE):
            ParticipantLikes::create(array('participant_id' => $user->id, 'user_id' => Auth::user()->id));
            return TRUE;
        endif;
        return FALSE;
    }
    /****************************************************************************/
}