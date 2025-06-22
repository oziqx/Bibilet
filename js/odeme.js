document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('paymentForm');
    const userSelect = document.getElementById('userSelect');
    const cardSelect = document.getElementById('cardSelect');
    const inputs = {
        ad: document.getElementById('ad'),
        soyad: document.getElementById('soyad'),
        email: document.getElementById('email'),
        telefon: document.getElementById('telefon'),
        tc: document.getElementById('tc'),
        dogum: document.getElementById('dogum'),
        cinsiyet: document.getElementById('cinsiyet'),
        kartAdi: document.getElementById('kart_adi'),
        kartNumarasi: document.getElementById('kart_numarasi'),
        sonKullanma: document.getElementById('son_kullanma'),
        cvc2: document.getElementById('cvc2')
    };

    // Eleman kontrolü
    if (!form || !userSelect || !cardSelect || Object.values(inputs).some(input => !input)) {
        console.error('Bir veya daha fazla eleman bulunamadı:', { form, userSelect, cardSelect, ...inputs });
        return;
    }

    // Kullanıcı seçildiğinde formu doldur
    userSelect.addEventListener('change', (e) => {
        const option = e.target.selectedOptions[0];
        if (option.value) {
            inputs.ad.value = option.getAttribute('data-ad') || '';
            inputs.soyad.value = option.getAttribute('data-soyad') || '';
            inputs.email.value = option.getAttribute('data-email') || '';
            inputs.telefon.value = option.getAttribute('data-telefon') || '';
            inputs.tc.value = option.getAttribute('data-tc') || '';
            inputs.dogum.value = option.getAttribute('data-dogum') || '';
            inputs.cinsiyet.value = option.getAttribute('data-cinsiyet') || '';
        } else {
            Object.values(inputs).forEach(input => input.value = '');
        }
    });

    // Kart seçildiğinde formu doldur
    cardSelect.addEventListener('change', (e) => {
        const option = e.target.selectedOptions[0];
        if (option.value) {
            inputs.kartAdi.value = option.getAttribute('data-kart-adi') || '';
            inputs.kartNumarasi.value = option.getAttribute('data-kart-numara') || '';
            inputs.sonKullanma.value = option.getAttribute('data-son-kullanma') || '';
            inputs.cvc2.value = option.getAttribute('data-cvc2') || '';
        } else {
            inputs.kartAdi.value = '';
            inputs.kartNumarasi.value = '';
            inputs.sonKullanma.value = '';
            inputs.cvc2.value = '';
        }
    });

    // Form submit
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        // Veri toplama ve doğrulama
        const data = {
            ad: inputs.ad.value.trim(),
            soyad: inputs.soyad.value.trim(),
            email: inputs.email.value.trim(),
            telefon: inputs.telefon.value.trim(),
            tc: inputs.tc.value.trim(),
            dogum: inputs.dogum.value.trim(),
            cinsiyet: inputs.cinsiyet.value.trim(),
            kartAdi: inputs.kartAdi.value.trim(),
            kartNumarasi: inputs.kartNumarasi.value.replace(/\s+/g, ''),
            sonKullanma: inputs.sonKullanma.value.trim(),
            cvc2: inputs.cvc2.value.trim()
        };

        const errors = [];
        if (!data.ad || !data.soyad || !data.email || !data.telefon || !data.tc || !data.dogum || !data.cinsiyet ||
            !data.kartAdi || !data.kartNumarasi || !data.sonKullanma || !data.cvc2) {
            errors.push('Tüm alanlar gereklidir.');
        }
        if (!/^\d{11}$/.test(data.tc)) errors.push('TC Kimlik No 11 haneli olmalı.');
        if (!/^\d{10}$/.test(data.telefon)) errors.push('Telefon numarası 10 haneli olmalı.');
        if (!/^\d{16}$/.test(data.kartNumarasi)) errors.push('Kart numarası 16 haneli olmalı.');
        if (!/^\d{2}\/\d{2}$/.test(data.sonKullanma)) errors.push('Son kullanma tarihi MM/YY formatında olmalı (ör: 12/29).');
        if (!/^\d{3}$/.test(data.cvc2)) errors.push('CVC2 3 haneli olmalı.');

        if (errors.length > 0) {
            alert(errors.join('\n'));
            return;
        }

        // Formu gönder
        const formData = new FormData(form);
        fetch('odeme_islem.php', {
            method: 'POST',
            body: new URLSearchParams([...formData]).toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => alert('Hata oluştu: ' + error.message));
    });
});