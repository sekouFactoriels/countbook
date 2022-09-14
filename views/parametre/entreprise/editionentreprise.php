<?php
# DEBUT PREPARATION DU MESSAGE
$msg = (isset(Yii::$app->session['msg'])) ? unserialize(Yii::$app->session['msg']) : ((!empty($msg)) ? unserialize($msg) : $msg);
unset(Yii::$app->session['msg']);
# FIN PREPARATION DU MESSAGE
?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-btns">
          <a href="<?= Yii::$app->request->baseUrl.'/'.md5('parametre_entreprises')?>" style="color: #000;" class="btn btn-circle btn-white" name="entreprises" id="entreprises"> <i class="fa fa-arrow-circle-left"></i></a>
        </div>
        <h4 class="panel-title"><i class="glyphicon glyphicon-edit">&nbsp;</i><?= yii::t('app','parametre_entreprise')?></h4>
      </div>
      <form action="<?= Yii::$app->request->baseUrl.'/'.md5("parametre_entreprises")?>" class="form-horizontal" id="editionentreprise_form" name="editionentreprise_form" method="post">
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
          <?php $msg = (!empty($msg['type']))?$msg:null;?>
          <div class="<?= $msg['type'] ?>">
            <strong><?= $msg['strong']?></strong> <?= $msg['text']?>
          </div>
          <!-- FIN CONTENEUR DE MESSAGE -->
          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','denomination')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input class="form-control" autocomplete="off" name="denomination" id="denomination" value="<?= isset($entreprise['nom']) ? $entreprise['nom'] :(isset($_POST['denomination']) ? $_POST['denomination'] : '')?>"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','monthlytargetsale')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input class="form-control" autocomplete="off" name="monthlytargetsale" id="monthlytargetsale" value="<?= isset($entreprise['monthlytargetsale']) ? $entreprise['monthlytargetsale'] : (isset($_POST['monthlytargetsale']) ? $_POST['monthlytargetsale'] : '')?>"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label">Fond Initial : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input class="form-control" autocomplete="off" name="bankmontant" id="bankmontant" value="<?= isset($entreprise['bankmontant']) ? $entreprise['bankmontant'] : (isset($_POST['bankmontant']) ? $_POST['bankmontant'] : '')?>"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>


          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','monthlytargetclient')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input class="form-control" autocomplete="off" name="monthlytargetclient" id="monthlytargetclient" value="<?= isset($entreprise['monthlytargetclient']) ? $entreprise['monthlytargetclient'] : (isset($_POST['monthlytargetclient']) ? $_POST['monthlytargetclient'] : '')?>"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <?php
              if($iswiz!='1'){
          ?>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','numerosCommerce')?> :
            </label>
            <div class="col-sm-4 input-group">
              <input class="form-control" autocomplete="off" name="numCommerce" id="numCommerce" value="<?= isset($entreprise['numerosCommerce']) ? $entreprise['numerosCommerce'] : (isset($_POST['numCommerce']) ? $_POST['numCommerce'] : '')?>"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','slogan')?> :
            </label>
            <div class="col-sm-4 input-group">
              <input class="form-control" autocomplete="off" name="slogan" id="slogan" value="<?=  isset($entreprise['slogan']) ? $entreprise['slogan'] :  (isset($_POST['slogan']) ? $_POST['slogan'] : '')?>"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','dteCreation')?> : <span class="asterisk">&nbsp;</span>
            </label>
            <div class="col-sm-4 input-group">
              <input type="text" data-provide="datepicker" autocomplete="off" value="<?=  isset($entreprise['dteCreation']) ? Yii::$app->nonSqlClass->convert_date_to_sql_form($entreprise['dteCreation'], "Y-M-D", "M/D/Y") :  (isset($_POST['dteCreation']) ? $_POST['dteCreation'] : '')?>" class="form-control datepicker" placeholder="mm/dd/yyyy" id="dteCreation" name="dteCreation">
              <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            </div>
          </div>

          <?php } ?>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','tel')?> : </label>
            <div class="col-sm-4 input-group">
              <input class="form-control" autocomplete="off" name="tel" id="tel" value="<?= isset($entreprise['tel']) ? $entreprise['tel'] :   (isset($_POST['tel']) ? $_POST['tel'] : '')?>"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>
          <?php
              if($iswiz!='1'){
          ?>
          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','email')?> :
            </label>
            <div class="col-sm-4 input-group">
              <input class="form-control" autocomplete="off" name="email" id="email" value="<?= isset($entreprise['email']) ? $entreprise['email'] : (isset($_POST['email']) ? $_POST['email'] : '')?>"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','description')?> :
            </label>
            <div class="col-sm-4 input-group">
              <textarea class="form-control" name="description" id="description"><?= isset($entreprise['description']) ? $entreprise['description'] : (isset($_POST['description']) ? $_POST['description'] : '')?></textarea>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          
          <?php
        }
              if($iswiz==='1'){
          ?>
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

          <fieldset><legend><h3><?= Yii::t('app','forfaitdata')?></h3></legend></fieldset>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','forfait')?> : <span class="asterisk">*</span> </label>
            <div class="col-sm-4 input-group">
              <select class="form-control" name="forfait" id="forfait">
                <option value="0"><?= Yii::t('app','slectOne')?></option>
                <option <?= (isset($entreprise['tl']) && $entreprise['tl']=='1')  ? "selected" : ((isset($_POST['forfait']) && $_POST['forfait'] == '1') ? "selected" : '') ?> value="1"><?= Yii::t('app','essentiel')?></option>
                <option <?= (isset($entreprise['tl']) && $entreprise['tl']=='2')  ? "selected" : ((isset($_POST['forfait']) && $_POST['forfait'] == '2') ? "selected" : '') ?> value="2"><?= Yii::t('app','partenaire')?></option>
                <option <?= (isset($entreprise['tl']) && $entreprise['tl']=='3')  ? "selected" : ((isset($_POST['forfait']) && $_POST['forfait'] == '3') ? "selected" : '') ?> value="3"><?= Yii::t('app','premium')?></option>
                <option <?= (isset($entreprise['tl']) && $entreprise['tl']=='4')  ? "selected" : ((isset($_POST['forfait']) && $_POST['forfait'] == '4') ? "selected" : '') ?> value="4"><?= Yii::t('app','special')?></option>
                <option <?= (isset($entreprise['tl']) && $entreprise['tl']=='5')  ? "selected" : ((isset($_POST['forfait']) && $_POST['forfait'] == '5') ? "selected" : '') ?> value="5"><?= Yii::t('app','standard')?></option>
              </select>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','dtedebutforfait')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input type="text" data-provide="datepicker" autocomplete="off" value="<?= isset($entreprise['ddl']) ? Yii::$app->nonSqlClass->convert_date_to_sql_form($entreprise['ddl'], "Y-M-D", "M/D/Y") : (isset($_POST['dtedebutforfait']) ? $_POST['dtedebutforfait'] : '')?>" class="form-control datepicker" placeholder="dd/mm/yyyy" id="dtedebutforfait" name="dtedebutforfait">
              <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','dtefinforfait')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input type="text" data-provide="datepicker" autocomplete="off" value="<?= isset($entreprise['dfl']) ? Yii::$app->nonSqlClass->convert_date_to_sql_form($entreprise['dfl'], "Y-M-D", "M/D/Y") : (isset($_POST['dtefinforfait']) ? $_POST['dtefinforfait'] : '')?>" class="form-control datepicker" placeholder="dd/mm/yyyy" id="dtefinforfait" name="dtefinforfait">
              <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            </div>
          </div>
          <?php } ?>


        </div>
        <!-- DEBUT : FOOTER -->
        <div class="panel-footer">
          <div class="row">
            <div class="col-sm-9 col-sm-offset-3">
              <a class="btn btn-circle btn-success" onclick="$('#beforeupdate_entreprise').modal('show');"><i class="glyphicon glyphicon-refresh"></i>&nbsp;<?= yii::t('app','miseajour')?></a>
              <span>&nbsp;</span>
              <a href="<?= Yii::$app->request->baseUrl.'/'.md5('parametre_entreprises')?>" type="reset" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= yii::t('app','annul')?></a>
            </div>
          </div>
        </div>
        <!-- FIN : FOOTER -->
      </form>
    </div>

    <div class="modal fade" id="beforeupdate_entreprise" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="newProdCat"><?= Yii::t('app','miseajour').'&nbsp;'.Yii::t('app','parametre_entreprise')?></h4>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <p><?= Yii::t('app','msg_continueroperation')?></p>
                  </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("entreprises_updateentreprise") ?>'; document.getElementById('action_on_this').value='<?= base64_encode($entreprise["id"]) ?>'; $('#editionentreprise_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app','oui')?></a>
                    <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','non')?></button>
                </div>
            </div>
        </div>
    </div>
