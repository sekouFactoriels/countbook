<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

abstract Class ImvisitorlayoutController extends Controller {
  public $connect = Null;
  Public function beforeAction($action){
    switch (Yii::$app->controller->action->id) {
      case 'login':
        $this->layout = '@app/views/layouts/login_layout.php';
      break;
              
      default:
        $this->layout = '@app/views/layouts/login_layout.php';
      break;
    }    
    return parent::beforeAction($action);
  }
}
