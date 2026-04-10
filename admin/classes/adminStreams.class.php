<?php

class adminStreaming{

    //METODE SADRŽANE U OVOJ KLASI
    //adminStreamovi (dodavanje Strimova)

    public $nazivAlbuma;
    public $izvodjacMaster;
    public $godinaIzdanja;

    //--------------------------------------------------------------------------------------------------------------------------------

    //********************************* Metoda za dodavanje linkova za streamove unijetom albumu  *********************************//
    public function adminStreamovi($idAlb)
    {
        global $conn;

        include_once "../classes/insertData-classes/insertStreams.class.php";
        $newStream= new insertStreaming();

        $q= "SELECT 
        albumi.idAlbum,
        albumi.nazivAlbuma,
        albumi.godinaIzdanja,
        albumi.slikaAlbuma,
        izvodjaci.izvodjacMaster,

        streamovi.youtubeVideo,
        streamovi.spotify,
        streamovi.deezer,
        streamovi.appleMusic,
        streamovi.tidal,
        streamovi.youtubeMusic,
        streamovi.amazonMusic,
        streamovi.soundCloud,
        streamovi.amazonShop,
        streamovi.bandCamp,
        streamovi.qobuz
        FROM albumi
        JOIN izvodjaci ON izvodjaci.idIzvodjaci = albumi.idIzvodjacAlbumi
        LEFT JOIN streamovi ON streamovi.albumId = albumi.idAlbum
        WHERE albumi.idAlbum = '" . (int)$idAlb . "'";

        $select_stream= mysqli_query($conn, $q);
        $row= mysqli_fetch_array($select_stream);

        $this->izvodjacMaster= $row["izvodjacMaster"];
        $this->nazivAlbuma= $row["nazivAlbuma"];
        $this->godinaIzdanja= $row["godinaIzdanja"];
        $nazivAlbuma= $row["nazivAlbuma"];
        $slikaAlbuma= $row["slikaAlbuma"];
        $youtubeVideoLinkBaza= $row["youtubeVideo"];
        $spotifyLinkBaza= $row["spotify"];
        $deezerLinkBaza= $row["deezer"];
        $appleMusicLinkBaza= $row["appleMusic"];
        $tidalLinkBaza= $row["tidal"];
        $youtubeMusicLinkBaza= $row["youtubeMusic"];
        $amazonMusicLinkBaza= $row["amazonMusic"];
        $soundCloudLinkBaza= $row["soundCloud"];
        $amazonShopLinkBaza= $row["amazonShop"];
        $bandCampLinkBaza= $row["bandCamp"];
        $qobuzLinkBaza= $row["qobuz"];

        if(isset($_POST["posalji"]))
        {
            // OLD stanje (iz baze)
            $old = [
                'youtubeVideo' => $youtubeVideoLinkBaza,
                'spotify'      => $spotifyLinkBaza,
                'deezer'       => $deezerLinkBaza,
                'appleMusic'   => $appleMusicLinkBaza,
                'tidal'        => $tidalLinkBaza,
                'youtubeMusic' => $youtubeMusicLinkBaza,
                'amazonMusic'  => $amazonMusicLinkBaza,
                'soundCloud'   => $soundCloudLinkBaza,
                'amazonShop'   => $amazonShopLinkBaza,
                'bandCamp'     => $bandCampLinkBaza,
                'qobuz'        => $qobuzLinkBaza
            ];

            // NEW stanje (iz FORME) — sada dozvoljavamo i brisanje
            // pravilo: prazno polje => NULL
            $youtubeVideoLink = trim($_POST["youtubeVideoLink"] ?? '');
            $spotifyLink      = trim($_POST["spotifyLink"] ?? '');
            $deezerLink       = trim($_POST["deezerLink"] ?? '');
            $appleMusicLink   = trim($_POST["appleMusicLink"] ?? '');
            $tidalLink        = trim($_POST["tidalLink"] ?? '');
            $youtubeMusicLink = trim($_POST["youtubeMusicLink"] ?? '');
            $amazonMusicLink  = trim($_POST["amazonMusicLink"] ?? '');
            $soundCloudLink   = trim($_POST["soundCloudLink"] ?? '');
            $amazonShopLink   = trim($_POST["amazonShopLink"] ?? '');
            $bandCampLink   = trim($_POST["bandCampLink"] ?? '');
            $qobuzLink   = trim($_POST["qobuzLink"] ?? '');

            // Ako nije prazno -> očisti, ako jeste -> NULL (brisanje)
            $new = [
                'youtubeVideo' => ($youtubeVideoLink === '') ? null : $newStream->cleanStreamsYoutubeVideo($youtubeVideoLink),
                'spotify'      => ($spotifyLink === '')      ? null : $newStream->cleanStreamsSpotify($spotifyLink),
                'deezer'       => ($deezerLink === '')       ? null : $newStream->cleanStreamsDeezer($deezerLink),
                'appleMusic'   => ($appleMusicLink === '')   ? null : $newStream->cleanStreamsAppleMusic($appleMusicLink),
                'tidal'        => ($tidalLink === '')        ? null : $newStream->cleanStreamsTidal($tidalLink),
                'youtubeMusic' => ($youtubeMusicLink === '') ? null : $newStream->cleanStreamsYoutubeMusic($youtubeMusicLink),
                'amazonMusic'  => ($amazonMusicLink === '')  ? null : $newStream->cleanStreamsAmazonMusic($amazonMusicLink),
                'soundCloud'   => ($soundCloudLink === '')   ? null : $newStream->cleanStreamsSoundCloud($soundCloudLink),
                'amazonShop'   => ($amazonShopLink === '')   ? null : $newStream->cleanStreamsAmazonShop($amazonShopLink),
                'bandCamp'     => ($bandCampLink === '')   ? null : $newStream->cleanStreamsbandCamp($bandCampLink),
                'qobuz'        => ($qobuzLink === '')   ? null : $newStream->cleanStreamsqobuz($qobuzLink),
            ];

            // DIFF (old/new)
            $changes = [];
            foreach ($new as $k => $v) {
                $oldVal = $old[$k] ?? null;
                if ($oldVal === '') $oldVal = null;

                if ($oldVal != $v) {
                    $changes[$k] = [
                        'old' => $oldVal,
                        'new' => $v
                    ];
                }
            }
            

            // Ako nema promjena - ništa
            if (empty($changes)) {
                echo "<h4 class='boja sredina'>Nema promjena na strimovima.</h4>";
            } else {

                // UPDATE (NULL mora bez navodnika)
                $q_stream = "UPDATE streamovi SET
                    youtubeVideo = " . sqlNullable($conn, $new['youtubeVideo']) . ",
                    spotify      = " . sqlNullable($conn, $new['spotify']) . ",
                    deezer       = " . sqlNullable($conn, $new['deezer']) . ",
                    appleMusic   = " . sqlNullable($conn, $new['appleMusic']) . ",
                    tidal        = " . sqlNullable($conn, $new['tidal']) . ",
                    youtubeMusic = " . sqlNullable($conn, $new['youtubeMusic']) . ",
                    amazonMusic  = " . sqlNullable($conn, $new['amazonMusic']) . ",
                    soundCloud   = " . sqlNullable($conn, $new['soundCloud']) . ",
                    amazonShop   = " . sqlNullable($conn, $new['amazonShop']) . ",
                    bandCamp     = " . sqlNullable($conn, $new['bandCamp']) . ",
                    qobuz        = " . sqlNullable($conn, $new['qobuz']) . "
                    WHERE streamovi.albumId='{$idAlb}'";
                $insert_stream = mysqli_query($conn, $q_stream);

                if($insert_stream == TRUE)
                {
                    // LOG (old/new) — sad će se vidjeti i brisanje (new:null)
                    logStreamUpdated($idAlb, $nazivAlbuma, $changes);

                    echo "<meta http-equiv='refresh' content='1'; url='dodajalbume.php'>";
                } else {
                    echo "Greška " . mysqli_error($conn) . "<br>";
                }
            }

        }//end if(isset($_POST["posalji"]))
    

        ?>
        <!--<h2 class="sredina mt-3">Biće vam vidljiva polja za unos koja već nisu popunjena</h2>-->
        <div class="pregledAlbumaUredi">
            <div class="col-md-2">
                <div class="slikeAlbumaPregled sredina">
                    <div class="editAlbum">
                        <h6 class="boja"><strong><?php echo $this->nazivAlbuma; ?></strong></h6><br>
                        <img src="../images/albumi/<?php echo $slikaAlbuma; ?>" alt="<?php echo $this->nazivAlbuma; ?>" title="<?php echo $this->nazivAlbuma; ?>" class=""/>
                    </div><!-- end .editAlbum -->
                </div><!-- end .slikeAlbumPregled -->
            </div><!-- end .col-md-2 -->

            <div class="col-md-7 sredina">
                <form method="POST" action="" enctype="multipart/form-data" name="izmjenaPjesme" id="izmjenaPjesme">
                    <h3 class="sredina mt-3">Linkovi koji nedostaju</h3>
                    
                    <label for="nazivStreamingServisa" class="text-light">Youtube Plejlsita</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://www.youtube.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="youtubeVideoLink" class="form-control form-control-sm text-danger" value="<?php echo $youtubeVideoLinkBaza; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="nazivStreamingServisa" class="text-light">Spotify</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://open.spotify.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="spotifyLink" class="form-control form-control-sm text-danger" value="<?php echo $spotifyLinkBaza; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="nazivStreamingServisa" class="text-light">Deezer</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://www.deezer.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="deezerLink" class="form-control form-control-sm text-danger" value="<?php echo $deezerLinkBaza; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="nazivStreamingServisa" class="text-light">Apple Music</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://music.apple.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="appleMusicLink" class="form-control form-control-sm text-danger" value="<?php echo $appleMusicLinkBaza; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="nazivStreamingServisa" class="text-light">Tidal</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://tidal.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="tidalLink" class="form-control form-control-sm text-danger" value="<?php echo $tidalLinkBaza; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="nazivStreamingServisa" class="text-light">Youtube Music</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://music.youtube.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="youtubeMusicLink" class="form-control form-control-sm text-danger" value="<?php echo $youtubeMusicLinkBaza; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="nazivStreamingServisa" class="text-light">Amazon Music</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://music.amazon.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="amazonMusicLink" class="form-control form-control-sm text-danger" value="<?php echo $amazonMusicLinkBaza; ?>">
                    </div><!-- end .input-group --><br><br>

                    <p>Mjesta gdje se može kupiti mp3 fajl ili CD (ili drugi format)</p>
                    
                    <label for="nazivStreamingServisa" class="text-light">SoundCloud Shop</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://soundcloud.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="soundCloudLink" class="form-control form-control-sm text-danger" value="<?php echo $soundCloudLinkBaza; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="nazivStreamingServisa" class="text-light">Amazon Shop</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://www.amazon.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="amazonShopLink" class="form-control form-control-sm text-danger" value="<?php echo $amazonShopLinkBaza; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="nazivStreamingServisa" class="text-light">BandCamp Shop</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://bandcamp.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="bandCampLink" class="form-control form-control-sm text-danger" value="<?php echo $bandCampLinkBaza; ?>">
                    </div><!-- end .input-group --><br><br>

                    <label for="nazivStreamingServisa" class="text-light">Qobuz Shop</label><br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">https://www.qobuz.com/</span>
                        </div><!-- end .input-group-prepend -->
                        <input type="text" class="form-control" name="qobuzLink" class="form-control form-control-sm text-danger" value="<?php echo $qobuzLinkBaza; ?>">
                    </div><!-- end .input-group --><br><br>

                        <br><br>
                        <hr class="hrLinija">
                        <input class="btn btn-primary mb-0" type="submit" name="posalji" value="Pošalji">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="btn btn-danger pt-1 mt-0" type="reset" value="Reset">
                </form>
            </div><!-- end .col-md-7 .sredina --><br>
        </div><!-- end pregledAlbumaUredi -->
        <?php 
    }//end adminStreamovi()

    //********************************* Pozvana metoda u fajlu editstreams.php, i showalbumstreams.php  *********************************//

    //--------------------------------------------------------------------------------------------------------------------------------

    

}//end class 