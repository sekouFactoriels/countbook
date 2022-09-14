<?php
   require_once('script/mainpage_js.php');
  ?>
    <form action="<?=yii::$app->request->baseurl.'/'.md5('devis_themain') ?>" method="POST" name="vente_simple_frm" id="vente_simple_frm" >
    <input type="hidden" name="_csrf" value="<?=yii::$app->request->getcsrftoken()?>">
    <input type="hidden" name="action_key" id="action_key" value="">

    </form>

    <div class="row">
      <a href="javascript:;" onclick="newdevis()">
        <div class="col-md-6">
          <div class="panel panel-success panel-alt widget-today">
            <div class="panel-heading text-center">
              <i class="fa fa-bold"></i>
            </div>
            <div class="panel-body text-center">
              <h3 class="today">NOUVEAU DEVIS</h3>
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div>
      </a>
      <a href="javascript:;" onclick="retreviewBondcommand()">
        <div class="col-md-6">
          <div class="panel panel-success panel-alt widget-today">
            <div class="panel-heading text-center">
              <i class="fa fa-bold"></i>
            </div>
            <div class="panel-body text-center">
              <h3 class="today">LISTE DEVIS</h3>
            </div><!-- panel-body -->
          </div><!-- panel -->
        </div>
      </a>
    </div>
