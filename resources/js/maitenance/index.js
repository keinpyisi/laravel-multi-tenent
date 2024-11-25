$(function () {
    flatpickr("#maintenance_term-datepicker", {
        mode: 'range',
        enableTime: true,
        dateFormat: "Y-m-d H:i:S",
        time_24hr: true,
        defaultDate: [
            $('#maintenance_term-datepicker').data('start'),
            $('#maintenance_term-datepicker').data('end')
        ]
    });
});