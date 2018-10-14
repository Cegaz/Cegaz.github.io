$("#students-list").find(".hand-up").click(function () {
    var student_id = $(this).closest('td').data("id");
    var params = {student_id: student_id};
    var element = $(this).parent().parent();

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
            var element = $(".right-columns[data-id='"+data.studentId+"']").parent();
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

$('.confirm-modal-button').on('click', function () {
    var td = $(this).closest('td');
    var studentId = td.attr('data-id');
    var name = td.data('name').toUpperCase();
    var surname = td.data('surname').replace(/\b\w/g, l => l.toUpperCase());
    $('#confirm-modal').find('.modal-body').html('<p>Are you sure <span class="bold">' + surname + ' ' + name + '</span> is absent ?</p>');
    $('.is-absent').attr('data-id', studentId);
});

$('.is-absent').on('click', function() {
    var studentId = $(this).attr('data-id');
    $('#confirm-modal').modal('hide');

    var line = $('#students-list').find('*[data-id="'+studentId+'"]').closest('tr');
    $.post("/absence/new", {student_id: studentId}, function(result) {
        if (result.success) {
            line.remove();
            alert(result.student + " is registered as absent on the " + result.date + '.');
        } else {
            alert(result.student + ' has already been registered as absent on the ' + result.date);
        }
    });
});

$('#are-absent').on('click', function() {
    $('.confirm-modal-button').toggleClass('hidden');
});

$('#absence-alert').on('click', function() {
    $('.confirm-modal-button').removeClass('hidden');
    $('.confirm-modal-button').addClass('blink');
});

$('#display-sanction').on('click', function () {
    $('.sanctionModalButton').toggleClass('hidden');
});

$('.sanctionModalButton').on('click', function () {
    var td = $(this).closest('td');
    var studentId = td.attr('data-id');
    var name = td.data('name').toUpperCase();
    var surname = td.data('surname').replace(/\b\w/g, l => l.toUpperCase());
    $('#sanctionModal').find('.modal-header').html('<p>Add a sanction for <span class="bold">' + surname + ' ' + name + '</span> :</p>');
    $('#sanctionModal').find('#appbundle_sanction_student').val(studentId);
});

$('#save-sanction-done').on('click', function() { //TODO CG pb ne marche que la 1è fois...
   var checkedInput = $('#show-sanctions-modal').find('input[name="sanction-done"]:checked');

   var sanctionsId = [];
   for (var i=0; i < checkedInput.length; i++) {
       sanctionsId.push($(checkedInput[i]).data('id'));
   }

    $('#show-sanctions-modal').modal('hide');
    // $('.modal-backdrop').remove();
    // $('.modal').remove();

    if (sanctionsId.length > 0) {
       $.post("/sanction/update", {sanctionsId: sanctionsId}, function (result) {
           $('#above-show-sanctions-modal').html(result.html);
           $('#sanctions-alert').find('.badge').text(result.nbSanctions);
       });
   }
});