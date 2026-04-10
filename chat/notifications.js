// ====================== UNREAD COUNTER GLOBAL ======================

// ⚙️ Možeš definisati ovu promenljivu u HTML-u pre nego što uključuješ ovaj JS fajl.
// Primer:
// <script> const UNREAD_COUNT_URL = "/sajtovi/albumi/chat/php/unread-count.php"; </script>
// <script src="/sajtovi/albumi/chat/notifications.js"></script>
// Ako je folder na serveru u koji smještamo sajt www onda stavljamo putanju /mojwebsajt/chat/php...

//KOD ISPOD JE SAMO ZA LOCALHOST
const unreadCountUrl = typeof UNREAD_COUNT_URL !== "undefined" 
  ? UNREAD_COUNT_URL 
  : "/sajtovi/albumi/chat/php/unread-count.php"; // default fallback
 
 
//----------------------------------------------------------------------------
/*KOD ISPOD JE ZA PRAVI SERVER SA PRAVIM NAZIVOM SAJTA
const unreadCountUrl = typeof UNREAD_COUNT_URL !== "undefined" 
? UNREAD_COUNT_URL 
: "https://dlux.rs/chat/php/unread-count.php"; // default fallback
*/
//KOD IZNAD JE ZA PRAVI SERVER SA PRAVIM NAZIVOM SAJTA

setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", unreadCountUrl, true);
  
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      let count = parseInt(xhr.response);
      const badge = document.getElementById("unread-counter");

      if (badge) {
        if (count > 0) {
          badge.style.display = "inline-block";
          badge.textContent = count;
        } else {
          badge.style.display = "none";
        }
      }
    }
  };

  xhr.send();
}, 3000); // Proverava svakih 3 sekunde
