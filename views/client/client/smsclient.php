<?php
  # DEBUT PREPARATION DU MESSAGE
  $msg = (isset(Yii::$app->session['msg'])) ? unserialize(Yii::$app->session['msg']) : ((!empty($msg)) ? unserialize($msg) : $msg);
  unset(Yii::$app->session['msg']);
  # FIN PREPARATION DU MESSAGE

  $segmentClient = isset($_POST['segmentClient']) ? $_POST['segmentClient'] : (!empty($dtlContact['naturecontact']) ? $dtlContact['naturecontact'] : Null);
  $statutClient = isset($_POST['statutClient']) ? $_POST['statutClient'] : (!empty($dtlContact['statutcontact']) ? $dtlContact['statutcontact'] : Null);
  $enrgmotif = isset($_POST['enrgmotif']) ? $_POST['enrgmotif'] : (isset($dtlContact['id_client_enrg_motif']) ? $dtlContact['id_client_enrg_motif'] : Null);
  if(isset($_POST['dtebirth'])){//Explode de date into : day,month,year
    $date = explode('/',$_POST['dtebirth']);
    $dayBirth = $date[1];
    $monthBirth = $date[0];
    $yearBirth = $date[2];
  }
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="panel-btns">
      <a href="<?= Yii::$app->request->baseUrl.'/'.md5('client_spaceclient');?>" style="color: #000;" class="btn btn-circle btn-white" > <i class="fa fa-arrow-circle-left">&nbsp;</i></a>
    </div>
    <h4 class="panel-title"><i class="fa fa-user"></i>&nbsp;&nbsp;<?= Yii::t('app','update').' '.Yii::t('app','cnt')?></h4>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("client_spaceclient")?>" id="editclient_form" name="editclient_form" method="POST">

    <!-- DEBUT PANEL BODY -->
    <div class="panel-body">
      <?=
        Yii::$app->nonSqlClass->getHiddenFormTokenField();
        $token2 = Yii::$app->getSecurity()->generateRandomString();
        $token2 = str_replace('+','.',base64_encode($token2));
      ?>
      <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
      <input type="hidden" name="token2" value="<?= $token2 ?>"/>
      <input type="hidden" name="action_key" id="action_key" value="<?= md5('1')?>"/>
      <input type="hidden" name="action_on_this" id="action_on_this" value="<?= base64_encode($dtlContact['id']) ?>"/>
      <input type="hidden" name="msg" id="msg" value=""/>

      <!-- DEBUT CONTENEUR DE MESSAGE  -->
      <?php $msg = (!empty($msg['type']))?$msg:null;?>
      <div class="<?= $msg['type'] ?>">
        <strong><?= $msg['strong']?></strong> <?= $msg['text']?>
      </div>
      <!-- FIN CONTENEUR DE MESSAGE -->

      <!-- formulaire -->

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','nomappelation')?> : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4 input-group">
          <input class="form-control" autocomplete="off" disabled name="appelation" id="appelation" value="<?= isset($_POST['appelation']) ? $_POST['appelation'] : (isset($dtlContact['nom_appellation']) ? $dtlContact['nom_appellation'] : '') ?>"/>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','tel')?> : <span class="asterisk">&nbsp;</span>
        </label>
        <div class="col-sm-4 input-group">
          <input class="form-control" name="tel" disabled autocomplete="off" placeholder="Ex : 224624123456" id="tel" value="<?= isset($_POST['tel']) ? $_POST['tel'] : (isset($dtlContact['tel1']) ? $dtlContact['tel1'] : '') ?>"/>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','ergmotif')?> : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4 input-group">
          <select disabled name="enrgmotif" id="enrgmotif" class="form-control chosen-select">
            <option value="0">>-- <?= Yii::t('app','slectOne') ?> --<</option>
            <?php
              if(sizeof($clientEnrgMotifs)>0){
                foreach ($clientEnrgMotifs as $key => $value) {
                  $selected = ($enrgmotif == $value["id"]) ? "selected" : '';
                  echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['libelle'].'</option>';
                }
              }else echo '<option value="0">'.Yii::t("app","pasEnregistrement").'</option>';
            ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','dtlsmotif')?> :<span class="asterisk">&nbsp;</span>
        </label>
        <div class="col-sm-4 input-group">
          <textarea class="form-control" rows="4" name="dtlsmotif" disabled id="dtlsmotif"><?= isset($_POST['dtlsmotif']) ? $_POST['dtlsmotif'] : (isset($dtlContact['dtlsMotif']) ? $dtlContact['dtlsMotif'] : '') ?></textarea>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label"><?= Yii::t('app','statut').'&nbsp;'.Yii::t('app','cnt')?> : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4 input-group">
          <select disabled name="statut" id="statut" class="form-control chosen-select">
            <option value="0">>-- <?= Yii::t('app','slectOne') ?> --<</option>
            <option value="1"><?= Yii::t('app','active') ?></option>
            <option value="2"><?= Yii::t('app','suprime') ?></option>
          </select>
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
          <a class="btn btn-circle btn-success" onclick="$('#beforeupdate_client').modal('show');"><i class="glyphicon glyphicon-refresh"></i>&nbsp;<?= Yii::t('app','update')?></a>
          <span>&nbsp;</span>
          <a type="reset" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Anuler</a>
        </div>
      </div>
    </div>
    <!-- FIN : FOOTER -->
  </form>
</div>

<div class="modal fade" id="beforeupdate_client" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="newProdCat"><?= Yii::t('app','update').'&nbsp;'.Yii::t('app','cnt') ?></h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <p><?= Yii::t('app','msg_continueroperation')?></p>
              </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("spaceclient_updateclient") ?>'; document.getElementById('editclient_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app','oui')?></a>
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
