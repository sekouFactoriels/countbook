<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author aidaf
 * @since 1.0
**/

class ReportAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      'web/slimstok/assets/css/printme.css',
      
    ];

    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
}
