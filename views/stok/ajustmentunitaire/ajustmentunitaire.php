<?php
  require_once ('script/ajustmentunitaire_sc.php');
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><i class="fa fa-adjust">&nbsp;</i>&nbsp;<?=yii::t('app','stok_ajustmentunitaire')?></h4>
  </div>
  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("inventaire_nproduit")?>" id="nProduitForm" name="nProduitForm" method="post">
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
          <h5>ACTEURS DU PROCESSUS</h5>
        </legend>
      </fieldset>
      <div class="form-group">
        <label class="col-sm-4 control-label">Source : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <select class="form-control chosen-select" name="listeEntrepot" id="listeEntrepot">
            <option value="0">Selectionne Un</option>
            <?php
              if(isset($listEntrepot) && sizeof($listEntrepot) > 0){
                foreach ($listEntrepot as $data) :
                  echo '<option value="'.$data["id"].'">'.$data["nom"].'</option>';
                endforeach;
              }
            ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Destination : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <select class="form-control chosen-select" name="pointdeVente" id="pointdeVente">
            <option value="0">Selectionne Un</option>
            <?php
              if(isset($listPointVente) && sizeof($listPointVente) > 0){
                foreach ($listPointVente as $data) :
                  echo '<option value="'.$data["id"].'">'.$data["nom"].'</option>';
                endforeach;
              }
            ?>
          </select>
        </div>
      </div>

      <fieldset>
        <legend>
          <h5>ELEMENTS A AJUSTER</h5>
        </legend>
      </fieldset>

      <div class="form-group">
        <label class="col-sm-4 control-label">Type d&#39;op&#233;ration : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <select class="form-control chosen-select"  name="typeAjustation" id="typeAjustation">
            <option value="0">Selectionne Un</option>
            <option value="1">Augmentation</option>
            <option value="2">Deduction</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Produit &#224; ajuster : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <select class="form-control chosen-select" onChange="getUDMproduct()" name="listProduct" id="listProduct">
            <option value="0">Saisiez les premi&#232;res lettres du produit</option>
            <?php
              if(isset($listProdcts) && sizeof($listProdcts) > 0){
                foreach ($listProdcts as $productData) :
                  echo '<option value="'.$productData["id"].'">'.$productData["libelle"].'</option>';
                endforeach;
              }
            ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">UDM du produit : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <select class="form-control" name="listUdm" id="listUdm">
            <option value="0">Selection un produit pour lister udm</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-4 control-label">Unit&#233;(s) de produit &#224; ajuster : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <input class="form-control" name="unitProduct" id="unitProduct" value=""/>
        </div>
        <div class="col-sm-1">
          <a href="javascript:;" onClick="$('#newProductGenericName').modal('show')" class="btn btn-circle btn-default" name="addGeneriqProduct" id="addGeneriqProduct"><i class="fa fa-money"></i>&nbsp;Equivalent Monaetaire</a>
        </div>
      </div>

      <!--  FOOTER : BUTTON SUBMIT -->
      <div class="panel-footer">
        <div class="row">
          <div class="col-sm-9 col-sm-offset-3">
            <a class="btn btn-circle btn-success" onclick="$('#beforeAjuster').modal('show')"><i class="fa fa-adjust"></i>&nbsp;Ajuster</a>
            <span>&nbsp;</span>
            <a type="reset" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Anuler</a>
          </div>
        </div>
      </div>
  </form>
</div>
<div class="modal fade" id="beforeAjuster" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="newProdCat">Ajuster un stok de Produit</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <p>Etes-vous sur de bien vouloir continuer cette action ?</p>
              </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" type="button" onClick="ajuster()" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;Oui</a>
                <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','fermer');?></button>
            </div>
        </div>
    </div>
</div>
<form methode="post" action="<?= Yii::$app->request->baseUrl.'/'.md5('site_index') ?>" id="goToMainPage">
  <div class="modal fade" id="actionMsg" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="newProdCat">Reussite</h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <p>Op&#233;ration d&#39;ajustment effectu&#233;e avec success !</p>
                </div>
              </div>
              <div class="modal-footer">
                  <a href="javascript:;" type="button" onClick="$('#goToMainPage').submit()" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;Retour au tableau</a>
                  <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Nouvelle Operation</button>
              </div>
          </div>
      </div>
  </div>

  <div class="modal fade" id="errorMsg" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="newProdCat">Echec</h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <p>Invalid op&#233;ration , </br> <ul><li>Soit la  quantit&#233; &#224; modifier est superieur &#224; c&#39;elle disponible </li><li>SOit les donn&#233;es saisies sont invalides</li></ul></p>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Annuler</button>
              </div>
          </div>
      </div>
  </div>
</form>
</div>
