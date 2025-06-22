$(document).ready(function() {
    const sidebarLinks = $('.sidebar a');
    const contentArea = $('#content-area');

    function loadContent(hash) {
        let content = '';
        switch (hash) {
            case '#anasayfa':
                content = '<h3>Anasayfa İçeriği</h3><p>Hoş geldiniz, burada genel bir özet yer alabilir.</p>';
                break;
            case '#sefer-olustur':
                content = `
                    <h3>Sefer Oluştur</h3>
                    <form id="sefer-form">
                        <div>
                            <label for="kalkis_sehir">Kalkış Şehri:</label>
                            <select id="kalkis_sehir" name="kalkis_sehir" required>
                                <option value="">Seçiniz</option>
                            </select>
                        </div>
                        <div>
                            <label for="kalkis_otogar">Kalkış Otogarı:</label>
                            <select id="kalkis_otogar" name="kalkis_otogar" required>
                                <option value="">Seçiniz</option>
                            </select>
                        </div>
                        <div>
                            <label for="varis_sehir">Varış Şehri:</label>
                            <select id="varis_sehir" name="varis_sehir" required>
                                <option value="">Seçiniz</option>
                            </select>
                        </div>
                        <div>
                            <label for="varis_otogar">Varış Otogarı:</label>
                            <select id="varis_otogar" name="varis_otogar" required>
                                <option value="">Seçiniz</option>
                            </select>
                        </div>
                        <div>
                            <label for="saat">Saat:</label>
                            <input type="time" id="saat" name="saat" required>
                        </div>
                        <div>
                            <label for="tarih">Tarih:</label>
                            <input type="date" id="tarih" name="tarih" required>
                        </div>
                        <div>
                            <label for="fiyat">Fiyat (TL):</label>
                            <input type="number" id="fiyat" name="fiyat" step="0.01" min="0" required>
                        </div>
                        <button type="submit">Sefer Oluştur</button>
                    </form>
                    <div id="message"></div>
                `;
                break;
            case '#seferler':
                content = '<h3>Seferler</h3><p>Var olan seferlerinizi burada görebilirsiniz.</p>';
                break;
            case '#bilgileri-guncelle':
                content = '<h3>Bilgileri Güncelle</h3><p>Firma bilgilerinizi güncelleyebilirsiniz.</p>';
                break;
            case '#kullanici-bilgisi':
                content = '<h3>Kullanıcı Bilgisi</h3><p>Oluşturulan seferlerin kullanıcı bilgilerini burada görebilirsiniz.</p>';
                break;
            default:
                content = '<h3>Anasayfa İçeriği</h3><p>Hoş geldiniz, burada genel bir özet yer alabilir.</p>';
                window.location.hash = '#anasayfa';
                break;
        }
        contentArea.html(content);

        if (hash === '#sefer-olustur') {
            // Şehirleri yükle
            $.getJSON('get_sehirler.php', function(data) {
                if (data.error) {
                    $('#message').text('Şehir verisi hatası: ' + data.error);
                    console.error('Şehir verisi hatası:', data.error);
                    return;
                }
                if (!Array.isArray(data)) {
                    $('#message').text('Geçersiz veri formatı: Şehir verisi bir dizi olmalı');
                    console.error('Geçersiz veri formatı:', data);
                    return;
                }
                $.each(data, function(index, sehir) {
                    $('#kalkis_sehir, #varis_sehir').append($('<option>', {
                        value: sehir.id,
                        text: sehir.sehir_adi
                    }));
                });
            }).fail(function(jqXHR, textStatus, errorThrown) {
                $('#message').text('Şehir verisi çekme hatası: ' + errorThrown);
                console.error('Hata:', textStatus, errorThrown, jqXHR.responseText);
            });

            // Otogarları yükle
            $('#kalkis_sehir, #varis_sehir').on('change', function() {
                const sehirId = $(this).val();
                const otogarSelect = $(this).attr('id') === 'kalkis_sehir' ? '#kalkis_otogar' : '#varis_otogar';
                $(otogarSelect).html('<option value="">Seçiniz</option>');
                if (sehirId) {
                    $.getJSON('get_otogarlar.php?sehir_id=' + sehirId, function(otogarlar) {
                        if (otogarlar.error) {
                            $('#message').text('Otogar verisi hatası: ' + otogarlar.error);
                            console.error('Otogar verisi hatası:', otogarlar.error);
                            return;
                        }
                        if (!Array.isArray(otogarlar)) {
                            $('#message').text('Geçersiz veri formatı: Otogar verisi bir dizi olmalı');
                            console.error('Geçersiz veri formatı:', otogarlar);
                            return;
                        }
                        if (otogarlar.length > 0) {
                            $.each(otogarlar, function(index, otogar) {
                                $(otogarSelect).append($('<option>', {
                                    value: otogar.id,
                                    text: otogar.otogar_adi + ' (' + otogar.sehir_adi + ')'
                                }));
                            });
                        } else {
                            $(otogarSelect).append('<option value="">Otogar bulunamadı</option>');
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        $('#message').text('Otogar verisi çekme hatası: ' + errorThrown);
                        console.error('Hata:', textStatus, errorThrown, jqXHR.responseText);
                    });
                }
            });

            $('#sefer-form').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.post('sefer_olustur.php', formData, function(response) {
                    $('#message').text(response);
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    $('#message').text('Gönderim hatası: ' + errorThrown);
                });
            });
        }
    }

    sidebarLinks.on('click', function(e) {
        const href = $(this).attr('href');
        if (href.includes('cikis-yap.php')) {
            return;
        }
        e.preventDefault();
        window.location.hash = href;
        loadContent(href);
    });

    $(window).on('hashchange', function() {
        loadContent(window.location.hash);
    });

    loadContent(window.location.hash || '#anasayfa');
});