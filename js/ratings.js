$(document).ready(function() {
    // Dohvati trenutnu ocjenu na učitavanju stranice
    loadCurrentRating();

    // Event handler za promjenu ocjene (klik na radio dugme)
    $(".ocena").click(function () {
        var odabrano = $(".ocena:checked").val();
        var params = new URLSearchParams(window.location.search);
        var izvodjac = params.get("izv");
        var album = params.get("album");
        saveToDb(odabrano, album, izvodjac);
    });

     // Event handler za brisanje ocjene
    /*$("#obrisiOcjenuBtn").on("click", function() {
        var params = new URLSearchParams(window.location.search);
        var izvodjac = params.get("izv");
        var album = params.get("album");
        obrisiOcjenu(album, izvodjac);
    });*/

    $("#obrisiOcjenuForm").on("submit", function(e) {
        e.preventDefault(); // Spreči default submit forme
        // Pozovi funkciju za brisanje ocjene
        var params = new URLSearchParams(window.location.search);
        var izvodjac = params.get("izv");
        var album = params.get("album");
        obrisiOcjenu(album, izvodjac);
    });
});

//------------------------------------------------------------------------------

// Spremanje ocjene i update prikaza bez osvježavanja stranice
function saveToDb(odabrano, album, izvodjac) {
    $.ajax({
        url: "./ratings_api.php",
        method: "POST",
        dataType: "json",
        data: {
            action: "save_rating",
            odabrano: odabrano,
            album: album,
            izvodjac: izvodjac
        },
        success: function (data) {
            if(data.error) {
               console.error('Greška:', data.error);
               return;
            }
            $("#trenOcjena").text(data.average);
            $("#brojGlasova").text(data.votes);

            if(data.userRating) {
        $(".ocena").prop("checked", false);
        $('#rate-' + data.userRating).prop('checked', true);
        // Ovdje dinamički generiši tekst sa ocjenom korisnika
        $("#userRatingText").html("Trenutna vaša ocjena za ovaj album je: <span id='trenOcjenaTekst'>" + data.userRating + "</span>");
    } else {
        $("#userRatingText").html("Niste ocijenili ovaj album.");
    }

            if(data.userRating) {
                $(".ocena").prop("checked", false);
                $('#rate-' + data.userRating).prop('checked', true);
            }
        },
        error: function (xhr, status, error) {
            console.error('Došlo je do greške:', error);
        }
    });
}

//------------------------------------------------------------------------------

// Dohvati i prikaži trenutnu ocjenu
function loadCurrentRating() {
    var params = new URLSearchParams(window.location.search);
    var album = params.get("album");
    if (!album) return;

    $.ajax({
        url: "./ratings_api.php",
        method: "POST",
        dataType: "json",
        data: {
            action: "get_rating",
            album: album
        },
        success: function(data) {
            if(!data.error) {
                $("#trenOcjena").text(data.average);
                $("#brojGlasova").text(data.votes);
                if (data.userRating) {
                    $(".ocena").prop("checked", false);
                    $('#rate-' + data.userRating).prop('checked', true);
                }
            }
        }
    });
}

//------------------------------------------------------------------------------

function obrisiOcjenu(album, izvodjac) {
    $.ajax({
        url: "./ratings_api.php",
        method: "POST",
        dataType: "json",
        data: {
            action: "delete_rating",
            album: album,
            izvodjac: izvodjac
        },
        success: function (data) {
            if(data.error) {
                console.error('Greška:', data.error);
                return;
            }
            // Očisti označenu ocjenu (untick radio dugmadi)
            $(".ocena").prop("checked", false);
            // Ažuriraj prikaz prosjeka i broja glasova na 0 ili nove vrijednosti
            $("#trenOcjena").text(data.average);
            $("#brojGlasova").text(data.votes);
        },
        error: function (xhr, status, error) {
            console.error('Došlo je do greške:', error);
        }
    });
}