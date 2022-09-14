<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\ReportAsset;

$asset = ReportAsset::register($this);
$baseUrl = $asset->baseUrl;
$this->title = 'COUNTBOOK';
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" >
  <!-- BEGIN HEAD -->
  <head>
    <meta charset="UTF-8">
    <meta name="description" content="COUNTBOOK" />
    <meta name="keywords" content="" />
    <meta name="author" content="FACTORIELS" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/png">
    <?php
      $this->head();
    ?>
  </head>
  <!-- END HEAD -->

  <!-- BEGIN BODY -->
  <body>
    <?php $this->beginBody() ?>
    <div id="preloader">
        <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
    </div>

    <section >
      <?= $this->render('report_component/content.php', ['content' => $content]) ?>
    </section>
    <?php $this->endBody() ?>
  </body>
</html>
<?php $this->endPage() ?>
