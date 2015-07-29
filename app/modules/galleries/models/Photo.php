<?php

class Photo extends Eloquent {

	protected $guarded = array();

	protected $table = 'photos';

	public static $order_by = 'photos.id DESC';

	public function thumb() {
		#return link::to(Config::get('site.galleries_thumb_dir')) . "/" . $this->name;
		return URL::to(Config::get('site.galleries_thumb_public_dir') . "/" . $this->name);
	}

	public function full() {
		return $this->path();
	}

	public function path() {
		#return link::to(Config::get('site.galleries_photo_dir')) . "/" . $this->name;
		return URL::to(Config::get('site.galleries_photo_public_dir') . "/" . $this->name);
	}

    public function thumbpath() {
        #return link::to(Config::get('site.galleries_photo_dir')) . "/" . $this->name;
        return str_replace('//', '/', public_path(Config::get('site.galleries_thumb_public_dir') . "/" . $this->name));
    }

    public function fullpath() {
        #return link::to(Config::get('site.galleries_photo_dir')) . "/" . $this->name;
        return str_replace('//', '/', public_path(Config::get('site.galleries_photo_public_dir') . "/" . $this->name));
    }

    public function is_correct() {
        $exists_thumb = file_exists($this->thumbpath());
        $exists_full  = file_exists($this->fullpath());
        return (bool)($exists_thumb && $exists_full);
    }

    public function extract() {
        return $this;
    }

	public function cachepath($w, $h, $method = 'crop') {
		return URL::to(Config::get('site.galleries_cache_public_dir') . "/" . $this->id . "_" . $w . "x" . $h . ($method == 'resize' ? 'r' : '') . ".png");
	}

	public function fullcachepath($w, $h, $method = 'crop') {
		return str_replace('//', '/', public_path(Config::get('site.galleries_cache_public_dir') . "/" . $this->id . "_" . $w . "x" . $h . ($method == 'resize' ? 'r' : '') . ".png"));
	}



    public static function upload($url, $gallery = NULL, $title = '') {

        $img_data = @file_get_contents($url);
        if (!$img_data)
            return false;

        $tmp_path = storage_path(md5($url));
        try {

            file_put_contents($tmp_path, $img_data);

        } catch(Exception $e) {
            echo 'Error #' . $e->getCode() . ':' . $e->getMessage() . "<br/>\n";
            echo 'In file: ' . $e->getFile() . ' (' . $e->getLine() . ')';
            die;
        }
        $file = (new \Symfony\Component\HttpFoundation\File\UploadedFile($tmp_path, basename($url)));

        ## Check upload & thumb dir
        $uploadPath = Config::get('site.galleries_photo_dir');
        $thumbsPath = Config::get('site.galleries_thumb_dir');

        if(!File::exists($uploadPath))
            File::makeDirectory($uploadPath, 0777, TRUE);
        if(!File::exists($thumbsPath))
            File::makeDirectory($thumbsPath, 0777, TRUE);

        ## Generate filename
        $fileName = time() . "_" . rand(1000, 1999) . '.' . $file->getClientOriginalExtension();

        #echo $fileName;

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


        ## Delete original file
        unlink($file->getRealPath());


        ## Gallery - none, existed, new
        $gallery_id = 0;

        if (is_int($gallery) && $gallery > 0) {

            $gallery_id = $gallery;

        } elseif (is_string($gallery)) {

            $gal = Gallery::create(['name' => $gallery]);
            $gallery_id = $gal->id;
        }

        ## Create MySQL record
        $photo = Photo::create(array(
            'name' => $fileName,
            'gallery_id' => $gallery_id,
            'title' => $title,
        ));

        return $photo;
    }


    public function full_delete() {

        ## Check upload & thumb dir
        $uploadPath = Config::get('site.galleries_photo_dir');
        $thumbsPath = Config::get('site.galleries_thumb_dir');
        $cachePath = public_path(Config::get('site.galleries_cache_public_dir'));

        @unlink($uploadPath . '/' . $this->name);
        @unlink($thumbsPath . '/' . $this->name);

        $cache_files = glob($cachePath . '/' . $this->id . "_*");
        foreach ($cache_files as $cache_file)
            @unlink($cache_file);

        $this->delete();

        return true;
    }

}