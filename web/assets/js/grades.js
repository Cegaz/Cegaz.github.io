// $("#add-skill").click(function() {
//     $("#skill-form").removeClass('hidden');
//     $(this).addClass('hidden');
// });
//
// $("#cancel-skill").click(function() {
//     $("#skill-form").addClass('hidden');
//     $("#add-skill").removeClass('hidden');
// });
//
// $("#save-skill").click(function() {
//     var params = {
//         label: $("#skill-form").find("input").val(),
//     };
//     $.post("/grades/add-skill", params, function(data) {
//         $("#skills-list").append(
//             "<button class='btn class-btn' data-skillid=" + data.skillId + ">" + data.label + "</button>");
//         $("#skill-form").addClass('hidden');
//         $("#add-skill").removeClass('hidden');
//     });
// });
//
// $("#show-skills").click(function() {
//     $.get("/grades/skills", function(data) {
//        $("#show-skills-modal").html(data);
//     });
// })