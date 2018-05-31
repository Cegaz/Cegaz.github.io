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
    $.post("/participation/cancel", function (data) {
        var element = $(".hand-up[data-id='"+data.studentId+"']").parent();
        element.find('.contributions').text(data.nbInterventions);
        element.find('.lastCall').text(data.lastIntervention);
        element.addClass('red-line');
        setTimeout(
            function() {
                element.removeClass('red-line');
            }, 2000);
    });
});

$("#menu-apps").find("select").change(function() {
    var newClassId = $(this).val();
    var params = {newClassId: newClassId};

    $.post("/change-class", params, function() {
        window.location.href = "/participation";
    });
});

$("#list-classes").find(".class-btn").click(function() {
    var newClassId = $(this).data('classid');
    var params = {newClassId: newClassId};

    $.post("/change-class", params, function() {
        window.location.href = "/participation";
    });
});