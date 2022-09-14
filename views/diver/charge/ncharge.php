<?php

# RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
$msg = (!empty($msg)) ? unserialize($msg) : $msg;

?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><i class="fa fa-minus-circle"></i>&nbsp;&nbsp;Nouvelle D&#233;pense</h4>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl . '/' . md5("diver_charge") ?>" id="ncharge" name="ncharge" method="post">
    <!-- DEBUT PANEL BODY -->
    <div class="panel-body">
      <?=
      Yii::$app->nonSqlClass->getHiddenFormTokenField();
      $token2 = Yii::$app->getSecurity()->generateRandomString();
      $token2 = str_replace('+', '.', base64_encode($token2));
      ?>
      <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
      <input type="hidden" name="token2" value="<?= $token2 ?>" />
      <input type="hidden" name="action_key" id="action_key" value="<?= md5('enrgCharge') ?>" />
      <input type="hidden" name="msg" id="msg" value="" />

      <!-- DEBUT CONTENEUR DE MESSAGE  -->
      <?php $msg = (!empty($msg['type'])) ? $msg : null; ?>
      <div class="<?= $msg['type'] ?>">
        <strong><?= $msg['strong'] ?></strong> <?= $msg['text'] ?>
      </div>
      <!-- FIN CONTENEUR DE MESSAGE -->

      <!-- NOUVEAU PRODUIT -->
      <div class="form-group required-container">
        <label class="col-sm-4 control-label">Motif :<span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <select class="form-control" name="motif" id="motif">
            <option value=""><?= Yii::t('app', 'slectOne') ?></option>
            <?php
            if (is_array($listMotif) && sizeof($listMotif) > 0) {
              foreach ($listMotif as $key => $value) {
                echo '<option value="' . $value['id'] . '">' . $value['nom'] . '</option>';
              }
            }
            ?>
          </select>
        </div>
        <div class="col-sm-1">
          <a href="javascript:;" onClick="$('#newMotif').modal('show')" class="btn btn-circle btn-default" name="addMotif" id="addMotif"><i class="fa fa-plus-circle"></i></a>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Date(moi/jour/année) :<span class="asterisk">*</span></label>
        <div class="col-sm-4">
          <input class="form-control datepicker" autocomplete="off" id="datefrom" name="chargedate" value=<?=date('m/d/Y')?>>
        </div>
      </div>

      <div class="form-group required-container">
        <label class="col-sm-4 control-label">Valeur Monetaire :<span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <input type="text" value="<?= (isset($_POST['montant'])) ? $_POST['montant'] : Null ?>" class="form-control" name="montant" id="montant" autocomplete="off" />
        </div>
      </div>

      <div class="form-group required-container">
        <label class="col-sm-4 control-label">Description :
        </label>
        <div class="col-sm-4">
          <textarea rows="3" name="desc" id="desc" class="form-control"><?= (isset($_POST['desc'])) ? $_POST['desc'] : Null ?></textarea>
        </div>
      </div>
    </div>
    <!-- FIN PANEL BODY -->

    <!-- DEBUT : FOOTER -->
    <div class="panel-footer">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3">
          <a class="btn btn-circle btn-success" onclick="javascript:$('#gnrlFormSubmitModal').modal('show');"><i class="glyphicon glyphicon-save"></i>&nbsp;Enregistrer</a>
          <span>&nbsp;</span>
          <a href="<?= Yii::$app->request->baseUrl . '/' . md5('diver_charge'); ?>" type="reset" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Anuler</a>
        </div>
      </div>
    </div>
    <!-- FIN : FOOTER -->
  </form>
</div>
<div class="modal fade" id="newMotif" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="newProdCat">Nouveau Motif</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="col-sm-4 control-label">Motif : <span class="asterisk">*</span>
          </label>
          <div class="col-sm-8">
            <input type="text" value="" class="form-control" name="motifLabel" id="motifLabel" autocomplete="off" />
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <a href="javascript:;" type="button" onClick="addNewMotif()" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app', 'ajouter'); ?></a>
        <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app', 'fermer'); ?></button>
      </div>
    </div>
  </div>
</div>
<?php require_once('script/ncharge_js.php') ?>