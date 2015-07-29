<?php

class GalleriesController extends BaseController {

    public static $name = 'galleries_public';
    public static $group = 'galleries';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;

        if (NULL !== ($galleries_cache_public_dir = Config::get('site.galleries_cache_public_dir'))) {
            Route::get(
                $galleries_cache_public_dir . '/{image_id}_{w}x{h}{method?}.png',
                array(
                    'as' => 'image.resize',
                    'uses' => $class."@getImageResize"
                )
            )
                ->where(array(
                    'image_id' => '\d+',
                    'w' => '\d+',
                    'h' => '\d+',
                    #'method' => '[^\d]+'
                ));
            ;
        }
    }
    
    ## Shortcodes of module
    public static function returnShortCodes() {

        $tpl = static::returnTpl();

    	shortcode::add("gallery",
        
            function($params = null) use ($tpl) {

                $default = array(
                    'tpl' => "gallery-default",
                );
                $params = array_merge($default, (array)$params);

                if(empty($params['tpl']) || !View::exists($tpl.$params['tpl'])) {
                    throw new Exception('Template not found: ' . $tpl.$params['tpl']);
                }

                if (!isset($params['id'])) {
                    #return false;
                    #return "Error: id of gallery is not defined!";
                    throw new Exception('ID of gallery is not defined');
                }

                $gallery_id = $params['id'];

                $gallery = Gallery::where('id', $gallery_id)->first();

                if (!is_object($gallery) || !@$gallery->id) {
                    return false;
    	        	#return "Error: gallery #{$gallery_id} doesn't exist!";
                }
                
                $photos = $gallery->photos;
                
                if (!$photos->count()) {
                    return false;
                }
                
                #dd($tpl.$params['tpl']);
                #dd(compact($photos));
                
                #return View::make($tpl.$params['tpl'], compact($photos)); ## don't work
                return View::make($tpl.$params['tpl'], array('photos' => $photos));
    	    }
        );
        
    }
    
    /****************************************************************************/

	public function __construct(){
        ##
    }

    /**
     * Ссылка на изображение, подвергнутое кропу или ресайзу
     *
     * URL::route('image.resize', [$photo->id, 200, 200])
     * URL::route('image.resize', [$photo->id, 200, 200, 'r'])
     *
     * См. также /app/config/site.php
     * - galleries_cache_public_dir
     * - galleries_cache_allowed_sizes
     *
     * @param string $method Method of resize - crop or resize
     */
    public function getImageResize($image_id, $w, $h, $method = 'crop'){

        $image = $image_id ? Photo::find($image_id) : null;

        #Helper::tad($method);

        /**
         * Костылек-с
         */
        if (is_numeric($method)) {
            $h = $h*10+$method;
            #$method = 'crop';
        }

        if ($method == 'r')
            $method = 'resize';
        else
            $method = 'crop';

        #Helper::tad($method);

        /**
         * Соблюдены ли все правила?
         */
        if (
            !$image_id || !$image || !is_object($image) || !@file_exists($image->fullpath())
            || !$w || !$h || $w < 0 || $h < 0
            || !in_array($w . 'x' . $h, (array)Config::get('site.galleries_cache_allowed_sizes'))
            || !in_array($method, ['crop', 'resize'])
        )
            App::abort(404);

        /*
        Helper::ta($image_id . '_' . $w . 'x' . $h . $method . '.jpg');
        Helper::ta($image->fullpath());
        Helper::ta($image->fullcachepath($w, $h, $method));
        Helper::ta($image->cachepath($w, $h, $method));
        #Helper::tad($image->full());
        */

        if(!File::exists(Config::get('site.galleries_cache_dir')))
            File::makeDirectory(Config::get('site.galleries_cache_dir'), 0777, TRUE);

        $img = ImageManipulation::make($image->fullpath());
        if ($method == 'resize') {

            /**
             * Resize + Resize Canvas
             */
            $img->resize($w, $h, function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->resizeCanvas($w, $h);

            /*
            $newimg = ImageManipulation::canvas($w, $h);
            $newimg->insert($img, 'center');
            $img = $newimg;
            #$newimg->destroy();
            #*/

        } else {

            /**
             * Resize + Crop
             */

            /**
             * Текущие значения ширины и высоты
             */
            $rw = $img->width();
            $rh = $img->height();

            /**
             * Находим требуемые коэффициенты
             */
            $c1 = $rw/$w; ## Делим реальную ширину на желаемую
            $c2 = $rh/$h; ## Делим реальную высоту на желаемую

            /**
             * Если c1 < c2 - то ресайзить нужно по ширине
             * Если c1 > c2 - то ресайзить нужно по высоте
             */
            if ($c1 < $c2) {

                /**
                 * Ресайзим по меньшей стороне, по ширине
                 */
                $nw = $w;
                $nh = null;

            } else {

                /**
                 * Ресайзим по меньшей стороне, по высоте
                 */
                $nw = null;
                $nh = $h;
            }

            #Helper::tad($nw . 'x' . $nh);

            $img->resize($nw, $nh, function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            /**
             * Новые значения ширины и высоты, после ресайза по меньшей стороне
             */
            $nnw = $img->width();
            $nnh = $img->height();

            /**
             * Кроп по центру по заданным размерам
             */
            $img->crop($w, $h, ceil(($nnw-$w)/2), ceil(($nnh-$h)/2));
        }

        /**
         * Сохраняем изображение
         */
        $img->save($image->fullcachepath($w, $h, $method), 90);

        /**
         * Отдаем изображение в браузер
         */
        header('Debug-ImageSource: onthefly');
        return $img->response();
    }

}