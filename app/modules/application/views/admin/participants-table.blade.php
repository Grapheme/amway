@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
    @include($module['tpl'].'/menu')
    @if($users->count())
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                {{ Form::open(array('route'=>array('moderator.participants.lists', $field),'style'=>'margin:0 0 10px 0;','class'=>'smart-form')) }}
                <div class="row">
                    <section class="col col-2">
                        <label class="select">
                            <select name="coding">
                                <option selected="" value="utf-8">UTF-8</option>
                                <option value="windows-1251">Windows 1251</option>
                            </select> <i></i>
                        </label>
                    </section>
                    <section class="col col-2">
                        <label class="checkbox">
                            {{ Form::checkbox('without_video', TRUE, NULL, array('id'=>'js-without-video')) }}
                            <i></i>Без видео
                        </label>
                    </section>
                </div>
                {{ Form::submit('Экспорт в CSV',array('class'=>'btn btn-default')) }}
                {{ Form::close() }}
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <table class="table table-striped table-bordered min-table white-bg">
                    <thead>
                    <tr>
                        <th></th>
                        @if($field == 'all')
                            <th class="col-lg-4 text-center">Email</th>
                            <th class="col-lg-4 text-center">Phone</th>
                        @else
                            <th class="col-lg-4 text-center">{{ ucfirst($field) }}</th>
                        @endif
                        <th class="col-lg-3 text-center">Фамилия</th>
                        <th class="col-lg-3 text-center">Имя</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $index => $user)
                        <tr class="vertical-middle{{ !empty($user->video) ? ' js-has-video' : ''}}">
                            <?php $fio = explode(' ', $user->name);?>
                            <td>{{ $index + 1 }}</td>
                            @if($field == 'all')
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                            @else
                                <td>{{ $user->$field }}</td>
                            @endif
                            <td>{{ @$fio[0] }}</td>
                            <td>{{ @$fio[1] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="ajax-notifications custom">
                    <div class="alert alert-transparent">
                        <h4>Список пуст</h4>
                        В данном разделе находятся пользователи сайта
                        <p><br><i class="regular-color-light fa fa-th-list fa-3x"></i></p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop
@section('scripts')
    <script type="application/javascript">
        $(function () {
            $("#js-without-video").click(function () {
                $(".js-has-video").fadeToggle();
            });
        });
    </script>
@stop

