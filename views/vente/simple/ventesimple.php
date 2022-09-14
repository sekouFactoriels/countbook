   <?php
   require_once('script/ventesimple_js.php');
   # RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
    $msg = (isset($_SESSION['msg'])) ? $_SESSION['msg'] : $msg;
    $msg = (!empty($msg)) ? unserialize($msg) : $msg;
    unset($_SESSION['msg']);
  ?>
    <form action="<?=yii::$app->request->baseurl.'/'.md5('vente_simple') ?>" method="POST" name="vente_simple_frm" id="vente_simple_frm" >
    <input type="hidden" name="_csrf" value="<?=yii::$app->request->getcsrftoken()?>">
    <input type="hidden" name="action_key" id="action_key" value="">

    </form>

    <!-- DEBUT CONTENEUR DE MESSAGE  -->
    <?php $msg = (!empty($msg['type']))?$msg:null;?>
      <div class="<?= $msg['type'] ?>">
        <strong><?= $msg['strong']?></strong> <?= $msg['text']?>
      </div>
    <!-- FIN CONTENEUR DE MESSAGE -->

    <div class="row">
            <a href="javascript:;" onclick="nVente()">
              <div class="col-md-6">
                <div class="panel panel-success panel-alt widget-today">
                  <div class="panel-heading text-center">
                    <i class="fa fa-money"></i>
                  </div>
                  <div class="panel-body text-center">
                    <h3 class="today">NOUVELLE VENTE</h3>
                  </div><!-- panel-body -->
                </div><!-- panel -->
              </div>
            </a>

            <a href="javascript:;" onclick="retreviewVente()">
              <div class="col-md-6">
                <div class="panel panel-success panel-alt widget-today">
                  <div class="panel-heading text-center">
                    <i class="fa fa-money"></i>
                  </div>
                  <div class="panel-body text-center">
                    <h3 class="today">LISTE VENTES</h3>
                  </div><!-- panel-body -->
                </div><!-- panel -->
              </div>
            </a>

</div>
