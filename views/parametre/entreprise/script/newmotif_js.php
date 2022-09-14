<script type="text/javascript">
  function enrgmotifenrgclient() {
    var myurl = "<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5('parametre_motifsenrgclient')) ?>";
    $.ajax({
      type: 'GET',
      cache: false,
      url: myurl,
      data: {
        _crsf: '<?= Yii::$app->request->getCsrfToken() ?>',
        action_key: '<?= md5("enrgmotifenrgclient") ?>',
        libelle: $('#libellemotif').val(),
      },
      success: function(rslt) {
        if (rslt == '2692') {
          $('#newmotifenrgclient').modal('hide');
        } else {

        }
      },
      error: function(data) {
        alert(JSON.stringify(data));
      }
    });
  }
</script>