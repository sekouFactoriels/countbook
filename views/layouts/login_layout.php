<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\loginAsset;

/**
 * @author Factoriels Sarl
 * @since 1.0
**/

$asset = loginAsset::register($this);
$baseUrl = $asset->baseUrl;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-full bg-gray-50">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode("COUNTBOOK "." | ".Yii::t('app','loginLabel')) ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
    <?php $this->head() ?>
</head>

<body>
  <style>
    body {
        font-family: 'Montserrat';font-size: 22px;
    }
  </style>
  <?php $this->beginBody() ?>
  <?= $this->render('login_component/content.php', ['content' => $content]) ?>

  <?php $this->endBody() ?>
</body>

<?= $this->render('login_component/footer.php') ?>

</html>
<?php $this->endPage() ?>
