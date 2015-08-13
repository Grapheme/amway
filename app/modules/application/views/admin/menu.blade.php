<form class="header-search pull-right" method="get" action="{{ URL::route('moderator.participants') }}">
    <input name="search" type="text" id="search-fld" placeholder="Поиск по имени">
    <button type="submit"><i class="fa fa-search"></i></button>
    <a title="Cancel Search" id="cancel-search-js" href="javascript:void(0);"><i class="fa fa-times"></i></a>
</form>
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
</p>
<br/>