<?php

class streaming{

    //METODE SADRŽANE U OVOJ KLASI
    //streamoviPrikaz (prikaz streamova albuma)
    //streamoviSingloviPrikaz (prikaz streamova singlova)

    //--------------------------------------------------------------------------------------------------------------------------------
    //*********************************Metoda za prikaz streamova ispod svakog albuma *********************************/
    public function streamoviPrikaz($idAlbum)
    {
        global $conn;

        $q= "SELECT * FROM streamovi WHERE streamovi.albumId=$idAlbum";
        $select_streams= mysqli_query($conn, $q);

        while($row= mysqli_fetch_array($select_streams))
        {
            $youtubeVideoL= $row['youtubeVideo'];
            $spotifyL= $row['spotify']; 
            $deezerL= $row['deezer']; 
            $appleMusicL= $row['appleMusic'];
            $tidalL= $row['tidal'];
            $youtubeMusicL= $row['youtubeMusic'];
            $amazonMusicL= $row['amazonMusic']; 
            $soundcloudL= $row['soundCloud'];
            $amazonShopL= $row['amazonShop'];
            $bandCampL= $row['bandCamp'];
            $qobuzL= $row['qobuz'];
        
            ?>
            <p class="sredina streamsOpis">Mesta na kojima možete da slušate:</p><br>
            <div class="streams">
                
                <?php
                if(!empty($youtubeVideoL)){
                    ?>
                    <a href="<?php echo $youtubeVideoL; ?>" target="_blank"><img src="images/streams/Youtube-icon.png" title="YouTube" alt="YouTube"></a>
                    <?php
                }//end if(!empty($youtubeVideoL)))

                if(!empty($spotifyL)){
                    ?>
                    <a href="<?php echo $spotifyL; ?>" target="_blank"><img src="images/streams/spotify-icon.png" title="Spotify" alt="Spotify"></a>
                    <?php
                }//end if(!empty($spotifyL))

                if(!empty($deezerL)){
                    ?>
                    <a href="<?php echo $deezerL; ?>" target="_blank"><img src="images/streams/deezer-logo.png" title="Deezer" alt="Deezer"></a>
                    <?php
                }//end if(!empty($deezerL))

                if(!empty($appleMusicL)){
                    ?>
                    <a href="<?php echo $appleMusicL; ?>" target="_blank"><img src="images/streams/AppleMusic-icon.png" title="Apple Music" alt="Apple Music"></a>
                    <?php
                }//end if(!empty($appleMusicL))
                
                if(!empty($tidalL)){
                    ?>
                    <a href="<?php echo $tidalL; ?>" target="_blank"><img src="images/streams/tidal-icon-png-7.jpg" title="Tidal" alt="Tidal"></a>
                    <?php
                }//end if(!empty($tidalL))

                if(!empty($youtubeMusicL)){
                    ?>
                    <a href="<?php echo $youtubeMusicL; ?>" target="_blank"><img src="images/streams/YoutubeMusic-icon.png" title="YouTube Music" alt="YouTube Music"></a>
                    <?php
                }//end if(!empty($youtubeMusicL))

                if(!empty($amazonMusicL)){
                    ?>
                    <a href="<?php echo $amazonMusicL; ?>" target="_blank"><img src="images/streams/amazon-music-logo2.png" title="Amazon Music" alt="Amazon Music"></a>
                    <?php
                }//end if(!empty($amazonMusicL))

                if(!empty($soundCloudL)){
                    ?>
                    <a href="<?php echo $soundCloudL; ?>" target="_blank"><img src="images/streams/soundcloud-logo.png" title="SoundCloud" alt="SoundCloud"></a>
                    <?php
                }//end if(!empty($soundcloudL))
                
                if(!empty($amazonShopL)){
                    ?>
                    <a href="<?php echo $amazonShopL; ?>" target="_blank"><img src="images/streams/amazon-logo-icon.webp" title="Amazon Shop" alt="SoundCloud"></a>
                    <?php
                }//end if(!empty($amazonShopL))

                if(!empty($bandCampL)){
                    ?>
                    <a href="<?php echo $bandCampL; ?>" target="_blank"><img src="images/streams/Bandcamp-icon.png" title="BandCamp" alt="BandCamp"></a>
                    <?php
                }//end if(!empty($bandCampL))
                
                if(!empty($qobuzL)){
                    ?>
                    <a href="<?php echo $qobuzL; ?>" target="_blank"><img src="images/streams/Qobuz-icon.png" title="Qobuz" alt="SoundCloud"></a>
                    <?php
                }//end if(!empty($qobuzL))
                ?>
            </div><!-- end .streams -->
            <?php
        }//end while
    }//end streamoviPrikaz()

    //********************************* Metoda pozvana u fajlu oalbumu.php *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //--------------------------------------------------------------------------------------------------------------------------------
    //*********************************Metoda za prikaz streamova Singlova ispod svakog singla *********************************/
    public function streamoviSingloviPrikaz($idSinglovi)
    {
        global $conn;

        $q= "SELECT * FROM singlovi WHERE idSinglovi=$idSinglovi";
        $select_streams= mysqli_query($conn, $q);

        while($row= mysqli_fetch_array($select_streams))
        {
            $youtubeVideoL= $row['youtubeVideo'];
            $spotifyL= $row['spotify']; 
            $deezerL= $row['deezer']; 
            $appleMusicL= $row['appleMusic'];
            $tidalL= $row['tidal'];
            $youtubeMusicL= $row['youtubeMusic'];
            $amazonMusicL= $row['amazonMusic']; 
            $soundcloudL= $row['soundCloud'];
            $amazonShopL= $row['amazonShop'];
            $bandCampL= $row['bandCamp'];
            $qobuzL= $row['qobuz'];
        
            ?>
            <div class="streams">
                <?php
                if(!empty($youtubeVideoL)){
                    ?>
                    <a href="<?php echo $youtubeVideoL; ?>" target="_blank"><img src="images/streams/Youtube-icon.png" title="YouTube" alt="YouTube"></a>
                    <?php
                }//end if(!empty($youtubeVideoL)))

                if(!empty($spotifyL)){
                    ?>
                    <a href="<?php echo $spotifyL; ?>" target="_blank"><img src="images/streams/spotify-icon.png" title="Spotify" alt="Spotify"></a>
                    <?php
                }//end if(!empty($spotifyL))

                if(!empty($deezerL)){
                    ?>
                    <a href="<?php echo $deezerL; ?>" target="_blank"><img src="images/streams/deezer-logo.png" title="Deezer" alt="Deezer"></a>
                    <?php
                }//end if(!empty($deezerL))

                if(!empty($appleMusicL)){
                    ?>
                    <a href="<?php echo $appleMusicL; ?>" target="_blank"><img src="images/streams/AppleMusic-icon.png" title="Apple Music" alt="Apple Music"></a>
                    <?php
                }//end if(!empty($appleMusicL))
                
                if(!empty($tidalL)){
                    ?>
                    <a href="<?php echo $tidalL; ?>" target="_blank"><img src="images/streams/tidal-icon-png-7.jpg" title="Tidal" alt="Tidal"></a>
                    <?php
                }//end if(!empty($tidalL))

                if(!empty($youtubeMusicL)){
                    ?>
                    <a href="<?php echo $youtubeMusicL; ?>" target="_blank"><img src="images/streams/YoutubeMusic-icon.png" title="YouTube Music" alt="YouTube Music"></a>
                    <?php
                }//end if(!empty($youtubeMusicL))

                if(!empty($amazonMusicL)){
                    ?>
                    <a href="<?php echo $amazonMusicL; ?>" target="_blank"><img src="images/streams/amazon-music-logo2.png" title="Amazon Music" alt="Amazon Music"></a>
                    <?php
                }//end if(!empty($amazonMusicL))

                if(!empty($soundCloudL)){
                    ?>
                    <a href="<?php echo $soundCloudL; ?>" target="_blank"><img src="images/streams/soundcloud-logo.png" title="SoundCloud" alt="SoundCloud"></a>
                    <?php
                }//end if(!empty($soundcloudL))
                
                if(!empty($amazonShopL)){
                    ?>
                    <a href="<?php echo $amazonShopL; ?>" target="_blank"><img src="images/streams/amazon-logo-icon.webp" title="Amazon Shop" alt="SoundCloud"></a>
                    <?php
                }//end if(!empty($amazonShopL))

                if(!empty($bandCampL)){
                    ?>
                    <a href="<?php echo $bandCampL; ?>" target="_blank"><img src="images/streams/Bandcamp-icon.png" title="BandCamp" alt="BandCamp"></a>
                    <?php
                }//end if(!empty($bandCampL))
                
                if(!empty($qobuzL)){
                    ?>
                    <a href="<?php echo $qobuzL; ?>" target="_blank"><img src="images/streams/Qobuz-icon.png" title="Qobuz" alt="SoundCloud"></a>
                    <?php
                }//end if(!empty($qobuzL))
                ?>
            </div><!-- end .streams -->
            <?php
        }//end while
    }//end streamoviPrikaz()

    //********************************* Metoda pozvana u fajlu singlovi.php *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------


    //--------------------------------------------------------------------------------------------------------------------------------

}//end class streaming