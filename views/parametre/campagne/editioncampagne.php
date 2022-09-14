<?php
# DEBUT PREPARATION DU MESSAGE
$msg = (isset(Yii::$app->session['msg'])) ? unserialize(Yii::$app->session['msg']) : ((!empty($msg)) ? unserialize($msg) : $msg);
unset(Yii::$app->session['msg']);
# FIN PREPARATION DU MESSAGE
?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-btns">
          <a href="<?= Yii::$app->request->baseUrl.'/'.md5('parametre_campagnes')?>" style="color: #000;" class="btn btn-circle btn-white" name="motifsenrgclient_form" id="motifsenrgclient_form"> <i class="fa fa-arrow-circle-left">&nbsp;</i></a>
        </div>
        <h4 class="panel-title"><i class="glyphicon glyphicon-plus-sign">&nbsp;</i><?= yii::t('app','parametre_campagnes')?></h4>
      </div>
      <form action="<?= Yii::$app->request->baseUrl.'/'.md5("parametre_campagnes")?>" class="form-horizontal" id="editioncampagne_form" name="editioncampagne_form" method="post">
        <div class="panel-body">
          <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
                $token2 = Yii::$app->getSecurity()->generateRandomString();
                $token2 = str_replace('+','.',base64_encode($token2));
          ?>
          <input type="hidden" name="token2" value="<?= $token2 ?>"/>
          <input type="hidden" name="_csrf" value="<?=yii::$app->request->getcsrftoken()?>">
          <input type="hidden" name="action_key" id="action_key" value="">
          <input type="hidden" name="action_on_this" id="action_on_this" value="">
          <!-- DEBUT CONTENEUR DE MESSAGE  -->

          <div class="<?= $msg['type'] ?>">
            <strong><?= $msg['strong']?></strong> <?= $msg['text']?>
          </div>
          <!-- FIN CONTENEUR DE MESSAGE -->
          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','denomination')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input class="form-control" autocomplete="off" name="denomination" id="denomination" value="<?= isset($naturedata['denomination']) ? $naturedata['denomination'] : $_POST['denomination'] ?>"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','msg')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <textarea class="form-control" autocomplete="off" name="msg" id="msg"/> <?= isset($naturedata['msg']) ? $naturedata['msg'] : $_POST['msg'] ?> </textarea>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','statut')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <select class="form-control" name="statut" id="statut">
                <option value="0"><?= Yii::t('app','slectOne')?></option>
                <option value="1"><?= Yii::t('app','lance')?></option>
                <option value="2"><?= Yii::t('app','nonlance')?></option>
              </select>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>
        </div>
        <!-- DEBUT : FOOTER -->
        <div class="panel-footer">
          <div class="row">
            <div class="col-sm-9 col-sm-offset-3">
              <a class="btn btn-circle btn-success" onclick="$('#beforeupdate_campagne').modal('show');"><i class="glyphicon glyphicon-save"></i>&nbsp;<?= yii::t('app','update')?></a>
              <span>&nbsp;</span>
              <a href="<?= Yii::$app->request->baseUrl.'/'.md5('parametre_campagnes')?>" type="reset" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= yii::t('app','annul')?></a>
            </div>
          </div>
        </div>
        <!-- FIN : FOOTER -->
      </form>
    </div>

    <div class="modal fade" id="beforeupdate_campagne" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="newProdCat"><?= Yii::t('app','miseajour').'&nbsp;'.Yii::t('app','parametre_campagnes')?></h4>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <p><?= Yii::t('app','msg_continueroperation')?></p>
                  </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("campagnes_updatecampagne") ?>'; document.getElementById('action_on_this').value='<?= base64_encode($naturedata["id"]) ?>'; document.getElementById('editioncampagne_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app','oui')?></a>
                    <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','non')?></button>
                </div>
            </div>
        </div>
    </div>
