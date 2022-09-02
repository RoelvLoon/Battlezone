$("#title").on("input", () => {
    $("#previewTitle").html($("#title").val());
    $("#previewTitleNav").html($("#title").val());
});

$("#description").on("input", () => {
    $("#previewDescription").html($("#description").val());
});

$("#url_button_text").on("input", () => {
    $("#previewURLButtonText").html($("#url_button_text").val());
});

$("#url_button").on("input", () => {
    $("#previewURLButtonText").attr("href", $("#url_button").val());
});

$("#button_text").on("input", () => {
    $("#previewButtonText").html($("#button_text").val());
});

$("#qr_instructions").on("input", () => {
    $("#previewQRInstructions").html($("#qr_instructions").val());
});

$("#logo_size").on("input", () => {
    $("#previewMainLogo").html($("#previewMainLogo").css("width", $("#logo_size").val() + "%"));
});

$("#main_color").on("input", () => {
    $("#previewMainCSS").html(getCSS($("#main_color").val(), "main"));
});

$("#nav_color").on("input", () => {
    $("#previewNavCSS").html(getCSS($("#nav_color").val(), "nav"));
});

$("#background_color").on("input", () => {
    $("#previewBackgroundCSS").html(getCSS($("#background_color").val(), "background"));
});

$("#main_logo").on("change", function() {
    const file = this.files[0];

    if (file) {
        const reader = new FileReader();

        reader.addEventListener("load", function() {
            $("#previewMainLogo").attr("src", this.result);
        });

        reader.readAsDataURL(file);
    }
})

$("#nav_logo").on("change", function() {
    const file = this.files[0];

    if (file) {
        const reader = new FileReader();

        reader.addEventListener("load", function() {
            $("#previewNavLogo").attr("src", this.result);
        });

        reader.readAsDataURL(file);
    }
})

$("#bg_image").on("change", function() {
    const file = this.files[0];

    if (file) {
        const reader = new FileReader();

        reader.addEventListener("load", function() {
            $("#previewBackgroundImage").css("background-image", "url(" + this.result + ")");
        });

        reader.readAsDataURL(file);
    }
})

$("#button_option").on("change", () => {
    
    switch ($("#button_option").val()) {
        case "0":
        
            $("#algemene_qr_toegang").css("display", "block");
            $("#eigen_link").css("display", "none");
        
            $("#algemene_qr_toegang_settings").css("display", "block");
            $("#eigen_link_settings").css("display", "none");

            break;
    
        case "1":
    
            $("#eigen_link").css("display", "block");
            $("#algemene_qr_toegang").css("display", "none");
        
            $("#algemene_qr_toegang_settings").css("display", "none");
            $("#eigen_link_settings").css("display", "block");

            break;
        
        default:
            $("#eigen_link").css("display", "none");
            $("#algemene_qr_toegang").css("display", "none");
        
            $("#algemene_qr_toegang_settings").css("display", "none");
            $("#eigen_link_settings").css("display", "none");
            break;
    }

});


    switch ($("#button_option").val()) {
        case "0":
        
            $("#algemene_qr_toegang").css("display", "block");
            $("#eigen_link").css("display", "none");
        
            $("#algemene_qr_toegang_settings").css("display", "block");
            $("#eigen_link_settings").css("display", "none");

            break;
    
        case "1":
    
            $("#eigen_link").css("display", "block");
            $("#algemene_qr_toegang").css("display", "none");
        
            $("#algemene_qr_toegang_settings").css("display", "none");
            $("#eigen_link_settings").css("display", "block");

            break;
        
        default:
            $("#eigen_link").css("display", "none");
            $("#algemene_qr_toegang").css("display", "none");
        
            $("#algemene_qr_toegang_settings").css("display", "none");
            $("#eigen_link_settings").css("display", "none");
            break;
    }
