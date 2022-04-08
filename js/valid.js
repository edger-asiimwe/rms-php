function validateForm() {
    const form = document.getElementById('form');
    const username = document.getElementById('username');
    const password = document.getElementById('password');

    console.log(username.value);
    console.log(password.value);

    if(username.value === '') {
        alert ('Username is required');
        return false;
    }

    if(password.value === '') {
        alert ('Password is required');
        return false;
    }

    if(password.value.length < 6) {
        alert ('Password must be at least 6 character.');
        return false;
    }

    return true;

}