@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
    <h1 class="top-module-menu">
        <a href="{{ URL::route('moderator.casting') }}">Заявки на кастинг</a>
    </h1>
    @if(count($applications_list))
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                @foreach($applications_list as $times => $applications)
                    <table class="table table-striped table-bordered min-table white-bg">
                        <h5>{{ $times }}</h5>
                        <thead>
                        <tr>
                            <th class="text-center">№ п.п</th>
                            <th class="text-center">ФИО</th>
                            <th class="text-center" style="white-space:nowrap;">Город</th>
                            <th class="text-center" style="white-space:nowrap;">Телефон</th>
                            <th class="text-center" style="white-space:nowrap;">Дата</th>
                            <th class="text-center" style="white-space:nowrap;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($applications as $index => $application)
                            <tr class="vertical-middle">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $application['name'] }}</td>
                                <td><nobr>{{ $application['city'] }}</nobr></td>
                                <td><nobr>{{ $application['phone'] }}</nobr></td>
                                <td><nobr>{{ $application['created_at']->format('d.m.Y в H:i') }}</nobr></td>
                                <td>
                                    <form method="delete"
                                          action="{{ URL::route('moderator.casting.delete', $application->id) }}"
                                          style="display:inline-block">
                                        <button type="button" class="btn btn-danger remove-application">
                                            Удалить
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="ajax-notifications custom">
                    <div class="alert alert-transparent">
                        <h4>Список пуст</h4>
                        В данном разделе находятся заявки на кастинг
                        <p><br><i class="regular-color-light fa fa-th-list fa-3x"></i></p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop
@section('scripts')
    <script>
        var essence = 'application';
        var essence_name = 'заявку';
        var validation_rules = {};
        var validation_messages = {};
    </script>
    {{ HTML::script('private/js/modules/standard.js') }}
    <script type="text/javascript">
        if(typeof pageSetUp === 'function'){pageSetUp();}
        if(typeof runFormValidation === 'function'){
            loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}", runFormValidation);
        }else{
            loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}");
        }
    </script>
@stop

