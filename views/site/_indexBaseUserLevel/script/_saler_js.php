<script type="text/javascript">
$('#detailArticle').ready(function(){
  var myurl = '<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("site_index")) ?>';
  $.ajax({
      type: 'GET',
      cache: false,
      url: myurl,
      data: {
        _csrf : '<?=Yii::$app->request->getCsrfToken()?>',
        ajax_action_key : '<?= md5("articleDtlsOnIndex")?>'
            },
      success: function(data){
        $('#loadingModal').modal('hide');
        $('#detailArticle').html(data);
      },
      error: function(){
        alert('erreur');
      },
  });
});

// Top 5 des articles vendus
$('#top5articlevente').ready(function(){
    var myurl = '<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("site_index")) ?>';
    $.ajax({
        type: 'GET',
        cache: false,
        url: myurl,
        data: {
          _csrf : '<?=Yii::$app->request->getCsrfToken()?>',
          ajax_action_key : '<?= md5("top5articleventeOnIndex")?>'
        },
        success: function(data){
          $('#loadingModal').modal('hide');
          $('#top5articlevente').html(data);
        },
        error: function(data){
          //alert(JSON.stringify(data,null, 4));
          alert(data.responseText);
        },
    });
});
</script>
