$(document).ready(function () {
    $('#phone_hidden').value = $('#phone').value;
    $('#phone').on('change', function (e) {
       $('#phone_hidden').value = $('#phone').value;
    });
});

