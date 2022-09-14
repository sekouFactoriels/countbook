<?php
require_once('script/nproduit_js.php');
# RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
$msg = (!empty($msg)) ? unserialize($msg) : $msg;
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="panel-btns">
      <a href="<?= Yii::$app->request->baseUrl . '/' ?>" style="color: #000;" class="btn btn-circle btn-white"> <i class="fa fa-arrow-circle-left"></i></a>
    </div>
    <h4 class="panel-title"><i class="fa fa-plus-circle"></i>&nbsp;<?= Yii::t('app', 'inventaire_nproduit') ?></h4>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl . '/' . md5("inventaire_nproduit") ?>" id="nProduitForm" name="nProduitForm" method="post">
    <!-- DEBUT PANEL BODY -->
    <div class="panel-body">
      <?=
      Yii::$app->nonSqlClass->getHiddenFormTokenField();
      $token2 = Yii::$app->getSecurity()->generateRandomString();
      $token2 = str_replace('+', '.', base64_encode($token2));
      ?>
      <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
      <input type="hidden" name="token2" value="<?= $token2 ?>" />
      <input type="hidden" name="action_key" id="action_key" value="<?= md5('newProduct') ?>" />
      <input type="hidden" name="action_on_this" id="action_on_this" value="" />
      <input type="hidden" name="ajax_action_key" id="ajax_action_key" value="" />
      <input type="hidden" name="msg" id="msg" value="" />

      <!-- DEBUT CONTENEUR DE MESSAGE  -->
      <?php $msg = (!empty($msg['type'])) ? $msg : null; ?>
      <div class="<?= $msg['type'] ?>">
        <strong><?= $msg['strong'] ?></strong> <?= $msg['text'] ?>
      </div>
      <!-- FIN CONTENEUR DE MESSAGE -->

      <!-- NOUVEAU PRODUIT -->

      

      <div class="form-group hidden">
        <label class="col-sm-4 control-label">Groupe :<span class="asterisk">*</span></label>
        <div class="col-sm-4 input-group">
          <select id="productType" name="productType" class="form-control">
            <option value="1" <?= (isset($_POST['productType'])) ? $_POST['productType'] ==  1 ? 'selected' : Null : Null ?>>Produit / Service</option>
          </select>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

       <div class="form-group">
        <label class="col-sm-4 control-label">Cat&#233;gorie : <span class="asterisk">*</span></label>
        <div class="col-sm-4 input-group">
          <select id="productCategory" name="productCategory" class="form-control chosen-select">
            <option value="0"><?= Yii::t('app', 'slectOne') ?></option>
            <?php
            if (is_array($productCategories) && sizeof($productCategories) > 0) {
              foreach ($productCategories as $data) : ?>
                <option value="<?= $data['id'] ?>" <?= (isset($_POST[Yii::$app->params['productCategory']]) && $_POST[Yii::$app->params['productCategory']] == $data['id']) ? 'selected' : '' ?>><?= $data['nom'] ?></option>;
            <?php endforeach;
            }
            ?>
          </select>
          <a href="<?= Yii::$app->request->baseUrl . '/' . md5('inventaire_cats') ?>" class="btn btn-default input-group-addon" name="addCategory" id="addCategory"><i class="fa fa-plus-circle"></i></a>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Unit&#233; de Vente et Achat (U.V.A) : <span class="asterisk">*</span></label>
        <div class="col-sm-4 input-group">
          <?php
          if (isset($productUdm) && sizeof($productUdm)) {
          ?>
            <select class="form-control chosen-select" name="udm" id="udm">
              <option value="">Selectionne un</option>
              <?php
              foreach ($productUdm as  $udmData) :
                echo '<option value="' . $udmData['id'] . '">' . $udmData["nom"] . ' </option>';
              endforeach;
              ?>
            </select>
            <a href="<?= Yii::$app->request->baseUrl . '/' . md5('inventaire_udms') ?>" class="btn btn-default input-group-addon" name="addCategory" id="addCategory"><i class="fa fa-plus-circle"></i></a>
          <?php
          } else {
            echo '<span class="label label-info">' . yii::t("app", "no_data_found") . '</span>';
          ?>
            <a href="<?= Yii::$app->request->baseUrl . '/' . md5('inventaire_udms') ?>" class="btn btn-default input-group-addon" name="addCategory" id="addCategory"><i class="fa fa-plus-circle"></i></a>
          <?php
          }
          ?>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">D&#233;signation article / service : <span class="asterisk">*</span></label>
        <div class="col-sm-4 input-group">
          <input type="text" value="<?= (isset($_POST[Yii::$app->params['productName']])) ? $_POST['productName'] : Null ?>" class="form-control" name="productName" id="productName" autocomplete="off" />
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Branche : <span class="asterisk">*</span></label>
        <div class="col-sm-4 input-group">
          <select class="form-control chosen-select" name="entite" id="entite">
            <?php
            if (isset($entite) && sizeof($entite) > 0) {
              if (sizeof($entite) > 1) {
                echo '<option value="">Selectionner la branche appropri&#233;e</option>';
              }
              foreach ($entite as  $data) :
                echo '<option value="' . $data['id'] . '">' . $data["nom"] . ' </option>';
              endforeach;
            }
            ?>
          </select>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <fieldset>
        <legend>
          <h5>STOCK / DISPONIBILITE</h5>
        </legend>
      </fieldset>


      <div class="form-group">
        <label class="col-sm-4 control-label">Stock de depart / Initial : <span class="asterisk">*</span></label>
        <div class="col-sm-4 input-group">
          <input type="text" value="<?= (isset($_POST[Yii::$app->params['prodcutQte']])) ? $_POST['prodcutQte'] : 0 ?>" class="form-control" name="prodcutQte" id="prodcutQte" autocomplete="off" />
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Stock d&#39;alerte : <span class="asterisk">*</span></label>
        <div class="col-sm-4 input-group">
          <input type="text" value="<?= (isset($_POST[Yii::$app->params['prodcutMinQtePV']])) ? $_POST['prodcutMinQtePV'] : Null ?>" class="form-control" name="prodcutMinQtePV" id="prodcutMinQtePV" autocomplete="off" />

          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Stock de s&#233;curit&#233; : <span class="asterisk">*</span></label>
        <div class="col-sm-4 input-group">
          <input type="text" value="<?= (isset($_POST[Yii::$app->params['prodcutMinQteEntrep']])) ? $_POST['prodcutMinQteEntrep'] : Null ?>" class="form-control" name="prodcutMinQteEntrep" id="prodcutMinQteEntrep" autocomplete="off" />
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="hide">
        <div class="form-group">
          <label class="col-sm-4 control-label">Group : </label>
          <div class="col-sm-4 input-group">
            <select id="productGroup" name="group" class="form-control">
              <option value="0"><?= Yii::t('app', 'slectOne') ?></option>
              <?php
              if (is_array($productGroupes) && sizeof($productGroupes) > 0) {
                foreach ($productGroupes as $dataOfGroup) : ?>
                  <option value="<?= $dataOfGroup['id'] ?>" <?= (isset($_POST[Yii::$app->params['group']]) && $_POST[Yii::$app->params['group']] == $dataOfGroup['id']) ? 'selected' : Null ?>><?= $dataOfGroup['nom'] ?></option>;
              <?php
                endforeach;
              }
              ?>
            </select>
            <a href="javascript:;" onclick="$('#newProductGroup').modal('show')" class="btn btn-default input-group-addon" name="addGroup" id="addGroup"><i class="fa fa-plus-circle"></i></a>
          </div>
        </div>

        <div class="form-group" style="display: none;">
          <label class="col-sm-4 control-label">Marque : </label>
          <div class="col-sm-4">
            <select id="productMarque" name="productMarque" class="form-control input-group">
              <option value="0"><?= Yii::t('app', 'slectOne') ?></option>
              <?php
              if (is_array($productMarque) && sizeof($productMarque) > 0) {
                foreach ($productMarque as $marqueData) : ?>
                  <option value="<?= $marqueData['id'] ?>" <?= (isset($_POST[Yii::$app->params['productMarque']]) && $_POST[Yii::$app->params['productMarque']] == $marqueData['id']) ? 'selected' : Null ?>> <?= $marqueData['nom'] ?> </option>;
              <?php
                endforeach;
              }
              ?>
            </select>
            <a href="javascript:;" onClick="$('#newProductMarque').modal('show')" class="btn btn-default input-group-addon" name="addMark" id="addMark"><i class="fa fa-plus-circle"></i></a>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-4 control-label">Nom g&#233;n&#233;rique : </label>
          <div class="col-sm-4 input-group">
            <select id="generiqueNameId" name="generiqueNameId" class="form-control">
              <option value="0"><?= Yii::t('app', 'slectOne') ?></option>
              <?php
              if (is_array($productGenricName) && sizeof($productGenricName) > 0) {
                foreach ($productGenricName as $data) : ?>
                  <option value="<?= $data['id'] ?>" <?= (isset($_POST[Yii::$app->params['generiqueNameId']]) && $_POST[Yii::$app->params['generiqueNameId']] == $data['id']) ? 'selected' : Null ?>> <?= $data['nom'] ?></option>;
              <?php
                endforeach;
              }
              ?>
            </select>
            <a href="javascript:;" onClick="$('#newProductGenericName').modal('show')" class="btn btn-default input-group-addon" name="addGeneriqProduct" id="addGeneriqProduct"><i class="fa fa-plus-circle"></i></a>
          </div>
        </div>
      </div>
      <fieldset>
        <legend>
          <h5>TARIFICATION</h5>
        </legend>
      </fieldset>

      <div class="form-group">
        <label class="col-sm-4 control-label">Prix d&#39;achat / U.V.A (GNF) : <span class="asterisk">*</span></label>
        <div class="col-sm-4 input-group">
          <input type="text" value="<?= (isset($_POST[Yii::$app->params['productPrixAchat']])) ? $_POST[Yii::$app->params['productPrixAchat']]  : Null ?>" class="form-control" name="productPrixAchat" id="productPrixAchat" autocomplete="off" />
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Prix de vente / U.V.A (GNF): <span class="asterisk">*</span></label>
        <div class="col-sm-4 input-group">
          <input type="text" value="<?= (isset($_POST[Yii::$app->params['productPrixVente']])) ? $_POST[Yii::$app->params['productPrixVente']] : Null ?>" class="form-control " name="productPrixVente" id="productPrixVente" autocomplete="off" />
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>


      <fieldset>
        <legend>
          <h5>CODE ARTICLE / SERVICE</h5>
        </legend>
      </fieldset>


      <div class="form-group">
        <label class="col-sm-4 control-label">Génération code article : </label>
        <div class="col-sm-4 input-group">
          <select class="form-control" name="generation_code_produit" id="generation_code_produit" onchange="source_code_produit()">
            <option value="0">Génération automatique</option>
            <option value="1">Génération code barre</option>
          </select>
          <span class="input-group-addon hidden-md hidden-lg"></span>
        </div>
      </div>


      <div class="form-group">
        <label class="col-sm-4 control-label">Code de l'Article/Service : <span class="asterisk">*</span></label>
        <div class="col-sm-4 input-group" id="div_code_article">
          <input onKeypress="return false;" type="text" value="<?= (isset($_POST[Yii::$app->params['productCode']])) ? $_POST['productCode'] : Null ?>" class="form-control" name="productCode" id="productCode" autocomplete="off" onkeyup="verifierCaracteres();"/>
          <a href="javascript:;" onClick="getNewProductCode();" class="btn btn-default input-group-addon" name="productCode" id="productCode"><i class="fa fa-random"></i></a>
        </div>
      </div>

    </div>
    <!-- FIN PANEL BODY -->

    <!-- DEBUT : FOOTER -->
    <div class="panel-footer">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3">
          <a class="btn btn-circle btn-success" onclick="alertSaveProduct()"><i class="fa fa-check-circle"></i>&nbsp;<?= yii::t('app', 'ajout') ?></a>
          <span>&nbsp;</span>
          <a href="<?= Yii::$app->request->baseUrl . '/' ?>" class="btn btn-circle btn-default"><i class="fa fa-home"></i>&nbsp;<?= yii::t('app', 'rtour_accueil') ?></a>
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
      <div style="text-align:center;color:#EEEEEE;line-height:320px;width:100%;"><i class="fa fa-spin fa-spinner" style="font-size: 35px;"></i> <span style="font-size: 20px;">&nbsp;<?= Yii::t('app', 'validatCours') ?></span></div>
      </p>
    </div>
  </div>
</div>
<?php
# INCLUDE PRODUCT CATEGORY
require_once(Yii::$app->basePath . '/views/inventaire/productCategory/productCategory.php');
# INCLUDE PRODUCT GROUP
require_once(Yii::$app->basePath . '/views/inventaire/productGroup/productGroup.php');
# INCLUDE PRODUCT MARQUE
require_once(Yii::$app->basePath . '/views/inventaire/productMarque/productMarque.php');
# INCLUDE GENRIC NAME
require_once(Yii::$app->basePath . '/views/inventaire/productGenericName/productGenericName.php');

?>