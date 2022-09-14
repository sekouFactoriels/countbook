<script type="text/javascript">
    /** Methode : Afficher les champs de la filiale **/
    function filiale_feild() {
        var val = $('#forfait').val();
        switch (val) {

            case '0':
                $('#div_filiale').hide();
                break;
            case 'essentiel':
                $('#div_filiale').hide();
                break;

            case 'standard':
                $('#div_filiale').hide();
                break;

            case 'premium':
                $('#div_filiale').show();
                break;

            case 'partenaire':
                $('#div_filiale').hide();
                break;

            case 'special':
                $('#div_filiale').show();
                break;

            default:
                $('#div_filiale').show();
            break;
        }
    }

    $(document).ready(() => {

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