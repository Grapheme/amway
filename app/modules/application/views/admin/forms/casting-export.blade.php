<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {{ Form::open(array('route'=>array('moderator.casting.import', Input::get('city')),'style'=>'margin:0 0 10px 0;','class'=>'smart-form')) }}
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
                    <select name="city" autocomplete="off">
                        <option value="-1">Все</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ Input::get('city') == $city ? 'selected="selected"' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select> <i></i>
                </label>
            </section>
        </div>
        {{ Form::submit('Экспорт в CSV',array('class'=>'btn btn-default')) }}
        {{ Form::close() }}
    </div>
</div>