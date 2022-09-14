  <?php 
require_once('script/fournis_sc.php');
   ?>
  <form action="<?=yii::$app->request->baseurl.'/'.md5('stok_fournisseur')?>" method="POST" name="founisseur_frm" id="founisseur_frm">
    
    <input type="hidden" name="_csrf" value="<?=yii::$app->request->getcsrftoken()?>">
    <input type="hidden" name="action_key" id="action_key" value="">
    
  </form>
    <div class="row">

            <a href="javascript:;" onclick="javascript:nfournis();">
              <div class="col-xs-6">
                <div class="panel panel-primary panel-alt widget-today">
                  <div class="panel-heading text-center">
                    <i class="fa fa-user"><span class="badge-circle"><i class="fa fa-plus-circle" style="position: absolute; bottom:145px; left: 53%; font-size: 43%;" ></i></span></i>
                  </div>
                  <div class="panel-body text-center">
                    <h3 class="today">Nouveau Fournisseur</h3>
                  </div><!-- panel-body -->
                </div><!-- panel -->
              </div>
            </a>

            <a href="javascript:;" onclick="lfournis()">
              <div class="col-xs-6">
                <div class="panel panel-primary panel-alt widget-today">
                  <div class="panel-heading text-center">
                    <i class="fa fa-users"></i>
                  </div>
                  <div class="panel-body text-center">
                    <h3 class="today">Liste des Fournisseurs</h3>
                  </div><!-- panel-body -->
                </div><!-- panel -->
              </div>
            </a>

</div>


  <div class="modal  fade" id="nouveleVente" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title" id="newProdCat">Nouvelle Vente</h4>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <p>Op&#233;ration d&#39;ajustment effectu&#233;e avec success !</p>
                </div>
              </div>
              <div class="modal-footer">
                  <a href="javascript:;" type="button" onClick="$('#goToMainPage').submit()" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;Retour au tableau</a>
                  <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;Nouvelle Operation</button>
              </div>
          </div>
      </div>
  </div>