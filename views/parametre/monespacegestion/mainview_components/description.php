<h5 class="subtitle"><?= yii::t('app','a_propos_de_vous')?></h5>
<p class="mb30">
  <?php 
  if(!empty($espace_gestion['description'])){
    echo $espace_gestion['description'].'&nbsp;</br>'.'<a href="javascript:;" onclick="$(\'#do_adress_updater\').modal(\'show\')">'.yii::t("app","ajouter_modifier").'</a>';
  }else{
    echo '<div class="alert alert-info">'.yii::t("app","no_data_found").'</br></br><a href="javascript:;" onclick="$(\'#do_adress_updater\').modal(\'show\')">'.yii::t("app","ajouter_modifier").'</a></div>';
  }
  ?>
  <div class="modal fade" id="do_adress_updater" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <h4><?= yii::t('app','nouvelle_adresse')?></h4>
          <div class="form-group">
            <p>
              <textarea class="form-control" name="adresseEntite" id="adresseEntite"><?=$espace_gestion['description']?></textarea>
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <a href="javascript:;" type="button" onClick="document.getElementById('action_key').value='<?= md5("do_update_adress") ?>'; $('#countbook_form').submit();" class="btn btn-circle btn-success"><i class="fa fa-save"></i>&nbsp;<?= Yii::t('app', 'enregistrer') ?></a>

          <button type="button" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i>&nbsp;<?= Yii::t('app', 'rtour') ?></button>
        </div>
      </div>
    </div>
  </div>
</p>