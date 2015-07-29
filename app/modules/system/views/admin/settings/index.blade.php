@extends(Helper::acclayout())


@section('content')

    @include($module['tpl'].'/menu')

    @if (Input::get('debug') == 1)
        {{ Helper::ta($settings_values) }}
    @endif

    @if(0 < ($sections_count = count($settings_sections)))

        {{ Form::open(array('url' => URL::route('system.settings.update'), 'class' => 'smart-form', 'id' => $module['entity'].'-form', 'role' => 'form', 'method' => 'POST', 'files' => TRUE)) }}

        <ul class="nav nav-tabs bordered">
            <? $i = 0; ?>
            @foreach($settings_sections as $section_slug => $section)
                <?
                $options_count = isset($section['options']) && is_array($section['options']) ? count($section['options']) : 0;
                if ($options_count == 0 || ($only_section && $only_section != $section_slug))
                    continue;
                ?>
                <li class="{{ !$i++ ? 'active' : '' }}">
                    <a href="#section_{{ $section_slug }}" data-toggle="tab">
                        {{ @$section['title'] ?: $section_slug }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content">
            <? $i = 0; ?>
            @foreach($settings_sections as $section_slug => $section)
                <?
                $options_count = isset($section['options']) && is_array($section['options']) ? count($section['options']) : 0;
                if ($options_count == 0 || ($only_section && $only_section != $section_slug))
                    continue;
                ?>
                <div class="tab-pane fade {{ !$i++ ? 'active in' : '' }} clearfix" id="section_{{ $section_slug }}">
                    @if(isset($section['options']) && is_array($section['options']) && 0 < ($options_count = count($section['options'])))
                        <fieldset>

                            @if (isset($section['description']))
                                <p class="alert alert-warning fade in padding-10 margin-bottom-15">
                                    {{ $section['description'] }}
                                </p>
                            @endif

                            @foreach($section['options'] as $field_name => $field)

                                <section>
                                    @if (@$field['group_title'])
                                        <{{ @$field['block'] ?: 'h3' }} style="{{ @$field['style'] ?: 'margin:20px 0 5px 0' }}">{{ $field['group_title'] }}</{{ @$field['block'] ?: 'h3' }}>
                                    @else
                                        @if (!@$field['no_label'])
                                            <label class="label">{{ @$field['title'] }}&nbsp;</label>
                                        @endif
                                        @if (isset($field['first_note']))
                                            <label class="note first_note">{{ $field['first_note'] }}</label>
                                        @endif
                                        <div class="input {{ @$field['type'] }} {{ @$field['label_class'] }}">
                                            {{ Helper::formField('settings[' . $section_slug . '][' . $field_name . ']', $field, @$settings_values[$section_slug][$field_name], FALSE) }}
                                        </div>
                                        @if (isset($field['second_note']))
                                            <label class="note second_note">{{ $field['second_note'] }}</label>
                                        @endif
                                    @endif
                                </section>

                            @endforeach
                        </fieldset>
                    @endif
                </div>
            @endforeach
        </div>

        <div style="position:fixed; right:15px; top:60px;">
            <button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
                <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сохранить</span>
            </button>
        </div>

        {{ Form::close() }}

        <div class="clear"></div>

    @else

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="ajax-notifications custom">
                    <div class="alert alert-transparent">
                        <h4>Список пуст</h4>

                        <p><br><i class="regular-color-light fa fa-th-list fa-3x"></i></p>
                    </div>
                </div>
            </div>
        </div>

    @endif

    <div class="clear"></div>

@stop


@section('scripts')
    <script>
        var essence = '{{ $module['entity'] }}';
        var essence_name = 'запись';
        var validation_rules = {
            name: {required: true}
        };
        var validation_messages = {
            name: {required: 'Укажите название'}
        };
    </script>

    {{ HTML::script('private/js/modules/standard.js') }}

    <script type="text/javascript">
        if (typeof pageSetUp === 'function') {
            pageSetUp();
        }
        if (typeof runFormValidation === 'function') {
            loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}", runFormValidation);
        } else {
            loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}");
        }
    </script>

    {{ HTML::script('private/js/vendor/redactor.min.js') }}
    {{ HTML::script('private/js/system/redactor-config.js') }}

@stop

