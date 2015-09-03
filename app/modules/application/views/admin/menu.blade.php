{{ Form::open(array('route'=>'moderator.participants', 'class'=>'header-search pull-right', 'method'=>'get')) }}
{{ Form::hidden('field', 'name') }}
<input name="search" type="text" id="search-fld" placeholder="Поиск по имени">
<button type="submit"><i class="fa fa-search"></i></button>
{{ Form::close() }}

{{ Form::open(array('route'=>'moderator.participants', 'class'=>'header-search pull-right', 'method'=>'get', 'style'=>'wight:200px; line-height:1.8;')) }}
{{ Form::hidden('field', 'location') }}
<input name="search-like" type="text" style="display: inline-block; width: 200px;" id="search-fld" placeholder="Поиск по городу">
<div style="display:inline-block;">
    {{ Form::select('search-select', User::groupBy('location')->lists('location','location'), NULL, array('style'=>'height:30px;')) }}
    <button type="submit"><i class="fa fa-search"></i></button>
</div>
{{ Form::close() }}
<h1 class="top-module-menu">
    <a href="{{ URL::route('moderator.participants') }}">Участники</a>
</h1>
<p>
    <a href="{{ URL::route('moderator.participants') }}" class="btn btn-default">
        {{ $filter_status == '0' ? '<i class="fa fa-check"></i>' : '' }}
        Новые ({{ @(int)$counts[0] }})
    </a>
    <a href="{{ URL::route('moderator.participants') }}?filter_status=1" class="btn btn-success">
        {{ $filter_status == '1' ? '<i class="fa fa-check"></i>' : '' }}
        Одобренные ({{ @(int)$counts[1] }})
    </a>
    <a href="{{ URL::route('moderator.participants') }}?filter_status=2" class="btn btn-warning">
        {{ $filter_status == '2' ? '<i class="fa fa-check"></i>' : '' }}
        Отложенные ({{ @(int)$counts[2] }})
    </a>
    <a href="{{ URL::route('moderator.participants') }}?filter_status=3" class="btn btn-danger">
        {{ $filter_status == '3' ? '<i class="fa fa-check"></i>' : '' }}
        Отклоненные ({{ @(int)$counts[3] }})
    </a>
    <a href="{{ URL::route('moderator.participants.lists','all') }}" class="btn btn-default">
        Полный список
    </a>
    <a href="{{ URL::route('moderator.participants.lists','email') }}" class="btn btn-default">
        Список Emails
    </a>
    <a href="{{ URL::route('moderator.participants.lists','phone') }}" class="btn btn-default">
        Список Phones
    </a>
</p>
<br/>