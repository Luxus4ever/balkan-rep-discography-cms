<?php
function footerPutanja($putanja="")
{
    ?>
    </div><!-- end #wrapper -->
    <footer>
        <div class="footer1">
            <h4>Pratite nas na društvenim mrežama:</h4><br>
            <span class="nav-item social-icons">
                <span class="fa-stack">
                    <a href="https://www.facebook.com/balkanhiphopradio" target="_blank">
                        <i class="fa fa-circle fa-2x" aria-hidden="true"></i>
                        <i class="fab fa-facebook-f fa-stack-1x"></i>
                    </a>
                </span><!-- end .fa-stack -->
                <span class="fa-stack">
                    <a href="https://www.twitter.com/balkanhiphopr/" target="_blank">
                        <i class="fas fa-circle fa-stack-2x"></i>
                        <i class="fab fa-twitter fa-stack-1x"></i>
                    </a>
                </span><!-- end .fa-stack -->
                <span class="fa-stack">
                    <a href="https://www.instagram.com/balkanhiphopradio/" target="_blank">
                        <i class="fas fa-circle fa-stack-2x"></i>
                        <i class="fab fa-instagram fa-stack-1x"></i>
                    </a>
                </span><!-- end .fa-stack -->
                <span class="fa-stack">
                    <a href="https://www.tiktok.com//" target="_blank">
                        <i class="fas fa-circle fa-stack-2x"></i>
                        <i class="fab fa-tiktok fa-stack-1x"></i>
                    </a>
                </span><!-- end .fa-stack -->
            </span><!-- end .nav-item social-icons -->
        </div><!-- end .footer1 -->

        <div class="footer2">
            <p>&copy; <?php echo date("Y"); ?> </p>
        </div><!-- end .footer2 -->

        <div class="footer3">
            <div class="footerText3">
                    <p>Slušajte Balkan Hip-Hop Radio</p>
            </div><!-- end .footerText3 -->
            <div class="logoRadio">
                <a href="https://balkanhiphopradio.com/" target="_blank">
                    <img src="<?php echo $putanja; ?>images/bhhr-logo.jpg" alt="Balkan Hip-Hop Radio" title="Balkan Hip-Hop Radio">
                </a>
            </div><!-- end .logoRadio -->
        </div><!-- end .footer3 -->
        
    </footer>



    <script src="js/oznaciProcitanoObavestenja.js"></script>
    <script src="js/jquery.magnific-popup.js"></script> <!-- Magnific Popup for lightboxes -->

    <script src="js/lightbox.js"></script>
    <script src="js/swiper.min.js"></script> <!-- Swiper for image and text sliders -->
    <script src="js/jquery.easing.min.js"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
    <script src="js/popper.min.js"></script> <!-- Popper tooltip library for Bootstrap -->
    <script src="js/scripts.js"></script> <!-- Custom scripts -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    
    </body>

    </html>
    <?php
    
}//end footerPutanja()
