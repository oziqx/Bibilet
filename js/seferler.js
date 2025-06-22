document.addEventListener('DOMContentLoaded', () => {
    const seferCards = document.querySelectorAll('.sefer-card');

    seferCards.forEach(card => {
        const koltukBtn = card.querySelector('.koltuk-sec-btn');
        const koltukPanel = card.querySelector('.sefer-koltuk-panel');
        const koltukAlani = koltukPanel.querySelector('.koltuk-alani');
        const kapatBtn = koltukPanel.querySelector('.kapat-btn');
        const cinsiyetRadios = koltukPanel.querySelectorAll('input[name="cinsiyet-' + card.dataset.seferId + '"]');
        const onaylaBtn = koltukPanel.querySelector('.onayla-btn');

        const koltukDüzeni = [
            { sutun1: [1, 2], koridor: null, sutun2: [3] },
            { sutun1: [4, 5], koridor: null, sutun2: [6] },
            { sutun1: [7, 8], koridor: null, sutun2: [9] },
            { sutun1: [10, 11], koridor: null, sutun2: [12] },
            { sutun1: [13, 14], koridor: null, sutun2: [15] },
            { sutun1: [16, 17], koridor: null, sutun2: [18] },
            { sutun1: [19, 20], koridor: null, sutun2: [21] },
            { sutun1: [22, 23], koridor: null, sutun2: [24] },
            { sutun1: [25, 26], koridor: null, sutun2: [27] },
            { sutun1: [28, 29], koridor: null, sutun2: [30] },
            { sutun1: [31, 32], koridor: null, sutun2: [33] },
            { sutun1: [34, 35], koridor: null, sutun2: [36] }
        ];

        koltukDüzeni.forEach((satir) => {
            const koltukSatiri = document.createElement('div');
            koltukSatiri.classList.add('koltuk-satiri');

            const sutun1 = document.createElement('div');
            sutun1.classList.add('koltuk-sutun');
            satir.sutun1.forEach(koltukNo => {
                const koltuk = document.createElement('div');
                koltuk.classList.add('koltuk');
                koltuk.textContent = koltukNo;
                sutun1.appendChild(koltuk);
            });
            koltukSatiri.appendChild(sutun1);

            const koridor = document.createElement('div');
            koridor.classList.add('koridor');
            koltukSatiri.appendChild(koridor);

            const sutun2 = document.createElement('div');
            sutun2.classList.add('koltuk-sutun');
            satir.sutun2.forEach(koltukNo => {
                const koltuk = document.createElement('div');
                koltuk.classList.add('koltuk');
                koltuk.textContent = koltukNo;
                sutun2.appendChild(koltuk);
            });
            koltukSatiri.appendChild(sutun2);

            koltukAlani.appendChild(koltukSatiri);
        });

        koltukAlani.addEventListener('click', (e) => {
            const koltuk = e.target.closest('.koltuk');
            if (koltuk && !koltuk.classList.contains('secili')) {
                const seciliKoltuk = koltukAlani.querySelector('.secili');
                if (seciliKoltuk) seciliKoltuk.classList.remove('secili');
                koltuk.classList.add('secili');
                onaylaBtn.disabled = false;
                cinsiyetRadios.forEach(radio => radio.checked = false);
            }
        });

        cinsiyetRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                if (document.querySelector('input[name="cinsiyet-' + card.dataset.seferId + '"]:checked')) {
                    onaylaBtn.disabled = false;
                }
            });
        });

        koltukBtn.addEventListener('click', () => {
            koltukPanel.style.display = 'block';
        });

        kapatBtn.addEventListener('click', () => {
            koltukPanel.style.display = 'none';
            const seciliKoltuk = koltukAlani.querySelector('.secili');
            if (seciliKoltuk) seciliKoltuk.classList.remove('secili');
            cinsiyetRadios.forEach(radio => radio.checked = false);
            onaylaBtn.disabled = true;
        });

        onaylaBtn.addEventListener('click', () => {
            const seciliKoltuk = koltukAlani.querySelector('.secili');
            const cinsiyet = document.querySelector('input[name="cinsiyet-' + card.dataset.seferId + '"]:checked')?.value;

            if (!seciliKoltuk || !cinsiyet) {
                alert('Lütfen bir koltuk ve cinsiyet seçin!');
                return;
            }

            if (!isLoggedIn) {
                alert('Lütfen giriş yapın!');
                window.location.href = '../uye/giris-yap.php';
                return;
            }

            // Ödeme sayfasına yönlendirme
            const seferId = card.dataset.seferId;
            window.location.href = `../panel/odeme.php?sefer_id=${seferId}&koltuk_no=${seciliKoltuk.textContent}&cinsiyet=${cinsiyet}`;
        });
    });
});