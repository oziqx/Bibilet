// Bugün ve Yarın için tarih ayarlama
function setDate(offset = 0) {
    const date = new Date();
    date.setDate(date.getDate() + offset);
    return date.toISOString().split('T')[0]; // YYYY-MM-DD formatı
}

function setToday() {
    document.getElementById('gidis_tarihi').value = setDate(0);
}

function setTomorrow() {
    document.getElementById('gidis_tarihi').value = setDate(1);
}

// Otogarları yer değiştirme (swap)
document.addEventListener('DOMContentLoaded', () => {
    const swapIcon = document.querySelector('.swap-icon');
    const neredenSelect = document.getElementById('nereden');
    const nereyeSelect = document.getElementById('nereye');
    const form = document.getElementById('search-form');

    // Element kontrolü
    if (!swapIcon || !neredenSelect || !nereyeSelect || !form) {
        console.error('Bazı öğeler bulunamadı:', { swapIcon, neredenSelect, nereyeSelect, form });
        return;
    }

    // Swap fonksiyonu
    swapIcon.addEventListener('click', () => {
        const temp = neredenSelect.value;
        neredenSelect.value = nereyeSelect.value;
        nereyeSelect.value = temp;
    });

    // Form submit kontrolü ve yönlendirme
    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Formun varsayılan gönderimini hemen engelle

        const gidisTarihi = new Date(document.getElementById('gidis_tarihi').value);
        const bugun = new Date();
        bugun.setHours(0, 0, 0, 0);

        if (gidisTarihi < bugun) {
            alert('Gidiş tarihi geçmiş bir tarih olamaz!');
            return;
        }

        const nereden = document.getElementById('nereden').value;
        const nereye = document.getElementById('nereye').value;
        if (nereden === nereye) {
            alert('Nereden ve Nereye aynı otogar olamaz!');
            return;
        }

        // ✅ PANEL dizinine doğru şekilde yönlendir
        const baseUrl = `${window.location.origin}/bibilet2/panel/seferler.php`;
        const params = `?kalkis_id=${encodeURIComponent(nereden)}&varis_id=${encodeURIComponent(nereye)}&tarih=${encodeURIComponent(document.getElementById('gidis_tarihi').value)}`;
        const newUrl = baseUrl + params;

        console.log('Yönlendirme URL:', newUrl);
        window.location.href = newUrl; // Doğrudan yönlendirme
    });

    // Varsayılan olarak bugünün tarihini ayarla
    setToday();
});
