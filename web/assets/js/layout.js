$("#menu-apps").find("select").change(function() {
    var params = {
        newClass: $(this).val()
    };

    $.post("/change-class", params, function() {
        location.reload();
    });
})