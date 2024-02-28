var translations = {
    'it': {
        'future_date_error': "Wow!!!!Vieni dal futuro!",
        'invalid_email_error': "Email non valida. Assicurati che l'email contenga una '@', seguita da un dominio valido. Evita l'uso di caratteri speciali consecutivi. L'email deve essere lunga da un minimo di 8 a un massimo di 40 caratteri.",
        'invalid_password_error': "Password non valida. Assicurati che contenga almeno una lettera maiuscola, almeno un numero e che abbia una lunghezza minima di 8 caratteri.",
        'password_mismatch_error': "Le password non corrispondono. Assicurati di inserire la stessa password in entrambi i campi."
    },
    'en': {
        'future_date_error': "Wow!!!! You're from the future!",
        'invalid_email_error': "Invalid email. Make sure the email contains an '@', followed by a valid domain. Avoid the use of consecutive special characters. The email must be between 8 and 40 characters long.",
        'invalid_password_error': "Invalid password. Make sure it contains at least one uppercase letter, one number, and has a minimum length of 8 characters.",
        'password_mismatch_error': "Passwords do not match. Make sure to enter the same password in both fields."
    }
};

function mostraPassword(id) {
    var campoPassword = document.getElementById("" + id);
    campoPassword.type = "text";
}

function nascondiPassword(id) {
    var campoPassword = document.getElementById("" + id);
    campoPassword.type = "password";
}

var date_birth = document.getElementById('datebirth');
var email = document.getElementById('email');
var password = document.getElementById('password');
var confirmation = document.getElementById('password_confirmation');

var currentURL = window.location.href;
var languageIndex = currentURL.indexOf('/en/');

// Se la lingua è presente nell'URL
if (languageIndex !== -1) {

    language = 'en';
} else {

    // Se la lingua non è 'en', impostala su 'it'
    language = 'it';
}


date_birth.addEventListener('input', function (){
    
    var feedback = document.getElementById('feedback-date-validate'); 
    var inputDate = new Date(date_birth.value);
    var currentDate = new Date();
    
    if (inputDate > currentDate) {

        feedback.textContent = translations[language]['future_date_error'];
        feedback.style.display = 'block';
        feedback.style.color = 'red';
    } 
    else{
        
        feedback.textContent = '';
        feedback.style.display = 'none';
    }
});


email.addEventListener('input', function (){
    
    var feedback = document.getElementById('feedback-email-validate'); 
    if( email.value.length != 0 ){

        var regex = /^(?!.*[_.-]{2})[a-zA-Z0-9_.-]{4,}@(?!-)(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/;
        if( email.value.match(regex) ){

            feedback.textContent = '';
            feedback.style.display = 'none';
        }
        else{
            
            feedback.textContent = translations[language]['invalid_email_error'];
            feedback.style.display = 'block';
            feedback.style.color = 'red';
        }
    }
    else{

        feedback.textContent = '';
        feedback.style.display = 'none';
    }      
});

password.addEventListener('input', function(){

    var feedback = document.getElementById('feedback-password-validate');
    if( password.value.length != 0 ){

        var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        if( password.value.match(regex) ){

            feedback.textContent = '';
            feedback.style.display = 'none';
        }
        else{

            feedback.textContent = translations[language]['invalid_password_error'];
            feedback.style.display = 'block';
            feedback.style.color = 'red';
        }
    }
    else{

        feedback.textContent = '';
        feedback.style.display = 'none';
    }
});

confirmation.addEventListener('input', function(){

    var feedback = document.getElementById('feedback-confirmation-validate');
    if( confirmation.value.length != 0 ){

        if( password.value === confirmation.value ){

            feedback.textContent = '';
            feedback.style.display = 'none';

        }
        else{

            feedback.textContent = translations[language]['password_mismatch_error'];
            feedback.style.display = 'block';
            feedback.style.color = 'red';
        
        }
    }
    else{

        feedback.textContent = '';
        feedback.style.display = 'none';
    }
});