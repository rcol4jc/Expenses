$(document).ready(function () {
   $('#datepicker').datepicker({changeMonth: true, changeYear: true});
   $('#totals-start-date').datepicker({changeMonth: true, changeYear: true});
   $('#totals-end-date').datepicker({changeMonth: true, changeYear: true});
   $('#index_start_date').datepicker({changeMonth: true, changeYear: true});
   $('#index_end_date').datepicker({changeMonth: true, changeYear: true});


   //When the amount field in the add expense field loses focus
   $("input[name='amount']").blur(function(e) {
      //Get current value of the field
      let currentVal=$(this).val();

      //Clear the span that gives javascript feedback
      $('#amount_feedback').html('');

      //Regex to verify is a valid dollar amount
      let r=/^\$?[0-9]+(\.[0-9][0-9])?$/;

      //test the value with the regex and if not valid, put the info into the amount_feedback
      if (!r.test(currentVal)) {
         $('#amount_feedback').html('This is not a dollar amount');
      }
   });

});