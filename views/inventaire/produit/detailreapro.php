<form action="<?= yii::$app->request->baseurl . '/' . md5('inventaire_produits') ?>" id="detailreappro" name="detailreappro" method="post">
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
                <a href="javascript:;" onclick="$('#action_key').val('<?= md5("inventaire_produits") ?>'); $('#detailreappro').submit();" style="color: #000;" class="btn btn-circle btn-white"> <i class="fa fa-arrow-circle-left"></i></a>
            </div>
            <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i>Détail du Réappovisionnement</h4>
        </div>
        <div class="panel-body table-responsive" style="height: 500px;">

            <div class="row" style="margin: 20px;">
                <div class="col-md-4">
                    <h4><b>Article/Service</b></h4>
                    <span><?=$Article["libelle"]?></span>
                </div>
                <div class="col-md-4">
                    <h4><b>Catégorie</b></h4>
                    <span><?=$Article["categorie"]?></span>
                </div>
                <div class="col-md-4">
                    <h4><b>U.V.A.</b></h4>
                    <span><?=$Article["uva"]?></span>
                </div>
            </div>
            <div class="table-responsive" id="listtable">
                <table class="table">
                    <thead>
                        <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                        <tr>
                            <th style="width: 5%; border: 1px;">#</th>
                            <th style="width: 100px;">Date</th>
                            <th style="width: 100px;">Quantité</th>
                            <th class="text-center" style="width: 250px;">Branche</th>
                            <th class="text-center" style="width: 250px;">Acteur</th>
                        </tr>
                        <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                    </thead>

                    <tbody>
                        <?php foreach ($historReapp as $key => $data) {
                            $cle = $key + 1;
                            echo '   
                                <tr>
                                    <td>' . $cle . '</td>
                                    <td>' . $data["mapDte"] . '</td>
                                    <td>' . $data["qteMaped"] . '</td>
                                    <td class="text-center">' . $data["nomEntite"] . '</td>
                                    <td class="text-center">' . $data["nomActeur"] . '&nbsp;' . $data["prenomActeur"] . '</td>
                                </tr>
                            ';
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>