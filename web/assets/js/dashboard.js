$(".dashboard").find(".student-row").click(function() {
    var student_id = $(this).data("id");

    $.get("/student/show/"+student_id, function(data) {
        $("#showStudent").html(data);
    });
});

// // pour régler le pb du 2è clic sur modale
// $('#showStudent').on('hide.bs.modal', function () {
//     $(this).off('hide.bs.modal');
// }); //TODO CG bofbof

// $("#comments").find("#add-comment").click(function() {
//     $("#comment-form").css('display','block');
//     $("#add-comment").css('display','none');
// });
//
// $("#comments").find("#cancel-comment").click(function() {
//     $("#comment-form").css('display', 'none');
//     $("#add-comment").css('display','block');
// })

// $("#save-comment").click(function() {
//     var params = {
//         text: $("#comment-form").find("#input-text").val(),
//         studentId: $("#comment-form").find("#input-id").val()
//     };
//     $.post("/comment/add", params, function(data) {
//         $("#comments-list").append("<p>" +
//             data.text +
//             "<br><span class='font-italic'>" +
//             data.date);
//         $("#comment-form").css('display','none');
//         $("#add-comment").css('display','block');
//     });
// });

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