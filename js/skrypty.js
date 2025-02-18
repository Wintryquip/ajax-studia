document.addEventListener('DOMContentLoaded', () => {
    var bonas = document.getElementById('onas');
    bonas.addEventListener("click", () => {
        console.log("Strona O nas");
        pokazOnas();
    });
    var bgaleria = document.getElementById('galeria');
    bgaleria.addEventListener("click", () => {
        console.log("Galeria zdjęć");
        pokazGalerie();
    });
    var bform = document.getElementById('formularz');
    bform.addEventListener("click", () => {
        console.log("Formularz zamówienia");
        pokazForm();
    });

    var bkontakt = document.getElementById('index');
    bkontakt.addEventListener("click", () => {
        console.log("Informacje kontaktowe");
        pokazKontakt();
    });
});

function pokazOnas() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/lab11/skrypty/onas.php', true);
    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById('main').innerHTML = this.responseText;
        } else {
            console.log('Coś poszło nie tak!');
        }
    };
    xhr.onerror = function() {
        console.log('Błąd podczas ładowania strony O nas');
    };
    xhr.send();
}

function pokazGalerie() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/lab11/skrypty/galeria.php', true);
    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById('main').innerHTML = this.responseText;
        } else {
            console.log('Coś poszło nie tak!');
        }
    };
    xhr.onerror = function() {
        console.log('Błąd podczas ładowania Galerii');
    };
    xhr.send();
}

function pokazForm() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/lab11/skrypty/formularz.php', true);
    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById('main').innerHTML = this.responseText;

            const form = document.querySelector('form');
            if (form) {
                // Obsługa zdarzenia submit dla formularza
                form.addEventListener('submit', function(event) {
                    event.preventDefault(); // Zatrzymuje domyślną akcję formularza
                });
                // Pobieranie przycisków na podstawie ich id
                var dodajButton = document.getElementById('Dodaj');
                var pokazButton = document.getElementById('Pokaz');

                // Obsługa kliknięcia przycisku "Dodaj"
                if (dodajButton) {
                    dodajButton.addEventListener('click', function() {
                        console.log("Przycisk 'Dodaj' został kliknięty");

                        // Zbieranie danych z formularza
                        const formData = new FormData(form);
                        formData.append('akcja', dodajButton.value);

                        // Tworzenie zapytania AJAX typu POST
                        const xhrPost = new XMLHttpRequest();
                        xhrPost.open('POST', '/lab11/skrypty/formularz.php', true);
                        xhrPost.onload = function() {
                            if (xhrPost.status === 200) {
                                document.getElementById('main').innerHTML = xhrPost.responseText;
                            } else {
                                console.log('Błąd podczas wysyłania danych!');
                            }
                        };
                        xhrPost.onerror = function() {
                            console.log('Błąd sieci podczas wysyłania danych!');
                        };
                        xhrPost.send(formData); // Wysłanie danych formularza
                    });
                }

                // Obsługa kliknięcia przycisku "Pokaż"
                if (pokazButton) {
                    pokazButton.addEventListener('click', function() {
                        console.log("Przycisk 'Pokaż' został kliknięty");


                        const formData = new FormData();
                        formData.append('akcja', pokazButton.value);
                        // Tworzenie zapytania AJAX typu GET, aby pobrać dane
                        const xhrGet = new XMLHttpRequest();
                        xhrGet.open('POST', '/lab11/skrypty/formularz.php', true);
                        xhrGet.onload = function() {
                            if (xhrGet.status === 200) {
                                document.getElementById('main').innerHTML = xhrGet.responseText;
                            } else {
                                console.log('Błąd podczas pobierania danych!');
                            }
                        };
                        xhrGet.onerror = function() {
                            console.log('Błąd sieci podczas pobierania danych!');
                        };
                        xhrGet.send(formData); // Wysłanie żądania
                    });
                }
            }
        } else {
            console.log('Coś poszło nie tak!');
        }
    };
    xhr.onerror = function() {
        console.log('Błąd podczas ładowania Formularza');
    };
    xhr.send(); // Wysłanie żądania GET, aby pobrać formularz
}


function pokazKontakt() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/lab11/skrypty/glowna.php', true);
    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById('main').innerHTML = this.responseText;
        } else {
            console.log('Coś poszło nie tak!');
        }
    };
    xhr.onerror = function() {
        console.log('Błąd podczas ładowania strony Kontakt');
    };
    xhr.send();
}