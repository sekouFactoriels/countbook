<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author softbox
 * @since 1.0
**/

class loginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
      
    ];

    public $js = [
        'web/slimstok/assets/js/jquery-1.10.2.min.js'
    ];

    public $depends = [
        
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
}
