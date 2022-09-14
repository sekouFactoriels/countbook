<form action="<?= Yii::$app->request->baseUrl . '/' . md5("parametre_entreprises") ?>" class="form-horizontal" id="countbook_form" name="countbook_form" method="post" enctype="multipart/form-data">
  <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
  $token2 = Yii::$app->getSecurity()->generateRandomString();
  $token2 = str_replace('+', '.', base64_encode($token2));
  
  ?>
  <input type="hidden" name="token2" value="<?= $token2 ?>" />
  <input type="hidden" name="_csrf" value="<?= yii::$app->request->getcsrftoken() ?>">
  <input type="hidden" name="action_key" id="action_key" value="">
  <input type="hidden" name="action_on_this" id="action_on_this" value="">
  <input type="hidden" name="idEntite" id="idEntite" value="">
  <!-- DEBUT CONTENEUR DE MESSAGE  -->
  <?= Yii::$app->session->getFlash('flashmsg');
  Yii::$app->session->removeFlash('flashmsg'); ?>
  <!-- FIN CONTENEUR DE MESSAGE -->

  <?php

  $nombreUser = Yii::$app->parametreClass->countUser($espace_gestion['id']);
  ?>
  <div class="row">
    <!-- main row -->
    <div class="col-sm-3">
      <!-- col-sm-3 -->

      <!-- LOGO -->
      <?php
      //Se rassurer que le logo existe
      if (isset($espace_gestion['logo']) && !empty($espace_gestion['logo'])) {
      ?>
        <img src="<?= yii::$app->params['usermedia'] . '/' . $espace_gestion['logo'] ?>" class="thumbnail img-responsive" alt="" style="width: 300px; height: 300px;" />
        <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("do_logouploader") ?>'; $('#countbook_form').submit();" class="btn btn-white mr5"><i class="fa fa-edit">&nbsp;</i><?= Yii::t('app', 'change_logo') ?></a>

      <?php
      } else {
        echo '<div class="alert alert-info">' . yii::t("app", "no_logo_trouve") . '</div>';
      ?>
        <!-- <a href="javascript:;" type="button" onClick="document.getElementById('do_slogan_updater').modal(show)='<?= md5("do_logouploader") ?>'; $('#countbook_form').submit();" class="btn btn-white mr5"><i class="fa fa-edit">&nbsp;</i><?= Yii::t('app', 'ajouter_logo') ?></a> -->
        <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("do_logouploader") ?>'; $('#countbook_form').submit();" class="btn btn-white mr5"><i class="fa fa-edit">&nbsp;</i><?= Yii::t('app', 'change_logo') ?></a>

      <?php
      }
      ?>
      <!-- .LOGO -->

      <!-- Séparateur -->
      <div class="mb30"></div>

      <!-- Description  / à propos du commerce -->
      <?php require_once('mainview_components/description.php'); ?>

    </div><!-- col-sm-3 -->

    <div class="col-sm-9">
      <div class="profile-header">
        <h2 class="profile-name"><?= $espace_gestion['nom'] ?></h2>
        <div class="profile-location">
          <?= $espace_gestion['slogan'] ?>
          </br>
          <i>(slogan)&nbsp;&nbsp;</i><a href="javascript:;" data-toggle="modal" data-target="#do_slogan_updater" type="button" class="btn btn-white mr5 btn-xs"><i class="fa fa-edit"></i><?= Yii::t('app', 'ajouter_modifier') ?></a>
        </div>
      </div><!-- profile-header -->

      <!-- Nav tabs -->
      <ul class="nav nav-tabs nav-justified nav-profile">
        <li class="active"><a href="#renseignements_generaux" data-toggle="tab"><strong><?= yii::t('app', 'renseigns_gnrx') ?></strong></a></li>
        <li><a href="#branche" data-toggle="tab"><strong><?= yii::t('app', 'branche') ?></strong></a></li>
        <li><a href="#compte_acces" data-toggle="tab"><strong><?= yii::t('app', 'compte_acces') ?></strong></a></li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div class="tab-pane active" id="renseignements_generaux">
          <div class="activity-list">
            <div class="media act-media">

              <div class="media-body act-media-body">

                <div class="row">

                  <div class="col-sm-6">
                    <label class="control-label"><?= Yii::t('app', 'denomination') ?> : <span class="asterisk">*</span>
                    </label>
                    <div>
                      <input class="form-control" autocomplete="off" name="denomination" id="denomination" value="<?= isset($espace_gestion['nom']) ? $espace_gestion['nom'] : '' ?>" />
                    </div>
                  </div>

                  <!-- Component Activites -->
                  <?php require_once('mainview_components/activites.php') ?>
                  <!-- // Component Activites // -->
                </div>

              </div>
            </div><!-- media -->

            <div class="media act-media">

              <div class="media-body act-media-body">

                <div class="row">

                  <div class="col-sm-12">
                    <label class="control-label"><?= Yii::t('app', 'Numéro Commerce') ?> :
                    </label>
                    <div class="">
                      <input class="form-control" autocomplete="off" name="numCommerce" id="numCommerce" value="<?= isset($espace_gestion['numerosCommerce']) ? $espace_gestion['numerosCommerce'] : '' ?>" />
                    </div>
                  </div>

                </div>

              </div>
            </div><!-- media -->

            <div class="media act-media">
              <div class="media-body act-media-body">

                <div class="row">

                  <div class="col-sm-6">
                    <label class="control-label"><?= Yii::t('app', 'tel') ?> : <span class="asterisk">*</span>
                    </label>
                    <div class="">
                      <input class="form-control" autocomplete="off" name="tel" id="tel" value="<?= isset($espace_gestion['tel']) ? $espace_gestion['tel'] : '' ?>" />
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <label class="control-label"><?= Yii::t('app', 'email') ?> : <span class="asterisk">*</span>
                    </label>
                    <div class="">
                      <input class="form-control" autocomplete="off" name="email" id="email" value="<?= isset($espace_gestion['email']) ? $espace_gestion['email'] : '' ?>" />
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- media -->

            <div class="media act-media">
              <div class="media-body act-media-body">

                <div class="row">
                  <div class="col-sm-12">
                    <label class="control-label"><?= Yii::t('app', 'addresse') ?> :
                    </label>
                    <div class="">
                      <textarea class="form-control" name="addresse" id="addresse"><?= isset($espace_gestion['addresse']) ? $espace_gestion['addresse'] : '' ?></textarea>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- media -->

            <div class="media act-media">

              <div class="media-body act-media-body">

                <div class="row">

                  <div class="col-sm-6">
                    <label class="control-label"><?= Yii::t('app', 'objectifVente') ?> :
                    </label>
                    <div>
                      <input class="form-control" autocomplete="off" name="objectifVente" id="objectifVente" value="<?= isset($espace_gestion['objectifVente']) ? $espace_gestion['objectifVente'] : '' ?>" />
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <label class="control-label"><?= Yii::t('app', 'objectifClientele') ?> :
                    </label>
                    <div>
                      <input class="form-control" autocomplete="off" name="objectifClientele" id="objectifClientele" value="<?= isset($espace_gestion['objectifClientele']) ? $espace_gestion['objectifClientele'] : '' ?>" />
                    </div>
                  </div>

                  <!-- Component Activites -->
                  <?php require_once('mainview_components/activites.php') ?>
                  <!-- // Component Activites // -->
                </div>

              </div>
            </div><!-- media -->

            <div class="row">
              <div class="col-sm-12">
                <a href="#" class="btn btn-primary btn-block text-black" onClick="document.getElementById('action_key').value='<?= md5("do_update_entreprise") ?>'; $('#countbook_form').submit();"><i class="fa fa-edit"></i>&nbsp;&nbsp;Mis à jour</a>
              </div>
            </div>



          </div><!-- activity-list -->

        </div>

        <!-- BRANCHE -->
        <div class="tab-pane" id="branche">
          <?php
          if ($espace_gestion['tl'] == 'premium' || $espace_gestion['tl'] == 'special') {
          ?>
            <div class="float-right">
              <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("branche_form") ?>'; $('#countbook_form').submit();" class="btn btn-circle btn-success"><i class="fa fa-plus"></i>&nbsp;Nouveau</a>
            </div>
          <?php
          }
          ?>

          <div class="">

            <div class="media">

              <div class="row">

                <?php
                foreach ($liste_branche as $branche) {
                ?>
                  <div class="col-md-6">
                    <div class="people-item">
                      <div class="media">
                        <div href="#" class="pull-left">
                          <img alt="" src="<?= yii::$app->params['usermedia'] . '/' . $espace_gestion['logo'] ?>" class="thumbnail media-object">
                          <?php
                          echo '
                          <a href="javascript:;" Class="btn btn-circle btn-primary mt-2" onClick="$(\'#action_key\').val(\'' . md5("editBranche") . '\'); $(\'#action_on_this\').val(\'' . base64_encode($branche["id"]) . '\'); $(\'#countbook_form\').submit();"><i class="fa fa-indent"></i>&nbsp;' . Yii::t("app", "edit") . '</a>
                          ';
                          ?>
                        </div>

                        <?php
                        echo '
                            <div class="media-body">
                              <h4 class="person-name">' . $branche['nom'] . '</h4>
                              <div class="text-muted"><i class="fa fa-map-marker"></i> ' . $branche['addresse'] . '</div>
                              <div class="text-muted"><i class="fa fa-phone"></i> ' . $branche['Tel'] . '</div>
                              <div class="text-muted"><i class="fa fa-envelope"></i> ' . $branche['email'] . '</div>
                              <div class="text-muted"><i class="fa fa-briefcase"></i> ' . $branche['description'] . '</div>
                              
                            </div>
                            ';
                        ?>
                      </div>
                    </div>
                  </div>

                <?php
                }
                ?>

              </div>

            </div><!-- media -->

          </div>
          <!--follower-list -->

        </div>

        <!-- COMPTE/ACCES -->
        <div class="tab-pane" id="compte_acces">

          <div class="activity-list"><!-- Renseignements généraux -->
            <div class="media act-media">
              <div class="row">
                <?php
                foreach ($liste_utilisateur as $user) {
                ?>
                  <div class="col-md-4">
                    <div class="people-item">
                      <strong style="color: blue;"><?= $user['prenom'] . '  ' . $user['nom'] ?></strong><br />
                      <div class="media-body act-media-body">
                        <h5>
                          <div class="text-muted"><i class="fa fa-phone"></i>&nbsp; <?= $user['adresse'] ?></div>
                        </h5>
                        
                        <h5>
                          <div class="text-muted"><i class="fa fa-user"></i>&nbsp; <?= $user['privilege'] ?></div>
                        </h5>
                        <b>
                          <div class="text-muted mb-4"><i class="fa fa-briefcase"></i>&nbsp; <?= $user['entite'] ?></div>
                        </b>
                        <ul class="social-list">
                        <?php
                        echo '
                            <a href="javascript:;" Class="btn btn-circle btn-primary mt-2" onClick="$(\'#action_key\').val(\'' . md5("users_editionuser") . '\'); $(\'#action_on_this\').val(\'' . base64_encode($user["idUserAuth"]) . '\'); $(\'#countbook_form\').submit();"><i class="fa fa-indent"></i>&nbsp;' . Yii::t("app", "edit") . '</a>
                            ';
                        ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                <?php
                }
                ?>
              </div>
            </div>
          </div><!-- Renseignements généraux -->


          <?php
          switch ($espace_gestion['tl'])
          {
            case 'special':
            break;

            case 'essentiel':
              if ($userCount < 3) {
              ?>
                  <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("new_user_form") ?>'; $('#countbook_form').submit();" class="btn btn-primary btn-block text-black"><i class="fa fa-plus"></i>&nbsp;Nouvel Utilisateur</a>
              <?php
              }
            break;

            case 'standard':
              if ($userCount < 9) {
              ?>
                  <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("new_user_form") ?>'; $('#countbook_form').submit();" class="btn btn-primary btn-block text-black"><i class="fa fa-plus"></i>&nbsp;Nouvel Utilisateur</a>
              <?php
              }
            break;

            case 'premium':
              if ($userCount < 15) {
              ?>
                  <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("new_user_form") ?>'; $('#countbook_form').submit();" class="btn btn-primary btn-block text-black"><i class="fa fa-plus"></i>&nbsp;Nouvel Utilisateur</a>
              <?php
              }
            break;
          }
          ?>

        </div>



      </div><!-- tab-content -->

    </div><!-- col-sm-3 -->
  </div><!-- main row -->
  <?php require_once('mainview_components/slogan.php'); ?>
</form>