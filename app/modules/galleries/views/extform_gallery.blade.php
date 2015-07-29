
    <!-- Don't forget add to parent page to scripts section JS code for correct functionality, some like this: -->
    <!-- loadScript("/js/modules/gallery.js"); -->


	<div class="egg-dropzone dropzone" data-name="{{ $name }}"<? if (isset($params) && is_array($params) && count($params)) { foreach($params as $key => $value) { echo ' data-' . $key . '="' . $value . '"'; } } ?>></div>
    <div class="superbox_" style="margin-top:10px;">

    	<input type="hidden" name="{{ $name }}[gallery_id]" value="{{ (isset($gallery) && is_object($gallery)) ? (int)$gallery->id : '0' }}" id="{{ $name }}_gallery_id" />

        @if (@is_object($gallery))
            <? $gallery_photos = $gallery->photos()->get(); ?>
            @if ($gallery_photos)
                <div class="sortable photo-previews">
                    <?
                    $bad_photos = [];
                    ?>
                    @foreach ($gallery_photos as $photo)
                        <?
                        if (!$photo->is_correct()) {
                            $bad_photos[] = $photo->id;
                            continue;
                        }
                        ?>

                        <div id="image-{{ $photo->id }}" class="modal fade not-sortable" tabindex="-1" role="dialog" aria-labelledby="image-{{ $photo->id }}Label" aria-hidden="true">
                            <div class="modal-dialog modal-lg block-center text-center">
                                    <img src="{{ URL::to($photo->path()) }}" style="max-width:100%">
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="display-inline-block image-data-popover" rel="popover" data-placement="top" data-original-title="<i class='fa fa-fw fa-pencil'></i> Изображение" data-content="
<div class='image-data' style='min-width:230px'>
    <div class='textarea'><textarea class='image-data-field' data-name='title' placeholder='Описание изображения...'></textarea></div>
    <div class='note color-red image-save-data-error'></div>
    <div class='form-actions text-center'>
        <a href='{{ URL::to($photo->path()) }}' data-toggle='modal' data-target='#image-{{ $photo->id }}' target='_blank' title='Полноразмерное изображение' class='btn btn-default btn-sm image-data-preview-link'>Просмотр</a>
        &nbsp;
        <span class='btn btn-primary btn-sm save-image-data' data-photo-id='{{ $photo->id }}'>Сохранить</span>
    </div>
</div>" data-image-title="{{ $photo->title }}" data-html="true">

                            <span class="photo-preview sortable_item" data-id="{{ $photo->id }}" style="background-image:url({{ URL::to($photo->thumb()) }});">

                                {{--<a href="{{ URL::to($photo->path()) }}" target="_blank" title="Полноразмерное изображение" style="display:block; height:100%; color:#090; background:transparent; display:none"></a>--}}

                                <span href="#" class="photo-delete" data-photo-id="{{ $photo->id }}" style="">Удалить</span>
                            </span>

                        </a>


                    @endforeach
                </div>
            @endif
        @endif

    </div>
    <div class="clear"></div>
<?
#/*
if (isset($bad_photos) && is_array($bad_photos) && count($bad_photos) && Input::get('delete_bad_photos') == 1) {
    #Photo::whereIn('id', $bad_photos)->full_delete();
    $photos = Photo::whereIn('id', $bad_photos)->get();
    foreach ($photos as $photo)
        $photo->full_delete();
}
#*/
?>