<script type="text/javascript">

// Function d'insertion d'une nouvelle entite 
	function saveData(){
		var requiredInput = ['nom', 'tel','adresse'];
	    var index = 1;
	    frmValidation = formValidator(index, requiredInput);
	    if(frmValidation){
	    	$('#action_key').val("<?= md5('save_entite') ?>");
			$('#nEntite_form').submit();
		}else{
			$('#empty_msg_modal').modal('show');
    		return false;
		}
	}
</script>