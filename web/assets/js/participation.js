$(document).ready(function() {
    $("#students-list").find(".hand-up").click(function(){
            var student_id = $(this).data("id");
            var params = {student_id: student_id};
            var element = $(this);

            $.post("/participation/new", params, function(data){
                element.parent().find('.contributions').text(data.nb);
                element.parent().find('.lastcall').text(data.lastCall);
            });
    });
})