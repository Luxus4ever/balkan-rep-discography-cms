<?php
//KLASA KOJA SLUŽI ZA DODAVANJE STRIMOVA U SINGLOVE
class insertStreaming{

    //METODE SADRŽANE U OVOJ KLASI
    //cleanStreamsYoutubeVideo (provjera linka)
    //cleanStreamsSpotify (provjera linka)
    //cleanStreamsDeezer (provjera linka)
    //cleanStreamsAppleMusic (provjera linka)
    //cleanStreamsTidal (provjera linka)
    //cleanStreamsYoutubeMusic (provjera linka)
    //cleanStreamsAmazonMusic (provjera linka)
    //cleanStreamsSoundcloudMusic (provjera linka)
    //cleanStreamsAmazonShop (provjera linka)
    //cleanStreamsBandCamp (provjera linka) 
    //cleanStreamsQobuz (provjera linka)

    private $youtubeVideoL;
    private $spotifyL;
    private $deezerL;
    private $appleMusicL;
    private $tidalL;
    private $youtubeMusicL;
    private $amazonMusicL;
    private $soundCloudL;
    private $amazonShopL;
    private $bandCampL;
    private $qobuzL;

    public $patternYoutubeVideo;
    public $patternSpotifyMusic;
    public $patternDeezerMusic;
    public $patternAppleMusic;
    public $patternTidalMusic;
    public $patternYoutubeMusic;
    public $patternAmazonMusic;
    public $patternSoundcloudMusic;
    public $patternAmazonShop;
    public $patternBandCampMusic;
    public $patternQobuzMusic;

    public $nazivAlbuma;
    public $izvodjacMaster;
    public $godinaIzdanja;

    public $lastInsertAlbumId;

    //--------------------------------------------------------------------------------------------------------------------------------
    

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda koja pročišćava unos za youtubeVideo Stream unos Linka *********************************/
    public function cleanStreamsYoutubeVideo($param) {
        $this->patternYoutubeVideo = '/^https:\/\/(www\.)?youtube\.com\/(watch\?v=|playlist\?list=|shorts\/|user\/|c\/|channel\/|@[^\/]+\/)?(.*)?$/';
        
        $param = trim((string)$param);

        if ($param === "") {
            return null;
        } else {
            if (preg_match($this->patternYoutubeVideo, $param)) {
                return $param;
            } else {
                echo "<h1 class='warning sredina'>Nepravilan link sajta za Youtube.</h1>";
                return null;
            }
        }
    }
    //********************************* Metoda pozvana u ovom fajlu u funkciji adminStreamovi() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda koja pročišćava unos za Spotify Stream unos Linka *********************************/
    public function cleanStreamsSpotify($param) {
        $this->patternSpotifyMusic = '/^https:\/\/open\.spotify\.com(\/.*)?$/';
        
        $param = trim((string)$param);

        if ($param === "") {
            return null;
        } else {
            if (preg_match($this->patternSpotifyMusic, $param)) {
                return $param;
            } else {
                echo "<h1 class='warning sredina'>Nepravilan link sajta za Spotify.</h1>";
                return null;
            }
        }
    }
    //********************************* Metoda pozvana u ovom fajlu u funkciji adminStreamovi() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda koja pročišćava unos za Deezer Stream unos Linka *********************************/
    public function cleanStreamsDeezer($param) {
        $this->patternDeezerMusic = '/^https:\/\/www\.deezer\.com(\/.*)?$/';
        
        $param = trim((string)$param);

        if ($param === "") {
            return null;
        } else {
            if (preg_match($this->patternDeezerMusic, $param)) {
                return $param;
            } else {
                echo "<h1 class='warning sredina'>Nepravilan link sajta za Deezer.</h1>";
                return null;
            }
        }
    }
    //********************************* Metoda pozvana u ovom fajlu u funkciji adminStreamovi() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda koja pročišćava unos za appleMusic Stream unos Linka *********************************/
    public function cleanStreamsAppleMusic($param) {
        $this->patternAppleMusic = '/^https:\/\/music\.apple\.com(\/.*)?$/';
        
        $param = trim((string)$param);

        if ($param === "") {
            return null;
        } else {
            if (preg_match($this->patternAppleMusic, $param)) {
                return $param;
            } else {
                echo "<h1 class='warning sredina'>Nepravilan link sajta za Apple Music.</h1>";
                return null;
            }
        }
    }
    //********************************* Metoda pozvana u ovom fajlu u funkciji adminStreamovi() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda koja pročišćava unos za Tidal Stream unos Linka *********************************/
    public function cleanStreamsTidal($param) {
        $this->patternTidalMusic = '/^https:\/\/tidal\.com(\/.*)?$/';
        
        $param = trim((string)$param);

        if ($param === "") {
            return null;
        } else {
            if (preg_match($this->patternTidalMusic, $param)) {
                return $param;
            } else {
                echo "<h1 class='warning sredina'>Nepravilan link sajta za Tidal.</h1>";
                return null;
            }
        }
    }
    //********************************* Metoda pozvana u ovom fajlu u funkciji adminStreamovi() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda koja pročišćava unos za youtubeMusic Stream unos Linka *********************************/
    public function cleanStreamsYoutubeMusic($param) {
        $this->patternYoutubeMusic = '/^https:\/\/music\.youtube\.com(\/.*)?$/';
        
        $param = trim((string)$param);

        if ($param === "") {
            return null;
        } else {
            if (preg_match($this->patternYoutubeMusic, $param)) {
                return $param;
            } else {
                echo "<h1 class='warning sredina'>Nepravilan link sajta za YoutubeMusic.</h1>";
                return null;
            }
        }
    }
    //********************************* Metoda pozvana u ovom fajlu u funkciji adminStreamovi() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda koja pročišćava unos za amazonMusic Stream unos Linka *********************************/
    public function cleanStreamsAmazonMusic($param) {
        $this->patternAmazonMusic = '/^https:\/\/music\.amazon\.com(\/.*)?$/';

        
        $param = trim((string)$param);

        if ($param === "") {
            return null;
        } else {
            if (preg_match($this->patternAmazonMusic, $param)) {
                return $param;
            } else {
                echo "<h1 class='warning sredina'>Nepravilan link sajta za Amazon Music.</h1>";
                return null;
            }
        }
    }
    //********************************* Metoda pozvana u ovom fajlu u funkciji adminStreamovi() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda koja pročišćava unos za soundCloud Stream unos Linka *********************************/
    public function cleanStreamsSoundCloud($param) {
        $this->patternSoundcloudMusic = '/^https:\/\/soundcloud\.com(\/.*)?$/';
        
        $param = trim((string)$param);

        if ($param === "") {
            return null;
        } else {
            if (preg_match($this->patternSoundcloudMusic, $param)) {
                return $param;
            } else {
                echo "<h1 class='warning sredina'>Nepravilan link sajta za SoundCloud.</h1>";
                return null;
            }
        }
    }
    //********************************* Metoda pozvana u ovom fajlu u funkciji adminStreamovi() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda koja pročišćava unos za amazonShop Stream unos Linka *********************************/
    public function cleanStreamsAmazonShop($param) {
        $this->patternAmazonShop = '/^https?:\/\/(www\.)?amazon\.com(\/.*)?$/';
        
        $param = trim((string)$param);

        if ($param === "") {
            return null;
        } else {
            if (preg_match($this->patternAmazonShop, $param)) {
                return $param;
            } else {
                echo "<h1 class='warning sredina'>Nepravilan link sajta za Amazon Shop.</h1>";
                return null;
            }
        }
    }//end cleanStreamsAmazonShop()
    //********************************* Metoda pozvana u ovom fajlu u funkciji adminStreamovi() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda koja pročišćava unos za BandCamp Stream unos Linka *********************************/
    public function cleanStreamsBandCamp($param) {
        $this->patternBandCampMusic = '/^https:\/\/[a-z0-9-]+\.bandcamp\.com\/(album|track)\/[a-z0-9-]+\/?$/i';
        
        $param = trim((string)$param);

        if ($param === "") {
            return null;
        } else {
            if (preg_match($this->patternBandCampMusic, $param)) {
                return $param;
            } else {
                echo "<h1 class='warning sredina'>Nepravilan link sajta za BandCamp.</h1>";
                return null;
            }
        }
    }//end cleanStreamsBandCamp(
    //********************************* Metoda pozvana u ovom fajlu u funkciji adminStreamovi() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------

    //*********************************Metoda koja pročišćava unos za Qobuz Stream unos Linka *********************************/
    public function cleanStreamsQobuz($param) {
        $this->patternQobuzMusic = '/^https:\/\/www\.qobuz\.com(\/.*)?$/';

        
        $param = trim((string)$param);

        if ($param === "") {
            return null;
        } else {
            if (preg_match($this->patternQobuzMusic, $param)) {
                return $param;
            } else {
                echo "<h1 class='warning sredina'>Nepravilan link sajta za Qobuz.</h1>";
                return null;
            }
        }
    }//end cleanStreamsQobuz()
    //********************************* Metoda pozvana u ovom fajlu u funkciji adminStreamovi() *********************************/

    //--------------------------------------------------------------------------------------------------------------------------------




}//end class 
