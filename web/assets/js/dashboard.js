$(".dashboard").find(".student-row").click(function() {
    var student_id = $(this).data("id");

    $.get("/student/show/"+student_id, function(data) {
        $("#showStudent").html(data);
    });
});

// pour régler le pb du 2è clic sur modale
$('#showStudent').on('hide.bs.modal', function () {
    $(this).off('hide.bs.modal');
}); //TODO CG bofbof

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


// sanctions on student modale : display more
$("#more-sanctions").click(function() {
    $(".hidden-sanction").toggleClass('display-none');

    if($(this).find('img').attr("src") == "/assets/images/arrow-top.png") {
        $(this).find('img').attr("src", "/assets/images/arrow-down.png");
    } else {
        $(this).find('img').attr("src", "/assets/images/arrow-top.png");
    }
});

// modify sanction
$('#sanctions-list').find('.modify-sanction').click(modifySanction);

function modifySanction() {
    disableAllSanctionButtons();
    var sanction = $(this).closest('.sanction');
    var input = sanction.find('.details');
    input.addClass('pseudo-input');
    input.attr('contenteditable', 'true');
    var buttons = sanction.find('.sanction-buttons');
    buttons.html('<div class="btn btn-secondary cancel-sanction">cancel</div>\n' +
        '<div class="btn btn-success save-sanction">save</div>');
    $("#sanctions-list").find(".cancel-sanction").click('on', cancelModifySanction);
    $("#sanctions-list").find('.save-sanction').click(doModifySanction);
}


function cancelModifySanction() {
    var $sanction = $(this).closest('.sanction');
    var $details = $sanction.find('.details');
    $details.find('.sanction-details').text($details.data('former-value'));
    uneditable($details);
    disableAllSanctionButtons();
}

function doModifySanction() {
    var $sanction = $(this).closest('.sanction');

    var params = {
        sanctionId: $sanction.data('id'),
        details: $sanction.find('.sanction-details').text()
    };
    $.post("/sanction/modify-details", params, function(result) {
        console.log(result);
    }, 'json');
    uneditable($sanction.find('.details'));
}

function disableAllSanctionButtons()
{
    $('#sanctions-list').find('.sanction-buttons').html('');
    var input = $('.details');
    input.removeClass('pseudo-input');
    input.attr('contenteditable', 'false');
}

$('#showStudent').find('#confirm-delete-student').click(function() {
    var studentId = $(this).attr('data-id');

    var line = $('#students-list').find('*[data-id="'+studentId+'"]').closest('tr');
    $.post("/student/delete", {studentId: studentId}, function(result) {
        $('#confirm-modal').modal('hide');
        $('.modal-backdrop').remove();
        $('.modal').remove();
        line.remove();
        alert(result.studentName + ' has been deleted.');
    });
});

// modify contact
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

// modify contact : save
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
    if(email.length > 0 && !validateEmail(email)) {
        errors++;
    }
    if(phone.length > 0 && !validatePhone(phone)) {
        errors++;
    }

    if(!errors) {
        $.post("/student/modify", params, function (res) {
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

// modify contact : cancel
$("#showStudent").find('#cancel-contact').click(function() {
    $("#student-phone, #student-email").removeClass('green-border');
    $("#student-phone, #student-email").removeClass('red-border');
    var elem = $("#student-email, #student-phone");
    elem.attr('contenteditable', 'false');
    elem.removeClass('pseudo-input');

    var elems = $("#save-contact, #cancel-contact");
    elems.css('display', 'none');

    var phoneElem = $('#student-phone');
    phoneElem.text(phoneElem.data('former-value'));
    var emailElem = $('#student-email');
    emailElem.text(emailElem.data('former-value'));
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
$("#cancel-sanction-reason").click(function() {
    $("#sanctions-form").addClass('hidden');
    $("#add-sanction").removeClass('hidden');
});
$("#save-sanction-reason").click(function() {
    var params = {
        name: $("#sanctions-form").find("input").val(),
    };
    $.post("/sanction-reason/add", params, function(data) {
        $("#sanctions-reasons-list").append(
            "<button class='btn class-btn' data-sanctionid=" + data.sanctionReasonId + ">" + data.name + "</button>");
        $("#sanction-form").addClass('hidden');
        $("#add-sanction").removeClass('hidden');
    });
});


// modify student name
$("#showStudent").find("#modify-student-name").click('on', modifyStudentName);
$("#showStudent").find("#cancel-name").click('on', cancelModifyStudentName);
$("#showStudent").find("#save-name").click('on', doModifyStudentName);

function modifyStudentName()
{
    var elem = $('#showStudent').find('#student-last-name, #student-first-name');
    editable(elem);
}

function cancelModifyStudentName()
{
    var elem1 = $("#student-last-name");
    elem1.text(elem1.data('former-value'));
    uneditable(elem1);
    var elem2 = $("#student-first-name");
    uneditable(elem2);
    elem2.text(elem2.data('former-value'));
}

function doModifyStudentName()
{
    var lastName = $('#student-last-name').text();
    var firstName = $('#student-first-name').text();

    if(lastName !== '' && firstName !== '') {
        var params = {
            idStudent: $(this).data('id'),
            lastName: lastName,
            firstName: firstName
        }

        $.post("/student/modify", params, function (res) {
            if (res) {
                var elem = $("#student-last-name, #student-first-name");
                uneditable(elem);
                alert("La modification du nom de l'élève a bien été enregistrée.");
            } else {
                alert("Une erreur est survenue lors de la modification de l'élève.");
            }
        });
    } else {
        alert('Le nom et le prénom ne peuvent pas être vides.');
    }
}

// modify island
$('#modify-island').click('on', modifyIsland);
$('#cancel-island').click('on', cancelModifyIsland);
$('#save-island').click('on', doModifyIsland);

function modifyIsland()
{
    var island = $('#showStudent').find('.island');
    editable(island);
}

function cancelModifyIsland()
{
    var island = $('#showStudent').find('.island');
    island.text(island.data('former-value'));
    uneditable(island);
}

function doModifyIsland()
{
    var island = $('#showStudent').find('.island');
    var islandValue = island.text();

    if(islandValue !== '') {
        var params = {
            idStudent: $(this).data('id'),
            islandLabel: islandValue
        }

        $.post("/student/modify", params, function (res) {
            if (res) {
                island.text(islandValue);
                uneditable(island);
                alert("L'ilôt a bien été modifié.");
            } else {
                alert("Une erreur est survenue lors de la modification de l'ilôt.");
            }
        });
    } else {
        alert("Aucun ilôt n'a été enregistré pour cet élève.");
    }
}

function editable(elem)
{
    elem.addClass('pseudo-input');
    elem.attr('contenteditable', 'true');

    var buttons = elem.closest('div').find('button');
    buttons.css('visibility', 'visible');
}

function uneditable(elem)
{
    elem.removeClass('pseudo-input');
    elem.attr('contenteditable', 'false');

    var buttons = elem.closest('div').find('button');
    buttons.css('visibility', 'hidden');
}

// $("#more-comments").click(function() {
//     $(".hidden-comment").toggleClass('display-none');
//
//     if($(this).find('img').attr("src") == "/assets/images/arrow-top.png") {
//         $(this).find('img').attr("src", "/assets/images/arrow-down.png");
//     } else {
//         $(this).find('img').attr("src", "/assets/images/arrow-top.png");
//     }
// });