<script type="text/javascript">
	function submitFilter(){
	  $('#action_key').val("<?= md5('lister_fournisseurs')?>");
	  $('#countbook_form').submit();
	}
</script>