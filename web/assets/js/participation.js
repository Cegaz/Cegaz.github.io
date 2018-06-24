$("#students-list").find(".hand-up").click(function () {
    var student_id = $(this).data("id");
    var params = {student_id: student_id};
    var element = $(this).parent();

    $.post("/participation/new", params, function (data) {
        element.find('.contributions').text(data.nb);
        element.find('.lastcall').text(data.lastCall);
        element.addClass('green-line');
        setTimeout(
            function () {
                element.removeClass('green-line');
            }, 2000);
    });
});

$("#cancel").click(function () {
    $.get("/participation/cancel", function (data) {
        if(data) {
            var element = $(".hand-up[data-id='"+data.studentId+"']").parent();
            element.find('.contributions').text(data.nbInterventions);
            element.find('.lastcall').text(data.lastIntervention);
            element.addClass('red-line');
            setTimeout(
                function() {
                    element.removeClass('red-line');
                }, 2000);
        }
    });
});