<?php
$userSessionDtls = unserialize(Yii::$app->session[Yii::$app->params['userSessionDtls']]);
$typeUser = Yii::$app->mainCLass->getTypeIdUniqUser($userSessionDtls['idUniqUser']);

?>
<div class="leftpanel">
    <!-- BEGIN LOGO -->
    <div class="logopanel">
        <h1><span>[</span> <?= Yii::$app->params['systemsname']?> <span>]</span></h1>
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
                        <span class="text-muted">date du jour</span>
                        <h4><?= date('d/m/Y')?></h4>
                    </div>
                    <div class="glyphicon glyphicon-time chart" style="font-size: 200%"></div>
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
