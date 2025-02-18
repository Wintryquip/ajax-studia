<?php
include_once("../klasy/Strona.php");
//wykorzystaj lekko zmodyfikowane wcześniej tworzone funkcje
//pomocnicza funkcja generująca formularz:
function drukuj_form() {
    $zawartosc = "<h1> Formularz zamowienia </h1>";
    $zawartosc .= '<div id="tresc">
 <form method="POST" >
 <table> <label><tr> <td> Nazwisko </td>  <td> <input type="text" name="surname"></td></tr> </label>
         <label><tr> <td> Wieku </td>  <td> <input type="number" name="age"></td></tr> </label>
         <label><tr> <td> Państwo </td> <td> <select name="country"><br>
            <option value="Polska">Polska</option>
            <option value="Niemcy">Niemcy</option>
            <option value="Dania"> Dania</option>
        </select> </td> </tr> </label>
        <label> <tr> <td> Adres e-mail (wymagane) </td> <td> <input type="email" name="email"></td></tr> </label>
        <tr> <label><td> <h2> Zamawiam tutorial z języka: </h2> </td> </label> </tr>
        <tr> <td> <label><input type="checkbox" name="lang[]" value="PHP"/> PHP </label>
                  <label><input type="checkbox" name="lang[]" value="C/C++"/> C/C++ </label>
                  <label><input type="checkbox" name="lang[]" value="Java"/> Java </label>
                  </td> </tr>
        <tr>  <label> <td> <h2> Sposób zapłaty </h2> </td> </label> </tr>
        <tr> <td> <label><input type="radio" name="paymentMethod" value="Master Card"/> Master Card </label>
                          <input type="radio" name="paymentMethod" value="Visa"/> Visa </label>
                          <input type="radio" name="paymentMethod" value="przelew bankowy"/> przelew bankowy </label>
                          </td> </tr>
        <tr> <td> <input type="submit" name="submit" id="Dodaj" value="Dodaj"/>
                  <input type="submit" name="submit" id="Pokaz" value="Pokaż"/>
                  <input type="reset" value="Anuluj"/></td></tr>
                          </table>
 </form>
 </div> ';
    return $zawartosc;
}
function walidacja() { //jak poprzednio – tylko usunąć polecenia echo
    $args = ['surname' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^[A-Z]{1}[a-ząęłńśćźżó-]{1,25}$/']],
        'age' => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 1]],
        'country' => ['filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS],
        'email' => ['filter' => FILTER_SANITIZE_EMAIL],
        'lang' => ['filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'flags' => FILTER_REQUIRE_ARRAY],
        'paymentMethod' => ['filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS]];
    $data = filter_input_array(INPUT_POST, $args);
    //var_dump($data);
    $errors = "";
    foreach ($data as $key => $value) {
        if ($value === false or $value === NULL) {
            $errors .= $key . " ";
        }
    }
    return $errors;
}
function dodajdoBD($bd) { //funkcja powinna zwracać łańcuch z komunikatem
//czy udało się czy nie dodać dane do bazy – reszta bez zmian
    $args = [
        'surname' => [
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => ['regexp' => '/^[A-Z]{1}[a-ząęłńśćźżó-]{1,25}$/']
        ],
        'age' => ['filter' => FILTER_VALIDATE_INT, 'options' => ['min_range' => 1]],
        'country' => ['filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS],
        'email' => ['filter' => FILTER_VALIDATE_EMAIL],
        'lang' => ['filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS, 'flags' => FILTER_REQUIRE_ARRAY],
        'paymentMethod' => ['filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS]
    ];

    $data = filter_input_array(INPUT_POST, $args);
    //var_dump($data);

    $errors = "";
    foreach ($data as $key => $value) {
        if ($value === false or $value === NULL) {
            $errors .= $key . " ";
        }
    }

    if ($errors == "") {
        $surname = $data['surname'];
        $age = $data['age'];
        $country = $data['country'];
        $email = $data['email'];

        if (is_array($data['lang'])) {
            $lang = implode(', ', $data['lang']);
        } else {
            $lang = $data['lang'];
        }

        $paymentMethod = $data['paymentMethod'];

        $sql = "INSERT INTO klienci (Nazwisko, Wiek, Panstwo, Email, Zamowienie, Platnosc) 
                VALUES ('$surname', $age, '$country', '$email', '$lang', '$paymentMethod')";

        if ($bd->insert($sql)) {
            echo 'Zapytanie wysłane prawidłowo.';
        } else {
            echo 'Błąd w wysyłaniu zapytania';
            return 0;
        }
    } else {
        echo "<br> Niepoprawne dane: " . $errors;
        return 0;
    }
    return 1;
}
//uchwyt do bazy klienci:
include_once "../klasy/Baza.php";
$tytul = "Formularz zamówienia";
$zawartosc = drukuj_form();
$bd = new Baza("localhost", "root", "", "klienci");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $akcja = $_POST["akcja"];
    switch($akcja) {
            case "Pokaż": $zawartosc.= $bd->select("select * from klienci",
                            ["Email", "Zamowienie"]); break;
            case "Dodaj": unset($_POST["akcja"]); dodajdoBD($bd); break;
    }
}

echo $zawartosc;