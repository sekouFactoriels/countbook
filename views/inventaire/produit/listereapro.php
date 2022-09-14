<?php
  //$msg = (!empty($msg)) ? unserialize($msg) : $msg;
  $userPrimaryData = (isset($userPrimaryData)) ? unserialize($userPrimaryData) : Null;
?>
<form action="<?= yii::$app->request->baseurl.'/'.md5('vente_simple') ?>" id="listeventes" name="listeventes" method="post">
      <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
            $token2 = Yii::$app->getSecurity()->generateRandomString();
            $token2 = str_replace('+','.',base64_encode($token2));
      ?>
          <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
          <input type="hidden" name="token2" value="<?= $token2 ?>"/>
          <input type="hidden" name="action_key" id="action_key" value=""/>
          <input type="hidden" name="action_on_this" id="action_on_this" value=""/>
          <input type="hidden" name="action_on_this_val" id="action_on_this_val" value=""/>
          <input type="hidden" name="msg" id="msg" value=""/>
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="panel-btns">
            <a href="javascript:;" style="color: #000;" class="btn btn-circle btn-white" name="lbl_listeventes"> <i class="fa fa-plus">&nbsp;</i><?= Yii::t('app','newProductLabel')?></a>
          </div>
          <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i>Historique des réapprovisionnements</h4>
        </div>
        <div class="panel-body">
    

          <?php $msg = (!empty($msg['type']))?$msg:null;?>
            <div class="<?= $msg['type'] ?>">
              <strong><?= $msg['strong']?></strong> <?= $msg['text']?>
            </div>

          <div class="table-responsive" id="listtable">
            <table class="table">
              <thead>
                 <tr>
                    <th colspan="3">
                      <div class="form-group">
                        <label><?= Yii::t('app','trierSelon')?> <span> : </span> </label>
                        <select class="form-control" id="selectCriteria" name="selectCriteria">
                          <option value="1" <?= isset($_POST['selectCriteria']) ? $_POST['selectCriteria'] == 1 ? 'selected' : '' : ''?> ><?= Yii::t('app','venterecente')?></option>
                          <option value="2" <?= isset($_POST['selectCriteria']) ? $_POST['selectCriteria'] == 2 ? 'selected' : '' : ''?> ><?= Yii::t('app','ventemoinrecente')?></option>
                        </select>
                      </div>
                    </th>

                    <th colspan="3">
                      <div class="form-group">
                        <label><?= Yii::t('app','donneeRecherche')?> <span> : </span> </label>
                        <input class="form-control" type="text" id="donneeRecherche" name="donneeRecherche" value="<?= isset($_POST[Yii::$app->params['donneeRecherche']]) ? $_POST[Yii::$app->params['donneeRecherche']] : Null ?>"/>
                      </div>
                    </th>

                    <th colspan="">
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
                        <a href="javascript:;" onclick="submitFilter()" class="btn btn-circle btn-primary" name="afficheBtn" id="afficheBtn"> <i class="fa fa-eye">&nbsp;</i><?= Yii::t('app','afficher')?></a>
                        <!-- <span>&nbsp;</span>
                        <a href="javascript:;" onclick="$('#action_key').val('<?= md5("retreviewVente")?>'); $('#listeventes').submit();" class="btn btn-circle btn-default" name="" id=""> <i class="fa fa-refresh">&nbsp;</i><?= Yii::t('app','reset')?></a> -->
                      </div>
                    </th>
                    <th colspan="2">&nbsp;</th>
                </tr>
                <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                <tr>
                  <th>#</th>
                  <th style="width: 150px;">No Facture</th>
                  <th style="width: 150px;">Date Vente</th>
                  <th style="width: 250px;">Compte Client</th>
                  <th style="width: 100px;">Facture globale</th>
                  <th style="width: 100px;">Remise Mon&#233;taire</th>
                  <th style="width: 120px;">Montant Final (+Remise)</th>
                  <th style="width: 100px;">Montant per&#231;ue</th>
                  <th style="width: 100px; text-align: center;">Reste à payer</th>
                  <th style="width: 100px; text-align: center;">[-]</th>
                  <th style="width: 100px; text-align: center;">[-]</th>
                  <th style="width: 100px; text-align: center;">[-]</th>
                </tr>
                <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
              </thead>

              <tbody>

              </tbody>
            </table>
        </div>
      </div>
    </div>
</form>
