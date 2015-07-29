<?php
if (isset($element) && is_object($element) && isset($element->metas) && count($element->metas) && isset($element->metas[$locale_sign])) {

    $block_meta = $element->metas[$locale_sign];

} else {

    $block_meta = null;
}

#Helper::ta($element);
#Helper::ta($block_meta);

$block_tpl = null;

$block_tpl_file = $module['tpl'].'_block_tpl_default';
/*
if ($element->template && View::exists(Helper::layout('blocks.' . $element->template))) {
    $block_tpl_file = Helper::layout('blocks.' . $element->template);
}
*/
$block_templates_full = Config::get('pages.block_templates');
if (is_callable($block_templates_full))
    $block_templates_full = $block_templates_full();
#Helper::ta($block_templates_full);
if ($element->template && isset($block_templates_full[$element->template])) {
    $block_tpl_file = $module['tpl'].'_block_tpl_custom';
}
#Helper::ta($block_tpl_file);
?>


<?php ######################################################################################## ?>

{{ View::make($block_tpl_file, compact('element', 'locale_sign', 'block_meta', 'block_templates', 'block_templates_full'))->render() }}

<?php ######################################################################################## ?>


@if (count($locales) > 1 && 0)

    <label class="control-label margin-top-10">
        <small>Шаблон языковой версии блока (необязательно)</small>
    </label>
    {{ Form::select('locales[' . $locale_sign . '][template]', array('По умолчанию')+$templates, NULL, array('class' => 'form-control')) }}

@endif