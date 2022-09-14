<script type="text/javascript">

	function nbondcommand()
	{	
		$("#action_key").val("<?= md5('nbondcommand')?>");
		$("#vente_simple_frm").submit();
	}

	function retreviewBondcommand()
	{
		$("#action_key").val("<?= md5('retreviewBondcommand')?>");
		$("#vente_simple_frm").submit();
	}

</script>