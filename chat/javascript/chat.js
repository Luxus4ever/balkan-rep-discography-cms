const form = document.querySelector(".typing-area"),
incoming_id = form.querySelector(".incoming_id").value,
inputField = form.querySelector(".input-field"),
sendBtn = form.querySelector("button[type=submit]"),
chatBox = document.querySelector(".chat-box"),
chatImageInput = document.getElementById("chat-image"); // fajl input za slike
const imagePreviewDiv = document.getElementById("image-preview");
const chatVideoInput = document.getElementById("chat-video");
const videoPreviewDiv = document.getElementById("video-preview");


// ❌ Sprečava podrazumevano ponašanje forme (reload stranice kad pritisneš Enter ili klikneš dugme).
// ✅ Umesto toga, forma će se obrađivati preko AJAX-a.
form.onsubmit = (e)=>{
    e.preventDefault();
}

// ✅ Čim se otvori chat, automatski stavlja kursor u input polje za unos poruke
inputField.focus();

// ✅ Na svako otpuštanje tastera (keyup) proverava da li u input polju ima teksta
inputField.onkeyup = ()=>{
    if(inputField.value != ""){
        // Ako ima teksta, dugme za slanje postaje "aktivno" (dodaje se klasa 'active')
        // To obično menja izgled dugmeta (npr. oboji ga i omogući klik).
        sendBtn.classList.add("active");
    }else{
        // Ako je polje prazno, dugme se vraća u "neaktivno" stanje (klasa se uklanja).
        sendBtn.classList.remove("active");
    }
}

// ====================== KADA KORISNIK KUCA -> ŠALJI AJAX ======================
inputField.addEventListener("input", () => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/typing.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  // Ako ima teksta → obavesti da kuca, inače resetuj
  let typing_to = inputField.value.trim() !== "" ? incoming_id : 0;
  xhr.send("typing_to=" + typing_to);
});

// ====================== PROVERAVA DA LI DRUGI KORISNIK KUCA ======================
setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/check-typing.php", true);
  xhr.onload = () => {
    if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200){
      let typingStatus = xhr.response;
      const typingIndicator = document.getElementById("typing-indicator");
      if(typingStatus === "typing"){
        typingIndicator.style.display = "block";
      } else {
        typingIndicator.style.display = "none";
      }
    }
  }
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("incoming_id=" + incoming_id);
}, 1000);


// ====================== SLANJE PORUKE (tekst + fajl) ======================
sendBtn.onclick = ()=> {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/insert-chat.php", true);
  xhr.onload = ()=> {
    if(xhr.readyState === XMLHttpRequest.DONE){
      if(xhr.status === 200){
        inputField.value = "";
        chatImageInput.value = "";         // resetuj fajl input
        imagePreviewDiv.innerHTML = "";    // obriši thumbnail
        chatVideoInput.value = "";       // resetuj fajl input
        videoPreviewDiv.innerHTML = "";  // obriši video preview
        scrollToBottom();

        // ✅ odmah resetuj typing status
        let resetTyping = new XMLHttpRequest();
        resetTyping.open("POST", "php/typing.php", true);
        resetTyping.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        resetTyping.send("typing_to=0");

        // ✅ dodatno: resetuj i lokalno indikator (da nestane odmah sa ekrana)
        const typingIndicator = document.getElementById("typing-indicator");
        if(typingIndicator){
          typingIndicator.style.display = "none";
        }
      }
    }
  }
  let formData = new FormData(form);
  xhr.send(formData);
}


// ====================== PREVIEW IZABRANOG VIDEA ======================
chatVideoInput.addEventListener("change", () => {
  videoPreviewDiv.innerHTML = ""; // očisti stari preview
  if(chatVideoInput.files && chatVideoInput.files[0]){
    const file = chatVideoInput.files[0];
    if(file.type.startsWith("video/")){
      if(file.size > 20 * 1024 * 1024){ // 20 MB limit
        alert("Video fajl je prevelik! Maksimalno 20 MB.");
        chatVideoInput.value = "";
        return;
      }
      const url = URL.createObjectURL(file);
      videoPreviewDiv.innerHTML = `
        <video src="${url}" class="chat-video-thumb" controls></video>
      `;
    }
  }
});


// ====================== PREVIEW IZABRANE SLIKE ======================
chatImageInput.addEventListener("change", () => {
  imagePreviewDiv.innerHTML = ""; // očisti stari preview
  if(chatImageInput.files && chatImageInput.files[0]){
    const file = chatImageInput.files[0];
    if(file.type.startsWith("image/")){
      const reader = new FileReader();
      reader.onload = (e) => {
        imagePreviewDiv.innerHTML = `
          <img src="${e.target.result}" class="chat-image-thumb" alt="preview">
        `;
      };
      reader.readAsDataURL(file);
    }
  }
});


// ====================== SCROLL HANDLING ======================
chatBox.onmouseenter = ()=>{
    chatBox.classList.add("active");
}
chatBox.onmouseleave = ()=>{
    chatBox.classList.remove("active");
}

// ====================== OSVEŽAVANJE CHAT-a ======================
setInterval(() =>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "get-chat.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
            let data = xhr.response;
            chatBox.innerHTML = data;
            if(!chatBox.classList.contains("active")){
                scrollToBottom();
              }
          }
      }
    }
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("incoming_id="+incoming_id);
}, 500);

function scrollToBottom(){
    chatBox.scrollTop = chatBox.scrollHeight;
}

// ====================== EMOJI PICKER ======================
document.addEventListener("DOMContentLoaded", () => {
  const emojiBtn    = document.querySelector(".emoji-btn");
  const emojiPicker = document.querySelector(".emoji-picker");

  if(!emojiBtn || !emojiPicker || !inputField) return;

  // Otvori/zatvori picker
  emojiBtn.addEventListener("click", (e) => {
    e.preventDefault();
    emojiPicker.classList.toggle("open");
  });

  // Klik na emoji -> ubaci u input i zatvori
  emojiPicker.addEventListener("click", (e) => {
    const btn = e.target.closest(".emoji");
    if(!btn) return;
    inputField.value += btn.textContent;
    emojiPicker.classList.remove("open");
    inputField.focus();
  });

  // Klik van pickera -> zatvori
  document.addEventListener("click", (e) => {
    if(!emojiPicker.contains(e.target) && !emojiBtn.contains(e.target)){
      emojiPicker.classList.remove("open");
    }
  });
});

// ====================== LIGHTBOX ZA SLIKE ======================
document.addEventListener("click", (e) => {
  const target = e.target;
  if(target.classList.contains("chat-image")){ 
    // kada klikneš na sliku u chatu
    const lightbox = document.getElementById("lightbox");
    const lightboxImg = document.getElementById("lightbox-img");
    lightbox.style.display = "block";
    lightboxImg.src = target.src;
  }
});

document.querySelector("#lightbox .close").addEventListener("click", () => {
  document.getElementById("lightbox").style.display = "none";
});

// zatvori klikom bilo gde van slike
document.getElementById("lightbox").addEventListener("click", (e) => {
  if(e.target.id === "lightbox"){
    document.getElementById("lightbox").style.display = "none";
  }
});

// ====================== LIGHTBOX GALERIJA ZA SLIKE ======================
let currentImageIndex = 0;
let galleryImages = [];

function openLightbox(index){
  const lightbox = document.getElementById("lightbox");
  const lightboxImg = document.getElementById("lightbox-img");
  lightbox.style.display = "block";
  lightboxImg.src = galleryImages[index].src;
  currentImageIndex = index;
}

document.addEventListener("click", (e) => {
  if(e.target.classList.contains("chat-image")){ 
    galleryImages = Array.from(document.querySelectorAll(".chat-image"));
    const index = galleryImages.indexOf(e.target);
    openLightbox(index);
  }
});

document.querySelector("#lightbox .close").addEventListener("click", () => {
  document.getElementById("lightbox").style.display = "none";
});

document.querySelector("#lightbox .prev").addEventListener("click", () => {
  currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
  openLightbox(currentImageIndex);
});

document.querySelector("#lightbox .next").addEventListener("click", () => {
  currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
  openLightbox(currentImageIndex);
});

document.getElementById("lightbox").addEventListener("click", (e) => {
  if(e.target.id === "lightbox"){
    document.getElementById("lightbox").style.display = "none";
  }
});

// ====================== LIGHTBOX ZA VIDEO ======================
document.addEventListener("click", (e) => {
  const link = e.target.closest(".chat-video-link");
  if(link){
    e.preventDefault();
    const videoSrc = link.getAttribute("data-video");
    const videobox = document.getElementById("videobox");
    const videoboxVideo = document.getElementById("videobox-video");
    videobox.style.display = "block";
    videoboxVideo.src = videoSrc;
  }
});

document.querySelector("#videobox .close").addEventListener("click", () => {
  const videobox = document.getElementById("videobox");
  const videoboxVideo = document.getElementById("videobox-video");
  videoboxVideo.pause();
  videoboxVideo.src = ""; // reset
  videobox.style.display = "none";
});

document.getElementById("videobox").addEventListener("click", (e) => {
  if(e.target.id === "videobox"){
    const videoboxVideo = document.getElementById("videobox-video");
    videoboxVideo.pause();
    videoboxVideo.src = "";
    document.getElementById("videobox").style.display = "none";
  }
});


// ====================== BLOCK-UNBLOCK USER ======================
document.addEventListener("DOMContentLoaded", () => {
  const blockBtn = document.getElementById("block-unblock-user");
  if (!blockBtn) return;

  const otherId = blockBtn.dataset.userid;
  const icon = document.getElementById("block-icon");
  const typingWrapper = document.querySelector(".typing-wrapper");
  const typingArea = typingWrapper.querySelector(".typing-area");

  // inicijalna provera statusa
  checkBlockStatus();

  blockBtn.addEventListener("click", () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/block-user.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = () => {
      if (xhr.status === 200) {
        if (xhr.response === "blocked") {
          icon.style.color = "red";
          showBlockedMessage("Blokirali ste ovu osobu");
        } else if (xhr.response === "unblocked") {
          icon.style.color = "white";
          hideBlockedMessage();
        }
      }
    };
    xhr.send("blocked_id=" + otherId);
  });

  function checkBlockStatus() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/check-block.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = () => {
      if (xhr.status === 200) {
        if (xhr.response === "blocked") {
          icon.style.color = "red";
          showBlockedMessage("Blokirali ste ovu osobu");
        } else if (xhr.response === "blocked_by_other") {
          showBlockedMessage("Blokirani ste od ove osobe");
        } else {
          hideBlockedMessage();
        }
      }
    };
    xhr.send("other_id=" + otherId);
  }

  function showBlockedMessage(text) {
  if (typingArea) typingArea.style.display = "none";

  let msg = typingWrapper.querySelector(".block-message");
  if (!msg) {
    msg = document.createElement("p");
    msg.className = "block-message";
    msg.style.background = "white";
    msg.style.padding = "8px";
    msg.style.borderRadius = "6px";
    msg.style.textAlign = "center";
    msg.style.color = "red";
    typingWrapper.appendChild(msg);
  }
  msg.textContent = text;
}

function hideBlockedMessage() {
  if (typingArea) typingArea.style.display = "flex";
  const msg = typingWrapper.querySelector(".block-message");
  if (msg) msg.remove();
}

  // inicijalna provera statusa
  checkBlockStatus();

  // ✅ periodično proveravaj blok stanje (za drugu osobu)
  setInterval(checkBlockStatus, 2000); // svake 2 sekunde
});


// ====================== DA JE AUTOMATSKI ISKLJUČENO SLANJE FAJLOVA ======================
// ====================== ENABLE/DISABLE FILES ======================
document.addEventListener("DOMContentLoaded", () => {
  const filesBtn = document.getElementById("enable-disable-files");
  if (!filesBtn) return;

  const otherId = filesBtn.dataset.userid;
  const filesIcon = document.getElementById("files-icon");
  const fileInputs = [
    document.getElementById("chat-image-wrapper"),
    document.getElementById("chat-video-wrapper")
  ];

  // da znamo ko je logovani korisnik (A) – server šalje njegov ID u session
  const myId = document.body.dataset.myid; 
  // dodaj u <body data-myid="<?=$_SESSION['idKorisnici']?>">

  checkFilesStatus();
  setInterval(checkFilesStatus, 3000);

  filesBtn.addEventListener("click", () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/files-control.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = () => {
      if (xhr.status === 200) {
        updateFilesUI(xhr.response, true); // true = ja sam kliknuo
      }
    };
    xhr.send("other_id=" + otherId + "&action=toggle");
  });

  function checkFilesStatus() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/files-control.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = () => {
      if (xhr.status === 200) {
        updateFilesUI(xhr.response, false); // false = samo refresh
      }
    };
    xhr.send("other_id=" + otherId + "&action=check");
  }

  function updateFilesUI(status, iAmController) {
    if (status === "disabled") {
      // ako sam ja kontroler → menjam ikonicu
      if (iAmController) {
        filesIcon.classList.remove("fa-toggle-on");
        filesIcon.classList.add("fa-toggle-off");
        filesIcon.style.color = "red";
      }
      // sakrij fajlove
      fileInputs.forEach(inp => inp && (inp.style.display = "none"));
    } else if (status === "enabled") {
      // ako sam ja kontroler → vidim da je uključeno (bela ikonica)
      if (iAmController) {
        filesIcon.classList.remove("fa-toggle-off");
        filesIcon.classList.add("fa-toggle-on");
        filesIcon.style.color = "white";
      } else {
        // ako sam druga osoba → uvek crvena (jer ne kontrolišem)
        filesIcon.classList.remove("fa-toggle-on");
        filesIcon.classList.add("fa-toggle-off");
        filesIcon.style.color = "red";
      }
      // prikaži fajlove
      fileInputs.forEach(inp => inp && (inp.style.display = "block"));
    }
  }
});


//====================== AKO ŽELIMO DA RUČNO TREBA ISKLJUČITI SLANJE ======================
// ====================== ENABLE/DISABLE FILES ======================
/*document.addEventListener("DOMContentLoaded", () => {
  const filesBtn = document.getElementById("enable-disable-files");
  if (!filesBtn) return;

  const otherId = filesBtn.dataset.userid;
  const filesIcon = document.getElementById("files-icon");
  const fileWrappers = [
    document.getElementById("chat-image-wrapper"),
    document.getElementById("chat-video-wrapper")
  ];

  // inicijalna provera odmah
  checkFilesStatus();

  // auto-refresh (da se ažurira i kod druge strane)
  setInterval(checkFilesStatus, 3000);

  // klik na ikonicu → toggle (ali samo JA odlučujem)
  filesBtn.addEventListener("click", () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/files-control.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = () => {
      if (xhr.status === 200) {
        // odgovor se odnosi SAMO na moju odluku
        updateMyFilesUI(xhr.response);
      }
    };
    xhr.send("other_id=" + otherId + "&action=toggle");
  });

  // proverava status → da li JE DRUGI meni zabranio
  function checkFilesStatus() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/files-control.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = () => {
      if (xhr.status === 200) {
        // odgovor može biti "enabled" ili "disabled"
        updateFilesVisibility(xhr.response);
      }
    };
    xhr.send("other_id=" + otherId + "&action=check");
  }

  // ažuriranje UI-ja KADA JA KLIKNEM
  function updateMyFilesUI(status) {
    if (status === "disabled") {
      // moja ikonica pocrveni
      filesIcon.classList.remove("fa-toggle-on");
      filesIcon.classList.add("fa-toggle-off");
      filesIcon.style.color = "red";

      // i ja ne vidim svoje inpute
      fileWrappers.forEach(w => w && (w.style.display = "none"));
    } else if (status === "enabled") {
      // vraćam sebi ikonicu
      filesIcon.classList.remove("fa-toggle-off");
      filesIcon.classList.add("fa-toggle-on");
      filesIcon.style.color = "white";

      // prikazujem svoje inpute
      fileWrappers.forEach(w => w && (w.style.display = "block"));
    }
  }

  // ažuriranje UI-ja kada se REFRESHUJE status (druga osoba je meni zabranila)
  function updateFilesVisibility(status) {
    if (status === "disabled") {
      // meni su zabranili → ja ne vidim inpute
      fileWrappers.forEach(w => w && (w.style.display = "none"));
    } else if (status === "enabled") {
      // meni su dozvolili → ja ih vidim
      fileWrappers.forEach(w => w && (w.style.display = "block"));
    }
  }
});*/











