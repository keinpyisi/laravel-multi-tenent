$(function() {
    var langs = window.Lang;

    axios.get('/api/backend/admin/user')
    .then(response => {
        console.log('Data received:', response.data);
    })
    .catch(error => {
        console.error('There was an error!', error);
    });
    // Select All checkbox
    $('#selectAll').on('change', function() {
        $('tbody input[type="checkbox"]').prop('checked', this.checked);
    });

    // Individual checkbox change
    $('tbody input[type="checkbox"]').change(function() {
        var allChecked = $('tbody input[type="checkbox"]:checked').length === $('tbody input[type="checkbox"]').length;
        $('#selectAll').prop('checked', allChecked);
    });

    // Search functionality
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#dataTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Delete button click event
    $('#delBtn').on('click', function() {
        var selectedIds = [];
        $('tbody input[type="checkbox"]:checked').each(function() {
            selectedIds.push($(this).closest('tr').find('td:eq(1)').text());
        });
        if (selectedIds.length > 0) {
            alert('Selected IDs for deletion: ' + selectedIds.join(', '));
            // Add your delete logic here
        } else {
            alert('Please select items to delete');
        }
    });

    $('#addBtn').on('click', function() {
        Swal.fire({
            icon: 'question',
            title: langs.ask_create.replace(':data', 'ユーザー'),
            html:
                '<input id="loginId" type="text" class="swal2-input" placeholder="'+langs.login_id+'" required>' +
                '<input id="password" type="password" class="swal2-input" placeholder="'+langs.password+'" required>' +
                '<input id="userName" type="text" class="swal2-input" placeholder="'+langs.user_name+'" required>',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: langs.yes,
            cancelButtonText: langs.no,
            customClass: {
                input: 'my-swal-input',
                confirmButton: 'btn btn-primary custom-confirm-button',
                cancelButton: 'btn btn-secondary'
            },
            allowOutsideClick: false,
            allowEscapeKey: false,
            preConfirm: () => {
                // This callback will return false initially, preventing the modal from closing
                return false;
            },
            didOpen: () => {
                const confirmButton = Swal.getConfirmButton(); // Get the confirm button
                if (confirmButton) {
                    confirmButton.addEventListener('click', async () => {
                        var loginId = Swal.getPopup().querySelector('#loginId').value;
                        var password = Swal.getPopup().querySelector('#password').value;
                        var userName = Swal.getPopup().querySelector('#userName').value;
                        // Validate inputs
                        if (!loginId || !password || !userName) {
                            let errorMessage = '';

                            if (!loginId) {
                                errorMessage += langs.no_input.replace(':data', langs.login_id) + '<br>';
                            }
                            if (!password) {
                                errorMessage += langs.no_input.replace(':data', langs.password) + '<br>';
                            }
                            if (!userName) {
                                errorMessage += langs.no_input.replace(':data', langs.user_name) + '<br>';
                            }
                            // Show validation message
                            Swal.showValidationMessage(errorMessage.trim());
                            return; // Keep the modal open if validation fails
                        }
                        // If validation passes, send the API request
                        var data = { login_id: loginId, password: password, user_name: userName };
                        try {
                            const response = await axios.post('/api/backend/admin/users', data);

                            if (response.data.type === 'error') {
                                var errorMessages = response.data.data;
                                var errorMessage = errorMessages.join('<br>');

                                Swal.showValidationMessage(errorMessage.trim());
                                return; // Keep the modal open if validation fails
                            }else{
                                Swal.close();
                            }
                        } catch (error) {
                            console.error(error);
                            Swal.fire('Error!', 'There was an issue with your request.', 'error');
                            return; // Keep the modal open in case of request failure
                        }
                    });
                }
            }
        });
    });

});
