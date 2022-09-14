<?php
  # RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
  $msg = (!empty($msg)) ? unserialize($msg) : $msg;
  $dataposted = (!empty($dataposted)) ? unserialize($dataposted) : $dataposted;
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="panel-btns">
      <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('1')?>'; document.getElementById('rg_invent_listarticle').submit()" style="color: #000;" class="btn btn-circle btn-white" name="retour" id="retour"> <i class="fa fa-backward">&nbsp;</i>Retour</a>
    </div>
    <small class="panel-title"><i class="glyphicon glyphicon-liste"></i>&nbsp;<?= Yii::t('app','liste_produit')?> : Filtre</small>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("rg_invent")?>" id="rg_invent_listarticle" name="rg_invent_listarticle" method="post">
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

      <div class="row">
        <div class="col-md-6">
      <!-- Rapport sur l'inventaire -->
        <fieldset>
          <legend> <h4>Filtrer par date d'enregistrement de produits</h4></legend>
        </fieldset>
          <div class="form-group">
            <label class="col-sm-4 control-label">Enregistr&#233; du :<span class="asterisk">*</span></label>
            <div class="col-sm-4">
              <input class="form-control datepicker" id="datefrom"  autocomplete="off" name="datefrom" value="<?= (is_array($dataposted) && sizeof($dataposted) > 0) ? $dataposted['datefrom'] : '01/01/2020'?>">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label">Au :<span class="asterisk">*</span></label>
            <div class="col-sm-4">
              <input class="form-control datepicker" id="dateto" autocomplete="off" name="dateto" value="<?= (is_array($dataposted) && sizeof($dataposted) > 0) ? $dataposted['dateto'] : ''?>">
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <fieldset>
            <legend> <h4>Filtrer / Services</h4></legend>
          </fieldset>
          <div class="form-group">
            <label class="col-sm-4 control-label">Services :<span class="asterisk">*</span></label>
            <div class="col-sm-6">
              <select class="form-control" name="listeservices" id="listeservices">
                <option value="<?= base64_encode(0);?>">Tous</option>
                <?php
                if (is_array($listeservicesentite) && sizeof($listeservicesentite)>0){
                    foreach ($listeservicesentite as $key => $value) {
                      echo '<option value="'.base64_encode($value['id']).'">'.$value['nom'].'</option>';
                }

                }  else{
                    echo '<tr><td colspan="7">'.Yii::t('app','pasEnregistrement').'</td></tr>';
            }

                ?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="row">&nbsp;</div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-sm-9 col-sm-offset-3">
            <a class="btn btn-circle btn-success" href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_invent_listarticle')?>'; document.getElementById('action_on_this').value='<?= md5('rg_invent_listarticle_repport')?>'; document.getElementById('rg_invent_listarticle').submit()"><i class="fa fa-eye"></i>&nbsp;Afficher</a>
            <span>&nbsp;</span>
            <a type="reset" href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('1')?>'; document.getElementById('rg_invent_listarticle').submit()" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Anuler</a>
          </div>
        </div>
      </div>

  </form>
</div>
