// Modal açma/kapama fonksiyonları
window.openAddKartModal = function() {
    const modal = document.getElementById('add-kart-modal');
    if (modal) {
        modal.style.display = 'block';
        initializeKartFormValidation();
    }
};

window.closeAddKartModal = function() {
    const modal = document.getElementById('add-kart-modal');
    if (modal) {
        modal.style.display = 'none';
    }
};

window.openAddYolcuModal = function() {
    const modal = document.getElementById('add-yolcu-modal');
    if (modal) {
        modal.style.display = 'block';
        initializeYolcuFormValidation();
    }
};

window.closeAddYolcuModal = function() {
    const modal = document.getElementById('add-yolcu-modal');
    if (modal) {
        modal.style.display = 'none';
    }
};

// Modal dışına tıklayınca kapatma
window.onclick = function(event) {
    const kartModal = document.getElementById('add-kart-modal');
    const yolcuModal = document.getElementById('add-yolcu-modal');
    if (event.target === kartModal) {
        kartModal.style.display = 'none';
    }
    if (event.target === yolcuModal) {
        yolcuModal.style.display = 'none';
    }
};

// Kart Ekleme Form Doğrulama
window.initializeKartFormValidation = function() {
    const form = document.getElementById('add-kart-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            const telefon = form.querySelector('#telefon').value;
            const kartNumarasi = form.querySelector('#kart_numarasi').value;
            const sonKullanmaTarihi = form.querySelector('#son_kullanma_tarihi').value;
            const cvc2 = form.querySelector('#cvc2').value;

            const telefonRegex = /^\d{10}$/;
            if (!telefonRegex.test(telefon)) {
                e.preventDefault();
                alert('Lütfen geçerli bir telefon numarası girin (10 rakam)!');
                return;
            }

            const kartNumarasiTemiz = kartNumarasi.replace(/\s/g, '');
            const kartNumarasiRegex = /^\d{16}$/;
            if (!kartNumarasiRegex.test(kartNumarasiTemiz)) {
                e.preventDefault();
                alert('Lütfen geçerli bir kart numarası girin (16 rakam)!');
                return;
            }

            const sonKullanmaRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
            if (!sonKullanmaRegex.test(sonKullanmaTarihi)) {
                e.preventDefault();
                alert('Lütfen geçerli bir son kullanma tarihi girin (AA/YY formatında, ay 01-12 arasında olmalı)!');
                return;
            }

            const [ay, yil] = sonKullanmaTarihi.split('/').map(Number);
            const bugun = new Date();
            const buYil = bugun.getFullYear() % 100;
            const buAy = bugun.getMonth() + 1;

            if (yil < buYil || (yil === buYil && ay < buAy)) {
                e.preventDefault();
                alert('Son kullanma tarihi geçmiş bir tarih olamaz!');
                return;
            }

            const cvc2Regex = /^\d{3}$/;
            if (!cvc2Regex.test(cvc2)) {
                e.preventDefault();
                alert('Lütfen geçerli bir CVC2 kodu girin (3 rakam)!');
                return;
            }
        });

        const kartNumarasiInput = form.querySelector('#kart_numarasi');
        kartNumarasiInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})/g, '$1 ').trim();
            e.target.value = value;
        });

        const sonKullanmaInput = form.querySelector('#son_kullanma_tarihi');
        sonKullanmaInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }
};

// Yolcu Ekleme Form Doğrulama
window.initializeYolcuFormValidation = function() {
    const form = document.getElementById('add-yolcu-form');
    if (form) {
        form.addEventListener('submit', (e) => {
            const ad = form.querySelector('#ad').value;
            const soyad = form.querySelector('#soyad').value;
            const email = form.querySelector('#email').value;
            const telefon = form.querySelector('#telefon').value;
            const tcKimlikNo = form.querySelector('#tc_kimlik_no').value;
            const dogumTarihi = form.querySelector('#dogum_tarihi').value;

            // Ad ve Soyad kontrolü
            const adSoyadRegex = /^[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+$/;
            if (!adSoyadRegex.test(ad)) {
                e.preventDefault();
                alert('Ad yalnızca harflerden oluşmalıdır!');
                return;
            }
            if (!adSoyadRegex.test(soyad)) {
                e.preventDefault();
                alert('Soyad yalnızca harflerden oluşmalıdır!');
                return;
            }

            // E-posta kontrolü
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Lütfen geçerli bir e-posta adresi girin!');
                return;
            }

            // Telefon kontrolü
            const telefonRegex = /^\d{10}$/;
            if (!telefonRegex.test(telefon)) {
                e.preventDefault();
                alert('Lütfen geçerli bir telefon numarası girin (10 rakam)!');
                return;
            }

            // TC Kimlik No kontrolü
            const tcKimlikRegex = /^\d{11}$/;
            if (!tcKimlikRegex.test(tcKimlikNo)) {
                e.preventDefault();
                alert('Lütfen geçerli bir TC Kimlik No girin (11 rakam)!');
                return;
            }

            // Doğum tarihi kontrolü
            const dogumTarihiDate = new Date(dogumTarihi);
            const bugun = new Date();
            if (isNaN(dogumTarihiDate) || dogumTarihiDate > bugun) {
                e.preventDefault();
                alert('Lütfen geçerli bir doğum tarihi girin (geçmiş bir tarih olmalı)!');
                return;
            }
        });

        // TC Kimlik No yalnızca rakam kabul etsin
        const tcKimlikInput = form.querySelector('#tc_kimlik_no');
        tcKimlikInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }
};