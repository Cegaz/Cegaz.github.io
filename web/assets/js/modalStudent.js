// modify island
$('#modify-island').click('on', modifyIsland);
$('#cancel-island').click('on', cancelModifyIsland);
$('#save-island').click('on', doModifyIsland);

function modifyIsland()
{
    let island = $(this).closest('div').find('.island');
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
        let params = {
            idStudent: $(this).data('id'),
            islandLabel: islandValue
        }

        $.post("/student/modify", params, function (res) {
            if (res) {
                island.text(islandValue);
                uneditable(island);
                alert("The island has been modified.");
            } else {
                alert("An error occurred during the island modification.");
            }
        });
    } else {
        alert("No island is registered for this student.");
    }
}

// sanctions on student modale : display more
$("#more-sanctions").click(function() {
    $(".hidden-sanction").toggleClass('display-none');

    if($(this).find('img').attr("src") == "/assets/images/arrow-top.png") {
        $(this).find('img').attr("src", "/assets/images/arrow-down.png");
    } else {
        $(this).find('img').attr("src", "/assets/images/arrow-top.png");
    }
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

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function validatePhone(phone) {
    var re = /^0[1-79][\s\.-]?([0-9][\s\.-]?){8}$/;
    return re.test(phone);
}

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
