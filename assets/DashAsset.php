<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author aidaf
 * @since 1.0
**/

class DashAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
      'web/slimstok/assets/css/jquery.datatables.css',
      'web/slimstok/assets/css/bootstrap.min.css',
      'web/slimstok/assets/css/bootstrap-override.css',
      'web/slimstok/assets/css/bootstrap-timepicker.min.css',
      //'web/slimstok/assets/css/bootstrap-datepicker3.css',
      'web/slimstok/assets/css/jquery-ui-1.10.3.css',
      'web/slimstok/assets/css/font-awesome.min.css',
      'web/slimstok/assets/css/animate.min.css',
      'web/slimstok/assets/css/animate.delay.css',
      'web/slimstok/assets/css/toggles.css',
      'web/slimstok/assets/css/chosen.css',
      'web/slimstok/assets/css/lato.css',
      'web/slimstok/assets/css/style.default.css',
    ];

    public $js = [
      'web/slimstok/assets/js/jquery-1.10.2.min.js',
        'web/slimstok/assets/js/jquery-migrate-1.2.1.min.js',
        'web/slimstok/assets/js/jquery-ui-1.10.3.min.js',
        'web/slimstok/assets/js/bootstrap.min.js',
        'web/slimstok/assets/js/modernizr.min.js',
        'web/slimstok/assets/js/jquery.sparkline.min.js',
        'web/slimstok/assets/js/toggles.min.js',
        'web/slimstok/assets/js/retina.min.js',
        'web/slimstok/assets/js/jquery.cookies.js',
        'web/slimstok/assets/js/chosen.jquery.min.js',

        'web/slimstok/assets/js/flot/flot.min.js',
        'web/slimstok/assets/js/flot/flot.resize.min.js',
        'web/slimstok/assets/js/flot/flot.symbol.min.js',
        'web/slimstok/assets/js/flot/flot.crosshair.min.js',
        'web/slimstok/assets/js/flot/flot.categories.min.js',
        'web/slimstok/assets/js/flot/flot.pie.min.js',
        'web/slimstok/assets/js/morris.min.js',
        'web/slimstok/assets/js/raphael-2.1.0.min.js',
        'web/slimstok/assets/js/jquery.datatables.min.js',
        'web/slimstok/assets/js/custom.js',
        //'web/slimstok/assets/js/charts_edited.js',
        'web/slimstok/assets/js/chart.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
}
