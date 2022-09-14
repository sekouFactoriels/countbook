   <?php require_once('script/filtre_sc.php');
    ?> 
    <form action="<?=yii::$app->request->baseurl.'/'.md5('parametre_entite') ?>" method="POST" name="entit_frm" id="entit_frm" >
    <?= Yii::$app->nonSqlClass->getHiddenFormTokenField(); ?>
          <?php
            $token2 = Yii::$app->getSecurity()->generateRandomString();
            $token2 = str_replace('+','.',base64_encode($token2));
          ?>
          <!-- DEBUT : BASIC HIDDEN IMPUT FOR THE FORM -->
          <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
          <input type="hidden" name="token2" value="<?= $token2 ?>"/>
          <input type="hidden" name="action_key" id="action_key" value=""/>
          <input type="hidden" name="msg" id="msg" value=""/>
          <!-- FIN : BASIC HIDDEN IMPUT FOR THE FORM -->

    </form>
    <div class="row">

            <a href="javascript:;" onclick="nEntite()">
              <div class="col-xs-6">
                <div class="panel panel-info panel-alt widget-today">
                  <div class="panel-heading text-center">
                    <i class="fa fa-plus-circle"></i>
                  </div>
                  <div class="panel-body text-center">
                    <h3 class="today">Nouvelle Entite</h3>
                  </div><!-- panel-body -->
                </div><!-- panel -->
              </div>
            </a>

            <a href="javascript:;" onclick="lEntite()">
              <div class="col-xs-6">
                <div class="panel panel-dark panel-alt widget-today">
                  <div class="panel-heading text-center">
                    <i class="fa fa-leaf  "></i>
                  </div>
                  <div class="panel-body text-center">
                    <h3 class="today">Liste des Entites</h3>
                  </div><!-- panel-body -->
                </div><!-- panel -->
              </div>
            </a>

</div>



