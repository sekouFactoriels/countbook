<?php
require_once('script/ventesimple_js.php');
?>
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
                <a href="javascript:;" onclick="$('#action_key').val('<?= md5("retreviewVente")?>'); $('#detailvendu').submit();" style="color: #000;" class="btn btn-circle btn-white"> <i class="fa fa-arrow-circle-left"></i></a>
            </div>
            <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i>Détail de la Vente</h4>
        </div>
        <div class="panel-body table-responsive" style="height: 500px;">

            <div class="table-responsive" id="listtable">
                <table class="table">
                    <thead>
                        <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                        <tr>
                            <th style="width: 5%; border: 1px;">#</th>
                            <th style="width: 250px;">Désignation</th>
                            <th style="width: 150px;">Quantité</th>
                            <th style="width: 100px;">Prix Unitaire</th>
                            <th style="width: 100px;">Montant Total</th>
                        </tr>
                        <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                    </thead>

                    <tbody>
                        <?php foreach ($prod as $key => $data) {
                            $cle = $key + 1;
                            echo '   
                                <tr>
                                    <td>' . $cle . '</td>
                                    <td>' . $data["libelle"] . '</td>
                                    <td>' . $data["qteVendu"] . '</td>
                                    <td>' . $data["spvtotal"] / $data["qteVendu"]. '</td>
                                    <td>' . $data["spvtotal"] . '</td>
                                </tr>
                            ';
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>