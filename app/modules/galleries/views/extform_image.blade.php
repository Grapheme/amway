
    <!-- Don't forget add to parent page to scripts section JS code for correct functionality, some like this: -->
    <!-- loadScript("/js/modules/gallery.js"); -->

    <?
    $photo_exists = isset($photo) && is_object($photo) && $photo->id && file_exists($photo->fullpath());
    #Helper::dd($photo_exists);
    ?>
    @if ($photo_exists)
    	<input type="text" name="{{ $name }}" value="{{ $photo->id }}" class="uploaded_image_{{ $photo->id }} uploaded_image_cap" style="position:absolute; left:-10000px;" />
    @else
        <input type="text" name="{{ $name }}" value="" class="uploaded_image_false uploaded_image_cap" style="position:absolute; left:-10000px;" />
    @endif
    <div>
    	<div class="egg-dropzone-single dropzone" data-name="{{ $name }}" data-gallery_id="0"<? if (isset($params) && is_array($params) && count($params)) { foreach($params as $key => $value) { echo ' data-' . $key . '="' . $value . '"'; } } ?><? if ($photo_exists) { echo " style='display:none'";} ?>></div>
        <div class="superbox_ photo-preview-container" style="margin-top:10px;">

            <div class="photo-preview photo-preview-single" style="@if($photo_exists) background-image:url({{ URL::to($photo->thumb()) }}); @else display:none; @endif">
                <a href="{{ $photo_exists ? URL::to($photo->path()) : '#photo-path' }}" target="_blank" title="Полноразмерное изображение" style="display:block; height:100%; color:#090; background:transparent" data-toggle='modal' data-target='#image-{{ md5($name) }}'></a>
                <a href="#" class="photo-delete-single" data-photo-id="{{ $photo_exists ? $photo->id : '#photo-id' }}" style="">Удалить</a>

                <div id="image-{{ md5($name) }}" class="modal fade not-sortable" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg block-center text-center">
                        <img src="{{ $photo_exists ? URL::to($photo->path()) : '#' }}" style="max-width:100%">
                    </div>
                </div>

            </div>

        </div>
    </div>