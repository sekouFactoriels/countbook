<form action="<?= yii::$app->request->baseurl . '/' . md5('site_index') ?>" id="countbook_form" name="countbook_form" method="post">
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
            <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i>Liste des Articles </h4>
        </div>
        <div class="panel-body table-responsive" style="height: 500px;">

            <div class="table-responsive" id="listtable">
                <table class="table">
                    <thead>
                        <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                        <tr>
                            <th>#</th>
                            <th>Code Produit</th>
                            <th style="width: 300px;">Description</th>
                            <th>U.V.</th>
                            <th class="text-center">Qte. Dispo</th>
                            <th style="width: 100px;">Prix A.U</th>
                            <th style="width: 100px;">Prix V.U</th>
                        </tr>
                        <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                    </thead>

                    <tbody>
                        <?php
                        if (is_array($listProduits) && sizeof($listProduits) > 0) {
                            foreach ($listProduits as $key => $data) {
                                $catName = $udmName = $markName = $productQte =  Null;
                                $key2 = $key + 1;
                                # RECUPERONS LA CATEGORIE
                                foreach ($productCategories as $catData) :
                                    if ($catData['id'] == $data["categoryId"]) {
                                        $catName = $catData['nom'];
                                    }
                                endforeach;

                                # RECUPERONS LES UDM
                                if (sizeof($produitUdm) > 0) {
                                    foreach ($produitUdm as $udmData) :
                                        if ($udmData['id'] == $data["udm"]) {
                                            $udmName = $udmData['nom'];
                                        }
                                    endforeach;
                                }

                                echo '
                                    <tr>
                                        <td>' . $key2 . '</td>
                                        <td>' . $data["productCode"] . '</td>
                                        <td><ul>
                                        <li><b>Catrgorie : </b>  ' . $catName . '</li>
                                        <li><b>Nom : </b>' . $data["libelle"] . '</li>
                                        </ul></td>
                                        <td>' . $udmName . '</td>
                                        <td class="text-center" style="color:#fcb903;">' . $data['qteDispo'] . '</td>
                                        <td>' . number_format($data["prixUnitaireAchat"]) . '</td>
                                        <td>' . number_format($data["prixUnitaireVente"]) . '</td>
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
</form>