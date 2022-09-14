<div class="col-md-6">
  <div class="panel panel-default panel-alt widget-messaging">
    <div class="panel-heading">
      <div class="panel-btns">
        <a href="<?= Yii::$app->request->baseUrl . '/' . md5('site_index'); ?>" style="color: #000;" class="btn btn-circle btn-white"> <i class="fa fa-arrow-circle-left"></i></a>
      </div>
      <h3 class="panel-title"><i class="fa fa-money" style="font-size: 13pt;"></i>&nbsp;&nbsp;Historiques Des Charges du Mois</h3>
    </div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Motif</th>
            <th>Date Opération</th>
            <th style="width: 100px;">Montant</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (is_array($listeCharge) && sizeof($listeCharge) > 0) {
            foreach ($listeCharge as $key => $data) {

              $key2 = $key + 1;
              echo '
                        <tr>
                          <td>' . $key2 . '</td>
                          <td>' . $data['motifname'] . '</td>
                          <td>' . Yii::$app->nonSqlClass->convert_date_to_sql_form($data["dateOperation"], 'Y-M-D', 'D/M/Y') . '</td>
                          <td>' . number_format($data['montant']) . '</td>
                          <td>' . $data['description'] . '</td>
                        </tr>
                        ';
            }
          } else {
            echo '<tr><td colspan="11">' . Yii::t('app', 'pasEnregistrement') . '</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>