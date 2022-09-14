<script type="text/javascript">
	function calculate_birthdate(date, age, show_on_this) {
		var date = new Date();
    	var d = new Date(date),
			month = '' + (d.getMonth() + 1),
	        day = '' + d.getDate(),
	        year = d.getFullYear();
	        year = year - age;

	    if (month.length < 2) 
	        month = '0' + month;
	    if (day.length < 2) 
	        day = '0' + day;

	    var birth_date = [month, day, year ].join('/');
	    $('#'+show_on_this).val(birth_date);
	}

	//Function : Charger les prefectures en fonction de la region selectionné
  	function load_prefectures() 
  	{
	    //$('#loadingModal').modal('show');
	    var myUrl = "<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("client_spaceclient")) ?>";
	    $.ajax({
	      type: 'GET',
	      url: myUrl,
	      cache: false,
	      data: {
	        _crsf : "<?=Yii::$app->request->getCsrfToken()?>",
	        ajax_action_key: "<?= md5('load_prefectures')?>",
	        ajax_action_value: $('#regionresidence').val(),
	      },
	      success: function(rslt){
	        if(rslt){
	          $('#prefecture_div').html(rslt);
	          $('#loadingModal').modal('hide');
	        }
	      },
	      error: function(){}
	    });
  	}

  	// Function : charger les regions en fonction du pays selectionné
  	function load_regions()
  	{
	    //$('#loadingModal').modal('show');
	    var myUrl = "<?= Yii::$app->getUrlManager()->createAbsoluteUrl(md5("client_spaceclient")) ?>";
	    $.ajax({
	      type: 'GET',
	      url: myUrl,
	      cache: false,
	      data: {
	        _crsf : "<?=Yii::$app->request->getCsrfToken()?>",
	        ajax_action_key: "<?= md5('load_regions')?>",
	        ajax_action_value: $('#paysresidence').val(),
	      },
	      success: function(rslt){
	        if(rslt){
	          $('#region_div').html(rslt);
	          $('#loadingModal').modal('hide');
	        }
	      },
	      error: function(){}
	    });
  	}

</script>