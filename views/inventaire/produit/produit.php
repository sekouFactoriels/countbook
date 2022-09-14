<?php
  require_once('script/produit_js.php');
  # RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
  $msg = (!empty($msg)) ? unserialize($msg) : $msg;
  $produitDtls = (isset($produitDtls)) ? json_decode($produitDtls) : Null;
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><i class="fa fa-edit"></i>&nbsp;<?= Yii::t('app','inventaire_produit')?></h4>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("inventaire_produits")?>" id="produitForm" name="produitForm" method="post">
    <!-- DEBUT PANEL BODY -->
    <div class="panel-body">
      <?=
        Yii::$app->nonSqlClass->getHiddenFormTokenField();
        $token2 = Yii::$app->getSecurity()->generateRandomString();
        $token2 = str_replace('+','.',base64_encode($token2));
      ?>
      <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
      <input type="hidden" name="token2" value="<?= $token2 ?>"/>
      <input type="hidden" name="action_key" id="action_key" value="<?= md5('editionProduct')?>"/>
      <input type="hidden" name="action_on_this" id="action_on_this" value=""/>
      <input type="hidden" name="ajax_action_key" id="ajax_action_key" value=""/>
      <input type="hidden" name="msg" id="msg" value=""/>

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
        <label class="col-sm-4 control-label">Code de Produit : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <input type="text" value="<?= (isset($produitDtls->productCode)) ? $produitDtls->productCode : Null ?>" class="form-control" name="productCode" id="productCode" autocomplete="off"/>
        </div>

      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Type :<span class="asterisk">*</span></label>
        <div class="col-sm-4">
          <select id="productType" name="productType" class="form-control">
            <option value="1" <?= (isset($_POST[Yii::$app->params['productType']])) ? $_POST[Yii::$app->params['productType']] ==  1 ? 'selected' : Null : Null ?>>Stock</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">D&#233;signation : <span class="asterisk">*</span></label>
        <div class="col-sm-4">
          <input type="text" value="<?= (isset($produitDtls->libelle))? $produitDtls->libelle : Null ?>" class="form-control" name="productName" id="productName"   autocomplete="off"/>
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
        <div class="col-sm-1">
          <a href="javascript:;" onClick="$('#newProductCategory').modal('show')" class="btn btn-circle btn-default" name="addCategory" id="addCategory"><i class="fa fa-plus-circle"></i></a>
        </div>
      </div>

      <fieldset>
        <legend>
          <h5>INFOS QUANTIFICATION</h5>
        </legend>
      </fieldset>

      <div class="form-group">
        <label class="col-sm-4 control-label">Quantite Disponible : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <input type="text" value="<?= (isset($produitDtls->qteDispo)) ? $produitDtls->qteDispo : Null ?>" class="form-control" name="qteDispo" id="qteDispo" autocomplete="off"/>
        </div>

      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Unit&#233; de mesure (UDM) : </label>
        <div class="col-sm-4">
          <select class="form-control chosen-select" name="udm" id="udm">
            <option value="">Selectionne un</option>
            <?php
              if(isset($produitUdm) && sizeof($produitUdm) > 0){
                foreach ($produitUdm as  $udmData) :
                  echo '<option value="'.$udmData['id'].'">'.$udmData["nom"].' </option>';
                endforeach;
              }
            ?>
          </select>
        </div>
      </div>

      <div style="display: none;">
      <fieldset style="display: none;">
        <legend>
          <h5>INFOS SUPLEMENTAIRES</h5>
        </legend>
      </fieldset>
      <div class="form-group">
        <label class="col-sm-4 control-label">Group :  </label>
        <div class="col-sm-4">
          <select id="group" name="group" class="form-control">
            <option value="0"><?= Yii::t('app','slectOne')?></option>
            <?php
              if(is_array($productGroupes) && sizeof($productGroupes) > 0){
                foreach ($productGroupes as $dataOfGroup) : ?>
                  <option value="<?= $dataOfGroup['id'] ?>" <?= ($dataOfGroup['id'] == $produitDtls->groupId) ? 'selected' : Null ?> ><?= $dataOfGroup['nom'] ?></option>;
            <?php
                endforeach;
              }
            ?>
          </select>
        </div>
        <div class="col-sm-1">
          <a href="javascript:;" onclick="$('#newProductGroup').modal('show')" class="btn btn-circle btn-default" name="addGroup" id="addGroup"><i class="fa fa-plus-circle"></i></a>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Marque : </label>
        <div class="col-sm-4">
          <select id="productMarque" name="productMarque" class="form-control">
            <option value="0"><?= Yii::t('app','slectOne')?></option>
            <?php
              if(is_array($productMarque) && sizeof($productMarque) > 0){
                foreach ($productMarque as $marqueData) : ?>
                  <option value="<?= $marqueData['id'] ?>" <?= ($marqueData['id'] == $produitDtls->markId) ? 'selected' : Null ?> > <?= $marqueData['nom'] ?> </option>;
            <?php
                endforeach;
              }
            ?>
          </select>
        </div>
        <div class="col-sm-1">
          <a href="javascript:;" onClick="$('#newProductMarque').modal('show')" class="btn btn-circle btn-default" name="addMark" id="addMark"><i class="fa fa-plus-circle"></i></a>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Nom g&#233;n&#233;rique :  </label>
        <div class="col-sm-4">
          <select id="generiqueNameId" name="generiqueNameId" class="form-control">
            <option value="0"><?= Yii::t('app','slectOne')?></option>
            <?php
              if(is_array($productGenericName) && sizeof($productGenericName) > 0){
                foreach ($productGenericName as $data) : ?>
                <option value="<?= $data['id'] ?>" <?= ($data['id'] == $produitDtls->generiqueId) ? 'selected' : Null ?> > <?= $data['nom'] ?></option>;
            <?php
                endforeach;
              }
            ?>
          </select>
        </div>
        <div class="col-sm-1">
          <a href="javascript:;" onClick="$('#newProductGenericName').modal('show')" class="btn btn-circle btn-default" name="addGeneriqProduct" id="addGeneriqProduct"><i class="fa fa-plus-circle"></i></a>
        </div>
      </div>
      </div>

      <fieldset>
        <legend>
          <h5>INFOS TARIFAIRE</h5>
        </legend>
      </fieldset>

      <div class="form-group">
        <label class="col-sm-4 control-label">Prix d&#233;achat Unitaire (GNF):
        </label>
        <div class="col-sm-4">
          <input type="text" value="<?= (isset($produitDtls->prixUnitaireAchat)) ? $produitDtls->prixUnitaireAchat  : Null ?>" class="form-control" name="productPrixAchat" id="productPrixAchat" autocomplete="off"/>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Prix de vente Unitaire (GNF):
        </label>
        <div class="col-sm-4">
          <input type="text" value="<?= (isset($produitDtls->prixUnitaireVente))? $produitDtls->prixUnitaireVente : Null ?>" class="form-control" name="productPrixVente" id="productPrixVente"   autocomplete="off"/>
        </div>
      </div>

    </div>
    <!-- FIN PANEL BODY -->

    <!-- DEBUT : FOOTER -->
    <div class="panel-footer">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3">
          <a class="btn btn-circle btn-success" onclick="document.getElementById('action_key').value='<?= base64_encode(strtolower("enrgmodifis")) ?>'; document.getElementById('action_on_this').value='<?= base64_encode($produitDtls->slimproductid) ?>'; document.getElementById('produitForm').submit();"><i class="glyphicon glyphicon-refresh"></i>&nbsp;Mettre &#224; jour</a>
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
<?php
  # INCLUDE PRODUCT CATEGORY
  require_once(Yii::$app->basePath.'/views/inventaire/productCategory/productCategory.php');
  # INCLUDE PRODUCT GROUP
  require_once(Yii::$app->basePath.'/views/inventaire/productGroup/productGroup.php');
  # INCLUDE PRODUCT MARQUE
  require_once(Yii::$app->basePath.'/views/inventaire/productMarque/productMarque.php');
  # INCLUDE GENRIC NAME
  require_once(Yii::$app->basePath.'/views/inventaire/productGenericName/productGenericName.php');

?>
