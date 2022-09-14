                            <!-- DEBUT LICENCE : CREATION D'UNE ASSOCIATION PAR UN SANTEYAKAH MODAL -->
                            <div class="modal fade" id="licenceCreatAssociat" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="licenceCreatAssociatLabel"><?php echo Yii::t('app','licenceDeCreationSanteyakah');?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                              <div class="scroller" style="height:200px" data-always-visible="1" data-rail-visible="1" data-rail-color="red" data-handle-color="green">
                                                <?= Yii::t('app','licenceCreatAssociatMsg')?>
                                              </div>
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" onClick="<?= md5('1Engagement')?>()" class="btn btn-success" data-dismiss="modal"><?= Yii::t('app','accepte');?></button>
                                            <button type="button" onClick="<?= md5('0Engagement')?>()" class="btn btn-warning" data-dismiss="modal"><?= Yii::t('app','decline');?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN LICENCE : CREATION D'UNE ASSOCIATION PAR UN SANTEYAKAH MODAL -->

                            <!-- DEBUT LICENCE : CREATION DE COMPTE SANTEYAKAH MODAL -->
                            <div class="modal fade" id="licenceCorpMedCreSanteyakah" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="licenceCorpMedCreSanteyakahLabel"><?php echo Yii::t('app','licenceDeCreationSanteyakah');?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>
                                              <div class="scroller" style="height:200px" data-always-visible="1" data-rail-visible="1" data-rail-color="red" data-handle-color="green">
                                                <?= Yii::t('app','licenceCorpMedCreSanteyakahMsg')?>
                                              </div>
                                            </p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" onClick="<?= md5('corpMedAccepteLicence')?>()" class="btn btn-success" data-dismiss="modal"><?= Yii::t('app','accepte');?></button>
                                            <button type="button" onClick="<?= md5('corpMedDeclineLicence')?>()" class="btn btn-warning" data-dismiss="modal"><?= Yii::t('app','decline');?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN LICENCE : CREATION DE COMPTE SANTEYAKAH MODAL -->

                            <!-- DEBUT EMPTY MESSAGE MODAL -->
                            <div class="modal fade" id="empty_msg_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel"><?php echo Yii::t('app','alertRouge');?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <p><?php echo Yii::t('app','champObligatoire');?></p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-warning" data-dismiss="modal"><?= Yii::t('app','ok');?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN EMPTY MESSAGE MODAL -->


                            <!-- DEBUT MESSAGE ERREUR  -->
                            <div class="modal fade" id="erreurMsgModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel"><?php echo Yii::t('app','alertRouge');?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <p><?php echo Yii::t('app','invalidData');?></p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-warning" data-dismiss="modal"><?= Yii::t('app','ok');?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN MESSAGE ERREUR  -->

                            <!-- DEBUT MESSAGE CONFIRMATION D'ACTION  -->
                            <div class="modal fade" id="submitFormModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" name="formSubmitModal" id="formSubmitModal"><?= Yii::t('app','alertRouge') ?></h4>
                                        </div>
                                        <div class="modal-body" id="submitFormModalText">
                                          <p><?= Yii::t('app','clickOkValid')?></p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-success" data-dismiss="modal" onclick="saveProduct()"><?= Yii::t('app','ok');?></button>
                                            <button type="button" class="btn btn-dark" data-dismiss="modal"><?= Yii::t('app','annul')?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- DEBUT MESSAGE CONFIRMATION D'ACTION  -->


                            <!-- DEBUT MESSAGE D'INFO -->
                            <div class="modal fade" id="infoMsgModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" name="formSubmitModal" id="formSubmitModal"><?= Yii::t('app','alertRouge') ?></h4>
                                        </div>
                                        <div class="modal-body" id="infoMsgBody">

                                        </div>

                                        <div class="modal-footer" id="infoMsgModalAction">
                                            <button type="button" class="btn btn-success" id="infoMsgBodyOkButton" name="infoMsgBodyOkButton" data-dismiss="modal"><?= Yii::t('app','ok');?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- FIN : MESSAGE D'INFO -->


                            <!-- DEBUT MESSAGE CONFIRMATION D'ACTION  -->
                            <div class="modal fade" id="submitComptForm" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" name="formSubmitModal" id="formSubmitModal"><?= Yii::t('app','alertRouge') ?></h4>
                                        </div>
                                        <div class="modal-body" id="msgtextCreeCompt">
                                          <p><?= Yii::t('app','msgActionCreeCompt')?></p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-success" data-dismiss="modal" id="submitComptAction"><?= Yii::t('app','ok');?></button>
                                            <button type="button" class="btn btn-dark" data-dismiss="modal"><?= Yii::t('app','annul')?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- DEBUT MESSAGE CONFIRMATION D'ACTION  -->




                            <div class="modal fade" id="gnrlFormSubmitModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" name="formSubmitModal" id="formSubmitModal"><?= Yii::t('app','alertRouge') ?></h4>
                                        </div>
                                        <div class="modal-body" id="gnrlFormSubmitText">
                                          <p><?= Yii::t('app','clickOkValid')?></p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-success" data-dismiss="modal" onclick="javascript:saveData()"><?= Yii::t('app','ok');?></button>
                                            <button type="button" class="btn btn-dark" data-dismiss="modal"><?= Yii::t('app','annul')?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- DEBUT MESSAGE CONFIRMATION D'ACTION  -->
