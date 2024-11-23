$(document).ready(function() {
    console.log("jQuery is working!");
    $('#delete_btn').click(function() {
        // Perform an action when the button is clicked
        Swal.fire({
            title: 'Error!',
            text: 'Do you want to continue',
            icon: 'error',
            confirmButtonText: 'Cool'
        })
    });
});
