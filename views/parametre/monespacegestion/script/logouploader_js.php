<script type="text/javascript">
$(document).ready(() => 
{
	$(function() {
		$( "#crop_div" ).draggable({ containment: "parent" });
	});

	function crop()
	{
		var posi = document.getElementById('crop_div');
		document.getElementById("top").value=posi.offsetTop;
		document.getElementById("left").value=posi.offsetLeft;
		document.getElementById("right").value=posi.offsetWidth;
		document.getElementById("bottom").value=posi.offsetHeight;
		return true;
	}
	$("#photo").change(function () {
	const file = this.files[0];
	    if (file) {
	        let reader = new FileReader();
	        reader.onload = function (event) {
	            $("#imgPreview")
	              .attr("src", event.target.result);
	        };
	        reader.readAsDataURL(file);
	    }
	});
});
</script>