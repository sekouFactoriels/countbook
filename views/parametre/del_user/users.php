<?php
# DEBUT PREPARATION DU MESSAGE
$msg = (isset(Yii::$app->session['msg'])) ? unserialize(Yii::$app->session['msg']) : ((!empty($msg)) ? unserialize($msg) : $msg);
unset(Yii::$app->session['msg']);
# FIN PREPARATION DU MESSAGE

?>
<div class="row">
  <div class="col-sm-12 col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-btns">
          <a href="javascript:;" onclick="$('#users_filtre').modal('show');" style="color: #fff;" class="btn btn-circle btn-primary" > <i class="fa fa-filter"></i></a>
          <a href="javascript:;" onClick="document.getElementById('action_key').value='<?= md5("users_newuser")?>'; $('#users_form').submit();" style="color: #000;" class="btn btn-circle btn-white" name="newuser" id="newuser"> <i class="fa fa-plus"></i></a>
        </div>
        <h4 class="panel-title"><i class="glyphicon glyphicon-list-alt">&nbsp;</i><?= yii::t('app','parametre_users')?></h4>
      </div>
      <div class="panel-body">
        <form action="<?= Yii::$app->request->baseUrl.'/'.md5("parametre_users")?>" id="users_form" name="users_form" method="post">
          <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
                $token2 = Yii::$app->getSecurity()->generateRandomString();
                $token2 = str_replace('+','.',base64_encode($token2));
          ?>
          <input type="hidden" name="token2" value="<?= $token2 ?>"/>
          <input type="hidden" name="_csrf" value="<?=yii::$app->request->getcsrftoken()?>">
          <input type="hidden" name="action_key" id="action_key" value="">
          <input type="hidden" name="action_on_this" id="action_on_this" value="">
          <!-- DEBUT CONTENEUR DE MESSAGE  -->
          <div class="<?= $msg['type'] ?>">
            <strong><?= $msg['strong']?></strong> <?= $msg['text']?>
          </div>
          <!-- FIN CONTENEUR DE MESSAGE -->
              <div class="table-responsive" id="listtable">
                <table class="table">
                  <thead>

                 </thead>
                </table>
                <table class="table table-bordered table-stripped">
                   <thead>
                    <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                    <tr>
                      <th>#</th>
                      <th><?= Yii::t('app','pNom')?></th>
                      <th><?= Yii::t('app','nom')?></th>
                      <th><?= Yii::t('app','parametre_entreprise')?></th>
                      <th style="text-align: center;">Action</th>
                    </tr>
                    <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                  </thead>

                  <tbody>
                    <?php
                    if(isset($users) && sizeof($users) > 0){
                      foreach ($users as $key => $value) {
                        $key2 = ++$key;
                        echo '<tr>
                          <td>'.$key2.'</td>
                          <td>'.$value['nom'].'</td>
                          <td>'.$value['prenom'].'</td>
                          <td>'.$value['nomentreprise'].'</td>
                          <td style="text-align: center;"><a href="javascript:;" Class="btn btn-circle btn-primary" onClick="$(\'#action_key\').val(\''.md5("users_editionuser").'\'); $(\'#action_on_this\').val(\''.base64_encode($value["idUserAuth"]).'\'); $(\'#users_form\').submit();"><i class="fa fa-indent"></i>&nbsp;'.Yii::t("app","edit").'</a></td>
                        </tr>';
                      }
                    }else echo '<td colspan="5">'.Yii::t('app','pasEnregistrement').'</td>';
                    ?>
                  </tbody>
                </table>
            </div>
            <!-- Debut conteneur du filtre -->
            <div class="modal fade" id="users_filtre" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="newProdCat"><i class="fa fa-filter">&nbsp;</i><?= Yii::t('app','filtretble')?></h4>
                        </div>
                        <div class="modal-body">
                          <div class="form-group">
                            <label class="col-sm-3 control-label"><?= Yii::t('app','parametre_entreprise')?> :
                            </label>
                            <div class="col-sm-8">
                              <select name="entreprise_filtre" id="entreprise_filtre" class="form-control chosen-select">
                                <option value="0">>-- <?= Yii::t('app','slectOne') ?> --<</option>
                                <?php
                                  if(sizeof($entreprises)>0){
                                    foreach ($entreprises as $key => $value) {
                                      echo '<option value="'.$value['id'].'">'.$value['nom'].'</option>';
                                    }
                                  }else echo '<option value="0">'.Yii::t("app","pasEnregistrement").'</option>';
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                            <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5('users_editionusers')?>'; document.getElementById('users_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-filter"></i>&nbsp;<?= Yii::t('app','filtrer')?></a>
                            <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','annul')?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin conteneur du filtre -->
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="take_we_to_success_form" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="licenceCreatAssociatLabel"><?php echo Yii::t('app','valid');?></h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <p><?= Yii::t('app','enrgSuccess')?></p>
              </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="$('#motifs_form').submit();" class="btn btn-circle btn-success" data-dismiss="modal"><?= Yii::t('app','ok');?></button>
            </div>
        </div>
    </div>
</div>
