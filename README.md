# FormGenerator
Prosty generator formularzy z podstawową walidacją
## Uruchomienie
Pobierz wtyczkę i dodaj ją forderu plugins w twoim motywie. Następnie aktywuj wtyczkę z poziomu admina w WordPress<br>
![image](https://github.com/user-attachments/assets/9a0e52a8-c002-4658-8f56-96590d5248d1)<br>
## Jak działa generator w edytorze
Po dodaniu wtyczki FormGenerator jest dostępny jako blok w edytorze<br>
![image](https://github.com/user-attachments/assets/82ebe39c-624c-4eaa-94b4-965b412afc66)<br>
Po dodaniu pojawia się blok generatora<br>
![image](https://github.com/user-attachments/assets/0108e987-5011-4b61-a773-0d346ae05483)<br>
Można w nim dodać action dla formularza, oraz pojedyńcze inputy, po dodaniu inputu pojawia się on wyżej, można go usunąć<br>
![image](https://github.com/user-attachments/assets/ed7a0058-b420-427b-bda7-73f32968585d)<br>
Nie można ddodać pustego inputu, pojawia się error<br>
![image](https://github.com/user-attachments/assets/0667ef41-8d04-435e-8ffa-06ccf0d88e1b)<br>
## Jak działa formularz na stronie
Do strony zostaje dodany prosty formularz<br>
![image](https://github.com/user-attachments/assets/8ac06a60-0e23-4132-828f-8592da6637c0)<br>
Po wysyłce pod nim pojawia się wiadomość, czy dane zostały przesłane do endpointu<br>
Error<br>
![image](https://github.com/user-attachments/assets/5dfba851-9433-4c88-b53a-a9556296855e)<br>
Poprawne dane<br>
![image](https://github.com/user-attachments/assets/34a73a97-d5f3-4f94-8cc4-29b849e8e3aa)
## Blok generatora
Blok został stworzony przy wykorzystaniu Komponentów WordPressa
## Generowanie formularza
Formularz jest generowany po stronie PHP, w pliku _render.php_.
Do dynamicznego tworzenia inputów wykorzystałam wzorzez projektowy Builder, najbardziej odpowiada temu co chcę osiągnąć, w przyszłości rozbudowa kodu będzie łatwiejsza
Renderowanie odbywa się w funkcji _fg_form_generator_render()_<br>
![image](https://github.com/user-attachments/assets/d27f3167-50cc-42b6-80eb-af6bb62f57a7)<br>
Pobieran zapisane i stworzone inputy z atrybutów bloku, następnie za pomocą pętli buduję je przygotowaną mechaniką
### InputBuilder
Przyjmuje przesłane dane, po czym buduje z nich input. Obecnie rozróżniam typ textarea i input, w przyszłości można zozbudować builder o kilka funkcji build(), na przykład dla typu select, żeby to zrobić należy rozbudować również blok generatora<br>
![image](https://github.com/user-attachments/assets/db53a1f2-2da0-4b08-8e65-5f423cac2092)
### Director
Inputy tworze poprzez Director'a<br>
![image](https://github.com/user-attachments/assets/9e62af59-a0be-491f-86c3-81f6f611b797)
## Wysyłka i walidacja formularza
### Customowy skrypt do wysyłki formularza
Do formularza endpoint końcowy jest przekazywany za pomocą action, żeby móc przed wysyłką sprawdzić poprawność pól oraz wyświetlić komunikat, dodałam kod js.
W handleForm() tworzę obiekt z danymi z formularza, dodaaję również action<br>
![image](https://github.com/user-attachments/assets/f5fd1718-0b09-4b55-bf65-496c7e0629c6)<br>
Następnie za pomocą fetch przesyłam dane do pliku validator.php
Na podstawie odpowiedzi generuję wiadomość, z errorem lub thankYou
### Walidacja
Po dostaniu danych, sprawdzam format w jakim zostały przesłane, oczekuję JSON<br>
![image](https://github.com/user-attachments/assets/9bafe660-9319-4088-9bfc-e5038e2785ca)<br>
Przygotowałam walidację dla 3 typów inputów, name, email i description<br>
![image](https://github.com/user-attachments/assets/b588c5ff-121f-4900-9db7-8c07367174e4)<br>
Po walidacji przesyłam odpowiedź do js<br>
![image](https://github.com/user-attachments/assets/c3a98417-db9a-4bf4-b9bd-ade7d1d2760d)<br>
oraz przesyłam dane dalej, do endpointu<br>
![image](https://github.com/user-attachments/assets/572c78ad-9eec-4cad-8f5d-314b9a06ac27)
## Ideas
W przyszłości wtyczkę można rozbudować o następne typy inputów oraz dodatkowe elementy.
### ACF
W tym przypadku nie chciałam używać wtyczki ACF, jednak w przyszłości, przy rozbudowie projektu chciałabym ją wykorzystać w następujący sposób:
1. Dodać nowy panel w Admin menu, w którym będzie znajdowała się cała mechanika tworzenia formularzy. Każdy formularz reprezentowałby inny typ, np. Contact form
2. Z bloku zniknąłby generator formularza, zastąpiłabym go selectem konkretnego typu folmularza
3. Dodałabym dodatkowe opcje, jak nagłówek formularza, możliwość dodania zgód, customowych wiadomości po wysyłce formularza.
