$(function() {
  $.datetimepicker.setLocale('fr');
  $('.js-datepicker').datetimepicker({
    disabledWeekDays: [0,2],
    timepicker: false,
    format: 'Y-m-d',
    autoclose: true,
    minDate: 0
    });
});