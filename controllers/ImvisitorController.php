<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ImvisitorController extends ImvisitorlayoutController
{
  private $pg = Null;
  public function beforeAction($action)
  {
    $this->layout = '@app/views/layouts/login_layout.php';
    return parent::beforeAction($action);
  }

  public function actionFaireesessais()
  {
    $forfait = strVal($_GET['forfait']);
    
    switch($forfait){
      case md5('essentiel'):
        die('hello');
      break;
    }
    return $this->render('/tarifs/nostarifs.php');
  }

  public function actionTarifs()
  {
    return $this->render('/tarifs/nostarifs.php');
  }
}
