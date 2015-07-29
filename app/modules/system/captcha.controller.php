<?php

use Gregwar\Captcha\CaptchaBuilder;

class CaptchaController extends BaseController {

    public static $name = 'captcha';
    public static $group = 'system';
    #private $kcaptcha_dir = 'lib/kcaptcha';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;

        Route::group(array('prefix' => 'kcaptcha'), function() use ($class) {
            Route::any('form.html', array('as' => 'kcaptcha_form', 'uses' => __CLASS__.'@getKcaptchaForm'));
            Route::any('image.png', array('as' => 'kcaptcha_image', 'uses' => __CLASS__.'@getKcaptchaImage'));
            Route::post('check.json', array('as' => 'kcaptcha_check', 'uses' => __CLASS__.'@checkKcaptchaImage'));
        });

        Route::group(array('prefix' => 'captcha'), function() use ($class) {
            Route::any('form.html', array('as' => 'captcha_form', 'uses' => __CLASS__.'@getCaptchaForm'));
            Route::any('image.png', array('as' => 'captcha_image', 'uses' => __CLASS__.'@getCaptchaImage'));
            Route::post('check.json', array('as' => 'captcha_check', 'uses' => __CLASS__.'@checkCaptchaImage'));
        });
    }

    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
    }

    /****************************************************************************/

	public function __construct(){

        /*
        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'tpl' => static::returnTpl('admin/kcaptcha'),
            'gtpl' => static::returnTpl(),

            'class' => __CLASS__,
        );
        View::share('module', $this->module);
        */
	}


    ##############################################################################
    ## http://captcha.ru/kcaptcha/
    ##############################################################################

    public function getKcaptchaForm() {

        session_start();
        ?>
        <form action="" method="post">
            <p>Enter text shown below:</p>
            <p><img src="<?php echo URL::route('kcaptcha_image', [session_name() => session_id()]) ?>"></p>
            <p><input type="text" name="keystring"></p>
            <p><input type="submit" value="Check"></p>
        </form>
        <?php
        if (count($_POST) > 0) {
            if (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $_POST['keystring']) {
                echo "Correct";
            } else {
                echo "Wrong";
            }
        }
        unset($_SESSION['captcha_keystring']);
    }


    public function getKcaptchaImage() {

        error_reporting (E_ALL);
        session_start();
        $captcha = new KCAPTCHA();
        if($_REQUEST[session_name()]){
            $_SESSION['captcha_keystring'] = $captcha->getKeyString();
        }
    }


    public function checkKcaptchaImage() {

        session_start();
        $json_response = array('status' => FALSE, 'responseText' => '');

        if (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $_POST['keystring']) {
            $json_response['status'] = TRUE;
        }

        $clear = Input::get('clear') !== NULL ? Input::get('clear') : TRUE;
        if ($clear) {
            unset($_SESSION['captcha_keystring']);
        }

        return Response::json($json_response, 200);
    }


    public static function checkKcaptcha($keystring, $clear = TRUE) {

        $return = FALSE;
        if (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $keystring) {
            $return = TRUE;
        }
        if ($clear) {
            unset($_SESSION['captcha_keystring']);
        }
        return $return;
    }


    ##############################################################################
    ## https://github.com/Gregwar/Captcha
    ##############################################################################

    public function getCaptchaForm() {

        session_start();
        ?>
        <form action="" method="post">
            <p>Enter text shown below:</p>
            <p><img src="<?php echo URL::route('captcha_image', [session_name() => session_id()]) ?>"></p>
            <p><input type="text" name="keystring"></p>
            <p><input type="submit" value="Check"></p>
        </form>
        <?php
        if (count($_POST) > 0) {
            #print_r($_SESSION);
            #print_r($_POST);
            if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $_POST['keystring']) {
                echo "Correct";
            } else {
                echo "Wrong";
            }
        }
        unset($_SESSION['captcha_keystring']);
    }


    public function getCaptchaImage() {

        session_start();
        $default_width = Config::get('site.captcha.default_width') ?: 150;
        $default_height = Config::get('site.captcha.default_height') ?: 40;
        $width = Input::get('w') ?: $default_width;
        $height = Input::get('h') ?: $default_height;

        $builder = new CaptchaBuilder;
        $builder->build($width, $height);

        $_SESSION['captcha_keystring'] = $builder->getPhrase();

        header('Content-type: image/jpeg');
        $builder->output();
    }


    public function checkCaptchaImage() {

        session_start();
        $json_response = array('status' => FALSE, 'responseText' => '');

        if (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $_POST['keystring']) {
            $json_response['status'] = TRUE;
        }

        $clear = Input::get('clear') !== NULL ? Input::get('clear') : TRUE;
        if ($clear) {
            unset($_SESSION['captcha_keystring']);
        }

        return Response::json($json_response, 200);
    }


    public static function checkCaptcha($keystring, $clear = TRUE) {

        $return = FALSE;
        if (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $keystring) {
            $return = TRUE;
        }
        if ($clear) {
            unset($_SESSION['captcha_keystring']);
        }
        return $return;
    }


    public static function clearCaptcha() {

        unset($_SESSION['captcha_keystring']);
    }
}