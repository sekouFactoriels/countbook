<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\DashAsset;

$asset = DashAsset::register($this);
$baseUrl = $asset->baseUrl;
$this->title = Yii::$app->params['systemsname'];
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
  <!-- BEGIN HEAD -->
  <head>
    <meta charset="UTF-8">
    <meta name="description" content="<?= Yii::$app->params['systemsname']?>" />
    <meta name="keywords" content="<?= Yii::$app->params['systemsname']?>" />
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    
    <?php
      $this->head();
      require_once(\Yii::$app->basePath.'/extensions/client/validator.php');
    ?>
  </head>
  <!-- END HEAD -->

  <!-- BEGIN BODY -->
  <body>
    <?php $this->beginBody() ?>
    <div id="preloader">
        <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
    </div>

    <section>
      <?= $this->render('dash_component/header.php', ['baseUrl' => $baseUrl]) ?>

      <?= $this->render('dash_component/content.php', ['content' => $content]) ?>

      <!-- DEBUT CHARGER LE FICHIER D'ALERT DE MESSAGE -->
      <?php require_once(Yii::$app->basePath.'/extensions/alertpopup/alert_msg_popup.php') ?>
      <!-- FIN CHARGER LE FICHIER D'ALERT DE MESSAGE -->
    </section>
    <?php $this->endBody() ?>
    <script type="text/javascript">
      $('.datepicker').datepicker({
          format: 'dd/mm/yyyy',
          orientation : "botton right",
          todayHighlight: true
      });

      // Spinner
      var spinner = jQuery('#spinner').spinner();
          spinner.spinner('value', 0);

    </script>
  </body>
</html>
<?php $this->endPage() ?>
