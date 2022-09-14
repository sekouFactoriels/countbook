<script type="text/javascript">

	function newdevis()
	{	
		$("#action_key").val("<?= md5('new_devis')?>");
		$("#vente_simple_frm").submit();
	}

	function retreviewBondcommand()
	{
		$("#action_key").val("<?= md5('liste_devis')?>");
		$("#vente_simple_frm").submit();
	}

</script>