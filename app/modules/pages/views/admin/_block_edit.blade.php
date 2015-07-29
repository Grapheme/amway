<?
#$create_title = "Редактировать " . $module['entity_name'] . ":";
#$edit_title   = "Добавить " . $module['entity_name'] . ":";
#$create_title = "Изменить страницу:";
#$edit_title   = "Новая страница:";

$url = action($module['class'] . '@postAjaxPagesSaveBlock');
$method = 'POST';

if (!is_array($element->settings) && $element->settings != '')
    $element->settings = json_decode($element->settings, 1);
?>

<?
#Helper::ta($element);
?>

{{ Form::model($element, array('url' => $url, 'class' => 'smart-form2', 'id' => 'block-form', 'role' => 'form', 'method' => $method)) }}
@if ($element->id)
    {{ Form::hidden('id', $element->id) }}
    {{ Form::hidden('settings[editor_state]') }}
@endif
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                &times;
            </button>
            <h4 class="modal-title" id="myModalLabel">
                Редактировать блок
            </h4>
        </div>
        <div class="modal-body padding-bottom-10">

            <div class="row">
                <div class="col-md-12">

                    <fieldset class="row margin-bottom-10">

                        <section class="col @if (Allow::action('pages', 'advanced', TRUE, FALSE)) col-lg-6 @else col-lg-12 @endif">
                            <label class="control-label"> Название </label>
                            {{ Form::text('name', NULL, array('class' => 'form-control', 'placeholder' => 'Название блока', 'required' => 'required')) }}
                        </section>

                        @if (Allow::action('pages', 'advanced', TRUE, FALSE))
                            <section class="col col-lg-6">
                                <label class="control-label"> Системное имя </label>
                                {{ Form::text('slug', NULL, array('class' => 'form-control')) }}
                            </section>

                            <section class="col col-lg-6">
                                <label class="checkbox">
                                    {{ Form::checkbox('settings[system_block]', 1, (@$element->settings['system_block'] == 0 ? NULL : TRUE)) }}
                                    <i></i> Запрет на удаление </label>
                            </section>
                        @endif

                        @if (0)
                            <section class="col col-lg-6">
                                <label class="control-label"> Шаблон блока </label>
                                {{ Form::select('template', array('Выберите...')+$templates, NULL, array('class' => 'form-control')) }}
                            </section>
                        @endif

                    </fieldset>

                </div>
            </div>

            @if (count($locales) > 1)

                <div class="widget-body" style="">
                    <ul id="myTab2" class="nav nav-tabs bordered" role="tablist">
                        <? $i = 0; ?>
                        @foreach ($locales as $locale_sign => $locale_name)
                            <li class="{{ !$i++ ? 'active' : '' }}">
                                <a href="#block_meta_{{ $locale_sign }}" class="modaltablink" data-toggle="tab">
                                    {{ $locale_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div id="myTabContent2" class="tab-content padding-10">
                        <? $i = 0; ?>
                        @foreach ($locales as $locale_sign => $locale_name)
                            <div class="tab-pane fade{{ !$i++ ? ' active in' : '' }}" id="block_meta_{{ $locale_sign }}">

                                @include($module['tpl'].'_block_meta', compact('locale_sign', 'locale_name', 'templates', 'element'))

                            </div>
                        @endforeach
                    </div>
                </div>

            @else

                @foreach ($locales as $locale_sign => $locale_name)
                    @include($module['tpl'].'_block_meta', compact('locale_sign', 'locale_name', 'templates', 'element', 'block_templates'))
                @endforeach

            @endif

        </div>
        <div class="modal-footer margin-top-5">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                Закрыть
            </button>
            <button type="submit" class="btn btn-primary btn-form-submit">
                Сохранить
            </button>
        </div>
    </div>
    <!-- /.modal-content -->
</div><!-- /.modal-dialog -->
{{ Form::close() }}