<script type="text/javascript">

	function removeLastCharacterTyped(inputId){
		var text = $('#' + inputId).val();
		var len = text.length;
		$('#' + inputId).val(text.substr(0,len - 1));
	}

	function formValidator(){
		var maxFileSize = <?= Yii::$app->params['maxFileSize'];?>;//GET THE MAXIMUN SIZE OF FILE PREVIOUSLY SET UN THE SYSTEM
		var validStep = true;//INITIATE A RESULT TO TRUE
	  var stage = arguments[0];//GET THE FIRST ELEMENT OF THE ARGUMENT
		var arg  = new Array();

		for(var i=1; i< arguments.length;i++){// LOOP BASE ON THE NUMBER OF ARGEUMENT
			if(i == stage){
				for(var j=0;j<arguments[i].length;j++){//LOOP BASE ON EACH ELEMENT OF THE CURRENT ELEMENT
					$("[id="+arguments[i][j]+"]").closest('.required-container').removeClass('has-error');
					var sval = $("[id="+arguments[i][j]+"]");
					var type = '';//THIS VARIABLE ILL CONTAINT THE TYPE OF THE INPUT
					if($("[id="+arguments[i][j]+"]").is("select")){
						type = 'select';
					}else if($("[id="+arguments[i][j]+"]").is("textarea")){
						type = 'textarea';
					}else{
						type = $("[id="+arguments[i][j]+"]").attr('type');
					}

					switch(type){

						case 'text':
						case 'password':
							if(sval.val()==""){
								$('[id='+arguments[i][j]+']').closest('.required-container').removeClass('has-success').addClass("has-error");
								validStep = false;
							}
						break;

						case 'checkbox':
							if(sval.val()!='1'){
								$('[id='+arguments[i][j]+']').closest('.required-container').removeClass('has-success').addClass('has-error');
								validStep = false;
							}
						break;

						case 'select':
							if(sval.val()<='0'){
								$('[id='+arguments[i][j]+']').closest('.required-container').removeClass('has-success').addClass('has-error');
								validStep = false;
							}
						break;

						case 'textarea':
							if(sval.val()==""){
								$('[id='+arguments[i][j]+']').closest('.required-container').removeClass('has-success').addClass('has-error');
								validStep = false;
							}
						break;

						case 'file':
							if(sval.val()==""){
								$('[id='+arguments[i][j]+']').closest('.required-container').removeClass('has-success').addClass('has-error');
								validStep = false;
							}else{
									var ext = sval.val().split('.')[1];//GET THE FILE EXTENSION
							    var weight = $('#'+arguments[i][j])[0].files[0].size;//GET THE FILE SIZE IN BYTE
							    weight = weight*0.001;//SIZE IN KB
							    ext = ext.toLowerCase();
							    switch (ext){
							    	case 'jpg':
							    	case 'jpeg':
							    	case 'bmp':
							    	case 'png':
							    		if(weight  > maxFileSize){
							    			$('[id='+arguments[i][j]+']').closest('.required-container').removeClass('has-success').addClass('has-error');
											validStep = false;
							    		}
							    	break;

							    	default:
							    		$('[id='+arguments[i][j]+']').closest('.required-container').removeClass('has-success').addClass('has-error');
										validStep = false;
							    	break;
							    }
							}
						break;
					}
				}
			}
		}
		return validStep;
	}
</script>
