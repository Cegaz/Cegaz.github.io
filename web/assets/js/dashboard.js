$(".dashboard").find(".student-row").click(function() {
    var student_id = $(this).data("id");

    $.get("/dashboard/student/"+student_id, function(data) {
        $("#showStudent").html(data);
    });
});

$("#comments").find("#add-comment").click(function() {
    $("#comment-form").css('display','block');
    $("#add-comment").css('display','none');
});

$("#comments").find("#cancel-comment").click(function() {
    $("#comment-form").css('display', 'none');
    $("#add-comment").css('display','block');
})

$("#save-comment").click(function() {
    var params = {
        text: $("#comment-form").find("#input-text").val(),
        studentId: $("#comment-form").find("#input-id").val()
    };
    $.post("/comment/add", params, function(data) {
        $("#comments-list").append("<p>" +
            data.text +
            "<br><span class='font-italic'>" +
            data.date);
        $("#comment-form").css('display','none');
        $("#add-comment").css('display','block');
    });
});

$("#more-comments").click(function() {
    $(".hidden-comment").toggleClass('display-none');

    if($(this).find('img').attr("src") == "/assets/images/arrow-top.png") {
        $(this).find('img').attr("src", "/assets/images/arrow-down.png");
    } else {
        $(this).find('img').attr("src", "/assets/images/arrow-top.png");
    }
});

$('#showStudent').find('#delete-student').click(function() {
    var params = {
        idStudent: $(this).data('id')
    }
   $.post("/dashboard/delete-student", params);
});

$("#showStudent").find("#modify-contact").click(function() {
    var elem = $("#student-email, #student-phone");
    elem.addClass('pseudo-input');
    elem.attr('contenteditable', 'true');
    var elems = $("#save-contact, #cancel-contact");
    elems.css('display', 'inline');

    var elem = $("#student-email");
    if(elem.text() == "pas d'email enregistré") {
        elem.text('');
    }
    elem = $("#student-phone");
    if(elem.text() == "pas de numéro de tél enregistré") {
        elem.text('');
    }
});

$("#student-email").keyup(function() {
    if(validateEmail($(this).text())) {
        $(this).addClass('green-border');
        $(this).removeClass('red-border');
    } else {
        $(this).removeClass('green-border');
        $(this).addClass('red-border');
    }
})

$("#student-phone").keyup(function() {
    if(validatePhone($(this).text())) {
        $(this).addClass('green-border');
        $(this).removeClass('red-border');
    } else {
        $(this).removeClass('green-border');
        $(this).addClass('red-border');
    }
})

$("#showStudent").find('#save-contact').click(function() {
    $("#student-phone, #student-email").removeClass('green-border');
    $("#student-phone, #student-email").removeClass('red-border');
    $("#email-error-msg").html('');
    $("#phone-error-msg").html('');

    var email = $('#student-email').text();
    var phone = $('#student-phone').text();
    var params = {
        idStudent: $(this).data('id'),
        email: email,
        phone: phone
    }

    var errors = 0;
    // TODO CG trouver plus propre ?
    if(email.length > 0 && !validateEmail(email)) {
        errors++;
    }
    if(phone.length > 0 && !validatePhone(phone)) {
        errors++;
    }

    if(!errors) {
        $.post("/dashboard/modify-student", params, function (res) {
            if (res) {
                var elem = $("#student-email, #student-phone");
                elem.attr('contenteditable', 'false');
                elem.removeClass('pseudo-input');
                $("#send-email").css('display', 'block');
                var elems = $("#save-contact, #cancel-contact");
                elems.css('display', 'none');
            }
        });
    }
});

$("#showStudent").find('#cancel-contact').click(function() {
    var elem = $("#student-email, #student-phone");
    elem.attr('contenteditable', 'false');
    elem.removeClass('pseudo-input');
    // $("#send-email").css('display', 'block');
    var elems = $("#save-contact, #cancel-contact");
    elems.css('display', 'none');
});

$("#search").click(function() {
    var input = $('.dashboard').find('#student-name').val();

    if (input != '') {
        var params = {
            student_name_like: input
        };

        $.post("/search", params, function (data) {
            $("#showStudent").html(data);
        });
    }
});

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validatePhone(phone) {
    var re = /^0[1-79][\s\.-]?([0-9][\s\.-]?){8}$/;
    return re.test(phone);
}

// admin sanction reasons
$("#add-sanction").click(function() {
    $("#sanctions-form").removeClass('hidden');
    $(this).addClass('hidden');
});
$("#cancel-sanction").click(function() {
    $("#sanctions-form").addClass('hidden');
    $("#add-sanction").removeClass('hidden');
});
$("#save-sanction").click(function() {
    var params = {
        name: $("#sanctions-form").find("input").val(),
    };
    $.post("/dashboard/add-sanction-reason", params, function(data) {
        $("#sanctions-list").append(
            "<button class='btn class-btn' data-sanctionid=" + data.sanctionReasonId + ">" + data.name + "</button>");
        $("#sanction-form").addClass('hidden');
        $("#add-sanction").removeClass('hidden');
    });
});
