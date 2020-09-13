let inputs = [  document.getElementById('registration_form_password_first'),
                document.getElementById('registration_form_password_second')];
console.log(first.type);
function check(input) {
    if (inputs[0].value != inputs[1].value) {
        inputs.forEach(inp => {
            inp.setCustomValidity('Password Must be Matching.')
        });
        input.reportValidity();
    } else {
        inputs.forEach(inp => {
            inp.setCustomValidity('')
        });
    }
}