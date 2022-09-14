<?php
require_once('script/listeventes_js.php');
$msg = (!empty($msg)) ? unserialize($msg) : $msg;
$userPrimaryData = (isset($userPrimaryData)) ? unserialize($userPrimaryData) : Null;
?>
<form action="<?= yii::$app->request->baseurl . '/' . md5('site_index') ?>" id="listeventes" name="listeventes" method="post">
    <?= Yii::$app->nonSqlClass->getHiddenFormTokenField();
    $token2 = Yii::$app->getSecurity()->generateRandomString();
    $token2 = str_replace('+', '.', base64_encode($token2));
    ?>
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
    <input type="hidden" name="token2" value="<?= $token2 ?>" />
    <input type="hidden" name="action_key" id="action_key" value="" />
    <input type="hidden" name="action_on_this" id="action_on_this" value="" />
    <input type="hidden" name="action_on_this_val" id="action_on_this_val" value="" />
    <input type="hidden" name="msg" id="msg" value="" />
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-btns">
                <a href="<?= Yii::$app->request->baseUrl . '/' . md5('site_index'); ?>" style="color: #000;" class="btn btn-circle btn-white"> <i class="fa fa-arrow-circle-left"></i></a>
            </div>
            <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i>Liste des ventes du mois</h4>
        </div>
        <div class="panel-body">


            <?php $msg = (!empty($msg['type'])) ? $msg : null; ?>
            <div class="<?= $msg['type'] ?>">
                <strong><?= $msg['strong'] ?></strong> <?= $msg['text'] ?>
            </div>

            <div class="table-responsive" id="listtable">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="3">
                                <div class="form-group">
                                    <label><?= Yii::t('app', 'trierSelon') ?> <span> : </span> </label>
                                    <select class="form-control" id="selectCriteria" name="selectCriteria">
                                        <option value="1" <?= isset($_POST['selectCriteria']) ? $_POST['selectCriteria'] == 1 ? 'selected' : '' : '' ?>><?= Yii::t('app', 'venterecente') ?></option>
                                        <option value="2" <?= isset($_POST['selectCriteria']) ? $_POST['selectCriteria'] == 2 ? 'selected' : '' : '' ?>><?= Yii::t('app', 'ventemoinrecente') ?></option>
                                    </select>
                                </div>
                            </th>

                            <th colspan="3">
                                <div class="form-group">
                                    <label><?= Yii::t('app', 'donneeRecherche') ?> <span> : </span> </label>
                                    <input class="form-control" type="text" id="donneeRecherche" name="donneeRecherche" value="<?= isset($_POST[Yii::$app->params['donneeRecherche']]) ? $_POST[Yii::$app->params['donneeRecherche']] : Null ?>" />
                                </div>
                            </th>

                            <th colspan="">
                                <div class="form-group">
                                    <label><?= Yii::t('app', 'margeLigne') ?> <span> : </span> </label>
                                    <select class="form-control" id="limit" name="limit">
                                        <option value="1" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 1 ? 'selected' : '' : '' ?>>1 - 10</option>
                                        <option value="2" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 2 ? 'selected' : '' : '' ?>>1 - 20</option>
                                        <option value="3" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 3 ? 'selected' : '' : '' ?>>1 - 30</option>
                                        <option value="4" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 4 ? 'selected' : '' : '' ?>>1 - 40</option>
                                        <option value="5" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 5 ? 'selected' : '' : '' ?>>1 - 50</option>
                                        <option value="6" <?= isset($_POST[Yii::$app->params['limit']]) ? $_POST[Yii::$app->params['limit']] == 6 ? 'selected' : '' : '' ?>>1 - 50 +</option>
                                    </select>
                                </div>
                            </th>

                            <th colspan="3">
                                <div class="form-group">
                                    <a href="javascript:;" onclick="submitFilter()" class="btn btn-circle btn-primary" name="afficheBtn" id="afficheBtn"> <i class="fa fa-eye">&nbsp;</i><?= Yii::t('app', 'afficher') ?></a>
                                    <!-- <span>&nbsp;</span>
                        <a href="javascript:;" onclick="$('#action_key').val('<?= md5("retreviewVente") ?>'); $('#listeventes').submit();" class="btn btn-circle btn-default" name="" id=""> <i class="fa fa-refresh">&nbsp;</i><?= Yii::t('app', 'reset') ?></a> -->
                                </div>
                            </th>
                            <th colspan="2">&nbsp;</th>
                        </tr>
                        <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                        <tr>
                            <th>#</th>
                            <th style="width: 150px;">No Facture</th>
                            <th style="width: 150px;">Date Vente</th>
                            <th style="width: 250px;">Compte Client</th>
                            <th style="width: 100px;">Facture globale</th>
                            <th style="width: 100px;">Remise Mon&#233;taire</th>
                            <th style="width: 120px;">Montant Final (+Remise)</th>
                            <th style="width: 100px;">Montant per&#231;ue</th>
                            <th style="width: 100px; text-align: center;">Reste à payer</th>
                            <th style="width: 100px; text-align: center;">[-]</th>
                            <th style="width: 100px; text-align: center;">[-]</th>
                        </tr>
                        <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                    </thead>

                    <tbody>
                        <?php
                        if (is_array($ventesDtls) && sizeof($ventesDtls) > 0) {
                            foreach ($ventesDtls as $key => $data) {
                                $key2 = $key + 1;
                                $clearbtn = '<a href="javascript:;" Class="btn btn-circle btn-danger" onclick="document.getElementById(\'action_key\').value=\'' . md5(strtolower("deletesale")) . '\'; document.getElementById(\'action_on_this\').value=\'' . base64_encode($data["id"]) . '\'; document.getElementById(\'listeventes\').submit();">Suprimer</a> ';
                                
                                $detailbtn = '<a href="javascript:;" Class="btn btn-circle btn-primary" onclick="document.getElementById(\'action_key\').value=\'' . md5(strtolower("detailvente")) . '\'; document.getElementById(\'action_on_this\').value=\'' . base64_encode($data["id"]) . '\'; document.getElementById(\'listeventes\').submit();">Détail</a> ';

                                $facture_id = yii::$app->paiementClass->get_bill_id($data['codeVente']);
                                // $facture_id = $data["id_client"];

                                $facture = '<a href="javascript:;" Class="btn btn-circle btn-info" onclick="document.getElementById(\'action_key\').value=\'' . md5(strtolower("charger_facture_data")) . '\';  document.getElementById(\'action_on_this\').value=\'' . $facture_id . '\';  document.getElementById(\'action_on_this_val\').value=\'' . "1" . '\'; $(\'#listeventes\').attr(\'action\',\'' . md5("paiement_themain") . '\'); $(\'#listeventes\').submit();">Facture</a>';

                                if (isset($data['statut']) && $data['statut'] == 2) {

                                    //Determiner l'id de la facture et le numero
                                    $facture_id = yii::$app->paiementClass->get_bill_id($data['codeVente']);

                                    //Btn de recouvrement
                                    $recouvrementBtn = '<a href="javascript:;" Class="btn btn-circle btn-success" onclick="document.getElementById(\'action_key\').value=\''.md5(strtolower("preparer_rembourssement_client")).'\';  document.getElementById(\'action_on_this\').value=\''.$data['codeVente'].'\'; document.getElementById(\'action_on_this_val\').value=\''. $facture_id.'\'; $(\'#listeventes\').attr(\'action\',\''.md5("paiement_themain").'\'); $(\'#listeventes\').submit();">Payer</a>';


                        ?>

                                    <div class="modal fade" id="recouvrement_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="newProdCat">Recouvrement de dette</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Montant en dette<span class="asterisk">*</span>
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" value="" class="form-control" name="montantendette" id="montantendette" autocomplete="off" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Montant à recouvrir<span class="asterisk">*</span>
                                                        </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" value="" class="form-control" name="montant_recouvir" id="montant_recouvir" autocomplete="off" />
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="modal-footer">
                                                    <a href="javascript:;" type="button" onClick="$('#action_key').val('<?= md5("faire_recouvrement") ?>'); $('#listeventes').submit();" class="btn btn-circle btn-success"><i class="glyphicon glyphicon-saved"></i>&nbsp;<?= Yii::t('app', 'enrg'); ?></a>

                                                    <button type="button" onClick="cancel()" class="btn btn-circle btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove-circle"></i>&nbsp;<?= Yii::t('app', 'fermer'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                        <?php
                                } else {
                                    $recouvrementBtn = '<span class="label label-success">soldé</span>';
                                }


                                echo '
                      <tr>
                        <td>' . $key2 . '</td>
                        <td>' . $data["codeVente"] . '</td>
                        <td>' . $data["lastUpdate"] . '</td>
                        <td>' . Yii::$app->venteClass->getClientname($data["id_client"]) . '</td>
                        <td>' . number_format($data["prixTotalVente"]) . '</td>
                        <td>' . number_format($data["remiseMonetaire"]) . '</td>
                        <td>' . number_format($data["prixVenteAccorde"]) . '</td>
                        <td>' . number_format($data["montantpercu"]) . '</td>
                        <td>' . number_format($data["detteVente"]) . '</td>
                        <td style="text-align: center;">' . $recouvrementBtn . '</td>
                        <td>' . $facture . '</td>
                      </tr>
                      ';
                            }
                        } else {
                            echo '<td colspan="14">' . Yii::t('app', 'pasEnregistrement') . '</td>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>