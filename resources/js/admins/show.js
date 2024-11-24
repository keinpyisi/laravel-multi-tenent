$(function() {
    var langs = window.Lang;
    $('.basic_reset_btn').on('click', function(e) {
        e.preventDefault(); // Prevent the form from submitting immediately
        var tenant = $(this).data('id');
        // Perform an action when the button is clicked
        Swal.fire({
            title: langs.confirmed,
            text: langs.reset_question.replace(':tenant', tenant).replace(':what', "Basic認識"),
            icon: 'warning',
            showCancelButton: true, // Show cancel button for confirmation
            confirmButtonText: langs.yes,
            cancelButtonText: langs.no
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user clicks 'Yes', submit the form
                $(this).closest('form').submit(); // Submit the form manually
            }
        });
    });
});
