<div class="mainpanel">
  <!-- BEDIN TOP BAR -->
  <div class="headerbar">
    <a class="menutoggle"><i class="fa fa-bars"></i></a>
    <div class="header-right">
        <ul class="headermenu">
          <li class="hidden-md hidden-lg">
            <a class="btn btn-circle btn-default dropdown-toggle" href="<?= Yii::$app->request->baseUrl.'/'.md5('deconnexion')?> "><i class="glyphicon glyphicon-log-out"></i> <?= Yii::t('app','deconnection')?></a>
          </li>

          <li class="hidden-sm hidden-xs">
            <div class="btn-group">
              <button type="button" class="btn btn-circle btn-default dropdown-toggle" data-toggle="dropdown">
                
                <?php
                $userPrimaryData = Yii::$app->mainCLass->getUserAuthPrimaryInfo();
                echo Yii::$app->mainCLass->getUserFullnameBaseId($userPrimaryData['auhId']);
                ?>
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                <li><a href="<?= Yii::$app->request->baseUrl.'/'.md5('deconnexion')?> "><i class="glyphicon glyphicon-log-out"></i> <?= Yii::t('app','deconnection')?></a></li>
              </ul>
            </div>
          </li>
        </ul>
      </div>
  </div>
  <!-- END TOP BAR -->


  <!-- BEGIN PAGE HEADER TITLE -->
  <div class="pageheader">
      <h2><i class="fa fa-<?= Yii::$app->leftMenuCLass->getIcon(Yii::$app->controller->id) ?>"></i> <?= Yii::$app->leftMenuCLass->getPageTitle(Yii::$app->controller->id) ?> <span> <?= Yii::$app->leftMenuCLass->getPageStitle(Yii::$app->controller->action->id) ?></span></h2>
  </div>
  <!-- END PAGE HEADER TITLE -->

  <!-- BEGIN DASHBOARD -->
  <div class="contentpanel">
    <?= $content ?>
  </div>
  <!-- END DASHBOARD -->
</div>
