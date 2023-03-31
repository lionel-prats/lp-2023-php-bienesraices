<?php 

// tutorial -> https://www.youtube.com/watch?v=xGaXH7spCyA&t=750s
 
require "includes/funciones.php";

// ***
// https://www.youtube.com/watch?v=xGaXH7spCyA&t=22s
// con expresiones regulares puedo extraer porciones de codigo (por ejemplo, el query string de la URL de arriba)

// una expresion debe estar delimitada por caracteres no alfanumericos, excepto las barras invertidas -> \
// algunos ejemplos -> / * + %
// para evaluar si un patron se cumple usamos la funcion nativa de PHP preg_match($expresion_regular, $string_a_evaluar);
// esta funcion retornara int(1) si el patron se cumple en el string a evaluar, y int(0) si el patron no se cumple

/* 

ALGUNOS METACARACTERES DE LAS EXPRESIONES REGULARES

^ (ALT + 94) -> despues del alfanumerico de apertura
$ -> antes del alfanumerico de cierre
[] -> para encerrar un caracter
\ -> para escapar caracteres
. -> este metacaracter hace referencia a cualquier caracter de cualquier tipo 
\w -> este metacaracter hace referencia a cualquier caracter alfanumerico (letras + numeros + "_")
\W -> este metacaracter hace referencia a cualquier caracter no alfanumerico (todos menos (letras + numeros + "_"))
\d -> este metacaracter hace referencia a cualquier caracter numerico 
\D -> este metacaracter hace referencia a cualquier caracter no numerico 
* -> ver ejemplo #14
+ -> ver ejemplo #15
? -> ver ejemplo #16 - 26'20"
{2} -> ver ejemplo #17 - 28'15"
{2,5} -> ver ejemplo #18 - 29'40"
($val_posible_1|$val_posible_2) -> ver ejemplo #19 - 30'55"
    si el usuario ingreso un valor posible valido podemos saber cual de los posibles ingreso, capturando ese valor en un 3er parametro dentro de preg_match() vvv   
    preg_match("&^Estoy (com|durm)iendo$&i", $string_a_evaluar, $opcion_ingresada) 
    -> ver ejemplo #20 - 33'50"

?: -> ver 
    con este metacaracter seteamos cada uno de los patrones (si los hay) que no querramos recuperar en el 3er. argumento de la funcion preg_match()


*/

//ejemplo 1 - verificar si un substring existe de un string 
//TENER EN CUENTA QUE LAS EXPRESIONES REGULARES SON SENSIBLES A MAYUSCULAS Y MINUSCULAS
//$string = "Hola mundo como estas?";
//$expresion = "|muNdo|";

// ejemplo 2 - verificar si un string empieza con determinado substring 
// para esto agregamos ^ (ALT + 94) luego del delimitador alfanumerico de apertura de la expresion
// ^ este simbolo es llamado un metacaracter
//$string = "https://www.youtube.com.https://";
//$expresion = "|^https://|";

// ejemplo 3 - verificar si un substring existe de un string, indicando que no se consideren mayusculas y minusculas
// para esto agregamos "i" luego del delimitador alfanumerico de cierre de la expresion
//$string = "LiOnElPrAtS@gmail.com";
//$expresion = "|lionelprats|i";

// ejemplo 4 - verificar si un string termina con determinado substring 
// para esto agregamos "$" antes del delimitador alfanumerico de cierre de la expresion
// $ este simbolo es llamado un metacaracter
//$string = "lionelprats@hotmail.com";
//$expresion = "|hotmail.com$|";

// ejemplo 5 - verificar que el string se inicie con el substring "bala", "bola" o "bula" 
// para especificar que un caracter puede ser variable (el 2do. en este ejemplo), lo hacemos con [] y dentro de los mismos definimos los valores posibles
// [valorposible1 valorposible2 valorposibleN]
// $ este simbolo es llamado un metacaracter
//$string = "Bila";
//$expresion = "|^b[aou]la|i";

//ejemplo 6 - verificar que al final de un string termina con un numero o una letra 
//$string = "mantisreligios8#";
//$expresion = "|[a-z0-9]$|i";

//ejemplo 7 - verificar un string no termine en numero
//para esto agregamos el metacaracter ^ inmediatamente despues del corchete de apertura
//en este caso decimos que ^ funciona como NEGACION
//$string = "hol9";
//$expresion = "|hol[^0-9]|i";

//ejemplo 8 - escapar metacaracteres
//en este ejemplo, queremos verificar si un string termina en numero o en "]"
//para esto agregamos el metacaracter "\" (utilizado para escapar caracteres) antes del metacaracter "]"
//de esta manera, PHP sabra que tiene que interpretar "]" como un caracter normal y no como un metacaracter
//$string = "hol%";
//$expresion = "|hol[0-9\]]|i";

//ejemplo 9 - metacaracter "."
//este metacaracter hace referencia a cualquier caracter de cualquier tipo 
//en este ejemplo, queremos indicar que esperamos recibir un string especifico, con el ultimo caracter variable
//$string = "hol$";
//$expresion = "|^hol.$|i";

//ejemplo 10 - metacaracter "\w"
//este metacaracter hace referencia a cualquier caracter alfanumerico (letras + numeros + "_")
//$string = "hol%";
//$expresion = "|^hol\w$|i";

//ejemplo 11 - metacaracter "\W"
//este metacaracter hace referencia a cualquier caracter no alfanumerico (todos menos (letras + numeros + "_"))
//$string = "hol_";
//$expresion = "|^hol\W$|i";

//ejemplo 12 - metacaracter "\d"
//este metacaracter hace referencia a cualquier caracter numerico 
//$string = "hol5";
//$expresion = "|^hol\d$|i";

//ejemplo 13 - metacaracter "\D"
//este metacaracter hace referencia a cualquier caracter no numerico 
//$string = "hol-";
//$expresion = "|^hol\D$|i";

//ejemplo 14 - metacaracter "*"
//este metacaracter hace referencia a que el caracter inmediatamente anterior (en este ejemplo "a") puede existir o no, y si existe puede ser unico o repetirse 1 o mas veces
// (en la primera explicacion no le encontre sentido a la funcionalidad...
//$string = "holaaaaaaa";
//$expresion = "|^hola*|i";

//ejemplo 15 - metacaracter "+"
//este metacaracter hace referencia a que el caracter inmediatamente anterior (en este ejemplo "a") tiene que existir, y puede repetirse una o mas veces
//$string = "holaaaaaaaaaaaaaaaaaas";
//$expresion = "|^hola+$|i";

//ejemplo 16 - 26'20" - metacaracter "?"
//este metacaracter hace referencia a que el caracter inmediatamente anterior (en este ejemplo "s") puede o no existir
//$string = "http://";
//$expresion = "|^https?://$|i";

//ejemplo 16.2
// aca uso el metacaracter "?" para definir que el string "hola" es opcional (puede existir o no)
//$string = " mundo";
//$expresion = "%^(hola)? mundo%i"; 
//debuguear(preg_match($expresion, $string));

//ejemplo 17 - 28'15" - metacaracter "{int}"
//este metacaracter indica que el caracter inmediatamente anterior debe repetirse la cantidad de veces especificada dentro de las {int}
//$string = "hoooola";
//$expresion = "|^ho{3}la$|i";

//ejemplo 18 - 29'40" - metacaracter "{int,int}"
//este metacaracter indica que el caracter inmediatamente anterior debe repetirse dentro del rango especificado dentro de las llaves {int,int}
//$string = "hola";
//$expresion = "|^ho{2,5}la$|i";

//ejemplo 19 - 30'55" - metacaracter "(|)"
//este metacaracter para subparametros opcionales
//$string = "Estoy viniendo";
//$expresion = "&^Estoy (com|durm)iendo$&i";

//ejemplo 20 - 33'50" - capturar un subpatron
//si el el subpatron existe en el string (preg_match() === 1) en $matches se almacenará un array con el string completo ingresado, y la opcion valida que ingreso el usuario
//$string = "Estoy comiendo";
//$expresion = "&^Estoy (com|durm)iendo$&i";
//echo "<pre>";
//var_dump(preg_match($expresion, $string, $matches));
//var_dump($matches);
//echo "</pre>";

// EJEMPLO PRACTICO
// ESPERAMOS POR UN ENLACE A YOU TUBE
// VALIDACION DE QUE EL ENLACE RECIBIDO SEA VALIDO
// la url en $url la obtuve del tutorial, haciendo click en "compartir" y copiando la URL proporcionada
$url = "http://youtu.be/xGaXH7spCyA";
//$url = "https://www.youtube.com/watch?v=0123456789123";
//$url = "https://www.youtube.com/watch?v=pQALDx409qw";

$patron = "%^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com/watch\?v=)(\w{10,12})%i";
// (https?://)? -> aca seteo que la "s" es opcional y que "http://" o "https://" al inicio del string tambien es opcional
// (www\.)? -> aca seteo que "www." es opcional
// (www\.)? -> aca seteo que "www." es opcional
// (youtu\.be/|youtube\.com/watch\?v=) -> aca seteo que esperamos "youtu.be/" o "youtube.com/watch?v="
// (\w{10,12}) -> aca seteo que esperamos entre 10 y 12 caracteres alfanumericos (nros + letras + "_")
// ?: -> con esto determino que no quiero guardar en el 3er. argumento de preg_match() un determinado patron -> (?:$patron)

echo "<pre>";
var_dump(preg_match($patron, $url, $matches));
var_dump($matches);
//var_dump($matches[1]);
//var_dump($matches[2]);
//var_dump($matches[3]);
//var_dump($matches[4]);
echo "</pre>";

//debuguear($expresion);
//debuguear(preg_match($expresion, $string));