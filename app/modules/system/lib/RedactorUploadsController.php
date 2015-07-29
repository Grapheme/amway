<?php

class RedactorUploadsController extends BaseController {
	
	public function __construct(){
		##
	}

	public function redactorUploadedImages(){

        /*
        $uploadPath = public_path('uploads');
        if(!file_exists($uploadPath)):
            echo json_encode(array());
            exit;
        endif;
        */

        ## Pathes
        $uploadPath = Config::get('site.uploads_photo_dir');
        $thumbsPath = Config::get('site.uploads_thumb_dir');
        $uploadPathPublic = Config::get('site.uploads_photo_public_dir');
        $thumbsPathPublic = Config::get('site.uploads_thumb_public_dir');

        #Helper::dd($thumbsPath);

        #$fullList[0] = $fileList = array('thumb' => '', 'image'=> '', 'title' => 'Изображение', 'folder' => 'Миниатюры');
        $fullList = [];

        if(file_exists($thumbsPath) && is_dir($thumbsPath) && $listDir = scandir($thumbsPath)) {
            #Helper::dd($listDir);
            $index = 0;
            foreach ($listDir as $number => $file) {

                if ($file == '.' || $file == '..')
                    continue;

                $thumbnail = $thumbsPath . '/' . $file;
                $fullimage = $uploadPath . '/' . $file;

                if (
                    file_exists($thumbnail) && is_file($thumbnail) && Helper::is_image($thumbnail)
                    && file_exists($fullimage) && is_file($fullimage) && Helper::is_image($fullimage)
                ) {

                    $fileList['thumb'] = $thumbsPathPublic . '/' . $file;
                    $fileList['image'] = $uploadPathPublic . '/' . $file;
                    $fileList['folder'] = 'Миниатюры';
                    $fullList[$index] = $fileList;
                    ++$index;
                }
            }
        }
		#echo json_encode($fullList);
        return Response::json($fullList);
    }
	
	public function redactorUploadImage(){

        $file = Input::file('file');

        $uploadPath = public_path('uploads');
		if(Input::hasFile('file')) {

            $fileName = str_random(16) . '.' . Input::file('file')->getClientOriginalExtension();
            if (!File::exists($uploadPath . '/thumbnail'))
                File::makeDirectory($uploadPath . '/thumbnail', 0777, TRUE);

            /*
            ImageManipulation::make(Input::file('file')->getRealPath())->resize(100, 100)->save($uploadPath . '/thumbnail/thumb_' . $fileName);
            ImageManipulation::make(Input::file('file')->getRealPath())->resize(600, 600)->save($uploadPath . '/' . $fileName);
            */

            ## Pathes
            $uploadPath = Config::get('site.uploads_photo_dir');
            $thumbsPath = Config::get('site.uploads_thumb_dir');
            $uploadPathPublic = Config::get('site.uploads_photo_public_dir');
            $thumbsPathPublic = Config::get('site.uploads_thumb_public_dir');

            if(!File::exists($uploadPath))
                File::makeDirectory($uploadPath, 0777, TRUE);
            if(!File::exists($thumbsPath))
                File::makeDirectory($thumbsPath, 0777, TRUE);

            ## Resize thumb image
            $thumb_upload_success = ImageManipulation::make($file->getRealPath())
                ->resize(200, 200, function($constraint){
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save($thumbsPath.'/'.$fileName);

            ## Resize full-size image
            $image_upload_success = ImageManipulation::make($file->getRealPath())
                ->resize(1280, 1280, function($constraint){
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save($uploadPath.'/'.$fileName);


            #$file = array('filelink'=>url('uploads/'.$fileName));
            $file = array('filelink' => $uploadPathPublic.'/'.$fileName);

            #return Response::json($file);
            echo stripslashes(json_encode($file)); die;

        } else {

            exit('Нет файла для загрузки!');
        }
	}
	
}