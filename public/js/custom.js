$(document).ready(function () {
    // Validation custom methods
    $.validator.addMethod("CustomEmail", function (value, element) {
        return value.match(/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i);
    }, "Please specify a valid email address.");
    $.validator.addMethod("inputCheck", function (value, element, param) {
        return value.match(new RegExp("." + param + "$"));
    });
    $.validator.addMethod("PhoneCheck", function (value, element, param) {
        return value.match(new RegExp("." + param + "$"));
    });

    // Add validation to the consent form
    $('#consentForm').validate({
        rules: {
            consent: "required"
        },
        messages: {
            consent: {
                required: "Select the checkbox to accept Terms & Conditions"
            }
        }
    });
    // Validate login form
    $('#loginForm').validate({
        rules: {
            email: "required",
            password: "required"
        }
    });

    // Add validation to the registration form
    $('#RegisterForm').validate({
        rules: {
            email: {
                required: true,
                CustomEmail: true
            },
            password: {
                required: true,
                minlength: 8
            },
            confirm_password: {
                required: true,
                equalTo: '#password'
            },
            name: {
                required: true,
                inputCheck: "[a-zA-Z]+"
            },
            organisation_id: {
                required: true
            },
            mobile: {
                required: true,
                PhoneCheck: "([0-9]{10})|(\([0-9]{3}\)\s+[0-9]{3}\-[0-9]{4})"
            }
        },
        messages: {
            name: {
                inputCheck: "Please enter only letters and space"
            },
            mobile: {
                PhoneCheck: "Please enter a valid contact number"
            }
        }
    });

    // Add validation to the my profile form
    $('#profileForm').validate({
        rules: {
            email: {
                required: true,
                CustomEmail: true
            },
            name: {
                required: true,
                inputCheck: "[a-zA-Z]+"
            },
            organisation_id: {
                required: true
            },
            phone: {
                required: false

            },
            mobile: {
                required: true,
                PhoneCheck: "([0-9]{10})|(\([0-9]{3}\)\s+[0-9]{3}\-[0-9]{4})"
            }
        },
        messages: {
            name: {
                inputCheck: "Please enter only letters and space"
            },
            phone: {
                PhoneCheck: "Please enter a valid contact number"
            }
        }
    });

    // Validate lost password form
    $('#lostPasswordForm').validate({
        rules: {
            email: {
                required: true,
                CustomEmail: true
            }
        }
    });

    // Make Organisation name mandatory for "Professonal" users
    if (typeof $('#organisation_id').val() !== 'undefined') {
        var val = $('#organisation_id').val();
        if ($.trim(val).length != 0 && val == "1") {
            $('#organisation_name').rules('add', 'required');
        } else {
            $('#organisation_name').rules('remove', 'required');
            $('#organisation_name-error').hide();
        }
    }
    $('#organisation_id').on('change', function () {
        if ($.trim($(this).val()).length != 0 && $.trim($(this).val()) == "1") {
            $('#organisation_name').rules('add', 'required');
        } else {
            $('#organisation_name').rules('remove', 'required');
            $('#organisation_name-error').hide();
        }
    });
    
});

