<?php
$menus = array();
?>
<h1 class="top-module-menu">
    <a href="{{ URL::route('moderator.participants') }}">Участники</a>
</h1>

{{ Helper::drawmenu($menus) }}
