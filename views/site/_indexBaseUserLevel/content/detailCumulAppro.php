<div class="col-md-6">
  <div class="panel panel-default panel-alt widget-messaging">
    <div class="panel-heading">
      <div class="panel-btns">
        <a href="<?= Yii::$app->request->baseUrl . '/' . md5('site_index'); ?>" style="color: #000;" class="btn btn-circle btn-white"> <i class="fa fa-arrow-circle-left"></i></a>
      </div>
      <h3 class="panel-title"><i class="fa fa-money" style="font-size: 13pt;"></i>&nbsp;&nbsp;Historiques Des Approvisionnements du Mois</h3>
    </div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>No Facture</th>
            <th>Date Vente</th>
            <th>Compte Fournsseur</th>
            <th>Facture globale</th>
            <th>Remise Mon&#233;taire</th>
            <th>Montant Net</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (is_array($listeAppro) && sizeof($listeAppro) > 0) {
            foreach ($listeAppro as $key => $data) {

              $key2 = $key + 1;
              echo '
                        <tr>
                          <td>' . $key2 . '</td>
                          <td>' . $data['bill_number'] . '</td>
                          <td>' . Yii::$app->nonSqlClass->convert_date_to_sql_form($data["date_topup"], 'Y-M-D', 'D/M/Y') . '</td>
                          <td>' . Yii::$app->venteClass->getFournisseurName($data["autre_partie_id"]) . '</td>
                          <td>' . number_format($data['billAmount']) . '</td>
                          <td>' . number_format($data['remiseMonetaire']) . '</td>
                          <td>' . number_format($data['montantTotalPayer']) . '</td>
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