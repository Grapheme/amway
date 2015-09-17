<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {{ Form::open(array('route'=>array('moderator.participants.lists', $field),'style'=>'margin:0 0 10px 0;','class'=>'smart-form')) }}
        {{ Form::hidden('field', $field) }}
        <div class="row">
            <section class="col col-2">
                <label class="select">
                    <select name="coding" autocomplete="off">
                        <option value="windows-1251">Windows 1251</option>
                        <option value="utf-8">UTF-8</option>
                    </select> <i></i>
                </label>
            </section>
            <section class="col col-2">
                <label class="select">
                    <select name="glue" autocomplete="off">
                        <option value=";">Точка с запятой</option>
                        <option value="tab">Табуляция</option>
                    </select> <i></i>
                </label>
            </section>
            <section class="col col-2">
                <label class="select">
                    <select name="filter_status" autocomplete="off">
                        <option value="-1">Все</option>
                        <option value="0">Новые</option>
                        <option value="1">Одобренные</option>
                        <option value="2">Отложенные</option>
                        <option value="3">Отклоненные</option>
                    </select> <i></i>
                </label>
            </section>
            <section class="col col-2">
                <label class="checkbox">
                    {{ Form::checkbox('without_video', TRUE, NULL, array('id'=>'js-without-video','autocomplete'=>'off')) }}
                    <i></i>Без видео
                </label>
            </section>
        </div>
        {{ Form::submit('Экспорт в CSV',array('class'=>'btn btn-default')) }}
        {{ Form::close() }}
    </div>
</div>