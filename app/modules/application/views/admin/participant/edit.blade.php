@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
    {{ Form::model($user,array('route'=>array('moderator.participants.update',$user->id),'class'=>'smart-form','id'=>'group-form','role'=>'form','method'=>'post','files'=>true)) }}
    <div class="row">
        <section class="col col-6">
            <div class="well">
                <fieldset>
                    <section>
                        <label class="label">Имя и фамилия</label>
                        <label class="input">
                            {{ Form::text('name') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Город</label>
                        <label class="input">
                            {{ Form::text('location') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Возраст</label>
                        <label class="input">
                            {{ Form::text('age') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Электронная почта</label>
                        <label class="input">
                            {{ Form::text('email') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Телефон</label>
                        <label class="input">
                            {{ Form::text('phone') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Skype</label>
                        <label class="input">
                            {{ Form::text('skype') }}
                        </label>
                    </section>
                    <section>
                    @if(!empty($user->photo) && File::exists(public_path($user->photo)))
                        <img height="100px" src="{{ asset($user->photo) }}"
                             alt="{{ $user->name }}" class="{{ $user->name }}">
                    @elseif(!empty($user->ulogin) && !empty($user->ulogin->photo_big))
                        <img height="100px" src="{{ $user->ulogin->photo_big }}" alt="{{ $user->name }}"
                             class="{{ $user->name }}">
                    @elseif(!empty($user->photo))
                        <img height="100px" src="{{ $user->photo }}" alt="{{ $user->name }}"
                             class="{{ $user->name }}">
                    @else
                        <img height="100px" src="{{ asset('/uploads/users/award-'.rand(1, 3).'.png') }}" alt="{{ $user->name }}"
                             class="{{ $user->name }}">
                    @endif
                    </section>
                    <section>
                        <label class="label">Фотография</label>
                        <label class="input">
                            {{ Form::file('photo') }}
                        </label>
                    </section>
                    <section>
                        <label class="checkbox">
                            {{ Form::checkbox('remove_photo', TRUE, NULL, array('autocomplete'=>'off')) }}
                            <i></i>удалить фото (+соц.сети)</label>
                    </section>
                    <section>
                        <label class="label">Почему именно я ...</label>
                        <label class="input">
                            {{ Form::textarea('way',NULL,array('class'=>'redactor')) }}
                        </label>
                    </section>
                </fieldset>
                <fieldset>
                    <section>
                        <label class="label">Yandex Disc File Name</label>
                        <label class="input">
                            {{ Form::text('yad_name') }}
                        </label>
                    </section>
                    <section>
                        <label class="checkbox">
                            {{ Form::checkbox('load_video', TRUE, NULL, array('autocomplete'=>'off')) }}
                            <i></i>Видео загружено</label>
                    </section>
                    <section>
                        <label class="label">Путь к локальному видео-файлу</label>
                        <label class="input">
                            {{ Form::text('local_video') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Дата загрузки видео-файла</label>
                        <label class="input">
                            {{ Form::text('local_video_date') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Ссылка на видео с Youtube</label>
                        <label class="input">
                            {{ Form::text('video') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Ссылка на картинку Youtube-видео</label>
                        <label class="input">
                            {{ Form::text('video_thumb') }}
                        </label>
                    </section>
                </fieldset>
                <footer>
                    <a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner"
                       href="{{URL::previous()}}">
                        <i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
                    </a>
                    <button autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
                        <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сохранить</span>
                    </button>
                </footer>
            </div>
        </section>
    </div>
    {{ Form::close() }}
@stop
@section('scripts')
    {{ HTML::script('private/js/vendor/redactor.min.js') }}
    {{ HTML::script('private/js/system/redactor-config.js') }}
@stop