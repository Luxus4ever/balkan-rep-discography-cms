<?php
require_once "../config/bootstrap.php";

if(!isset($_SESSION['idKorisnici'])){
  header("location: ../login.php");
}

/********************************** Ovo je moja profilna u chatu, gdje je ispod spisak ostalih učesnika na chatu *********************************************/
?>
<?php include_once "header.php"; 
@$statusKorisnika= (int) $_SESSION["statusKorisnika"];
?>
<body>
  <div class="chat-container">
  <div class="chat-wrapper">
    <section class="users">
      <header class="header-users">
        <div class="content">
          <?php 
          if($statusKorisnika!==0)
{
            $sql = mysqli_query($conn, "SELECT * FROM korisnici WHERE idKorisnici = {$_SESSION['idKorisnici']}");
            if(mysqli_num_rows($sql) > 0){
              $row = mysqli_fetch_assoc($sql);
            }
          ?>
          <img src="../images/profilne/<?php echo $row['profilnaSlika']; ?>" alt="Moja profilna" title="<?php echo $row['username']; ?>">
          <div class="details">
            <span><?php echo $row['username'] ?></span>
            
          </div><!-- end .details -->
        </div><!-- end .content -->
      </header>
      <div class="search">
        <span class="text">Izaberi korisnika za chat</span>
        <input type="text" placeholder="Unesi ime...">
        <button><i class="fas fa-search"></i></button>
      </div><!-- end .search -->
      <div class="users-list">
  
      </div><!-- end .users-list -->
                <?php 
}else{
  echo "<h4 class='sredina text-danger'>Blokirani ste od strane sajta i ne možete da pristupite porukama!</h4>";
  }?>
    </section>
  </div><!-- end .chat-wrapper -->
  </div><!-- end .chat-container -->

  <script src="javascript/users.js"></script>

</body>
<?php 
include_once "footer.php"; ?>
</html>
