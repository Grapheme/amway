<?php
$fields = $block_templates_full[$element->template]['fields'];
#$content = $element->content;
$meta = isset($element['metas'][$locale_sign]) ? $element['metas'][$locale_sign] : null;
#Helper::ta($meta);
if (is_object($meta))
    $content = json_decode($meta->content, true);
#Helper::ta($content);
?>

<?php ######################################################################################## ?>

{{--{{ Helper::ta($fields) }}--}}

<div class="smart-form">

    @if (isset($fields) && is_array($fields) && count($fields))

        @foreach($fields as $field_name => $field)
            <?
            $value = isset($content[$field_name]) ? $content[$field_name] : null;
            ?>

            <section>
                @if (!@$field['no_label'] && isset($field['title']))
                    <label class="label">{{ @$field['title'] }}&nbsp;</label>
                @endif
                @if (@$field['first_note'])
                    <label class="note first_note">{{ @$field['first_note'] }}</label>
                @endif
                <div class="input {{ @$field['type'] }} {{ @$field['label_class'] }}">
                    {{ Helper::formField('locales[' . $locale_sign . '][content][' . $field_name . ']', $field, $value, NULL, $field_name) }}
                </div>
                @if (@$field['second_note'])
                    <label class="note second_note">{{ @$field['second_note'] }}</label>
                @endif
            </section>

        @endforeach

    @endif

</div>

<?php ######################################################################################## ?>

<script>
    // Activate dropzone
    _global_activate_dropzone();
</script>
