<?php

class AdminGalleriesController extends BaseController {

    public static $name = 'galleries';
    public static $group = 'galleries';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;

        Route::post('admin/gallery/ajax-order-save', array('as' => 'gallery.order', 'uses' => $class."@postAjaxOrderSave"));

        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
        	Route::get($class::$group.'/manage', array('uses' => $class.'@getIndex'));
        	Route::controller($class::$group, $class);
        });
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
    }

    ## Extended Form elements of module
    public static function returnExtFormElements() {

        $mod_tpl = static::returnTpl();
        $class = __CLASS__;

        ##
        ## EXTFORM GALLERY
        ##
        /*
        ################################################
        ## Process gallery
        ################################################
        if (Allow::action('admin_galleries', 'edit')) {
            ExtForm::process('gallery', array(
                'module'  => self::$name,
                'unit_id' => $id,
                'gallery' => Input::get('gallery'),
            ));
        }
        ################################################
        */
    	ExtForm::add(
            ## Name of element
            "gallery",
            ## Closure for templates (html-code)
            function($name = 'gallery', $value = '', $params = null) use ($mod_tpl, $class) {
                ## default template
                $tpl = "extform_gallery";
                ## custom template
                if (@$params['tpl']) {
                    $tpl = $params['tpl'];
                    unset($params['tpl']);
                }

                #Helper::dd($value);
                #Helper::dd($params);

                if (!is_object($value))
                    $gallery = NULL;

                if ( $value === false || $value === null ) {
                    $val = Form::text($name);
                    preg_match("~value=['\"]([^'\"]+?)['\"]~is", $val, $matches);
                    #Helper::d($matches);
                    $val = (int)@$matches[1];
                    #Helper::tad($val);
                    if ( $val > 0 ) {
                        $gallery = Gallery::where('id', $val)->with('photos')->first();
                        #Helper::tad($gallery);
                    }
                } elseif (is_numeric($value)) {
                    $gallery = Gallery::where('id', $value)->with('photos')->first();
                }

                #Helper::tad($gallery);

                ## return view with form element
                return View::make($mod_tpl.$tpl, compact('name', 'value', 'gallery', 'params'))->render();
    	    },
            ## Processing results closure
            function($params) use ($mod_tpl, $class) {

                #dd($params);

                $module          = isset($params['module']) ? $params['module'] : false;
                $unit_id         = isset($params['unit_id']) ? $params['unit_id'] : false;
                $gallery         = isset($params['gallery']) ? $params['gallery'] : false;

                $gallery_id      = isset($gallery['gallery_id']) ? $gallery['gallery_id'] : 0;
                $uploaded_images = isset($gallery['uploaded_images']) ? $gallery['uploaded_images'] : array();
                $module = (string)trim($module);
                $unit_id = (string)trim($unit_id);

                if (@$params['single']) {

                    $gallery_id = $class::moveImagesToGallery($uploaded_images, $gallery_id);
                    if ($gallery_id)
                        $class::renameGallery($gallery_id, $module . " - " . $unit_id);

                } else {

                    ## Perform all actions for adding photos to the gallery & bind gallery to the unit_id of module
                    $gallery_id = $class::imagesToUnit($uploaded_images, $module, $unit_id, $gallery_id);
                }

                #Helper::dd($gallery_id);

                return $gallery_id;
            }
        );

        ##
        ## EXTFORM IMAGE
        ##
        /*
        ################################################
        ## Process image
        ################################################
        if (Allow::action('admin_galleries', 'edit')) {
            $input['image_id'] = ExtForm::process('image', array(
                                    'image' => Input::get('image'),
                                    'return' => 'id',
                                ));
        }
        ################################################
        */
    	ExtForm::add(
            ## Name of element
            "image",
            ## Closure for templates (html-code)
            function($name = 'image', $value = '', $params = null) use ($mod_tpl, $class) {
                ## default template
                $tpl = "extform_image";
                ## custom template
                if (@$params['tpl']) {
                    $tpl = $params['tpl'];
                    unset($params['tpl']);
                }

                #dd($value);
                #Helper::dd($params);

                if (!is_object($value))
                    $photo = NULL;

                if ( $value === false || $value === null ) {
                    $val = Form::text($name);
                    preg_match("~value=['\"]([^'\"]+?)['\"]~is", $val, $matches);
                    #Helper::d($matches);
                    $val = (int)@$matches[1];
                    #Helper::tad($val);
                    if ( $val > 0 ) {
                        #$photo = Photo::firstOrNew(array('id' => $val));
                        $photo = Photo::find($val);
                        #Helper::tad($photo);
                    }
                } elseif (is_numeric($value)) {
                    $photo = Photo::find($value);
                }

                #Helper::tad($value);
                #Helper::tad($photo);

                ## return view with form element
                return View::make($mod_tpl.$tpl, compact('name', 'value', 'photo', 'params'));
    	    },
            ## Processing results closure
            function($params) use ($mod_tpl, $class) {

                #Helper::dd($params);

                ## Array with POST-data
                $image = @$params['image'] ?: false;
                ## Return format
                $return = @$params['return'] ?: false;
                ## ID of uploaded image
                #$uploaded_image = isset($image['uploaded_image']) ? (int)$image['uploaded_image'] : false;
                $uploaded_image = $image;

                #Helper::dd($uploaded_image);

                if (!$uploaded_image)
                    return false;

                ## Find photo by ID
                $photo = Photo::where('id', $uploaded_image)->first();
                ## If photo don't exists - return false
                if (is_null($photo))
                    return false;
                ## Mark photo as "single used image"
                $photo->gallery_id = 0;
                $photo->save();
                ## Return needable property or full object
                return $return ? @$photo->$return : $photo;
            }
        );

    }
    
    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
        return array(
        	'view'   => 'Просмотр',
        	'create' => 'Создание',
        	'edit'   => 'Редактирование',
        	'delete' => 'Удаление',
        );
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        return array(
        	'name' => self::$name,
        	'group' => self::$group,
        	'title' => 'Галереи',
            'visible' => 1,
        );
    }

    ## Menu elements of the module
    public static function returnMenu() {
        return array(
            array(
            	'title' => 'Галереи',
                'link' => self::$group,
                'class' => 'fa-picture-o',
                'permit' => 'view',
            ),
        );
    }

    /****************************************************************************/
    
	public function __construct(){
		
		$this->beforeFilter('galleries');
        $this->locales = Config::get('app.locales');

        View::share('module_name', self::$name);

        $this->tpl = static::returnTpl('admin');
        $this->gtpl = static::returnTpl();
        View::share('module_tpl', $this->tpl);
        View::share('module_gtpl', $this->gtpl);
	}
	
	public function getIndex(){
		
		$galleries = Gallery::orderBy('id', 'desc')->get();
		return View::make($this->tpl.'index', compact('galleries'));
	}

    /****************************************************************************/

	public function postCreate(){
		
		$input = Input::all();
		$validation = Validator::make($input, Gallery::getRules());
		if($validation->fails()) {
			return Response::json($validation->messages()->toJson(), 400);
		} else {
			$id = Gallery::create($input)->id;
			$href = link::to('admin/galleries/edit/'.$id);
			return Response::json($href, 200);
		}
	}

    /****************************************************************************/
    
	public function getEdit($id){
		
        $gallery = Gallery::where('id', $id)->with('photos')->first();



		return View::make($this->tpl.'edit', compact('gallery', 'bread'));
	}

    /****************************************************************************/

	public function postDelete(){
		
		$id = Input::get('id');
		$gallery = Gallery::find($id);

		@Rel_mod_gallery::where('gallery_id', $gallery->id)->delete();
		$deleted = $gallery->delete();

		if($deleted) {
			return Response::json('success', 200);
		} else {
			return Response::json('error', 400);
		}
	}

    /****************************************************************************/

	public function postUpload(){
		
        return $this->postAbstractupload();
	}

    ##
    ## Загрузка изображений в галерею и привязка галереи к сущности любого модуля
    ##
	public function postAbstractupload(){

        ## Upload gallery image
        $result = $this->uploadImage('file');

        ## Check response
		if($result['result'] == 'error') {
	        return Response::json($result, 400);
	        exit;
		}

        ## Get gallery
		$gallery_id = Input::get('gallery_id') ? (int)Input::get('gallery_id') : 0;

        ## Make photo object
		$photo = Photo::create(array(
			'name' => $result['filename'],
			'gallery_id' => $gallery_id,
		));

        ## All OK, return result
		$result['result'] = 'success';
		$result['image_id'] = $photo->id;
		$result['gallery_id'] = $gallery_id;
		$result['thumb'] = $photo->thumb();
		$result['full'] = $photo->full();
		return Response::json($result, 200);		
	}


    ##
    ## Загрузка одиночного изображения и пометка о его использовании
    ##
	public function postSingleupload($param_name = 'file', $return = 'ajax'){

        ## Upload gallery image
        $result = $this->uploadImage($param_name);

        ## Check response
		if($result['result'] == 'error') {
            if (Request::ajax())
	            return Response::json($result, 400);
            else
                return false;
		}

        ## Make photo object
		$photo = Photo::create(array(
			'name' => $result['filename'],
			'gallery_id' => 0,
            'title' => '',
		));

        ## All OK, return result
		$result['result'] = 'success';
		$result['image_id'] = $photo->id;
		$result['gallery_id'] = -1;
		$result['thumb'] = $photo->thumb();
		$result['full'] = $photo->full();

        if (Request::ajax() && $return == 'ajax')
            return Response::json($result, 200);
        else
            return $photo;
	}


    ##
    ## Общая функция загрузки изображений
    ##
    private function uploadImage($input_file_name = 'file') {

		$result = array('result' => 'error');

        ## Check data
        if (is_string($input_file_name)) {
            if(!Input::hasFile($input_file_name)) {
                $result['desc'] = 'No input file.';
                return $result;
            }
            $file = Input::file($input_file_name);
        } elseif (is_object($input_file_name)) {
            $file = $input_file_name;
        }

        $rules = array(
        	'file' => 'image'
	    );	 
	    $validation = Validator::make(array('file' => $file), $rules);
	    if ($validation->fails()){
	    	$result['desc'] = 'This extension is not allowed.';
	        return $result;
	    }

        ## Check upload & thumb dir
		$uploadPath = Config::get('site.galleries_photo_dir');
		$thumbsPath = Config::get('site.galleries_thumb_dir');

		if(!File::exists($uploadPath))
			File::makeDirectory($uploadPath, 0777, TRUE);
		if(!File::exists($thumbsPath))
			File::makeDirectory($thumbsPath, 0777, TRUE);

        ## Generate filename
        srand((float)microtime() * 1000000);
		$fileName = time() . "_" . rand(1000, 1999) . '.' . $file->getClientOriginalExtension();

        ## Get images resize parameters from config
		$thumb_size = Config::get('site.galleries_thumb_size');
		$photo_size = Config::get('site.galleries_photo_size');

        ## Get image width & height
        $image = ImageManipulation::make($file->getRealPath());
        $w = $image->width();
        $h = $image->height();

        if ($thumb_size > 0) {
            ## Normal resize
            $thumb_resize_w = $thumb_size;
            $thumb_resize_h = $thumb_size;
        } else {
            ## Resize "by the smaller side"
            $thumb_size = abs($thumb_size);
            ## Resize thumb & full-size images "by the smaller side".
            ## Declared size will always be a minimum.
            $thumb_resize_w = ($w > $h) ? null : $thumb_size;
            $thumb_resize_h = ($w > $h) ? $thumb_size : null;
        }
        ## Resize thumb image
        $thumb_upload_success = ImageManipulation::make($file->getRealPath())
                                                ->resize($thumb_resize_w, $thumb_resize_h, function($constraint){
                                                    $constraint->aspectRatio();
                                                    $constraint->upsize();
                                                })
                                                ->save($thumbsPath.'/'.$fileName);

        if ($photo_size > 0) {
            ## Normal resize
            $image_resize_w = $photo_size;
            $image_resize_h = $photo_size;
        } else {
            ## Resize "by the smaller side"
            $photo_size = abs($photo_size);
            ## Resize full-size images "by the smaller side".
            ## Declared size will always be a minimum.
            $image_resize_w = ($w > $h) ? null : $photo_size;
            $image_resize_h = ($w > $h) ? $photo_size : null;
        }
        ## Resize full-size image
		$image_upload_success = ImageManipulation::make($file->getRealPath())
                                                ->resize($image_resize_w, $image_resize_h, function($constraint){
                                                    $constraint->aspectRatio();
                                                    $constraint->upsize();
                                                })
                                                ->save($uploadPath.'/'.$fileName);

		if (!$thumb_upload_success || !$image_upload_success) {
	    	$result['desc'] = 'Error on the saving images.';
	        return $result;
		}

    	$result['result'] = 'success';
    	$result['filename'] = $fileName;
        
        return $result;
    }

	public function postPhotodelete() {

        $model = NULL;
		$id = (int)Input::get('id');
        if ($id)
            $model = Photo::find($id);

        if (!$id || is_null($model) || !$model)
            return 'false';

        $db_delete = $model->delete();

		if(@$db_delete) {
			$file_delete = File::delete(Config::get('site.galleries_photo_dir').'/'.$model->name);
			$thumb_delete = File::delete(Config::get('site.galleries_thumb_dir').'/'.$model->name);
		}

		#if(@$db_delete && @$file_delete && @$thumb_delete) {
		#if(@$db_delete) {
			#return Response::json('success', 200);
			return 'success';
		#} else {
		#	return Response::json('error', 400);
		#}
	}

	public function postPhotoupdate() {

        //App::abort(404);

        $model = NULL;
		$id = (int)Input::get('id');
		$title = (string)Input::get('title');
        if ($id)
            $model = Photo::find($id);

        if (!$id || is_null($model) || !$model)
            return 'false';

        $title = str_replace(["\r", "\n"], ' ', $title);
        $title = str_replace('  ', ' ', $title);
        $model->update([
            'title' => $title,
        ]);

        return 'true';
	}

    /****************************************************************************/

    ##
    ## USE imagesToUnit()!
    ##
	public static function moveImagesToGallery($images = array(), $gallery_id = false) {

		if ( !isset($images) || !is_array($images) || !count($images) )
			return $gallery_id;

        ## Find gallery
        $gallery = $gallery_id ? Gallery::find($gallery_id) : null;

        ## If gallery not found - create her
		if (!$gallery) {
			$gallery = Gallery::create(array(
				'name' => 'noname',
			));
		}
 
        ## Get gallery ID
        $gallery_id = $gallery->id;

        ## Move all images to gallery
		foreach ($images as $i => $img_id) {
			$img = Photo::find($img_id);
			if (@$img) {
				$img->gallery_id = $gallery_id;
				#print_r($img);
				$img->save();
			}
		}

		return $gallery_id;
	}

    ##
    ## USE imagesToUnit()!
    ##
	public static function relModuleUnitGallery($module = '', $unit_id = 0, $gallery_id = 0) {

		if ( !@$module || !$unit_id || !$gallery_id )
			return false;

		$rel = Rel_mod_gallery::where('module', $module)->where('unit_id', $unit_id)->where('gallery_id', $gallery_id)->first();

		if (!is_object($rel) || !@$rel->id) {
			$rel = Rel_mod_gallery::create(array(
				'module' => $module,
				'unit_id' => $unit_id,
				'gallery_id' => $gallery_id,
			));
		}

        self::renameGallery($gallery_id, $module . " - " . $unit_id);

		return $rel->id;
	}

    public static function renameGallery($gallery_id = false, $name = false) {
        if ( !$gallery_id || !$name )
            return false;

        $gallery = Gallery::find($gallery_id);
        if (is_object($gallery)) {
            $gallery->name = $name;
            $gallery->save();
            return true;
        }

        return false;
    }

	public static function imagesToUnit($images = array(), $module = '', $unit_id = 0, $gallery_id = false) {

		if (
			!isset($images) || !is_array($images) || !count($images)
			|| !@$module || !$unit_id
		)
			return $gallery_id;

		$gallery_id = self::moveImagesToGallery($images, $gallery_id);
		self::relModuleUnitGallery($module, (int)$unit_id, $gallery_id);


		return $gallery_id;
	}


    public function postAjaxOrderSave() {

        $poss = Input::get('poss');
        $pls = Photo::whereIn('id', $poss)->get();
        if ( $pls ) {
            foreach ( $pls as $pl ) {
                $pl->order = array_search($pl->id, $poss);
                $pl->save();
            }
        }
        return Response::make('1');
    }

}


