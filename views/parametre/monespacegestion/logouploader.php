<style>
  .holder {
    height: 300px;
    width: 300px;
    border: 2px solid black;
  }

  img {
    max-width: 300px;
    max-height: 300px;
    min-width: 300px;
    min-height: 300px;
  }

  input[type="file"] {
    margin-top: 5px;
  }

  .heading {
    font-family: Montserrat;
    font-size: 45px;
    color: green;
  }
</style>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="panel-btns">
      <a href="<?= Yii::$app->request->baseUrl . '/' . md5('parametre_entreprises') ?>" style="color: #000;" class="btn btn-circle btn-white" name="newentreprise" id="newentreprise"> <i class="fa fa-arrow-circle-left"></i></a>
    </div>
    <h4 class="panel-title"><i class="fa fa-building-o">&nbsp;</i><?= yii::t('app', 'parametre_entreprise') ?>&nbsp;:&nbsp;logo&nbsp;</h4>
  </div>
  <form action="<?= Yii::$app->request->baseUrl . '/' . md5("parametre_entreprises") ?>" class="form-horizontal" id="countbook_form" name="countbook_form" method="post" enctype="multipart/form-data">
    <div class="panel-body">
      <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
      $token2 = Yii::$app->getSecurity()->generateRandomString();
      $token2 = str_replace('+', '.', base64_encode($token2));
      ?>
      <input type="hidden" name="token2" value="<?= $token2 ?>" />
      <input type="hidden" name="_csrf" value="<?= yii::$app->request->getcsrftoken() ?>">
      <input type="hidden" name="action_key" id="action_key" value="">
      <input type="hidden" name="action_on_this" id="action_on_this" value="">
      <!-- DEBUT CONTENEUR DE MESSAGE  -->
      <?= Yii::$app->session->getFlash('flashmsg'); Yii::$app->session->removeFlash('flashmsg');?>
      <!-- FIN CONTENEUR DE MESSAGE -->


      <!-- logo -->
      <div class="form-group">
        <label class="col-sm-4 control-label">Logo : <span class="asterisk"></span>
        </label>
        <div class="col-sm-4 input-group">
          <div class="holder">
            <img id="imgPreview" name="imgPreview" src="#" alt="pic" />
          </div>
          <input type="file" name="logo" id="photo" required="true" />
        </div>
      </div>
      <!-- /logo -->
    </div>
    <!-- DEBUT : FOOTER -->
    <div class="panel-footer">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3">
          <a class="btn btn-circle btn-success" onclick="$('#do_upload_modal').modal('show');"><i class="glyphicon glyphicon-save"></i>&nbsp;<?= yii::t('app', 'enrgtrer') ?></a>
          <span>&nbsp;</span>
          <a href="<?= Yii::$app->request->baseUrl . '/' . md5('parametre_entreprises') ?>" type="reset" class="btn btn-circle btn-warning"><i class="fa fa-arrow-circle-left"></i>&nbsp;<?= yii::t('app', 'rtour') ?></a>
        </div>
      </div>
    </div>
    <!-- FIN : FOOTER -->
  </form>
</div>
<div class="modal fade" id="do_upload_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="newProdCat"><?= Yii::t('app', 'enrg') . '&nbsp;' . Yii::t('app', 'parametre_entreprise') ?></h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p><?= Yii::t('app', 'msg_continueroperation') ?></p>
        </div>
      </div>
      <div class="modal-footer">
        <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("do_upload") ?>'; $('#countbook_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app', 'oui') ?></a>
        <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app', 'non') ?></button>
      </div>
    </div>
  </div>
</div>
<?php require_once('script/logouploader_js.php');?>