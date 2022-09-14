<form action="<?=yii::$app->request->baseurl.'/'.md5('client_themain') ?>" id="countbook_form" name="countbook_form" method="post">
    <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
    $token2 = Yii::$app->getSecurity()->generateRandomString();
    $token2 = str_replace('+', '.', base64_encode($token2));
    ?>
    <!-- DEBUT : BASIC HIDDEN IMPUT FOR THE FORM -->
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
            <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i>Liste des Créances du Mois</h4>
        </div>
        <div class="panel-body table-responsive" style="height: 500px;">

            <div class="table-responsive" id="listtable">
                <table class="table">
                    <thead>
                        <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Facture</th>
                            <th>Client</th>
                            <th>Prix TT Achat</th>
                            <th>Remise</th>
                            <th>TT à payer</th>
                            <th>TT Payé</th>
                            <th>Reste à payer</th>
                            <th>[-]</th>
                        </tr>
                        <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                    </thead>

                    <tbody>
                        <?php
                        $nb = 0;
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

                                                $action_btn = '<a href="javascript:;" Class="btn btn-circle btn-success" onclick="document.getElementById(\'action_key\').value=\''.md5(strtolower("preparer_rembourssement_client")).'\';  document.getElementById(\'action_on_this\').value=\''.$each_bill["bill_number"].'\'; document.getElementById(\'action_on_this_val\').value=\''.$each_bill["id"].'\'; $(\'#countbook_form\').attr(\'action\',\''.md5("paiement_themain").'\'); $(\'#countbook_form\').submit();">Payer</a>';


                                            }
                                        }
                                    }
                                }
                             
                                if ($dette > 0) {
                                    $nb = $nb + 1;
                                    echo '
                                            <tr>
                                            <td>' . $nb . '</td>
                                            <td>' . Yii::$app->nonSqlClass->convert_date_to_sql_form($each_bill["date_topup"], 'Y-M-D', 'D/M/Y') . '</td>
                                            <td style="font-size: 16px; font-weight: bold;">' . $each_bill["bill_number"] . '</td>
                                            <td style="font-size: 16px; font-weight: bold;">' . Yii::$app->venteClass->getClientname($each_bill["autre_partie_id"]) . '</td>
                                            <td>' . number_format($each_bill["billAmount"]) . '</td>
                                            <td>' . number_format($each_bill["remiseMonetaire"]) . '</td>
                                            <td>' . number_format($each_bill["montantTotalPayer"]) . '</td>
                                            <td>' . number_format($tt_paid) . '</td>
                                            <td><span class="badge badge-' . $badge . '">' . number_format($dette) . '</span></td>
                                            <td>' . $action_btn . '</td>
                                            ';
                                }
                            }
                        } else {
                            echo '<tr><td colspan="9">' . yii::t('app', 'nonEnre') . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>