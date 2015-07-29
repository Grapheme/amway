<?
#Helper::dd($params);
$seo = isset($value) && is_object($value) ? $value : new Seo;
?>
<div class="well">

    <header>SEO</header>

    <fieldset>

        @if (!is_array($params) || in_array('title', $params))
            <section>
                <label class="label">Title</label>
                <label class="input">
                    {{ Form::text($name.'[title]', $seo->title) }}
                </label>
            </section>
        @endif

        @if (!is_array($params) || in_array('description', $params))
            <section>
                <label class="label">Description</label>
                <label class="textarea">
                    {{ Form::textarea($name.'[description]', $seo->description, array('rows' => 4)) }}
                </label>
            </section>
        @endif

        @if (!is_array($params) || in_array('keywords', $params))
            <section>
                <label class="label">Keywords</label>
                <label class="textarea">
                    {{ Form::textarea($name.'[keywords]', $seo->keywords, array('rows' => 3)) }}
                </label>
            </section>
        @endif

        @if (!is_array($params) || in_array('h1', $params))
            <section>
                <label class="label">Заголовок H1</label>
                <label class="input">
                    {{ Form::text($name.'[h1]', $seo->h1) }}
                </label>
            </section>
        @endif

    </fieldset>

</div>

<style>
    .redactor_redactor_preview {
        height: 80px !important;
    }
    .redactor_redactor_content {
        height: 200px !important;
    }
</style>