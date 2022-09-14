<div class="col-md-6">
  <div class="panel panel-default panel-alt widget-messaging">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-money" style="font-size: 13pt;"></i>&nbsp;&nbsp;Top 10 des articles vendus</h3>
    </div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Libell&#233;</th>
            <th class="text-center">Quantit&#233; Disponible</th>
            <th>Cumul Vendu</th>
            <th style="text-align: center;" width="5px;">[-]</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($topDixArticle as $key => $data) {

            $key2 = $key + 1;

            $voirPlus = '<a href="javascript:;" Class="btn btn-circle btn-success" onclick="document.getElementById(\'action_key\').value=\'' . md5(strtolower("detail_top_dix_article")) . '\'; document.getElementById(\'action_on_this\').value=\'' . $data["idProduit"] . '\'; $(\'#countbook_form\').attr(\'action\',\'' . md5("site_index") . '\'); $(\'#countbook_form\').submit();">Voir plus</a>';

            echo '
              <tr>
                <td>' . $key2 . '</td>
                <td>' . $data["productCode"]."&nbsp:&nbsp".Yii::$app->venteClass->getProductname($data["idProduit"]) . '</td>
                <td class="text-center">' . $data["qteDispo"] . '</td>
                <td>' . number_format($data["prixVenteAccorde"]) . '</td>
                <td>' . $voirPlus . '</td>
              </tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>