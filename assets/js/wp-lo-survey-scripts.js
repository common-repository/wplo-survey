// HTML 5 Form Validation for LO forms in WP

// Display red border around invalid inputs
jQuery(document).ready(function(){
    var invalidClassName = 'invalid';
    var inputs = Array.prototype.slice.call(document.querySelectorAll('input, select, textarea'));
    inputs.forEach(function (input) {
        // Add a css class on submit when the input is invalid.
        input.addEventListener('invalid', function () {
            input.classList.add(invalidClassName)
        });

        // Remove the class when the input becomes valid.
        // 'input' will fire each time the user types
        input.addEventListener('input', function () {
            if (input.validity.valid) {
                input.classList.remove(invalidClassName)
            }
        })
    });

    if (jQuery("#cons_birth_date").length){
        var consDateInput = jQuery( "#cons_birth_date" );
        consDateInput.on('keypress',function () {
            return false;
        });
        consDateInput.attr('autocomplete', 'off');
        consDateInput.datepicker({
            dateFormat: "yy-mm-dd",
            changeYear: true,
            changeMonth: true,
            yearRange: '1900:+2',
        });
        consDateInput.on('change',function () {
            if (consDateInput.valid()) {
                consDateInput.removeClass("invalid");
            }
        });
    }

    jQuery('.datequestion').each(function() {
        var dateQuestion = jQuery(this);
        dateQuestion.on('keypress',function () {
            return false;
        });
        dateQuestion.attr('autocomplete', 'off');
        dateQuestion.datepicker({
            dateFormat: "yy-mm-dd",
            changeYear: true,
            changeMonth: true,
            yearRange: '1900:+2',
        });
        dateQuestion.on('change',function () {
            if (dateQuestion.valid()) {
                dateQuestion.removeClass("invalid");
            }
        });
    });


});
