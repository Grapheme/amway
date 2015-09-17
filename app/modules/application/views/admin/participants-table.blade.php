@extends(Helper::acclayout())
@section('style')
    <style type="text/css">
        .js-has-video {
            background-color: #96bf48;
        }
    </style>
@stop
@section('content')
    @include($module['tpl'].'/menu')
    @if($users->count())
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                {{ Form::open(array('route'=>array('moderator.participants.lists', $field),'style'=>'margin:0 0 10px 0;','class'=>'smart-form')) }}
                {{ Form::hidden('field', $field) }}
                <div class="row">
                    <section class="col col-2">
                        <label class="select">
                            <select name="coding" autocomplete="off">
                                <option value="windows-1251">Windows 1251</option>
                                <option value="utf-8">UTF-8</option>
                            </select> <i></i>
                        </label>
                    </section>
                    <section class="col col-2">
                        <label class="select">
                            <select name="glue" autocomplete="off">
                                <option value=";">Точка с запятой</option>
                                <option value="tab">Табуляция</option>
                            </select> <i></i>
                        </label>
                    </section>
                    <section class="col col-2">
                        <label class="select">
                            <select name="filter_status" autocomplete="off">
                                <option value="-1">Все</option>
                                <option value="0">Новые</option>
                                <option value="1">Одобренные</option>
                                <option value="2">Отложенные</option>
                                <option value="3">Отклоненные</option>
                            </select> <i></i>
                        </label>
                    </section>
                    <section class="col col-2">
                        <label class="checkbox">
                            {{ Form::checkbox('without_video', TRUE, NULL, array('id'=>'js-without-video','autocomplete'=>'off')) }}
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
                Всего: <span id="js-user-count">{{ count($users) }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <table class="table table-bordered min-table white-bg">
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
                        <tr class="vertical-middle{{ !empty($user->video) ? ' js-has-video' : ''}}"
                            data-status="{{ $user->status }}">
                            <?php $fio = explode(' ', $user->name);?>
                            <td class="js-index-column">{{ $index + 1 }}</td>
                            @if($field == 'all')
                                <td>
                                    <nobr>{{ $user->email }}</nobr>
                                </td>
                                <td>
                                    <nobr>{{ $user->phone }}</nobr>
                                </td>
                            @else
                                <td>
                                    <nobr>{{ $user->$field }}</nobr>
                                </td>
                            @endif
                            <td><nobr>{{ @$fio[0] }}</nobr></td>
                            <td><nobr>{{ @$fio[1] }}</nobr></td>
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
            function count_calc() {
                var tr_cnt = $(".vertical-middle:visible").length;
                $("#js-user-count").html(tr_cnt);
            }

            function set_filter(filter_status) {
                if (filter_status < 0) {
                    $(".vertical-middle").show();
                } else {
                    $(".vertical-middle").hide();
                    $(".vertical-middle[data-status='" + filter_status + "']").show();
                }
            }

            $("#js-without-video").click(function () {
                var filter_status = $("select[name='filter_status']").val();
                if (filter_status < 0) {
                    $(".js-has-video").toggle();
                } else {
                    $(".js-has-video[data-status='" + filter_status + "']").toggle();
                }
                count_calc();
            });
            $("select[name='filter_status']").change(function () {
                $("#js-without-video").removeAttr("checked");
                set_filter($(this).val());
                count_calc();
            });
        });
    </script>
@stop

