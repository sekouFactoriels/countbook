<div class="col-md-12">
    <div class="panel panel-default panel-alt widget-messaging">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-exchange" style="font-size: 13pt;"></i>&nbsp;&nbsp;<?= yii::t("app","alerte_systeme")?></h3>
      </div>
      <div class="panel-body">
        <div class="table-responsive" id="listtable">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th style="text-align: center;">Libell&#233;</th>
                <th style="text-align: center;">Cumul Valeur</th>
                <th style="text-align: center;">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>*</td>
                <td>CUMUL DES CREANCES</td>
                <td><?= number_format($cumulCreance) ?></td>
                <td style="text-align: center;"><?= '<a href="javascript:;" Class="btn btn-circle btn-success" onclick="document.getElementById(\'action_key\').value=\'' . md5(strtolower("cumul_creance")) . '\';   $(\'#countbook_form\').attr(\'action\',\'' . md5("site_index") . '\'); $(\'#countbook_form\').submit();">Voir plus</a>'; ?></td>
              </tr>

              <tr>
                <td>*</td>
                <td>CUMUL DES DETTES</td>
                <td><?= number_format($cumulDette) ?></td>
                <td style="text-align: center;"><?= '<a href="javascript:;" Class="btn btn-circle btn-success" onclick="document.getElementById(\'action_key\').value=\'' . md5(strtolower("cumul_dette")) . '\';   $(\'#countbook_form\').attr(\'action\',\'' . md5("site_index") . '\'); $(\'#countbook_form\').submit();">Voir plus</a>'; ?></td>
              </tr>

              

            </tbody>
          </table>
        </div>
      </div><!-- panel-body -->
    </div><!-- panel -->
  </div><!-- col-md-6 -->