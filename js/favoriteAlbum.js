$(document).ready(function(){
    $(".favAlbum").on("change", function(){
        const albumId = $(this).val();
        const checked = $(this).is(":checked");
        const $msg = $("#favMsg");

        if(checked){
            $.ajax({
                url: "oalbumu.php",
                method: "POST",
                data: { odabranoId: 1, album: albumId },
                success: function(response){
                    console.log(response);
                    $msg.text("Sačuvano u favoritima").show().delay(2000).fadeOut();
                },
                error: function(){
                    $msg.text("Greška pri dodavanju!").show().delay(2000).fadeOut();
                }
            });
        } else {
            $.ajax({
                url: "oalbumu.php",
                method: "POST",
                data: { action: "unchecked", albumId: albumId },
                success: function(response){
                    console.log(response);
                    $msg.text("Uklonjeno iz favorita").show().delay(2000).fadeOut();
                },
                error: function(){
                    $msg.text("Greška pri uklanjanju!").show().delay(2000).fadeOut();
                }
            });
        }
    });
});
