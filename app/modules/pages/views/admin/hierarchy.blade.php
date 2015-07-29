@extends(Helper::acclayout())


<?
function write_level($hierarchy, $elements, $sortable, &$pages2, $first_level = false) {
global $total_elements;
global $pages2_output_marker;
#dd($hierarchy);
?>
@if($count = @count($elements))
    <ol class="dd-list">
        @if ($hierarchy !== null && $hierarchy !== false && @count($hierarchy))
            @foreach($hierarchy as $h)
                <?
                #Helper::d($h); continue;
                #if (!isset($h['id']))
                #    continue;
                if (!isset($elements[$h['id']]))
                    continue;

                $element = $elements[$h['id']];

                unset($pages2[$h['id']]);

                $line = $element->name;
                $line = preg_replace("~<br[/ ]*?".">~is", ' ', $line);
                $line2 = $element->slug;
                ?>

                <li class="dd-item dd3-item dd-item-fixed-height" data-id="{{ $element->id }}">
                    @if ($sortable > 0)
                        <div class="dd-handle dd3-handle">
                            Drag
                        </div>
                    @endif
                    <div class="dd3-content{{ $sortable > 0 ? '' : ' padding-left-15 padding-top-10' }} clearfix">

                        <div class="dicval-lines">
                            {{ $line }}
                            <br/>
                            <span class="note dicval_note">
                                {{ $line2 }}
                            </span>
                        </div>


                    </div>
                    @if (isset($h['children']) && is_array($h['children']) && count($h['children']))
                        <?
                        /**
                         * Вывод дочерних элементов
                         */
                        write_level($h['children'], $elements, $sortable, $pages2);
                        #Helper::dd($h['children']);
                        ?>
                    @endif
                </li>
            @endforeach
        @endif

        <?
        if ($first_level)
            write_unhierarchical_pages($pages2);
        ?>

    </ol>

@endif
<?
}

function write_unhierarchical_pages($pages2) {
    if (isset($pages2) && count($pages2)) {
        foreach ($pages2 as $element) {

            $line = $element->name;
            $line = preg_replace("~<br[/ ]*?".">~is", ' ', $line);
            $line2 = $element->slug;
?>
    <li class="dd-item dd3-item dd-item-fixed-height" data-id="{{ $element->id }}">
        <div class="dd-handle dd3-handle">
            Drag
        </div>
        <div class="dd3-content clearfix">
            <div class="dicval-lines">
                {{ $line }}
                <br/>
                <span class="note dicval_note">
                    {{ $line2 }}
                </span>
            </div>
        </div>
    </li>
<?
        }
    }
}
?>

@section('content')

    @include($module['tpl'].'/menu')

    <?
    #global $pages2;
    #dd($pages2);
    ?>

	@if($pages->count())


        <div class="dd dicval-list" data-output="#nestable-output">

{{--            {{ $pages->count() }}--}}

            <?php
            write_level($hierarchy, $pages, $sortable, $pages2, true);
            ?>

{{--            {{ $pages2->count() }}--}}

            <?php
            #write_unhierarchical_pages($pages2);
            ?>
        </div>


    @else

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="ajax-notifications custom">
                    <div class="alert alert-transparent">
                        <h4>Список пуст</h4>
                    </div>
                </div>
            </div>
        </div>

	@endif

    <div class="clear"></div>

@stop


@section('scripts')
	<script type="text/javascript">
		if(typeof pageSetUp === 'function'){pageSetUp();}
		if(typeof runFormValidation === 'function'){
			loadScript("{{asset('private/js/vendor/jquery-form.min.js');}}",runFormValidation);
		}else{
			loadScript("{{asset('private/js/vendor/jquery-form.min.js');}}");
		}
	</script>

    <script>
        $(document).ready(function() {

            var updateOutput = function(e) {

                show_hide_delete_buttons();

                var list = e.length ? e : $(e.target), output = $(list.data('output'));
                if (window.JSON) {
                    var data = window.JSON.stringify(list.nestable('serialize'));
                    $.ajax({
                        url: "{{ URL::route('pages.nestedsetmodel') }}",
                        type: "post",
                        data: { data: data },
                        success: function(jhr) {
                            //console.clear();
                            //console.log(jhr);
                        }
                    });
                    output.val(data);
                } else {
                    output.val('JSON browser support required for this demo.');
                }
            };

            //updateOutput($('.dd.dicval-list').data('output', $('#nestable-output')));

            $('.dd.dicval-list').nestable({
                maxDepth: {{ (int)$sortable }},
                group: 1
            }).on('change', updateOutput);

            function show_hide_delete_buttons() {
                $('.dd-item > button:first-child').parent().find('.dd3-content:first .dicval-actions .dicval-actions-delete').hide();
                $('.dd-item > div:first-child').parent().find('.dd3-content:first .dicval-actions .dicval-actions-delete').show();
            }

            show_hide_delete_buttons();

        });
    </script>

@stop
