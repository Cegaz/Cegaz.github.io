$("#students-list").find(".hand-up").click(function () {
    var student_id = $(this).data("id");
    var params = {student_id: student_id};
    var element = $(this).parent();

    $.post("/participation/new", params, function (data) {
        element.find('.participationsToday').text(data.nbToday);
        element.find('.participations').text(data.nb);
        element.find('.lastParticipation').text(data.lastParticipation);
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
            element.find('.participationsToday').text(data.nbParticipationsToday);
            element.find('.participations').text(data.nbParticipations);
            element.find('.lastParticipation').text(data.lastParticipation);
            element.addClass('red-line');
            setTimeout(
                function() {
                    element.removeClass('red-line');
                }, 2000);
        }
    });
});