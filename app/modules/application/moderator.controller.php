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
                Route::get('participants/{params}', array('as' => 'moderator.participants.lists',
                    'uses' => $class . '@participantsLists'))->where('params', '(all|phone|email)');
                Route::post('participants/{params}', array('as' => 'moderator.participants.lists',
                    'uses' => $class . '@participantsListsImport'));
                Route::get('participants/{user_id}/edit', array('as' => 'moderator.participants.edit',
                    'uses' => $class . '@participantsEdit'));
                Route::put('participants/{user_id}/save', array('as' => 'moderator.participants.save',
                    'uses' => $class . '@participantsUpdate'));
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
    public function participantsEdit($user_id) {
        if ($user = Accounts::where('id', $user_id)->with('ulogin')->first()):
            return View::make($this->module['tpl'] . 'participant.edit', compact('user'));
        else:
            return Redirect::back();
        endif;
    }

    public function participantsUpdate($user_id) {

        if ($user = Accounts::where('id', $user_id)->first()):
            try {
                $post = Input::all();
                if (Input::has('remove_photo')):
                    if (!empty($user->photo) && File::exists(public_path($user->photo))):
                        File::delete(public_path($user->photo));
                    endif;
                    $user->photo = '';
                    if (!empty($user->thumbnail) && File::exists(public_path($user->thumbnail))):
                        File::delete(public_path($user->thumbnail));
                    endif;
                    $user->thumbnail = '';
                    foreach (Ulogin::where('user_id', $user->id)->get() as $ulogin):
                        $ulogin->photo_big = '';
                        $ulogin->photo = '';
                        $ulogin->save();
                    endforeach;
                else:
                    $user->photo = AdminUploadsController::getUploadedFile('photo');
                    $user->thumbnail = '';
                endif;
                $names = explode(' ', $user->name);
                if (count($names) > 2):
                    $user->name = @$names[0] . ' ' . @$names[1];
                else:
                    $user->name = $post['name'];
                endif;
                $user->email = $post['email'];
                $user->surname = '';
                $user->location = $post['location'];
                $user->phone = $post['phone'];
                $user->age = $post['age'];
                $user->way = $post['way'];
                $user->yad_name = $post['yad_name'];
                $user->load_video = Input::has('load_video') ? 1 : 0;
                $user->local_video = $post['local_video'];
                $user->local_video_date = $post['local_video_date'];
                $user->video = $post['video'];
                $user->video_thumb = $post['video_thumb'];
                $user->save();
                $user->touch();
                return Redirect::route('moderator.participants');
            } catch (Exception $e) {

            }
        endif;
        return Redirect::back();
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
        $groups[0] = 'Без группы';
        foreach (ParticipantGroup::lists('title', 'id') as $index => $title):
            $groups[$index] = $title;
        endforeach;
        if (Input::has('search')):
            $users = Accounts::where('group_id', 4)->where('name', 'like', '%' . Input::get('search') . '%')->orderBy('created_at', 'DESC')->with('ulogin')->paginate(20);
        else:
            $users = Accounts::where('group_id', 4)->orderBy('created_at', 'DESC')->where('status', $filter_status)->with('ulogin')->paginate(20);
        endif;
        return View::make($this->module['tpl'] . 'participants', compact('users', 'filter_status', 'counts', 'groups'));
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
            $user->top_video = Input::has('top_video') ? 1 : 0;
            $user->participant_group_id = Input::get('participant_group_id');
            $user->comment = Input::get('comment');
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
    public function participantsLists($params) {

        if ($counts_all = (new User())->select(DB::raw('status, COUNT(*) AS count'))->where('group_id', 4)->groupBy('status')->get()):
            $temp = $counts = array();
            foreach ($counts_all as $count):
                $temp[$count->status] = $count->count;
            endforeach;
            $counts = $temp;
        endif;
        $counts = (array)$counts;
        $filter_status = Input::get('filter_status') ?: '0';
        $groups[0] = 'Без группы';
        foreach (ParticipantGroup::lists('title', 'id') as $index => $title):
            $groups[$index] = $title;
        endforeach;
        $field = $params;
        $users = Accounts::where('group_id', 4)->orderBy('created_at', 'DESC')->get();
        return View::make($this->module['tpl'] . 'participants-table', compact('users', 'filter_status', 'counts', 'groups', 'field'));
    }

    public function participantsListsImport($params) {

        if (Input::has('without_video')):
            $users_list = Accounts::where('group_id', 4)->where('video', '')->orderBy('created_at', 'DESC')->get();
        else:
            $users_list = Accounts::where('group_id', 4)->orderBy('created_at', 'DESC')->get();
        endif;
        $users = array();
        $output = '';
        foreach ($users_list as $user):
            $fio = explode(' ', $user->name);
            $name = iconv("UTF-8", Input::get('coding'), @$fio[0]);
            $surname = iconv("UTF-8", Input::get('coding'), @$fio[1]);
            $glue = Input::get('glue');
            if ($params == 'all'):
                if ($glue === 'tab'):
                    $output .= implode("\t", array($user->email, $user->photo, $name, $surname)) . "\n";
                else:
                    $output .= implode("$glue", array($user->email, $user->photo, $name, $surname)) . "\n";
                endif;
            else:
                if ($glue === 'tab'):
                    $output .= implode("\t", array($user->email, $user->photo, $name, $surname)) . "\n";
                else:
                    $output .= implode("$glue", array($user->email, $user->photo, $name, $surname)) . "\n";
                endif;
            endif;
        endforeach;
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="ExportList.csv"',
        );
        return Response::make(rtrim($output, "\n"), 200, $headers);
    }
    /****************************************************************************/
}