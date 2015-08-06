<?php

class ParticipantGroupController extends BaseController {

    public static $name = 'participant_group';
    public static $group = 'application';

    /****************************************************************************/

    public static function returnRoutes() {

        $class = __CLASS__;

        if (Auth::check() && Auth::user()->group_id == 3):
            Route::group(array('before' => '', 'prefix' => 'admin'), function () use ($class) {
                Route::resource('participant-groups', $class,
                    array(
                        'except' => array('show'),
                        'names' => array(
                            'index' => 'participant_group.index',
                            'create' => 'participant_group.create',
                            'store' => 'participant_group.store',
                            'edit' => 'participant_group.edit',
                            'update' => 'participant_group.update',
                            'destroy' => 'participant_group.destroy'
                        )
                    )
                );
            });
        endif;
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
            'tpl' => static::returnTpl('admin'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,
        );
        View::share('module', $this->module);
    }

    /****************************************************************************/

    public function index() {

        $groups = ParticipantGroup::orderBy('title')->with('participants')->get();
        return View::make($this->module['tpl'] . 'participant_group.index', compact('groups'));
    }

    public function create() {

        return View::make($this->module['tpl'] . 'participant_group.create');
    }

    public function store() {

        $validator = Validator::make(Input::all(), ParticipantGroup::$rules);
        if ($validator->passes()):

            $group = new ParticipantGroup();
            $group->title = Input::get('title');
            $group->description = Input::get('description');
            $group->save();

            $json_request['responseText'] = "Группа добавлена";
            $json_request['redirect'] = URL::route('participant_group.index');
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validator->messages()->all(), '<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function edit($group_id) {

        if ($group = ParticipantGroup::where('id', $group_id)->first()):
            return View::make($this->module['tpl'] . 'participant_group.edit', compact('group'));
        else:
            App::abort(404);
        endif;
    }

    public function update($group_id) {

        $validator = Validator::make(Input::all(), ParticipantGroup::$rules);
        if ($validator->passes()):
            $group = ParticipantGroup::where('id', $group_id)->first();
            $group->title = Input::get('title');
            $group->description = Input::get('description');
            $group->save();
            $json_request['responseText'] = "Группа сохранена";
            $json_request['redirect'] = URL::route('participant_group.index');
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validator->messages()->all(), '<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function destroy($group_id) {

        $json_request = array('status' => FALSE, 'responseText' => '', 'redirect' => FALSE);
        if (Request::ajax()):
            ParticipantGroup::where('id', $group_id)->delete();
            $json_request['responseText'] = "Группа удалена.";
            $json_request['status'] = TRUE;
        else:
            return Redirect::back();
        endif;
        return Response::json($json_request, 200);
    }
    /****************************************************************************/
}