<?php
  # RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
  $msg = (!empty($msg)) ? unserialize($msg) : $msg;
  $dataposted = (!empty($dataposted)) ? unserialize($dataposted) : $dataposted;

?>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="panel-btns">
      <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('1')?>'; document.getElementById('rg_diver_evenement').submit()" style="color: #000;" class="btn btn-circle btn-white" name="retour" id="retour"> <i class="fa fa-backward">&nbsp;</i>Retour</a>
    </div>
    <small class="panel-title"><i class="glyphicon glyphicon-liste"></i><?= Yii::t('app','rapEvene') ?></small>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("rg_diver")?>" id="rg_diver_evenement" name="rg_diver_evenement" method="post">
    <!-- DEBUT PANEL BODY -->
    <div class="panel-body">
      <?=
        Yii::$app->nonSqlClass->getHiddenFormTokenField();
        $token2 = Yii::$app->getSecurity()->generateRandomString();
        $token2 = str_replace('+','.',base64_encode($token2));
      ?>
      <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
      <input type="hidden" name="token2" value="<?= $token2 ?>"/>
      <input type="hidden" name="action_key" id="action_key" value=""/>
      <input type="hidden" name="action_on_this" id="action_on_this" value=""/>
      <input type="hidden" name="msg" id="msg" value=""/>

      <!-- DEBUT CONTENEUR DE MESSAGE  -->
      <?php $msg = (!empty($msg['type']))?$msg:null;?>
        <div class="<?= $msg['type'] ?>">
          <strong><?= $msg['strong']?></strong> <?= $msg['text']?>
        </div>
      <!-- FIN CONTENEUR DE MESSAGE -->


      <!-- Rapport sur l'inventaire -->
      <div class="row">
        <div class="col-md-6">
          <fieldset>
            <legend> <h4> <?= Yii::t('app','filtreDte') ?></h4></legend>
          </fieldset>
          <div class="form-group">
            <label class="col-sm-4 control-label">Du :<span class="asterisk">*</span></label>
            <div class="col-sm-6 input-group">
              <input class="form-control datepicker" autocomplete="off" id="datefrom" name="datefrom" value="<?= (is_array($dataposted) && sizeof($dataposted) > 0) ? $dataposted['datefrom'] : ''?>">
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label">Au :<span class="asterisk">*</span></label>
            <div class="col-sm-6 input-group">
              <input class="form-control datepicker" autocomplete="off" id="dateto" name="dateto" value="<?= (is_array($dataposted) && sizeof($dataposted) > 0) ? $dataposted['dateto'] : ''?>">
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <fieldset>
            <legend> <h4>Filtrer / Type d&#39;Evenement</h4></legend>
          </fieldset>
          <div class="form-group">
            <label class="col-sm-4 control-label">Type :<span class="asterisk">*</span></label>
            <div class="col-sm-6 input-group">
              <select class="form-control" name="listevenement" id="listevenement">
                <option value="0">Tous</option>
                <?php
               if (is_array($allEventType) and sizeof($allEventType) > 0){

                      foreach ( $allEventType as $key => $value ){
                        echo '<option value ="'.$value['code'].'">'.$value['titre'].'</option>' ;
                      }
               }

                   else{
                    echo '<tr><td colspan="7">'.Yii::t('app','pasEnregistrement').'</td></tr>';
               }
                ?>
              </select>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>
        </div>
      </div>
      </div>

      <div class="row">&nbsp;</div>
        <div class="panel-footer">
          <div class="row">
            <div class="col-sm-9 col-sm-offset-3">
              <a class="btn btn-circle btn-success" href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_diver_event')?>'; document.getElementById('action_on_this').value='<?= md5('rg_diver_event_repport')?>'; document.getElementById('rg_diver_evenement').submit()"><i class="fa fa-eye"></i>&nbsp;Afficher</a>
              <span>&nbsp;</span>
              <a type="reset" href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('1')?>'; document.getElementById('rg_diver_evenement').submit()" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Anuler</a>
            </div>
          </div>
        </div>
  </form>
</div>
