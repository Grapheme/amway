<div class="clearfix">
    <section class="clearfix">
        <label class="input">
            <div id="{{ isset($map_id) ? $map_id : 'map' }}" style="@if(isset($map_style)) {{ $map_style }} @else height:300px; @endif"></div>
        </label>
    </section>
</div>