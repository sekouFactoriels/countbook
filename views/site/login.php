<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Url;

$this->title = Yii::t('app', 'connexion');
$msg = (!empty($msg['type'])) ? $msg : null;
$userName = !empty($userName) ? $userName : null;
$motPass = !empty($motPass) ? $motPass : null;
$sugarpot = !empty($sugarpot) ? $sugarpot : null;
?>


<!-- Banniere Principale -->
<div class="relative bg-white shadow ">
  <div class="max-w-7xl mx-auto px-4 sm:px-4">
    <div class="flex justify-between items-center py-6 md:justify-start md:space-x-10">
      <div class="flex justify-start lg:w-0 lg:flex-1">
        <a href="javascript:;">
          <span class="sr-only">Countbook</span>
          <img class="mx-auto h-9 w-auto" src="<?= yii::$app->request->baseUrl . '/web/slimstok/assets/images/lg-svg.png' ?>" alt="Countbook">
        </a>
      </div>
      <div class="hidden md:flex items-center justify-end md:flex-1 lg:w-0">
        <a href="javascript:;" onclick="$('#login-form').attr('action','<?= md5("imvisitor_tarifs") ?>'); document.getElementById('login-form').submit();" class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-black hover:bg-red-800 sm:px-8"> Nos tarifs </a>
        <a href="#" class="flex ml-5 items-center justify-center px-4 py-3 border border-black text-base font-medium rounded-md shadow-sm text-black bg-transparent bg-opacity-60 hover:bg-red-800 hover:text-white sm:px-8"> Faites un essai </a>
      </div>
    </div>
  </div>
</div>
<div class="relative bg-gray-400 overflow-hidden">
  <!-- main -->
  <main class="mt-12 sm:mt-16 sm:mb-8">
    <div class="mx-auto max-w-7xl">
      <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        <?php
            //Charger la banniere
          require_once('login_components/banniere_ui.php');
        ?>
        <!-- Formulaire de connexion -->
        <div class="mt-16 sm:mt-24 lg:mt-0 lg:col-span-6 sm:mb-2 md:mb-12 py-12 px-3">
          <div class="bg-white sm:max-w-md sm:w-full sm:mx-auto rounded-lg sm:overflow-hidden ">
            <div class="px-4 py-8 sm:px-10">
              <div>
                <form action="<?= Yii::$app->request->baseUrl.'/'.md5('login')?>" name="login-form" id="login-form" method="post">
                  <?= Yii::$app->nonSqlClass->getHiddenFormTokenField(); ?>
                    <?php
                    $token2 = Yii::$app->getSecurity()->generateRandomString();
                    $token2 = str_replace('+','.',base64_encode($token2));
                  ?>
                    <!-- DEBUT : BASIC HIDDEN IMPUT FOR THE FORM --> 
                  <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                  <input type="hidden" name="token2" value="<?= $token2 ?>"/>
                <?php
                  //Charger le formulaire
                  require_once('login_components/login_form.php');
                ?>
              </form>
              </div>
            </div>
          </div>
        </div>
        <!-- ./. Formulaire de connexion -->
      </div>
    </div>
  </main>
</div>
<!-- ./. Banniere Principale -->
<?php
  /** PME - TPE - ONG **/
  require_once('login_components/tpe_pme_ui.php');
  /** PME - TPE - ONG **/
  require_once('login_components/stock_management_ui.php');
  /** Trésorerie globale **/
  require_once('login_components/tresorerie_gb_ui.php');
  /** Last chance totake action **/
  require_once('login_components/last_ch_tk_act_ui.php');
?>

<?php 
  require_once('script/login_sc.php');
?>