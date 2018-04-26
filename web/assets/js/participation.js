$("#students-list").find(".hand-up").click(function () {
    var student_id = $(this).data("id");
    var params = {student_id: student_id};
    var element = $(this);

    $.post("/participation/new", params, function (data) {
        element.parent().find('.contributions').text(data.nb);
        element.parent().find('.lastcall').text(data.lastCall);
        element.parent().addClass('green-line');
        setTimeout(
            function() {
                element.parent().removeClass('green-line');
            }, 2000);
    });
});

$("#cancel").click(function () {
    var element = $(this);
    $.post("/participation/cancel", function (data) {
        //TODO refresh de la page
        $('.hand-up[data-id="7"]').parent().addClass('red-line');
        setTimeout(
            function() {
                $('.hand-up[data-id="7"]').parent().removeClass('red-line');
            }, 2000);
    });
});