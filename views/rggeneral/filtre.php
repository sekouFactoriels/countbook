<?php
  require_once('script/filtre_sc.php');
  # RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
  $msg = (!empty($msg)) ? unserialize($msg) : $msg;

?>
<div class="panel panel-default">
  <div class="panel-heading">
    <small class="panel-title"><i class="glyphicon glyphicon-import"></i>&nbsp;<?= Yii::t('app','repport_gene')?></small>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("repport_gene")?>" id="repport_gnl_frm" name="repport_gnl_frm" method="post">
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

      <!-- Rapport sur l'inventaire -->
  <fieldset>
    <legend>
      <h5><i class="fa fa-exchange"></i>   Inventaire</h5>
    </legend>
  </fieldset>
  <div class="row">
  <!-- Rapport des produits -->
  <div class="col-sm-4 col-md-3">
    <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5('rg_invent_analyse')?>'; document.getElementById('repport_gnl_frm').submit()">
      <div class="panel panel-okay panel-stat">
        <div class="panel-heading">
          <div class="stat">
            <div class="row">
              <div class="col-xs-2">
                <span class="fa fa-exchange" style="font-size: 200%"></span>
              </div>
              <div class="col-xs-8 widthpanel">
                <small class="stat-label">Inventaire</small>
                <h4>Liste des produits</h4>
              </div>
            </div><!-- row -->
          </div><!-- stat -->
        </div><!-- panel-heading -->
      </div><!-- panel -->
    </a>
    </div><!-- col-sm-6 -->
  </div><!-- row-->
</form>
</div>
