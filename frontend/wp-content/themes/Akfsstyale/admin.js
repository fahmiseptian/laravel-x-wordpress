jQuery(document).ready(function ($) {
    let mediaUploader;
    
    $(".upload_banner_button").click(function (e) {
        e.preventDefault();
        
        let target = $(this).data("target");
        let wrapper = $("#" + target + "_wrapper");

        // Batasi hanya 4 gambar untuk Banner Dashboard
        if (target === "banner_dashboard" && wrapper.find(".banner-item").length >= 4) {
            alert("Maksimal 4 gambar untuk Banner Dashboard!");
            return;
        }

        // Buat media uploader baru atau gunakan yang sudah ada
        mediaUploader = wp.media({
            title: "Pilih Gambar",
            button: { text: "Gunakan Gambar" },
            multiple: target === "banner_dashboard" // Hanya multiple untuk Dashboard
        });

        mediaUploader.on("select", function () {
            let selection = mediaUploader.state().get("selection");

            // Jika yang dipilih untuk Banner Contact Us, kosongkan sebelumnya
            if (target === "banner_contact") {
                wrapper.empty();
            }

            selection.each(function (attachment) {
                let imageUrl = attachment.attributes.url;

                if (target === "banner_dashboard") {
                    let html = `<div class="banner-item">
                                    <img src="${imageUrl}" style="max-width:150px; height:auto; margin:5px;">
                                    <button type="button" class="button remove-banner">Hapus</button>
                                    <input type="hidden" name="banner_dashboard[]" value="${imageUrl}">
                                </div>`;
                    wrapper.append(html);
                } else if (target === "banner_contact") {
                    let html = `<div class="banner-item">
                                    <img src="${imageUrl}" style="max-width:300px; height:auto;">
                                    <button type="button" class="button remove-banner">Hapus</button>
                                    <input type="hidden" name="banner_contact" value="${imageUrl}">
                                </div>`;
                    wrapper.html(html);
                }
            });
        });

        mediaUploader.open();
    });

    $(document).on("click", ".remove-banner", function () {
        $(this).closest(".banner-item").remove();
    });
});
