<?php
$userSessionDtls = unserialize(Yii::$app->session[Yii::$app->params['userSessionDtls']]);
$typeUser = Yii::$app->mainCLass->getTypeIdUniqUser($userSessionDtls['idUniqUser']);

?>
<div class="leftpanel">
    <!-- BEGIN LOGO -->
    <div class="logopanel">
        <h1><span>[</span> SLIMSTOK <span>]</span></h1>
    </div><!-- logopanel -->
    <!-- END LOGO -->

    <!-- BEGIN LEFT MENU -->
    <h8 class="sidebartitle">&nbsp;</h8>
    <ul class="nav nav-pills nav-stacked nav-bracket">
      <?php Yii::$app->leftMenuCLass->menuConstructeur($typeUser, $userSessionDtls['idUniqUser']); ?>
    </ul>
    <!-- END LEFT MENU -->

    <div class="leftpanelinner">
      <div class="infosummary">

            <ul>
                <li>
                    <div class="datainfo">
                        <span class="text-muted">Derniere Session</span>
                        <h4><?= date('Y-m-d')?></h4>
                    </div>
                    <div class="glyphicon glyphicon-time chart" style="font-size: 200%"></div>
                </li>

                <li>
                    <div class="datainfo">
                        <span class="text-muted">Compteur Connexion</span>
                        <h4>50</h4>
                    </div>
                    <div class="fa fa-user chart" style="font-size: 200%"></div>
                </li>
            </ul>
          </div>
    </div>
  </div>



<!-- BEGIN HEADER INNER -->
<div class="page-header-inner">

  <!-- BEGIN HORIZANTAL MENU -->
    <?php
      echo '<div class="hor-menu hor-menu-light hidden-sm hidden-xs">
              <ul class="nav navbar-nav">';
      //Yii::$app->topMenuCLass->menuConstructeur($typeUser, $userAuthDtlsArray['idUniqUser']);
      echo    '</ul>
            </div>';
    ?>
  <!-- END HORIZANTAL MENU -->

</div>
<!-- END HEADER INNER -->
