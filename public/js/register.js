let first = document.getElementById('registration_form_password_first'),
    second = document.getElementById('registration_form_password_second');
console.log(first.type);
function check(input) {
    if (first.value != second.value) {
        first.setCustomValidity('Password Must be Matching.');
        second.setCustomValidity('Password Must be Matching.');
        input.reportValidity();
    } else {
        first.setCustomValidity('');
        second.setCustomValidity('');
    }
}