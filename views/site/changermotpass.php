<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
$this->title = Yii::t('app','imperatif');

# RECUPERATION DES INFOS DU TABLEAU DE MESSAGE
$msg = (!empty($msg)) ? unserialize($msg) : $msg;
# RECUPERATION des infos precedements postees
$dataPosted = (!empty($dataPosted)) ? unserialize($dataPosted) : Null;

?>
<body class="signin" style="background-color: #F5F5F5; background-image: url('web/slimstok/assets/images/bg/3.jpg'); color : #fff;">

<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>

<section>

    <div class="signinpanel">

        <div class="row">

            <div class="col-md-6">

                <div class="signin-info">
                    <div class="logopanel">
                        <h1><span>[</span> Ciferiome <span>]</span></h1>
                    </div><!-- logopanel -->

                    <div class="mb20"></div>
                    <div class="hidden-xs hidden-sm">
                      <h5><strong><?= Yii::t('app','')?></strong></h5>
                      <h7><?= Yii::t('app','unsystemepour')?></h7>
                      <ul>
                          <li><i class="fa fa-arrow-circle-o-right mr5"></i> Multiplier les r&#233;venues de votre affaire</li>
                          <li><i class="fa fa-arrow-circle-o-right mr5"></i> Reduire votre temps dans les comptabilit&#233;s</li>
                          <li><i class="fa fa-arrow-circle-o-right mr5"></i> Securiser les donn&#233;es de vos op&#233;rations.</li>
                          <li><i class="fa fa-arrow-circle-o-right mr5"></i> Fid&#233;liser/multiplier la liste vos clients</li>
                          <li><i class="fa fa-arrow-circle-o-right mr5"></i> Transparence dans la gestion</li>
                          <li><i class="fa fa-arrow-circle-o-right mr5"></i> Superviser &#224; distance toutes les op&#233;rations au sein de votre business.</li>
                      </ul>
                      <div class="mb20"></div>
                    </div>

                </div><!-- signin0-info -->

            </div><!-- col-sm-7 -->

            <div class="col-md-6">
                <form action="<?= Yii::$app->request->baseUrl.'/'?>" name="changermotpass" id="changermotpass" method="post">
                  <?=
            		    Yii::$app->nonSqlClass->getHiddenFormTokenField();
            		    $token2 = Yii::$app->getSecurity()->generateRandomString();
            		    $token2 = str_replace('+','.',base64_encode($token2));
            		  ?>
            		  <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                  <input type="hidden" name="action_key" id="action_key" value=""/>
                    <h4 class="nomargin" style="align: center;"><?= Yii::t('app','panelmotpass')?></h4>


                    <div class="<?php echo $msg['type']." ".((is_array($msg) && count($msg)>0)?'show':'hide')?>">
                      <strong><?= $msg['strong']?></strong> <?= $msg['text']?>
                    </div>

                    <input type="password" style="display: none;" name="sugarpot" value="<?= isset($dataPosted['sugarpot']) ? $dataPosted['sugarpot'] : ''  ?>" id="sugarpot" class="form-control" autocomplete="off" placeholder="Laissez vide ce champ" />
                    <input type="password" name="motpass1" value="<?= isset($dataPosted['motpass1']) ? $dataPosted['motpass1'] : ''  ?>" id="motpass1" class="form-control" autocomplete="off" placeholder="<?= Yii::t('app','nouveaumotpasse')?>" />
                    <input type="password" name="motpass2" value="<?= isset($dataPosted['motpass2']) ? $dataPosted['motpass2'] : ''  ?>" id="motpass2" class="form-control" autocomplete="off" placeholder="<?= Yii::t('app','nouveaumotpasse')?>" />
                    <a href="javascript:;" onClick="document.getElementById('action_key').value='<?= md5('updatepswrd')?>'; $('#changermotpass').submit();" class="btn btn-circle btn-success btn-block"><?= Yii::t('app','creer')?></a>
                </form>
            </div><!-- col-sm-5 -->

        </div><!-- row -->

        <div class="signup-footer">
            <div class="pull-left">
                &copy; <?= date('Y')?>. Tout droit r&#233;serv&#233;
            </div>
            <div class="pull-right">
                @ : <a href="http://wizboxtech.com/" target="_blank">wizbox</a>
            </div>
        </div>

    </div><!-- signin -->

</section>
