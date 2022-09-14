<?php

  require_once __DIR__ . '/../../../extensions/vendor/autoload.php';
  $client = new infobip\api\client\SendSingleTextualSms(new infobip\api\configuration\BasicAuthConfiguration('wizbox10', 'PASSWORD'));
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="panel-btns">
      <a href="<?= Yii::$app->request->baseUrl.'/'.md5('client_spaceclient');?>" style="color: #000;" class="btn btn-circle btn-white" > <i class="fa fa-arrow-circle-left">&nbsp;</i></a>
    </div>
    <h4 class="panel-title"><i class="fa fa-user"></i>&nbsp;&nbsp;NOUVEAU CLIENT</h4>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("client_themain")?>" id="newclient_form" name="newclient_form" method="post">
    <!-- DEBUT PANEL BODY -->
    <div class="panel-body">
      <?=
        Yii::$app->nonSqlClass->getHiddenFormTokenField();
        $token2 = Yii::$app->getSecurity()->generateRandomString();
        $token2 = str_replace('+','.',base64_encode($token2));
      ?>
      <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
      <input type="hidden" name="token2" value="<?= $token2 ?>"/>
      <input type="hidden" name="action_key" id="action_key" value="<?= md5('newProduct')?>"/>
      <input type="hidden" name="action_on_this" id="action_on_this" value=""/>
      <input type="hidden" name="ajax_action_key" id="ajax_action_key" value=""/>
      <input type="hidden" name="msg" id="msg" value=""/>

     <!-- AFFICHER MESSAGE -->
    <?= yii::$app->session->getFlash('flashmsg'); yii::$app->session->removeFlash('flashmsg');?>

      <!-- formulaire -->
      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','stat_client')?> : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4 input-group">
          <select name="statutClient" id="statutClient" class="form-control chosen-select">
            <option value="0">>-- <?= Yii::t('app','slectOne') ?> --<</option>
            <option value="1"><?= Yii::t('app','particulier') ?></option>
            <option value="2"><?= Yii::t('app','entreprise') ?></option>
          </select>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','segment_client')?> : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4 input-group">
          <select name="segmentClient" id="segmentClient" class="form-control chosen-select">
            <option value="0">>-- <?= Yii::t('app','slectOne') ?> --<</option>
            <option value="1"><?= Yii::t('app','prospect_froid') ?></option>
            <option value="2"><?= Yii::t('app','prospect_tiede') ?></option>
            <option value="3"><?= Yii::t('app','prospect_chaud') ?></option>
            <option value="4"><?= Yii::t('app','client') ?></option>
          </select>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','nomappelation')?> : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4 input-group">
          <input class="form-control" autocomplete="off" name="appelation" id="appelation" value="<?= isset($_POST['appelation']) ? $_POST['appelation'] : ''?>"/>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','dteNaiss')?> : <span class="asterisk">&nbsp;</span>
        </label>
        <div class="col-sm-4 input-group">
          <input type="text" data-provide="datepicker" autocomplete="off" value="" class="form-control datepicker" placeholder="dd/mm/yyyy" id="dtebirth" name="dtebirth">
          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','tel')?> : <span class="asterisk">&nbsp;</span>
        </label>
        <div class="col-sm-4 input-group">
          <input class="form-control" name="tel" placeholder="Ex : 224624123456" autocomplete="off" id="tel" value="<?= isset($_POST['tel']) ? $_POST['tel'] : ''?>"/>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','email')?> : <span class="asterisk">&nbsp;</span>
        </label>
        <div class="col-sm-4 input-group">
          <input class="form-control" name="email" placeholder="Ex : test@test.com" autocomplete="off" id="email" value="<?= isset($_POST['email']) ? $_POST['email'] : ''?>"/>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','ergmotif')?> : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4 input-group">
          <select name="enrgmotif" id="enrgmotif" class="form-control chosen-select">
            <option value="0">>-- <?= Yii::t('app','slectOne') ?> --<</option>
            <?php
              if(sizeof($clientEnrgMotifs)>0){
                foreach ($clientEnrgMotifs as $key => $value) {
                  echo '<option value="'.$value['id'].'">'.$value['libelle'].'</option>';
                }
              }else echo '<option value="0">'.Yii::t("app","pasEnregistrement").'</option>';
            ?>
          </select>
          <a href="javascript:;" class="btn btn-circle btn-primary input-group-addon" onclick="$('#newmotifenrgclient').modal('show');"><i class="fa fa-plus-circle"></i></a>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','dtlsmotif')?> :<span class="asterisk">&nbsp;</span>
        </label>
        <div class="col-sm-4 input-group">
          <textarea class="form-control" rows="3" name="dtlsmotif" id="dtlsmotif"><?= isset($_POST['dtlsmotif']) ? $_POST['dtlsmotif'] : ''?></textarea>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>
      <!-- fin formulaire -->


    </div>
    <!-- FIN PANEL BODY -->

    <!-- DEBUT : FOOTER -->
    <div class="panel-footer">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3">
          <a class="btn btn-circle btn-success" onclick="$('#beforesave_newclient').modal('show');"><i class="glyphicon glyphicon-save"></i>&nbsp;Enregistrer</a>
          <span>&nbsp;</span>
          <a type="reset" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Anuler</a>
        </div>
      </div>
    </div>
    <!-- FIN : FOOTER -->
  </form>
</div>

<div class="modal fade" id="beforesave_newclient" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="newProdCat">Enregistrement / Client</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <p>Etes-vous sur de bien vouloir continuer cette op&#233;ration ?</p>
              </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("spaceclient_enrgclient") ?>'; document.getElementById('newclient_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app','oui')?></a>
                <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','non')?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="take_we_to_success_form" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="licenceCreatAssociatLabel"><?php echo Yii::t('app','valid');?></h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <p><?= Yii::t('app','enrgSuccess')?></p>
              </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="$('#newclient_form').submit();" class="btn btn-circle btn-success" data-dismiss="modal"><?= Yii::t('app','ok');?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="loadingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content"> </div>
      <div class="modal-body" style="text-align: center;">
          <p>
            <div style="text-align:center;color:#EEEEEE;line-height:320px;width:100%;"><i class="fa fa-spin fa-spinner" style="font-size: 35px;"></i> <span style="font-size: 20px;">&nbsp;<?= Yii::t('app','validatCours')?></span></div>
          </p>
      </div>
  </div>
</div>
<?php
  require_once(Yii::$app->basePath.'/views/parametre/motifenrgclient/newmotif.php')
?>
