<?php
require_once('script/produits_js.php');
$msg = (!empty($msg)) ? unserialize($msg) : $msg;
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="panel-btns">

    </div>
    <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i>Réagistement du Stock</h4>
  </div>
  <div class="panel-body">
    <form action="<?= Yii::$app->request->baseUrl . '/' . md5("reajustement_themain") ?>" id="listeProduits" name="listeProduits" method="post">
      <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
      $token2 = Yii::$app->getSecurity()->generateRandomString();
      $token2 = str_replace('+', '.', base64_encode($token2));
      ?>
      <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
      <input type="hidden" name="token2" value="<?= $token2 ?>" />
      <input type="hidden" name="action_key" id="action_key" value="" />
      <input type="hidden" name="action_on_this" id="action_on_this" value="" />
      <input type="hidden" name="msg" id="msg" value="" />

      <?php $msg = (!empty($msg['type'])) ? $msg : null; ?>
      <div class="<?= $msg['type'] ?>">
        <strong><?= $msg['strong'] ?></strong> <?= $msg['text'] ?>
      </div>

      <div class="table-responsive" id="listtable">
        <table class="table">
          <thead>
            <tr>
              <th colspan="2">
                <div class="form-group">
                  <label><?= Yii::t('app', 'trierSelon') ?> <span> : </span> </label>
                  <select class="form-control" id="selectCriteria" name="selectCriteria">
                    <option value="1" <?= isset($_POST['selectCriteria']) ? $_POST['selectCriteria'] == 1 ? 'selected' : '' : '' ?>><?= Yii::t('app', 'dernierAjout') ?></option>
                    <option value="2" <?= isset($_POST['selectCriteria']) ? $_POST['selectCriteria'] == 2 ? 'selected' : '' : '' ?>><?= Yii::t('app', 'stokcroissant') ?></option>
                    <option value="3" <?= isset($_POST['selectCriteria']) ? $_POST['selectCriteria'] == 3 ? 'selected' : '' : '' ?>><?= Yii::t('app', 'stokdecroissant') ?></option>
                  </select>
                </div>
              </th>

              <th colspan="1">
                <div class="form-group">
                  <label><?= Yii::t('app', 'donneeRecherche') ?> <span> : </span> </label>
                  <input class="form-control" type="text" id="donneeRecherche" name="donneeRecherche" value="<?= isset($_POST[Yii::$app->params['donneeRecherche']]) ? $_POST[Yii::$app->params['donneeRecherche']] : Null ?>" />
                </div>
              </th>

              <th colspan="2">
                <div class="form-group">
                  <label><?= Yii::t('app', 'margeLigne') ?> <span> : </span> </label>
                  <select class="form-control" id="limit" name="limit">
                    <option value="1" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 1 ? 'selected' : '' : '' ?>>1 - 10</option>
                    <option value="2" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 2 ? 'selected' : '' : '' ?>>1 - 20</option>
                    <option value="3" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 3 ? 'selected' : '' : '' ?>>1 - 30</option>
                    <option value="4" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 4 ? 'selected' : '' : '' ?>>1 - 40</option>
                    <option value="5" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 5 ? 'selected' : '' : '' ?>>1 - 50</option>
                    <option value="6" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 6 ? 'selected' : '' : '' ?>>1 - 50 +</option>
                  </select>
                </div>
              </th>

              <th colspan="3">
                <div class="form-group">
                  <a href="javascript:;" class="btn btn-circle btn-success" name="afficheBtn" id="afficheBtn" onclick="submitFilter()"> <i class="fa fa-eye">&nbsp;</i><?= Yii::t('app', 'afficher') ?></a>
                  <span>&nbsp;</span>
                  <a href="javascript:;" onclick='$("#action_key").val("<?= md5("reajuster") ?>"); $("#listeProduits").submit();' style="color: #000;" class="btn btn-circle btn-white" class="btn btn-circle btn-default" name="" id=""> <i class="fa fa-refresh">&nbsp;</i>Actualiser</a>&nbsp;

                  <a href="<?= Yii::$app->request->baseUrl . '/' . md5('site_index') ?>" class="btn btn-circle btn-primary" name="" id=""> <i>&nbsp;</i>Retour A L'Accueil</a>
                </div>
              </th>
              <th colspan="1">&nbsp;</th>
            </tr>
            <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
            <tr>
              <th>#</th>
              <th style="width: 100px;">Code Produit</th>
              <th style="width: 350px;">Désignation</th>
              <th style="width: 150px;">U.V.A.</th>
              <th style="width: 150px;">Quantité Actuelle</th>
              <th style="width: 150px;">Quantité existante</th>
              <th colspan="" style="text-align: center;">Action</th>
              <th colspan="4"></th>
            </tr>
            <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
          </thead>

          <tbody>
            <?php
            if (is_array($listProduits) && sizeof($listProduits) > 0) {
              foreach ($listProduits as $key => $data) {
                $catName = $udmName = $markName = $productQte =  Null;
                $key2 = $key + 1;
                # RECUPERONS LA CATEGORIE
                foreach ($productCategories as $catData) :
                  if ($catData['id'] == $data["categoryId"]) {
                    $catName = $catData['nom'];
                  }
                endforeach;
                /*
                      #RECUPERONS LA MARQUE
                      if(sizeof($productMarque)>0){
                        foreach ($productMarque as $markData) :
                          if($markData['id'] == $data["markId"]){
                            $markName = $markData['nom'];
                          }
                        endforeach;
                      }
                      */
                # RECUPERONS LES UDM
                if (sizeof($produitUdm) > 0) {
                  foreach ($produitUdm as $udmData) :
                    if ($udmData['id'] == $data["udm"]) {
                      $udmName = $udmData['nom'];
                    }
                  endforeach;
                }

                echo '
                      <tr>
                        <td>' . $key2 . '</td>
                        
                        <td>' . $data["productCode"] . '</td>
                        <td>
                          <ul>
                            <li><b>Catrgorie : </b>  ' . $catName . '</li>
                            <li><b>Nom : </b>' . $data["libelle"] . '</li>
                          </ul>
                        </td>
                        <td>' . $udmName . '</td>
                        <td class="text-center">' . $data['qteDispo'] . '</td>
                        <td>
                          <input autocomplete="off" class="form-control totalmustpay text-center" type="number"  name="qteExistante[]" id="qteExistante_'.$data['slimproductid'].'" value="">
                        </td>
                        <td class="text-center">
                          <a href="javascript:;" Class="btn btn-circle btn-warning" onclick="reagistement('.$data['slimproductid'].')">Réconduire</a>
                        </td>
                        <td>
                          <input autocomplete="off" type="hidden" name="selectectedELement[]" id="selectectedELement' . $data["slimproductid"] . '" class="eachHidden" value="' . $data["slimstockentrepotid"] . '">
                          <input autocomplete="off" type="hidden" name="qteActuelle[]" id="qteActuelle' . $data["slimproductid"] . '" class="eachHidden" value="' . $data["qteDispo"] . '">
                          
                        </td>
                      </tr>
                      ';
              }
            } else {
              echo '<tr><td colspan="11">' . Yii::t('app', 'pasEnregistrement') . '</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </form>
  </div>
</div>