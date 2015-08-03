@extends(Helper::acclayout())
@section('style')
    <style type="text/css">
        .popover{
            width: 600px !important;
            max-width: 600px !important;
        }
    </style>
@stop
@section('content')
    @include($module['tpl'].'/menu')
@if($users->count())
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <table class="table table-striped table-bordered min-table white-bg">
                <thead>
                <tr>
                    <th class="col-lg-1 text-center">ID</th>
                    <th class="col-lg-9 text-center" style="white-space:nowrap;">Данные пользователя</th>
                    <th class="col-lg-9 text-center" style="white-space:nowrap;">Видео</th>
                    <th class="col-lg-1 text-center"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr class="vertical-middle<? if($user->active == 0){ echo ' warning'; } ?>">
                        <td class="text-center">{{ $user->id }}</td>
                        <td>
                            {{ $user->name }}
                            <br/>
                            <i class="fa fa-envelope-o"></i> {{ HTML::mailto($user->email, $user->email) }}
                        </td>
                        <td>
                            @if($user->load_video && $user->video == '')
                                <p>Видео загружается на Youtube</p>
                            @elseif(!$user->load_video)
                                <p>Видео не загружено</p>
                            @elseif($user->load_video && $user->video != '')
                                <a data-content="{{{ $user->video }}}" data-html="true" data-original-title="Загруженное видео" data-placement="bottom" rel="popover" class="btn btn-default" href="javascript:void(0);"><i class="fa fa-arrow-down"></i> Смотреть видео</a>
                            @endif
                        </td>
                        <td class="text-center" style="white-space:nowrap;">
                            {{ Form::model($user,array('route'=>array('moderator.participants.save',$user->id),'method'=>'post')) }}
                            {{ Form::checkbox('in_main_page') }} Показывать на главной <br>
                            {{ Form::checkbox('top_week_video') }} Лучшее видео недели <br>
                            {{ Form::checkbox('winner') }} Победитель <br>
                            {{ Form::button('Сохранить',array('class'=>'white-btn actions__btn','type'=>'submit')) }}
                            {{ Form::close() }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
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
    <script type="text/javascript">
        if(typeof pageSetUp === 'function'){pageSetUp();}
        if(typeof runFormValidation === 'function'){
            loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}", runFormValidation);
        }else{
            loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}");
        }
    </script>
@stop

