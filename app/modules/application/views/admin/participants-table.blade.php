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
        @include($module['tpl'].'/forms/participants-export')
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

