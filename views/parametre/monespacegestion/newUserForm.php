<?php
# DEBUT PREPARATION DU MESSAGE
$msg = (isset(Yii::$app->session['msg'])) ? unserialize(Yii::$app->session['msg']) : ((!empty($msg)) ? unserialize($msg) : $msg);
unset(Yii::$app->session['msg']);
$postInStrg = (isset($postInStrg) && !empty($postInStrg)) ? unserialize($postInStrg) : Null;
# FIN PREPARATION DU MESSAGE
?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-btns">
          <a href="<?= Yii::$app->request->baseUrl.'/'.md5('parametre_entreprises')?>" style="color: #000;" class="btn btn-circle btn-white" name="users" id="users"> <i class="fa fa-arrow-circle-left"></i></a>
        </div>
        <h4 class="panel-title"><i class="glyphicon glyphicon-plus-sign">&nbsp;</i><?= yii::t('app','parametre_user')?></h4>
      </div>
      <form action="<?= Yii::$app->request->baseUrl.'/'.md5("parametre_users")?>" class="form-horizontal " id="user_form" name="user_form" method="post">
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
          
          <div class="form-group required-container">
            <label class="col-sm-4 control-label"><?= Yii::t('app','pNom')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input type="text" value="<?= isset($postInStrg['pnom']) ? $postInStrg['pnom'] : ''?>" class="form-control" name="pnom" id="pnom" autocomplete="off"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group required-container">
            <label class="col-sm-4 control-label"><?= Yii::t('app','nom')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input type="text" value="<?= isset($postInStrg['nom']) ? $postInStrg['nom'] : ''?>" class="form-control" name="nom" id="nom" autocomplete="off"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','tel')?> :
            </label>
            <div class="col-sm-4 input-group">
              <input class="form-control" autocomplete="off" name="tel" id="tel" value="<?= isset($postInStrg['tel']) ? $postInStrg['tel'] : ''?>"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <fieldset>
            <legend>
              <h5><?= strtoupper('accr&#233;ditation')?></h5>
            </legend>
          </fieldset>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','typeuser')?> : <span class="asterisk">*</span>   </label>
            <div class="col-sm-4 input-group">
              <select name="typeUsr" id="typeUsr" class="form-control chosen-select">
                <option value="0"><?= Yii::t('app','slectOne')?></option>
                <?php
                  if(sizeof($typeUser) > 0){
                    foreach ($typeUser as $value) {
                      $selected = (isset($postInStrg['typeUsr']) && $value['usertypeid'] == $postInStrg['typeUsr']) ? "selected" : '';
                      if($value['usertypeid'] != '9'){
                      echo '<option value="'.$value['usertypeid'].'" '.$selected.'>'.$value['nom'].'</option>';
                      }
                    }
                  }else {
                    echo '<option value="0">'.Yii::t("app","pasEnregistrement").'</option>';
                  }
                ?>
              </select>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group required-container">
            <label class="col-sm-4 control-label"><?= Yii::t('app','nomuser')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input type="text" value="" class="form-control" name="nomuser" id="nomuser" autocomplete="off"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <div class="form-group required-container">
            <label class="col-sm-4 control-label"><?= Yii::t('app', 'nouveaumotpasse')?> : <span class="asterisk">*</span>
            </label>
            <div class="col-sm-4 input-group">
              <input type="password" value="" class="form-control" name="motpass" id="motpass" autocomplete="off"/>
              <span class="input-group-addon hidden-md hidden-lg"></span>
            </div>
          </div>

          <fieldset>
            <legend>
              <h5><?= strtoupper(Yii::t('app','infosAuth'))?></h5>
            </legend>
          </fieldset>

          <div class="form-group">
            <label class="col-sm-4 control-label"><?= Yii::t('app','entreprise')?> : <span class="asterisk">*</span>   </label>
            <div class="col-sm-4 input-group">
              <select name="entreprise" id="entreprise" class="form-control">
                <?php
                  if(sizeof($entreprises) > 0){
                    foreach ($entreprises as $value) {
                      echo '<option value="'.$value['id'].'">'.$value['nom'].'</option>';
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
            <label class="col-sm-4 control-label"><?= Yii::t('app','service')?> : <span class="asterisk">*</span>   </label>
            <div class="col-sm-4 input-group">
              <select name="service" id="service" class="form-control">
                <?php
                  if(sizeof($services) > 0){
                    foreach ($services as $value) {
                      echo '<option value='.$value['id'].'>'.$value['nom'].'</option>';
                    }
                  }else {
                    echo '<option value="0">'.Yii::t("app","pasEnregistrement").'</option>';
                  }
                ?>
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
          <?php 
            if(isset($privilege_ref['privilege']) && strlen($privilege_ref['privilege'])>0){
              $privilege = $privilege_ref['privilege'];
              $privilege_breadown = explode(yii::$app->params['menuSeperator'], $privilege);
              if(sizeof($privilege_breadown)>0)
              {
                foreach($privilege_breadown as $each_privilege){
                  $single_privilege = explode(yii::$app->params['subMenuSeperator'], $each_privilege);
                  if(!in_array($single_privilege[0], Yii::$app->params['hiddenaction'])){
                  ?>
                  <div class="col-sm-4 privilemain" style="margin-top: 25px;">
                    <div class="ckbox ckbox-primary">
                      <input type="checkbox" id="<?= $single_privilege[0] ?>" value="@<?= $each_privilege ?>" name="privilege[]" <?= ($single_privilege[0] == 'site_index') ? "checked" : ''?> />
                      <label for="<?= $single_privilege[0] ?>"><?= Yii::t('app', $single_privilege[0]) ?></label>
                    </div>
                  </div>
                  <?php
                  }
                }
              }
            }
          ?>
          </div>

        </div>

        <!-- DEBUT : FOOTER -->
        <div class="panel-footer">
          <div class="row">
            <div class="col-sm-9 col-sm-offset-3">
              <a class="btn btn-circle btn-success" onclick="$('#beforesave_newuser').modal('show');"><i class="glyphicon glyphicon-save"></i>&nbsp;<?= yii::t('app','enrgtrer')?></a>
              <span>&nbsp;</span>
              <a href="<?= Yii::$app->request->baseUrl.'/'.md5('parametre_entreprises')?>" type="reset" class="btn btn-circle btn-warning"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= yii::t('app','annul')?></a>
            </div>
          </div>
        </div>
        <!-- FIN : FOOTER -->

      </form>
    </div>

    <div class="modal fade" id="beforesave_newuser" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="newProdCat"><?= Yii::t('app','enrg').'&nbsp;'.Yii::t('app','parametre_user')?></h4>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                    <p><?= Yii::t('app','msg_continueroperation')?></p>
                  </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("users_saveuser") ?>'; $('#user_form').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app','oui')?></a>
                    <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app','non')?></button>
                </div>
            </div>
        </div>
    </div>