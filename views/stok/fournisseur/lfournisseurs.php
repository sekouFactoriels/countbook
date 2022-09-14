  <?php
  //die(var_dump($founisseurListe));
  # RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
  $msg = (!empty($msg)) ? unserialize($msg) : $msg;

?>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="panel-btns">
      <a href="<?= Yii::$app->request->baseUrl.'/'.md5('stok_lfounisseurs');?>" style="color: #000;" class="btn btn-circle btn-white" name="lfournisseur" id="lfournisseur"> <i class="fa fa-plus">&nbsp;</i><?= Yii::t('app','newProductLabel')?></a>
    </div>
    <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i><?= Yii::t('app','l_fournisseur')?></h4>
  </div>
  <div class="panel-body">
    <form action="<?= Yii::$app->request->baseUrl.'/'.md5("stok_fournisseur")?>" id="lfournisseurform" name="lfournisseurform" method="post">
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
                  <th>Nom</th>
                  <th style="width: 200px;">Societe</th>
                  <th style="width: 200px;">Telephone</th>
                  <th style="width: 200px;">Adresse</th>
                  <th colspan="4" style="text-align: center;">Actions</th>
                </tr>
                <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
              </thead>
              <tbody>
              <?php foreach ($founisseurListe as $key=>$data): 
              $cmpt = $key + 1;
              ?>
                <tr>
                  <td><?=$cmpt ?></td>
                  <td><?=$data['nom']?></td>
                  <td><?=$data['societe']?></td>
                  <td><?=$data['telephone']?></td>
                  <td><?=$data['adresse']?></td>
                  <td class="table-action" colspan="2">
                  <a href="#"><i class="fa fa-pencil"></i></a>
                  <a href="#" class="delete-row"><i class="fa fa-trash-o"></i></a>
                </td>
                  
                </tr>
              <?php endforeach ?>
              </tbody>
            </table>
        </div>
        </form>
        </div>
        </div>
        <?php require_once('script/lfournisseur_sc.php'); ?>