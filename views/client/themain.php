
<!-- BEGIN FORM-->
<form class="form-horizontal" action="" name="countbook_form" id="countbook_form" action="<?=yii::$app->request->baseurl.'/'.md5('client_themain') ?>" method="post">
<?=
  Yii::$app->nonSqlClass->getHiddenFormTokenField();
  $token2 = Yii::$app->getSecurity()->generateRandomString();
  $token2 = str_replace('+','.',base64_encode($token2));
  ?>
  <!-- DEBUT : BASIC HIDDEN IMPUT FOR THE FORM -->
  <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
  <input type="hidden" name="token2" value="<?= $token2 ?>"/>
  <input type="hidden" name="action_key" id="action_key" value=""/>

  <!-- DEBUT CONTENEUR DE MESSAGE -->
 
  <div class="row">

    <!-- AFFICHER MESSAGE -->
    <?= yii::$app->session->getFlash('flashmsg'); yii::$app->session->removeFlash('flashmsg');?>

    <a href="javascript:;" onclick="$('#action_key').val('<?= md5("nouveau_client")?>'); $('#countbook_form').attr('action', '<?= md5("client_themain")?>'); $('#countbook_form').submit(); ">
      <div class="col-md-6">
        <div class="panel panel-success panel-alt widget-today">
          <div class="panel-heading text-center">
            <i class="fa fa-user"></i>
          </div>
          <div class="panel-body text-center">
            <h3 class="today">NOUVEAU CLIENTS</h3>
          </div><!-- panel-body -->
        </div><!-- panel -->
      </div>
    </a>

     <a href="javascript:;" onclick="$('#action_key').val('<?= md5("lister_clients")?>'); $('#countbook_form').attr('action', '<?= md5("client_themain")?>'); $('#countbook_form').submit(); ">
      <div class="col-md-6">
        <div class="panel panel-success panel-alt widget-today">
          <div class="panel-heading text-center">
            <i class="fa fa-users"></i>
          </div>
          <div class="panel-body text-center">
            <h3 class="today">LISTE CLIENT</h3>
          </div><!-- panel-body -->
        </div><!-- panel -->
      </div>
    </a>


  </div>
</form>