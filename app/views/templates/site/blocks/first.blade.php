<?php
if (is_numeric($image) && $image) {
    $image = Photo::find($image);
}
?>

<div class="tpl tpl-first">

    <h3>Шаблон FIRST</h3>

    <h4>{{ $text }}</h4>
    @if (isset($image) && is_object($image))
        <img src="{{ $image->thumb() }}" />
    @endif

</div>
