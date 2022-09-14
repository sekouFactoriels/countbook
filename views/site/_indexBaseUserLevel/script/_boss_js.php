<script type="text/javascript">
  //Detail des articles
  $('#detailArticle').ready(function() {

    /*
    new Chart(document.getElementById("bar-chart-grouped"), {
      type: 'bar',
      data: {
        labels: ["01/05/2022", "02/05/2022", "03/05/2022", "04/05/2022"],
        datasets: [{
            label: "Recettes",
            backgroundColor: "#26364b",
            data: [120000, 3250000, 7000000, 4500000]
          },
          {
            label: "Charges",
            backgroundColor: "#fc1919",
            data: [10000, 50000, 700000, 500000]
          }
        ]
      },
      options: {
        title: {
          display: true,
          text: ''
        }
      }
    });
    */

    var myurl = '<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("site_index")) ?>';
    $.ajax({
      type: 'GET',
      cache: false,
      url: myurl,
      data: {
        _csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
        ajax_action_key: '<?= md5("articleDtlsOnIndex") ?>'
      },
      success: function(data) {
        $('#loadingModal').modal('hide');
        $('#detailArticle').html(data);
      },
      error: function() {
        alert('erreur');
      },
    });
  });

  var mydata = '';


  //docuement.addEventListener('e', );
</script>