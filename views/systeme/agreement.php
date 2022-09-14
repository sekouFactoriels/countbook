<!-- BEGIN FORM-->
<form class="form-horizontal" action="" name="countbook_form" id="countbook_form" action="<?=yii::$app->request->baseurl.'/'.md5('fournisseur_themain') ?>" method="post">
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
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">CONDITIONS</h4>
        </div><!-- panel-heading -->
        <div class="panel-body">
          <p><?= $agrrement['content']?></p>
        </div>
        <!-- PIEDS DU FORMULAIRE -->
        <div class="panel-footer">
          <div class="row">
            <div class="col-sm-9 col-sm-offset-3">
              <a class="btn btn-circle btn-success" onclick="$('#countbook_form_modal').modal('show');"><i class="fa  fa-thumbs-up"></i>&nbsp;J'accepte tout</a>
            </div>
          </div>
        </div>
      </div><!-- panel -->
    </div><!-- col-md-6 -->
  </div><!-- row -->
</form>
<div class="modal fade" id="countbook_form_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="newProdCat"><i class="fa fa-exclamation-circle">&nbsp;</i>CONDITIONS GENERALES D'UTILISATION</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <p>Accepetez-vous accepter les conditions générales d'utlisation de la solution, sachant que votre forfait est <b> <?= yii::t('app',$infos_espace_gest['tl']); ?> </b> ?</p>
              </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" type="button" onClick="$('#action_key').val('<?= md5("do_agreement") ?>'); $('#action_on_this').val('<?= $infos_espace_gest["id"]; ?>'); $('#countbook_form').attr('action','<?= md5("site_signagreement") ?>'); document.getElementById('countbook_form').submit(); " class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app','jaccepte')?></a>

                <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','non')?></button>
            </div>
        </div>
    </div>
</div>
