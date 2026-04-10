const searchBar = document.querySelector(".search input"),
searchIcon = document.querySelector(".search button"),
usersList = document.querySelector(".users-list");


// Kada se klikne na ikonicu za pretragu
searchIcon.onclick = ()=>{
  searchBar.classList.toggle("show");
  searchIcon.classList.toggle("active");
  searchBar.focus();
   // Ako searchBar već ima klasu "active"
  if(searchBar.classList.contains("active")){
    searchBar.value = "";
    searchBar.classList.remove("active");
  }
}

// Kada korisnik kuca u searchBar
searchBar.onkeyup = ()=>{
  let searchTerm = searchBar.value;
  if(searchTerm != ""){
    searchBar.classList.add("active");
  }else{
    searchBar.classList.remove("active");
  }
  // Kreiramo AJAX zahtev
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/search.php", true);
   // Kada server vrati odgovor
  xhr.onload = ()=>{
    if(xhr.readyState === XMLHttpRequest.DONE){
        if(xhr.status === 200){
          // Uzimamo podatke iz PHP fajla (rezultate pretrage)
          let data = xhr.response;
          // Ubacujemo rezultate direktno u listu korisnika
          usersList.innerHTML = data;
        }
    }
  }
  // Definišemo tip podataka koje šaljemo serveru
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  // Šaljemo searchTerm kao POST podatak PHP fajlu search.php
  xhr.send("searchTerm=" + searchTerm);
}

// Osvežavanje liste korisnika svakih 0.5 sekundi
setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "activeUsers.php", true); // ✅ bez razmaka
  xhr.onload = ()=> {
    if(xhr.readyState === XMLHttpRequest.DONE){
        if(xhr.status === 200){
          let data = xhr.response;
          if(!searchBar.classList.contains("active")){
            usersList.innerHTML = data;
          }
        }
    }
  }
  xhr.send();
}, 500);

// Slanje pinga za update aktivnosti svakih 7 sekundi
setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "php/update-activity.php", true);
  xhr.onload = () => {
    if(xhr.readyState === XMLHttpRequest.DONE && xhr.status !== 200){
      console.error("Greška kod update-activity:", xhr.status, xhr.responseText);
    }
  }
  xhr.send();
}, 7000);


// ====================== Korisnik je offline ako ode sa stranice ======================
window.addEventListener("beforeunload", () => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "php/logout.php?silent=1", false); // sinhrono
  xhr.send();
});





