<?php ######################################################################################## ?>

<span class="pull-right" style='position:relative; top:8px; left:-2px; z-index:0'>
        <button type="reset" class="btn btn-warning btn-sm pull-left2" id="reset_block_content">
            <i class="fa fa-warning"></i> Отменить изменения
        </button>

        <a href="#" class='btn btn-default btn-sm pages_block_redactor_toggle'>
            Редактор вкл./выкл.
        </a>
    </span>
<label class="control-label margin-top-10"> Содержимое блока </label>
{{ Form::textarea('locales[' . $locale_sign . '][content]', ($block_meta ? ($block_meta->content) : FALSE), array('class' => 'form-control redactor-no-filter  redactor_250 editor_block_content editor_locale_' . $locale_sign . '', 'placeholder' => 'Содержимое блока', 'style' => 'position:relative; z-index:0') ) }}

<div id="default_block_content" style="display:none;">{{ $block_meta ? ($block_meta->content) : FALSE }}</div>

<?php ######################################################################################## ?>

@if (@$element->settings['editor_state'])
    <script>
        //$('.pages_block_redactor_toggle').trigger('click');
        var element = $('.editor_locale_{{ $locale_sign }}');
        activate_block_editor(element)
    </script>
@endif