$(document).ready(function() {
    $('.edit-btn').click(function(e) {
        e.preventDefault();
        var cell = $(this).closest('tr').find('.nb-points');
        cell.attr('contenteditable', true).focus();
    });
});
