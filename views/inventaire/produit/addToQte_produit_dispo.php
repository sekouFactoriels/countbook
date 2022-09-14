<?php
  require_once('script/produit_js.php');
  # RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
  $msg = (!empty($msg)) ? unserialize($msg) : $msg;
  $produitDtls = (isset($produitDtls)) ? json_decode($produitDtls) : Null;
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><i class="fa fa-edit"></i>&nbsp;<?= Yii::t('app','addToQteDispo')?></h4>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("inventaire_produits")?>" id="addtoqtedispoForm" name="addtoqtedispoForm" method="post">
    <!-- DEBUT PANEL BODY -->
    <div class="panel-body">
      <?=
        Yii::$app->nonSqlClass->getHiddenFormTokenField();
        $token2 = Yii::$app->getSecurity()->generateRandomString();
        $token2 = str_replace('+','.',base64_encode($token2));
      ?>
      <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
      <input type="hidden" name="token2" value="<?= $token2 ?>"/>
      <input type="hidden" name="action_key" id="action_key" value="<?= md5('addToQteDispo')?>"/>
      <input type="hidden" name="action_on_this" id="action_on_this" value=""/>
      <input type="hidden" name="ajax_action_key" id="ajax_action_key" value=""/>
      <input type="hidden" name="msg" id="msg" value=""/>

      <!-- Extr Hidden Input -->
      <input type="hidden" name="udmToBeSend" id="udmToBeSend" value=""/>
      <input type="hidden" name="qteDispoToBeSend" id="qteDispoToBeSend" value=""/>

      <!-- DEBUT CONTENEUR DE MESSAGE  -->
      <?php $msg = (!empty($msg['type']))?$msg:null;?>
        <div class="<?= $msg['type'] ?>">
          <strong><?= $msg['strong']?></strong> <?= $msg['text']?>
        </div>
      <!-- FIN CONTENEUR DE MESSAGE -->

      <!-- NOUVEAU PRODUIT -->
      <fieldset>
        <legend>
          <h5>INFOS SPECIFIQUES</h5>
        </legend>
      </fieldset>

       <div class="form-group">
          <label class="col-sm-4 control-label">Date de l'opération :<span class="asterisk">*</span></label>
          <div class="col-sm-4">
            <input class="form-control datepicker" autocomplete="off" id="datefrom" name="operationdate" value="<?= date('m/d/Y')?>">
          </div>
        </div>
        
      <div class="form-group">
        <label class="col-sm-4 control-label">Code de Produit : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <input type="text" disabled value="<?= (isset($produitDtls->productCode)) ? $produitDtls->productCode : Null ?>" class="form-control" name="productCode" id="productCode" autocomplete="off"/>
        </div>

      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Type :<span class="asterisk">*</span></label>
        <div class="col-sm-4">
          <select id="productType" name="productType" class="form-control">
            <option value="1" disabled selected <?= (isset($_POST[Yii::$app->params['productType']])) ? $_POST[Yii::$app->params['productType']] ==  1 ? 'selected' : Null : Null ?>>Stock</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">D&#233;signation : <span class="asterisk">*</span></label>
        <div class="col-sm-4">
          <input type="text" onKeypress="return false;" value="<?= (isset($produitDtls->libelle))? $produitDtls->libelle : Null ?>" class="form-control" name="productName" id="productName"   autocomplete="off"/>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-4 control-label">Cat&#233;gorie : <span class="asterisk">*</span></label>
        <div class="col-sm-4">
          <select id="productCategory" name="productCategory" class="form-control">
            <option value="0"><?= Yii::t('app','slectOne')?></option>
            <?php
              if(is_array($productCategories) && sizeof($productCategories) > 0){
                  foreach ($productCategories as $data) : ?>
                    <option value="<?= $data['id'] ?>"  <?= ($data['id'] == $produitDtls->categoryId) ? 'selected' : Null ?>><?= $data['nom'] ?></option>;
            <?php endforeach;
              }
            ?>
          </select>
        </div>
      </div>

      <fieldset>
        <legend>
          <h5>INFOS QUANTIFICATION</h5>
        </legend>
      </fieldset>

      <div class="form-group">
        <label class="col-sm-4 control-label">Quantit&#233; Disponible <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <input type="text" disabled value="<?= (isset($produitDtls->qteDispo)) ? $produitDtls->qteDispo : Null ?>" class="form-control" name="productCode" id="productCode" autocomplete="off"/>
        </div>

      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Quantit&#233; &#224; ajouter : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <input type="text" value="<?= (isset($_POST['qteAajouter'])) ? $_POST['qteAajouter'] : Null ?>" class="form-control" name="qteAajouter" id="qteAajouter" autocomplete="off"/>
        </div>

      </div>


      <div class="form-group">
        <label class="col-sm-4 control-label">Prix d'achat TT : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <input type="text" value="<?= (isset($_POST['ttprixachat'])) ? $_POST['ttprixachat'] : Null ?>" class="form-control" name="ttprixachat" id="ttprixachat" autocomplete="off"/>
        </div>

      </div>


    </div>
    <!-- FIN PANEL BODY -->

    <!-- DEBUT : FOOTER -->
    <div class="panel-footer">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3">
          <a class="btn btn-circle btn-success" onclick="document.getElementById('action_key').value='<?= base64_encode(strtolower("enrgAjoutsQte")) ?>'; document.getElementById('action_on_this').value='<?= base64_encode($produitDtls->slimproductid) ?>'; document.getElementById('udmToBeSend').value='<?= base64_encode($produitDtls->udm) ?>';  document.getElementById('qteDispoToBeSend').value='<?= base64_encode($produitDtls->qteDispo) ?>'; document.getElementById('addtoqtedispoForm').submit();"><i class="glyphicon glyphicon-save"></i>&nbsp;Enregistrer</a>
          <span>&nbsp;</span>
          <a href="<?= Yii::$app->request->baseUrl.'/'.md5('inventaire_produits');?>" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Annuler</a>
        </div>
      </div>
    </div>
    <!-- FIN : FOOTER -->
  </form>
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
