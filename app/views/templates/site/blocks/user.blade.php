<div class="unit">
    <div class="img">
        @if(!empty($user->photo) && File::exists(public_path($user->photo)))
            <img src="{{ asset($user->photo) }}"
                 alt="{{ $user->name }}" class="{{ $user->name }}">
        @elseif(!empty($user->ulogin) && !empty($user->ulogin->photo_big))
            <img src="{{ $user->ulogin->photo_big }}" alt="{{ $user->name }}"
                 class="{{ $user->name }}">
        @else
            <img src="{{ asset('/uploads/users/award-'.rand(1, 3).'.png') }}" alt="{{ $user->name }}"
                 class="{{ $user->name }}">
        @endif
    </div>
    <div class="name">
        {{ $user->name }}
    </div>
    <div class="location">
        {{ $user->location }}
    </div>
    <div class="rating">
        <span class="icon2-star"></span>
        <div class="count">{{ count($user->likes) }}</div>
        <div class="legend">{{ Lang::choice('голос|голоса|голосов', (int)count($user->likes) ) }}</div>
    </div>
    <a href="{{ URL::route('participant.public.set.like', $user->id) }}" class="vote">Проголосовать</a>
</div>