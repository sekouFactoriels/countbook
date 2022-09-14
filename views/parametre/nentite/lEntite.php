<?php
  # RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
  $msg = (!empty($msg)) ? unserialize($msg) : $msg;
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="panel-btns">
      <a href="<?= Yii::$app->request->baseUrl.'/'.md5('parametre_lEntite');?>" style="color: #000;" class="btn btn-circle btn-white" name="lentite" id="lentite"> <i class="fa fa-plus">&nbsp;</i><?= Yii::t('app','newProductLabel')?></a>
    </div>
    <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i><?= Yii::t('app','entite_lentite')?></h4>
  </div>
  <div class="panel-body">
    <form action="<?= Yii::$app->request->baseUrl.'/'.md5("parametre_entite")?>" id="lEntite_frm" name="lEntite_frm" method="post">
      <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
            $token2 = Yii::$app->getSecurity()->generateRandomString();
            $token2 = str_replace('+','.',base64_encode($token2));
      ?>
          <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
          <input type="hidden" name="token2" value="<?= $token2 ?>"/>
          <input type="hidden" name="action_key" id="action_key" value=""/>
          <input type="hidden" name="action_on_this" id="action_on_this" value=""/>
          <input type="hidden" name="msg" id="msg" value=""/>


          <div class="table-responsive" id="listtable">
            <table class="table">
              <thead>
                 <tr>
                    <th colspan="3">
                      <div class="form-group">
                        <label><?= Yii::t('app','trierSelon')?> <span> : </span> </label>
                        <select class="form-control" id="selectCriteria" name="selectCriteria">
                          <option value="1" <?= isset($_POST[Yii::$app->params['selectCriteria']]) ? $_POST[Yii::$app->params['selectCriteria']] == 1 ? 'selected' : '' : ''?> ><?= Yii::t('app','dernierAjout')?></option>
                        </select>
                      </div>
                    </th>

                    <th colspan="3">
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

                    <th colspan="3">
                      <div class="form-group">
                        <a href="javascript:;" class="btn btn-circle btn-primary" name="afficheBtn" id="afficheBtn" onclick="submitFilter()"> <i class="fa fa-eye">&nbsp;</i><?= Yii::t('app','afficher')?></a>
                        <span>&nbsp;</span>
                        <a href="javascript:;" class="btn btn-circle btn-default" name="" id=""> <i class="fa fa-refresh">&nbsp;</i><?= Yii::t('app','reset')?></a>
                      </div>
                    </th>
                    <th colspan="2">&nbsp;</th>
                </tr>
                <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES fournisseurs -->
                <tr>
                  <th>#</th>
                  <th style="width: 200px;">Nom</th>
                  <th style="width: 200px;">Adresse</th>
                  <th style="width: 200px;">Telephone</th>
                  <th style="width: 200px;">Description</th>
                  <th style="width: 200px;">Email</th>
                  <th  style="text-align: center;">Actions</th>
                </tr>
                <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
              </thead>
              <tbody>
                
  
              </tbody>
            </table>
        </div>
        </form>
        </div>
        </div>
        <?php require_once('script/lEntite_sc.php'); ?>