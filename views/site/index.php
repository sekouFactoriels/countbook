<!-- NOTIFICATION -->
<?= Yii::$app->session->getFlash('flashmsg'); Yii::$app->session->removeFlash('flashmsg');  ?>

<form class="form-horizontal" action="" name="countbook_form" id="countbook_form" action="<?=yii::$app->request->baseurl.'/'.md5('site_index') ?>" method="post">
<?=
  Yii::$app->nonSqlClass->getHiddenFormTokenField();
  $token2 = Yii::$app->getSecurity()->generateRandomString();
  $token2 = str_replace('+','.',base64_encode($token2));
  ?>
  <!-- DEBUT : BASIC HIDDEN IMPUT FOR THE FORM -->
  <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
  <input type="hidden" name="token2" value="<?= $token2 ?>"/>
  <input type="hidden" name="action_key" id="action_key" value=""/>
  <input type="hidden" name="action_on_this" id="action_on_this" value=""/>
  <input type="hidden" name="action_on_this_val" id="action_on_this_val" value=""/>


<?php
/** Identifi le niveau d'access de l'utilisateur **/
if(!empty(Yii::$app->session[Yii::$app->params['userSessionDtls']])){
  $UserAuthPrimaryInfo = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
  $userLevel = $UserAuthPrimaryInfo['typeUser'];
  switch ($userLevel) {
    case 9: // BOSS
    case 8: // Administrateur
    case 7: // Controlleur
      require_once('_indexBaseUserLevel/_boss.php');
    break;

    case 6:
      require_once('_indexBaseUserLevel/_saler.php');
    break;
  }
}
?>
</form>
