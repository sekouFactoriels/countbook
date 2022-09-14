<?php
  require_once('script/productCategory_js.php');
?>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="panel-btns">
      <a onClick="$('#newProductCategory').modal('show')"  style="color: #000;" class="btn btn-circle btn-white btn-xs" name="newProduct" id="newProduct"> <i class="fa fa-plus-circle">&nbsp;</i><?= Yii::t('app','ajout')?></a>
    </div>
    <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i><?= Yii::t('app','listCats')?></h4>
  </div>
  <div class="panel-body">
    <form action="<?= Yii::$app->request->baseUrl.'/'.md5("inventaire_cats")?>" id="listeCategories" name="listeCategories" method="post">
      <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
            $token2 = Yii::$app->getSecurity()->generateRandomString();
            $token2 = str_replace('+','.',base64_encode($token2));
      ?>
          <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
          <input type="hidden" name="token2" value="<?= $token2 ?>"/>
          <input type="hidden" name="action_key" id="action_key" value="<?= md5('listCats')?>"/>
          <input type="hidden" name="action_on_this" id="action_on_this" value=""/>
          <input type="hidden" name="productCatName" id="productCatName" value=""/>
          <input type="hidden" name="productCatDesc" id="productCatDesc" value=""/>
          <input type="hidden" name="statutCat" id="statutCat" value=""/>
          <input type="hidden" name="msg" id="msg" value=""/>

          <div class="table-responsive" id="listtable">
            <table class="table">
              <thead>
                <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                <tr>
                  <th>#</th>
                  <th>Libell&#233;</th>
                  <th>Description</th>
                  <th>[-]</th>
                </tr>
                <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
              </thead>

              <tbody>
                <?php
                  if(is_array($entrepriseCats) && sizeof($entrepriseCats) > 0){
                    foreach ($entrepriseCats as $key => $data) {
                      $key2 = $key +1;
                      echo '
                      <tr>
                        <td>'.$key2.'</td>
                        <td>'.$data["nom"].'</td>
                        <td>'.$data["description"].'</td>
                        <td>
                        <a href="javascript:;" Class="btn btn-circle btn-warning" onClick="$(\'#productCatNameUpdate\').val(\''.$data["nom"].'\'); $(\'#productCatDescUpdate\').val(\''.($data["description"]).'\'); $(\'#statutCatUpdate\').val(\''.$data["statut"].'\'); $(\'#action_on_this\').val(\''.base64_encode($data["id"]).'\'); $(\'#updateProductCategory\').modal(\'show\');"><i class="fa fa-indent"></i>&nbsp;'.Yii::t("app","edit").'</a>
                        </td>
                      </tr>
                      ';
                    }
                  }else{
                    echo '<tr><td colspan="11">'.Yii::t('app','no_data_found').'</td></tr>';
                  }
                ?>
              </tbody>
            </table>
        </div>
    </form>
  </div>
</div>
<?php
# INCLUDE PRODUCT CATEGORY
require_once('productCategory.php');
require_once('productCategoriesUpdate.php');
?>
