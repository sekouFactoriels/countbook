
<!-- BEGIN FORM-->
<form action="" name="countbook_form" id="countbook_form" action="<?=yii::$app->request->baseurl.'/'.md5('fournisseur_themain') ?>" method="post">
<?=
  Yii::$app->nonSqlClass->getHiddenFormTokenField();
  $token2 = Yii::$app->getSecurity()->generateRandomString();
  $token2 = str_replace('+','.',base64_encode($token2));
  ?>

  <!-- DEBUT : BASIC HIDDEN IMPUT FOR THE FORM -->
  <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
  <input type="hidden" name="token2" value="<?= $token2 ?>"/>
  <input type="hidden" name="action_key" id="action_key" value=""/>
  <input type="hidden" name="action_on_this" id="action_on_this" value=""/>

  <div class="panel panel-default">
    <!-- LIBELLE TABLEAU -->
    <div class="panel-heading">
      <h4 class="panel-title"><i class="fa fa-link"></i>&nbsp;&nbsp;LISTE FOURNISSEURS</h4>
    </div>
    <!-- LIBELLE TABLEAU -->

    <!-- CORPS TABLEAU -->
    <div class="panel-body">
      <div class="table-responsive" id="listtable">

      <table class="table">
        <thead>
          <tr>
            <th colspan="2">
              <div class="form-group">
                <label><?= Yii::t('app','donneeRecherche')?> <span> : </span> </label>
                <input class="form-control" type="text" id="donneeRecherche" name="donneeRecherche" value="<?= isset($_POST['donneeRecherche']) ? $_POST['donneeRecherche'] : Null ?>"/>
              </div>
            </th>
            <th>
              <div class="form-group">
                <label><?= Yii::t('app','margeLigne')?> <span> : </span> </label>
                <select class="form-control" id="limit" name="limit">
                  <option value="1" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 1 ? 'selected' : '' : ''?> >1 - 10</option>
                  <option value="2" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 2 ? 'selected' : '' : ''?> >1 - 20</option>
                  <option value="3" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 3 ? 'selected' : '' : ''?> >1 - 30</option>
                  <option value="4" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 4 ? 'selected' : '' : ''?> >1 - 40</option>
                  <option value="5" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 5 ? 'selected' : '' : ''?> >1 - 50</option>
                  <option value="6" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 6 ? 'selected' : '' : ''?> >1 - 50 +</option>
                </select>
              </div>
            </th>
            <th>
              <div class="form-group">
                <a href="javascript:;" onclick="submitFilter()" class="btn btn-circle btn-primary" name="afficheBtn" id="afficheBtn">Afficher le resultat</a>
              </div>
            </th>
            <th>&nbsp;</th>

          <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
          <tr>
            <th>#</th>
            <th>Raison Sociale</th>
            <th>Denomination</th>
            <th>Téléphone</th>
            <th>Site Web</th>
            <th style="width: 120px; text-align: center;">[-]</th>
          </tr>
          <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
        </thead>

        <tbody>
          <?php 
          if(isset($fournisseurs) && sizeof($fournisseurs)>0)
          {
            $i = 0;
            foreach($fournisseurs as $key => $each_fournisseur)
            {
              $compte_btn = '<a href="javascript:;" Class="btn btn-circle btn-primary" onclick="document.getElementById(\'action_key\').value=\''.md5(strtolower("charger_data_fournisseur")).'\';  document.getElementById(\'action_on_this\').value=\''.$each_fournisseur["id"].'\'; $(\'#countbook_form\').submit();">ACCEDER AU PROFIL</a>';

              ++$i;
              echo '<tr>
                <td>'.$i.'</td>
                <td>'.yii::$app->nonSqlClass->libeller_raison_sociale($each_fournisseur["raison_sociale"]).'</td>
                <td>'.$each_fournisseur["denomination"].'</td>
                <td>'.$each_fournisseur["telephone"].'</td>
                <td>'.$each_fournisseur["site_web"].'</td>
                <td>'.$compte_btn.'</td>
              </tr>';
            }
          }else{
            echo '<tr><td colspan="7">'.yii::t('app','nonEnre').'</td></tr>';
          }

          ?>
        </tbody>
        <!-- CORPS TABLEAU -->
      </table>
    </div>
  </div>
</div>
<?php require_once('script/fournisseurs_js.php')?>
</form>