$("#students-list").find(".student-row").click(function() {
    var student_id = $(this).data("id");

    $.post("/dashboard/student/"+student_id, function(data) {
        $("#showStudent").html(data);
        console.log(data);
    });
})

$("#comments").find("#add-comment").click(function() {
    $("#comment-form").css('display','block');
    $("#add-comment").css('display','none');
});

$("#comment-save").click(function() {
    var params = {
        text: $("#comment-form").find("#input-text").val(),
        studentId: $("#comment-form").find("#input-id").val()
    };
    $.post("/comment/add", params, function(data) {
        $("#comments-list").append("<p>" +
            data.text +
            "<br><span class='font-italic'>" +
            data.date +
            "</span><img id=\"comment-modif\" src=\"/assets/images/crayon.png\"></p>");
        $("#comment-form").css('display','none');
        $("#add-comment").css('display','block');
    });
});

$("#more-comments").click(function() {
    if($(".hidden-comment").css('display') == 'block') {
        $(".hidden-comment").css('display', 'none');
    } else {
        $(".hidden-comment").css('display', 'block');
    }

    if($(this).find('img').attr("src") == "/assets/images/arrow-top.png") {
        $(this).find('img').attr("src", "/assets/images/arrow-down.png");
    } else {
        $(this).find('img').attr("src", "/assets/images/arrow-top.png");
    }
});
