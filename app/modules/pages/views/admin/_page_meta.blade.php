<?
#Helper::tad($element->metas->where('language', $locale_sign)->first());
#Helper::ta($element);
if (isset($element->metas[$locale_sign]))
    $element_meta = $element->metas[$locale_sign]; else
    $element_meta = new PageMeta;
/*
foreach ($element->metas as $tmp) {
    #Helper::ta($tmp);
    if ($tmp->language == $locale_sign) {
        $element_meta = $tmp;
        break;
    }
}
*/
?>
@if (count($locales) > 1)

    <section>
        <label class="label">Шаблон</label> <label class="input select input-select2">
            {{ Form::select('locales[' . $locale_sign . '][template]', array('По умолчанию')+$templates, $element_meta->template) }}
        </label>
    </section>

    <span></span>
    <br />

@endif

<?
#########################################################################
## FIELDS
#########################################################################

$fields_values = json_decode($element_meta->settings, true);
$fields_values = isset($fields_values['fields']) ? (array)$fields_values['fields'] : [];
#Helper::ta($fields_values);

$fields = Config::get('pages.fields');
if (isset($fields) && is_callable($fields))
    $fields = $fields();
if (is_array($fields) && count($fields)) {
foreach ($fields as $field_name => $field) {
    $field['_name'] = $field_name;
    $value = isset($fields_values[$field_name]) ? $fields_values[$field_name] : NULL;
    $element = NULL; ## need for check
?>
<section>
    @if (!@$field['no_label'] && isset($field['title']))
        <label class="label">{{ @$field['title'] }}&nbsp;</label>
    @endif
    @if (@$field['first_note'])
        <label class="note first_note">{{ @$field['first_note'] }}</label>
    @endif
    <div class="input {{ @$field['type'] }} {{ @$field['label_class'] }}">
        {{ Helper::formField('fields[' . $locale_sign . '][' . @$field_name . ']', $field, $value, $element, $field_name) }}
    </div>
    @if (@$field['second_note'])
        <label class="note second_note">{{ @$field['second_note'] }}</label>
    @endif
</section>
<?
    }
}
#########################################################################
?>

<?
$page_seo_params = Config::get('pages.seo');
?>
<div class="clearfix margin-bottom-10">
    {{ ExtForm::seo('locales['.$locale_sign.'][seo]', @$element->seos[$locale_sign], $page_seo_params) }}
</div>