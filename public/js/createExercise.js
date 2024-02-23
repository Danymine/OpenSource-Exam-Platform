document.addEventListener("DOMContentLoaded", function() {
    var radios = document.querySelectorAll('input[type=radio][name=type]');
    radios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            var selectedOption = this.value;
            switch(selectedOption) {
                case 'Risposta Aperta':

                    document.getElementById('risposta-aperta').style.display = "block";
                    document.getElementById('risposta-multipla').style.display = "none";
                    document.getElementById('vero-falso').style.display = "none";
                    break;
                case 'Risposta Multipla':

                    document.getElementById('risposta-aperta').style.display = "none";
                    document.getElementById('risposta-multipla').style.display = "block";
                    document.getElementById('vero-falso').style.display = "none";
                    break;
                case 'Vero o Falso':
                    
                    document.getElementById('risposta-aperta').style.display = "none";
                    document.getElementById('risposta-multipla').style.display = "none";
                    document.getElementById('vero-falso').style.display = "block";
                    break;
                default:
                    explanationText = '';
            }
        });
    });

});