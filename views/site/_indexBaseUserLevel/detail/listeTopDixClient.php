<form action="<?= yii::$app->request->baseurl . '/' . md5('vente_simple') ?>" id="detailvendu" name="detailvendu" method="post">
    <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
    $token2 = Yii::$app->getSecurity()->generateRandomString();
    $token2 = str_replace('+', '.', base64_encode($token2));
    ?>
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
    <input type="hidden" name="token2" value="<?= $token2 ?>" />
    <input type="hidden" name="action_key" id="action_key" value="" />
    <input type="hidden" name="action_on_this" id="action_on_this" value="" />
    <input type="hidden" name="action_on_this_val" id="action_on_this_val" value="" />

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-btns">
                <a href="<?= Yii::$app->request->baseUrl . '/' . md5('site_index'); ?>" style="color: #000;" class="btn btn-circle btn-white"> <i class="fa fa-arrow-circle-left"></i></a>
            </div>
            <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i>Détail des Opérations du Client dans le mois</h4>
        </div>
        <div class="panel-body table-responsive" style="height: 500px;">

            <div class="row" style="margin: 20px;">
                <div class="col-md-4">
                    <h4><b>Client</b></h4>
                    <span><?= isset($client['nom_appellation']) ? $client['nom_appellation'] :  Yii::t('app', 'clientordi') ?></span>
                </div>
                <div class="col-md-4">
                    <h4><b>Téléphone</b></h4>
                    <span><?=$client['tel1'] ?></span>
                </div>
                <div class="col-md-4">
                    <h4><b>Email</b></h4>
                    <span><?= $client['email'] ?></span>
                </div>
            </div>
            <div class="table-responsive" id="listtable">
                <table class="table">
                    <thead>
                        <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>No. Facture</th>
                            <th>Prix TT Achat</th>
                            <th>Remise</th>
                            <th>TT à payer</th>
                            <th>TT Payé</th>
                            <th>Reste à payer</th>
                        </tr>
                        </tr>
                        <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                    </thead>

                    <tbody>
                        <?php
                        if (is_array($bills) && sizeof($bills) > 0) {
                            foreach ($bills as $key => $each_bill) {
                                $badge = "success";
                                $tt_paid = $dette = Null;
                                $key2 = $key + 1;
                                $paiement_btn = '';

                                if (isset($each_bill_ttpaid)) {
                                    foreach ($each_bill_ttpaid as $data_ttpaid) {
                                        if (isset($data_ttpaid['bill_id']) && $data_ttpaid['bill_id'] == $each_bill["id"]) {
                                            $tt_paid = $data_ttpaid['tt_paid'];
                                            $dette = $each_bill["montantTotalPayer"] - $tt_paid;
                                            if (isset($dette) && $dette > 0) {
                                                $badge = "danger";

                                                $paiement_btn = '<a href="javascript:;" Class="btn btn-circle btn-success" onclick="document.getElementById(\'action_key\').value=\'' . md5(strtolower("preparer_rembourssement_client")) . '\';  document.getElementById(\'action_on_this\').value=\'' . $each_bill["bill_number"] . '\'; document.getElementById(\'action_on_this_val\').value=\'' . $each_bill["id"] . '\'; $(\'#countbook_form\').attr(\'action\',\'' . md5("paiement_themain") . '\'); $(\'#countbook_form\').submit();">Paiement</a>';
                                            }
                                        }
                                    }
                                }
                                $voirplus_btn = '<a href="javascript:;" Class="btn btn-circle btn-primary" onclick="document.getElementById(\'action_key\').value=\'' . md5(strtolower("charger_facture_data")) . '\';  document.getElementById(\'action_on_this\').value=\'' . $each_bill["id"] . '\';  document.getElementById(\'action_on_this_val\').value=\'' . "1" . '\'; $(\'#countbook_form\').attr(\'action\',\'' . md5("paiement_themain") . '\'); $(\'#countbook_form\').submit();">Voir plus</a>';

                                echo '
                                        <tr>
                                        <td>' . $key2 . '</td>
                                        <td>' . Yii::$app->nonSqlClass->convert_date_to_sql_form($each_bill["date_topup"], 'Y-M-D', 'D/M/Y') . '</td>
                                        <td style="font-size: 16px; font-weight: bold;">' . $each_bill["bill_number"] . '</td>
                                        <td>' . number_format($each_bill["billAmount"]) . '</td>
                                        <td>' . number_format($each_bill["remiseMonetaire"]) . '</td>
                                        <td>' . number_format($each_bill["montantTotalPayer"]) . '</td>
                                        <td>' . number_format($tt_paid) . '</td>
                                        <td><span class="badge badge-' . $badge . '">' . number_format($dette) . '</span></td>
                                        ';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>