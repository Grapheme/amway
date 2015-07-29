@extends(Helper::acclayout())


@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="margin-top-10">

                <h1>Добро пожаловать в Egg CMS!</h1>
                <p>Воспользуйтесь меню для перехода к нужному модулю.</p>
                <p>
                    <form action="{{ URL::route('drop_cache') }}" method="POST" target="_blank"><input type="submit" value="Очистить кэш" class="btn btn-default"></form>
                </p>

                @if (Allow::superuser())
                    <p>
                        <a href="{{ URL::route('system.phpinfo') }}" target="_blank" class="btn btn-default">phpinfo()</a>
                    </p>
                @endif

            </div>
        </div>
    </div>
@stop


@section('scripts')
@stop

