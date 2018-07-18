/* scripts for Question Answering project */
// series List Toggle
$('.question').click(function () {
    var qID = $(this).attr('id');
    $('#' + qID + ' .a').slideToggle(200);
    var p = $('#' + qID + ' .i').html();
    if (p === '+') {
        $('#' + qID + ' .i').html('-');
    } else {
        $('#' + qID + ' .i').html('+');
    }
});
$(".qManage .qm,.question .r").click(function (e) {
    e.stopPropagation();
});

