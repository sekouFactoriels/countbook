	<?php
  
  # RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
  $msg = (!empty($msg)) ? unserialize($msg) : $msg;

?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><i class="glyphicon glyphicon-import"></i>&nbsp;<?= Yii::t('app','fournisseur_nfournisseur')?></h4>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("stok_fournisseur")?>" id="nfournisseurForm" name="nfournisseurForm"" method="post">
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
      <input type="hidden" name="msg" id="msg" value=""/>

      <!-- DEBUT CONTENEUR DE MESSAGE  -->
      <?php $msg = (!empty($msg['type']))?$msg:null;?>
        <div class="<?= $msg['type'] ?>">
          <strong><?= $msg['strong']?></strong> <?= $msg['text']?>
        </div>
      <!-- FIN CONTENEUR DE MESSAGE -->

      <!-- NOUVEAU fournisseur -->
      <fieldset>
        <legend>
          <h5>INFOS SPECIFIQUES</h5>
        </legend>
      </fieldset>
      <div class="form-group required-container">
          <label class="col-sm-4 control-label">Type fournisseur :<span class="asterisk">*</span>
          </label>
          <div class="col-sm-4">
            <select class="form-control chosen-select" name="typeFourn" id="typeFourn">
              <option value="0">Selectionne un</option>
              <option value="1">Individu</option>
              <option value="2">Societe</option>
             
            </select>
          </div>
      </div>
      <div class="form-group required-container">
        <label class="col-sm-4 control-label">Nom:<span class="asterisk">*</span>
        </label>
            <div class="col-sm-4">
              <input type="text" value="" class="form-control" name="nom" id="nom" autocomplete="off"/>
            </div>
     </div>
	  <div class="form-group required-container">
		    <label class="col-sm-4 control-label">Socit&#233;:<span class="asterisk">*</span>
		    </label>
		      	<div class="col-sm-4">
		      		<input type="text" value="" class="form-control" name="societe" id="societe" autocomplete="off"/>
		      	</div>
	   </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">T&#233;l&#233;phone:<span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <input type="text" value="" class="form-control" name="tel" id="tel" autocomplete="off"/>
        </div>
      </div>
      <div class="form-group required-container">
	      	<label class="col-sm-4 control-label">Adresse :<span class="asterisk">*</span>   
	      	</label>
	      	<div class="col-sm-4">
	      	   <input type="text" value="" class="form-control" name="adresse" id="adresse" autocomplete="off"/>
	        </div>        
      </div>
      <fieldset>
        <legend>
          <h5>INFOS COMPLEMENTAIRES</h5>
        </legend>
      </fieldset>

       <div class="form-group">
        <label class="col-sm-4 control-label">Site Web : 
        </label>
        <div class="col-sm-4">
          <input type="text" value="" class="form-control" name="site" id="site" autocomplete="off"/>
        </div>
        
      </div>
      <div class="form-group">
      	<label class="col-sm-4 control-label">Mobil:
      	</label>
      	<div class="col-sm-4">
      		<input type="text" value="" class="form-control" name="mobil" id="mobil" autocomplete="off"/>
      	</div>
      </div>
      <div class="form-group required-container">
          <label class="col-sm-4 control-label">type de societ&#233; :
          </label>
          <div class="col-sm-4">
            <select class="form-control chosen-select" name="typeSoci" id="typeSoci">
              <option value="0">Selectionne un</option>
              <option value="1">SARL</option>
              <option value="2">SA</option>
             
            </select>
          </div>
      </div>


    </div>
    <!-- FIN PANEL BODY -->

    <!-- DEBUT : FOOTER -->
    <div class="panel-footer">
      <div class="row">
        <div class="col-sm-9 col-sm-offset-3">
          <a class="btn btn-circle btn-success" onclick="javascript:$('#gnrlFormSubmitModal').modal('show');"><i class="glyphicon glyphicon-save"></i>&nbsp;Enregistrer</a>
          <span>&nbsp;</span>
          <a type="reset" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Anuler</a>
        </div>
      </div>
    </div>
    <!-- FIN : FOOTER -->
  </form>
</div>
<?php require_once('script/nfournisseur_sc.php'); ?>



