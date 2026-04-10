document.addEventListener('DOMContentLoaded', function() {
    const dugmici = document.querySelectorAll('.oznaci');

    dugmici.forEach(btn => {
        btn.addEventListener('click', function() {
            const idObav = this.getAttribute('data-id');
            const red = document.getElementById('obav' + idObav);

            fetch('oznaciProcitanoObavestenja.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'idObav=' + idObav
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'ok') {
                    red.classList.remove('bg-warning');
                    red.classList.add('bg-light');
                    this.remove();
                    const oznaka = document.createElement('span');
                    oznaka.className = 'badge badge-success mt-2';
                    oznaka.textContent = 'Прочитано';
                    red.appendChild(oznaka);
                } else {
                    alert('Greška pri označavanju kao pročitanog.');
                }
            });
        });
    });
});
