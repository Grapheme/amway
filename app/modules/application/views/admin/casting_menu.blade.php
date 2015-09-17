<h1 class="top-module-menu">
    <a href="{{ URL::route('moderator.casting') }}">Заявки на кастинг</a>
</h1>
<p>
    <a href="{{ URL::route('moderator.casting') }}" class="btn btn-default">
        {{ Input::has('city') == FALSE ? '<i class="fa fa-check"></i>' : '' }}
        Все города ({{ Casting::count() }})
    </a>
    @foreach($cities as $city)
        <a href="{{ URL::route('moderator.casting') }}?city={{ $city }}" class="btn btn-default">
            {{ Input::get('city') == $city ? '<i class="fa fa-check"></i>' : '' }}
            {{ $city }} ({{ @(int)$counts[$city] }})
        </a>
    @endforeach
</p>
<br/>