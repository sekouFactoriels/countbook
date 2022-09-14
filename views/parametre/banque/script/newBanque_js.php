<script type="text/javascript">
  function newBanque(){
    var myurl = "<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5('parametre_comptebancaire'))?>";
    $.ajax({
      type: 'GET',
      cache: false,
      url: myurl,
      data: {
        _crsf: '<?= Yii::$app->request->getCsrfToken()?>',
        action_key: '<?= md5("enrg_banque")?>',
        numero: $('#numero').val(),
        libelle: $('#libelle').val(),
        adresse: $('#adresse').val(),
      },
      success: function(rslt){
        $('#loadingModal').modal('hide');
        $('#addBanque').modal('hide');
        $('#banque_form').submit();
      },
      error: function(data){
        alert(JSON.stringify(data));
      }
    });
  }
</script>