$("#list-classes").find(".class-btn").click(function() {
    var newClassId = $(this).data('classid');
    var params = {newClassId: newClassId};

    $.post("/change-class", params, function() {
        var url = window.location.href;
        if(url.match(/participation/)) {
            window.location.href = "/participation";
        } else if(url.match(/dashboard/)) {
            window.location.href = "/dashboard";
        } else if(url.match(/grades/)) {
            window.location.href = "/grades";
        }
    });
});

$("#menu-apps").find("select").change(function() {
    var newClassId = $(this).val();
    var params = {newClassId: newClassId};

    $.post("/change-class", params, function() {
        var url = window.location.href;
        if(url.match(/participation/)) {
            window.location.href = "/participation";
        } else if(url.match(/dashboard/)) {
            window.location.href = "/dashboard";
        } else if(url.match(/grades/)) {
            window.location.href = "/grades";
        }
    });
});

$('.alert').on('click', function() {
   $(this).addClass('hidden');
});