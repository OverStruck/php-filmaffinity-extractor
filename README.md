# php-filmaffinity-extractor
PHP class to extract and parse Filmaffinity.com movie information.
*Para ESPAÑOL leer mas abajo*

* Please see *example-script.php* & *example-page.php* for a working example on how to use this class

## Usage:
```
  //can be a full url or just the id: 486156
  $url = 'https://www.filmaffinity.com/en/film486156.html';
  
  include 'FilmaffinityExtractor.class.php';
  
  //takes a language parameter, defaults to 'es' for SPANISH
  //use 'en' for English
  $extractor = new FilmaffinityExtractor('en');
  
  //outputs a JSON encoded string
  //use a second boolean (TRUE) parameter if you want the result to be returned
  //rather than echoed
  //$info = $extractor->get($url, true); //returns Object instead of string
  $extractor->get($url);
```
![](https://i.imgur.com/1ZMQkl3.gif)

# ESPAÑOL
Clase PHP para extraer y analizar información de la películas de Filmaffinity.com

* Por favor, vea *example-script.php* & *example-page.php* para un ejemplo sobre el uso de esta clase

## USO:
```
  //puede ser una URL completa o sólo la id: 486156
  $url = 'https://www.filmaffinity.com/en/film486156.html';
  
  include 'FilmaffinityExtractor.class.php';
  
  //toma un parámetro de idioma, por defecto 'es' para ESPAÑOL
  //utilizar 'es' de Inglés
  $extractor = new FilmaffinityExtractor('en');
  

  //regresa por defecto una cadena de texto en formato JSON
  //utiliza un segundo booleano (TRUE) si desea que el resultado se devuelva
  //en lugar de ser enviado al navegador usando echo
  //$info = $extractor->get($url, true); //devuelve objeto en lugar de texto
  $extractor->get($url);
```
![](https://i.imgur.com/eWvPkXw.gif)

