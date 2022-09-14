<?php
  # RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
  $msg = (!empty($msg)) ? unserialize($msg) : $msg;

?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><i class="glyphicon glyphicon-import"></i>&nbsp;<?= Yii::t('app','entite_nentite')?></h4>
  </div>

  <form class="form-horizontal" action="<?= Yii::$app->request->baseUrl.'/'.md5("parametre_entite")?>" id="nEntite_form" name="nEntite_form" method="post">
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

     <!-- <input type="hidden" name="action_on_this" id="action_on_this" value=""/>
      <input type="hidden" name="ajax_action_key" id="ajax_action_key" value=""/>   -->
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
        <label class="col-sm-4 control-label">Nom : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <input type="text" value="" class="form-control" name="nom" id="nom" autocomplete="off"/>
        </div> 

        </div>    

        <div class="form-group">
        <label class="col-sm-4 control-label">Telephone : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <input type="text" value="" class="form-control" name="tel" id="tel" autocomplete="off"/>
        </div> 
        </div> 

        <div class="form-group">
        <label class="col-sm-4 control-label">Adresse : <span class="asterisk">*</span>
        </label>
        <div class="col-sm-4">
          <input type="text" value="" class="form-control" name="adresse" id="adresse" autocomplete="off"/>
        </div>
        </div>

        <div class="form-group">
          <label class="col-sm-4 control-label">Entreprise : <span class="asterisk">*</span>
          </label>
          <div class="col-sm-4">
            <select type="text" class="form-control" name="entreprise" id="entreprise">
              <option><?= Yii::t('app','slectOne')?></option>
              <?php
              if(isset($entreprises) && sizeof($entreprises)>0){
                foreach ($entreprises as $key => $value) {
                  echo '<option value="'.$value['id'].'">'.$value['nom'].'</option>';
                }
              }
              ?>
            </select>
          </div>
        </div>



         <fieldset>
        <legend>
          <h5>INFOS COMPLEMENTAIRE</h5>
        </legend>
      </fieldset>

         <div class="form-group">
        <label class="col-sm-4 control-label">email :
        </label>
        <div class="col-sm-4">
          <input type="text" value="" class="form-control" name="email" id="email" autocomplete="off"/>
        </div> 
        </div> 

      

         <div class="form-group">
        <label class="col-sm-4 control-label">Description :
        </label>
        <div class="col-sm-4">
          <input type="text" value="" class="form-control" name="description" id="description" autocomplete="off"/>
        </div> 
        </div> 
        

      <div class="form-group">
        <label class="col-sm-4 control-label">Date de creation:
        </label>
        <div class="col-sm-4">
          <input class="form-control datepicker" id="dte" name="dte" value="">
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

<?php  require_once('script/nEntite_sc.php'); ?>
