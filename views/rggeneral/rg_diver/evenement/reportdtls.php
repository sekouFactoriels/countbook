<?php
//echo print_r($coredata);
$userPrimaryData = unserialize($userPrimaryData);
$entrepriseData = Yii::$app->mainCLass->getEntrepriseData($userPrimaryData['idEntreprise']);
$formdataposted = unserialize($formdataposted);
$data = (isset($data)) ? unserialize($data) : Null;
require_once(Yii::$app->basePath . '/views/rggeneral/singleHeader.php');
?>
<div class="row hidden-xs" style="text-align: center;">
  <strong style="font-size: 30px; text-decoration: underline;">Rapport d&#39;evenements</strong>
</div>
<div class="row visible-xs" style="text-align: center;">
  <strong style="font-size: 20px; text-decoration: underline;">Rapport d&#39;evenements</strong>
</div>

<div class="row">
  <div class="col-sm-6">
    <address>
      <?=
      '</br><label style="font-size: 11px;">@ le : ' . date("d/m/Y") . '</br>
              Filtrer / date d&#39;evenement </br>Du : ' . Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['datefrom'], "M/D/Y", "D/M/Y") . '</br>
              Au : ' . Yii::$app->nonSqlClass->convert_date_to_sql_form($formdataposted['dateto'], "M/D/Y", "D/M/Y") . '
          </label>';
      ?>
    </address>
  </div>
</div>

<div class="row">
  <div class="col-sm-12 hidden-xs">
    <table border="1" width="100%">
      <thead style="font-size: 14px;">
        <th width="4%">&nbsp;N&#176;</th>
        <th width="20%">&nbsp;Evenement</th>
        <th width="20%">&nbsp;Utulisateur</th>
        <th width="20%">&nbsp;Description</th>
        <th width="17%">&nbsp;Adresse IP</th>
        <th width="17%">&nbsp;Date / Temps</th>
      </thead>
      <tbody>
        <?php
        if (is_array($coredata) && sizeof($coredata) > 0) {
          foreach ($coredata as $key1 => $value) {
            $server = unserialize($value['serverDtls']);
            $ip = $server['SERVER_ADDR'];
            $key1 = $key1 + 1;
            echo '<tr>
                      <td>' . $key1 . '</td>
                      <td>' . Yii::$app->mainCLass->geteventtypename($value["eventTypeCode"]) . '</td>
                      <td>' . Yii::$app->mainCLass->getUserFullnameBaseId($value["idUserAuth"]) . '</td>
                      <td>' . $value["eventDesc"] . '</td>
                      <td>' . $ip . '</td>
                      <td>' . $value["insertedle"] . '</td>
                  </tr>';
          }
        } else {
          echo '<tr><td colspan="7">' . Yii::t('app', 'pasEnregistrement') . '</td></tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
  <div class="col-sm-12 visible-xs">
    <table border="1" width="100%">
      <tbody>
        <?php
        if (is_array($coredata) && sizeof($coredata) > 0) {
          foreach ($coredata as $key => $value) {
            $server = unserialize($value['serverDtls']);
            $ip = $server['SERVER_ADDR'];
            $key2 = $key + 1;
            echo '<tr>
                       <td> <b>&nbsp;N&#176; : </b>' . $key2 . '
                       </br><b>&nbsp;Evenement : </b>' . Yii::$app->mainCLass->geteventtypename($value["eventTypeCode"]) . '
                       </br><b>&nbsp;Utulisateur : </b>' . Yii::$app->mainCLass->getUserFullnameBaseId($value["idUserAuth"]) . '
                       </br><b>&nbsp;Description : </b>' . $value["eventDesc"] . '
                       </br><b>&nbsp;Adresse IP : </b>' . $ip . '
                       </br><b>&nbsp;Date / Temps : </b>' . $value["insertedle"] . '
                       </br> </td>
                  </tr>';
          }
        } else {
          echo '<tr><td colspan="7">' . Yii::t('app', 'pasEnregistrement') . '</td></tr>';
        }
        ?>
      </tbody>
    </table>
  </div>





</div>