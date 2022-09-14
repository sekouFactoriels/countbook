<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-btns">
            <!-- <a onClick="$('#newProductUdm').modal('show')" style="color: #000;" class="btn btn-circle btn-white" name="newUdm" id="newUdm"> <i class="fa fa-plus">&nbsp;</i><?= Yii::t('app', 'newProductLabel') ?></a> -->
        </div>
        <h4 class="panel-title"><i class="fa fa-list">&nbsp;</i><?= Yii::t('app', 'listentreprise') ?></h4>
    </div>
    <div class="panel-body">
        <form action="<?= Yii::$app->request->baseUrl . '/' . md5("parametre_listentreprise") ?>" id="listentreprise" name="listentreprise" method="post">
            <?=
            Yii::$app->nonSqlClass->getHiddenFormTokenField();
            $token2 = Yii::$app->getSecurity()->generateRandomString();
            $token2 = str_replace('+', '.', base64_encode($token2));
            ?>
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />
            <input type="hidden" name="token2" value="<?= $token2 ?>" />
            <input type="hidden" name="action_key" id="action_key" value="<?= md5('listGroups') ?>" />
            <input type="hidden" name="action_on_this" id="action_on_this" value="" />
            <input type="hidden" name="msg" id="msg" value="" />

            <div class="table-responsive" id="listtable">
                <table class="table">
                    <thead>
                        <!-- DEBUT : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Numéro Commerce</th>
                            <th>activité</th>
                            <th>Date de Création</th>
                            <th class="text-center">Licence</th>
                            <th class="text-center">Contact</th>
                            <th>Adresse</th>
                        </tr>
                        <!-- FIN : LISTE DES COLONNES DE LA TABLE VIEW DES PRODUITS -->
                    </thead>

                    <tbody>
                        <?php
                        if (is_array($listentreprise) && sizeof($listentreprise) > 0) {
                            foreach ($listentreprise as $key => $data) {
                                $key2 = $key + 1;
                                echo '
                      <tr>
                        <td>' . $key2 . '</td>
                        <td>
                            <ul>
                                <li><b>Raison Sociale: </b>' . $data["nom"] . '</li>
                                <li><b>Slogan: </b>' . $data["slogan"] . '</li>
                                <li><b>Description: </b>' . $data["description"] . '</li>
                            </ul>
                        </td>
                        <td>' . $data["numerosCommerce"] . '</td>
                        <td>' . $data["activite"] . '</td>
                        <td>' . $data["dteCreation"] . '</td>
                        <td>
                        <ul>
                                <li><b>Type de Licence: </b>' . $data["tl"] . '</li>
                                <li><b>Date Debut: </b>' . $data["ddl"] . '</li>
                                <li><b>Date Fin: </b>' . $data["dfl"] . '</li>
                            </ul>
                        </td>
                        <td>
                            <ul>
                                <li><b>Téléphone: </b>' . $data["tel"] . '</li>
                                <li><b>Email: </b>' . $data["email"] . '</li>
                            </ul>
                        </td>
                        <td>' . $data["addresse"] . '</td>
                        
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

        </form>
    </div>
</div>
<?php
# INCLUDE PRODUCT CATEGORY
?>