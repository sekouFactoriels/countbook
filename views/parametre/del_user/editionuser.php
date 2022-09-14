    <?php
    require_once('script/editionuser_js.php');
    # DEBUT PREPARATION DU MESSAGE
    $msg = (isset(Yii::$app->session['msg'])) ? unserialize(Yii::$app->session['msg']) : ((!empty($msg)) ? unserialize($msg) : $msg);
    unset(Yii::$app->session['msg']);
    $postInStrg = (isset($postInStrg) && !empty($postInStrg)) ? unserialize($postInStrg) : Null;
    # FIN PREPARATION DU MESSAGE

    //die(print_r($user));
    ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-btns">
          <a href="<?= Yii::$app->request->baseUrl.'/'.md5('parametre_users')?>" style="color: #000;" class="btn btn-circle btn-white" name="users" id="users"> <i class="fa fa-arrow-circle-left"></i></a>
        </div>
        <h4 class="panel-title"><i class="glyphicon glyphicon-plus-sign">&nbsp;</i><?= yii::t('app','parametre_user')?></h4>
      </div>
      <form action="<?= Yii::$app->request->baseUrl.'/'.md5("parametre_users")?>" class="form-horizontal" id="editionuser_form" name="editionuser_form" method="post">
        <div class="panel-body">
          <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
                $token2 = Yii::$app->getSecurity()->generateRandomString();
                $token2 = str_replace('+','.',base64_encode($token2));
          ?>
          <input type="hidden" name="token2" value="<?= $token2 ?>"/>
          <input type="hidden" name="_csrf" value="<?=yii::$app->request->getcsrftoken()?>">
          <input type="hidden" name="action_key" id="action_key" value="">
          <input type="hidden" name="action_on_this" id="action_on_this" value="">
          <!-- DEBUT CONTENEUR DE MESSAGE  -->
          <?php $msg = (!empty($msg['type']))?$msg:null;?>
          <div class="<?= $msg['type'] ?>">
            <strong><?= $msg['strong']?></strong> <?= $msg['text']?>
          </div>
          <!-- FIN CONTENEUR DE MESSAGE -->
          <fieldset>
            <legend>
              <h5><?= strtoupper('Identifiants')?></h5>
            </legend>
          </fieldset>
          <div class="form-group required-container">
            <label class="col-sm-4 control-label"><?= Yii::t('app','pNom')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input type="text" value="<?= isset($user['prenom']) ? $user['prenom'] :(isset($_POST['pnom']) ? $_POST['pnom'] : '')?>" class="form-control" name="pnom" id="pnom" autocomplete="off"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group required-container">
            <label class="col-sm-4 control-label"><?= Yii::t('app','nom')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input type="text" value="<?= isset($user['nom']) ? $user['nom'] :(isset($_POST['nom']) ? $_POST['nom'] : '')?>" class="form-control" name="nom" id="nom" autocomplete="off"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','tel')?> :
            </label>
            <div class="col-sm-4 input-group">
              <input class="form-control" autocomplete="off" name="tel" id="tel" value="<?= isset($user['adresse']) ? $user['adresse'] :(isset($_POST['tel']) ? $_POST['tel'] : '')?>"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <fieldset>
            <legend>
              <h5><?= strtoupper(Yii::t('app','infosAuth'))?></h5>
            </legend>
          </fieldset>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','typeuser')?> : <span class="asterisk">*</span>   </label>
            <div class="col-sm-4 input-group">
              <select name="typeUsr" id="typeUsr" class="form-control">
                <option value="0"><?= Yii::t('app','slectOne')?></option>
                <?php
                  if(sizeof($typeUser) > 0){
                    foreach ($typeUser as $value) {
                      $selected = (isset($user['typeUser']) && $value['usertypeid'] == $user['typeUser']) ? "selected" : ((isset($_POST['typeUser']) && $value['usertypeid'] == $_POST['typeUsr']) ? "selected" : '');
                      echo '<option value="'.$value['usertypeid'].'" '.$selected.'>'.$value['nom'].'</option>';
                    }
                  }else {
                    echo '<option value="0">'.Yii::t("app","pasEnregistrement").'</option>';
                  }
                ?>
              </select>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','vouloirrenitialisermotpass')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <select class="form-control" name="questionmotpass" id="questionmotpass" onchange="questionmotpassaction()">
                <option value="1"><?= Yii::t('app','oui')?></option>
                <option value="2" selected><?= Yii::t('app','non')?></option>
              </select>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group required-container" style="display: none;" id="divnouveaumotpasse">
            <label class="col-sm-4 control-label"><?= Yii::t('app', 'nouveaumotpasse')?> : <span class="asterisk">*</span> </label>
            <div class="col-sm-4 input-group">
              <input type="password" value="<?= isset($_POST['motpass']) ? $_POST['motpass'] : "" ?>" class="form-control" name="motpass" id="motpass" autocomplete="off"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','statut')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <select class="form-control" name="statut" id="statut">
                <option value="1"><?= Yii::t('app','active')?></option>
                <option value="2"><?= Yii::t('app','suprime')?></option>
              </select>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <!-- ROLE -->
          <fieldset>
            <legend>
              <h5><?= strtoupper(Yii::t('app','droituser'))?></h5>
            </legend>
          </fieldset>


          <div class="form-group">
            <div class="col-sm-4">
              <label class="col-sm-6 control-label"><?= Yii::t('app','accessa').Yii::t('app','inventaire')?> : </label>
              <div class="col-sm-4">
                <select class="form-control" name="inventaire" id="inventaire">
                  <option value="0" <?= (isset($postInStrg['inventaire']) && $postInStrg['inventaire']) == 0 ? "selected" : Null ?>>Non</option>
                  <option value="1" <?= (isset($postInStrg['inventaire']) && $postInStrg['inventaire']) == 1 ? "selected" : Null ?>>Oui</option>
                </select>
              </div>
            </div>

            <div class="col-sm-4">
              <label class="col-sm-6 control-label"><?= Yii::t('app','accessa').Yii::t('app','vente')?> : </label>
              <div class="col-sm-4">
                <select class="form-control" name="vente" id="vente">
                  <option value="0" <?= (isset($postInStrg['vente']) && $postInStrg['vente']) == 0 ? "selected" : Null ?>>Non</option>
                  <option value="1" <?= (isset($postInStrg['vente']) && $postInStrg['vente']) == 1 ? "selected" : Null ?>>Oui</option>
                </select>
              </div>
            </div>

            <div class="col-sm-4">
              <label class="col-sm-6 control-label"><?= Yii::t('app','accessa').Yii::t('app','client_spaceclient')?> : </label>
              <div class="col-sm-4">
                <select class="form-control" name="client" id="client">
                  <option value="0" <?= (isset($postInStrg['client']) && $postInStrg['client']) == 0 ? "selected" : Null ?>>Non</option>
                  <option value="1" <?= (isset($postInStrg['client']) && $postInStrg['client']) == 1 ? "selected" : Null ?>>Oui</option>
                </select>
              </div>
            </div>

          </div>

          <div class="form-group">
            <div class="col-sm-4">
              <label class="col-sm-6 control-label"><?= Yii::t('app','accessau').Yii::t('app','rg_invent')?> : </label>
              <div class="col-sm-4">
                <select class="form-control" name="rg_invent" id="rg_invent">
                  <option value="0" <?= (isset($postInStrg['rg_invent']) && $postInStrg['rg_invent']) == 0 ? "selected" : Null ?>>Non</option>
                  <option value="1" <?= (isset($postInStrg['rg_invent']) && $postInStrg['rg_invent']) == 1 ? "selected" : Null ?>>Oui</option>
                </select>
              </div>
            </div>

            <div class="col-sm-4">
              <label class="col-sm-6 control-label"><?= Yii::t('app','accessau').Yii::t('app','rg_vente')?> : </label>
              <div class="col-sm-4">
                <select class="form-control" name="rg_vente" id="rg_vente">
                  <option value="0" <?= (isset($postInStrg['rg_vente']) && $postInStrg['rg_vente']) == 0 ? "selected" : Null ?>>Non</option>
                  <option value="1" <?= (isset($postInStrg['rg_vente']) && $postInStrg['rg_vente']) == 1 ? "selected" : Null ?>>Oui</option>
                </select>
              </div>
            </div>

            <div class="col-sm-4">
              <label class="col-sm-6 control-label"><?= Yii::t('app','accessau').Yii::t('app','rg_diver')?> : </label>
              <div class="col-sm-4">
                <select class="form-control" name="rg_diver" id="rg_diver">
                  <option value="0" <?= (isset($postInStrg['rg_diver']) && $postInStrg['rg_diver']) == 0 ? "selected" : Null ?>>Non</option>
                  <option value="1" <?= (isset($postInStrg['rg_diver']) && $postInStrg['rg_diver']) == 1 ? "selected" : Null ?>>Oui</option>
                </select>
              </div>
            </div>
          </div>


          <div class="form-group">
            <div class="col-sm-4">
              <label class="col-sm-6 control-label"><?= Yii::t('app','accessa').Yii::t('app','parametre')?> : </label>
              <div class="col-sm-4">
                <select class="form-control" name="parametre" id="parametre">
                  <option value="0"<?= (isset($postInStrg['parametre']) && $postInStrg['parametre']) == 0 ? "selected" : Null ?>>Non</option>
                  <option value="1" <?= (isset($postInStrg['parametre']) && $postInStrg['parametre']) == 1 ? "selected" : Null ?>>Oui</option>
                </select>
              </div>
            </div>

            <div class="col-sm-4">
              <label class="col-sm-6 control-label"><?= Yii::t('app','accessa').Yii::t('app','diver')?> : </label>
              <div class="col-sm-4">
                <select class="form-control" name="diver" id="diver">
                  <option value="0"<?= (isset($postInStrg['diver']) && $postInStrg['diver']) == 0 ? "selected" : Null ?>>Non</option>
                  <option value="1" <?= (isset($postInStrg['diver']) && $postInStrg['diver']) == 1 ? "selected" : Null ?>>Oui</option>
                </select>
              </div>
            </div>
          </div>

        </div>
        <!-- DEBUT : FOOTER -->
        <div class="panel-footer">
          <div class="row">
            <div class="col-sm-9 col-sm-offset-3">
              <a class="btn btn-circle btn-success" onclick="$('#beforeupdate_user').modal('show');"><i class="glyphicon glyphicon-refresh"></i>&nbsp;<?= yii::t('app','miseajour')?></a>
              <span>&nbsp;</span>
              <a href="<?= Yii::$app->request->baseUrl.'/'.md5('parametre_users')?>" type="reset" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= yii::t('app','annul')?></a>
            </div>
          </div>
        </div>
        <!-- FIN : FOOTER -->
      </form>
    </div>

    <div class="modal fade" id="beforeupdate_user" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="newProdCat"><?= Yii::t('app','miseajour').'&nbsp;'.Yii::t('app','parametre_user')?></h4>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <p><?= Yii::t('app','msg_continueroperation')?></p>
                  </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("users_updateuser") ?>'; document.getElementById('action_on_this').value='<?= base64_encode($user["id"]) ?>'; $('#editionuser_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app','oui')?></a>
                    <button type="button"  class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','non')?></button>
                </div>
            </div>
        </div>
    </div>
