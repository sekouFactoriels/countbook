<?php
//require_once('script/ventesimple_js.php');
# RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
 $msg = (isset($_SESSION['msg'])) ? $_SESSION['msg'] : $msg;
 $msg = (!empty($msg)) ? unserialize($msg) : $msg;
 unset($_SESSION['msg']);
?>
 <form action="<?=yii::$app->request->baseurl.'/'.md5('diver_charge') ?>" method="POST" name="diver_charge_frm" id="diver_charge_frm" >
 <input type="hidden" name="_csrf" value="<?=yii::$app->request->getcsrftoken()?>">
 <input type="hidden" name="action_key" id="action_key" value="">

 </form>

 <!-- DEBUT CONTENEUR DE MESSAGE  -->
 <?php $msg = (!empty($msg['type']))?$msg:null;?>
   <div class="<?= $msg['type'] ?>">
     <strong><?= $msg['strong']?></strong> <?= $msg['text'] ?>
   </div>
 <!-- FIN CONTENEUR DE MESSAGE -->

 <div class="row">
         <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5("ncharge")?>'; document.getElementById('diver_charge_frm').submit();">
           <div class="col-md-6">
             <div class="panel panel-info panel-alt widget-today">
               <div class="panel-heading text-center">
                 <i class="fa fa-plus-circle"></i>
               </div>
               <div class="panel-body text-center">
                 <h3 class="today">Nouvelle D&#233;pense</h3>
               </div><!-- panel-body -->
             </div><!-- panel -->
           </div>
         </a>

         <a href="javascript:;" onclick="document.getElementById('action_key').value='<?= md5("charges")?>'; document.getElementById('diver_charge_frm').submit();">
           <div class="col-md-6">
             <div class="panel panel-warning panel-alt widget-today">
               <div class="panel-heading text-center">
                 <i class="glyphicon glyphicon-eye-open"></i>
               </div>
               <div class="panel-body text-center">
                 <h3 class="today">Retrouver une D&#233;pense</h3>
               </div><!-- panel-body -->
             </div><!-- panel -->
           </div>
         </a>

</div>
