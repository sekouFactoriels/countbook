<div class="col-md-6" >
  <div class="panel panel-default panel-alt widget-messaging">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-money" style="font-size: 13pt;"></i>&nbsp;&nbsp;Top 10 des Clients Payeurs du mois</h3>
    </div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Le Client</th>
            <th>Cumul Pay&#233;</th>
            <th style="text-align: center;" width="5px;">[-]</th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach($topDixClient as $key=> $data){

              $key2 = $key +1;

              $voirPlus= '<a href="javascript:;" Class="btn btn-circle btn-success" onclick="document.getElementById(\'action_key\').value=\'' . md5(strtolower("detail_top_dix_client")) . '\'; document.getElementById(\'action_on_this\').value=\'' . $data["id_client"] . '\';   $(\'#countbook_form\').attr(\'action\',\'' . md5("site_index") . '\'); $(\'#countbook_form\').submit();">Voir plus</a>';


          echo'
              <tr>
                <td>'.$key2.'</td>
                <td>'.Yii::$app->venteClass->getClientname($data["id_client"]).'</td>
                <td>'.number_format($data["sommevente"]).'</td>
                <td>'.$voirPlus.'</td>
              </tr>';
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>