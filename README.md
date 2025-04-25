# FormGenerator
Prosty generator formularzy z podstawową walidacją
## Uruchomienie
Pobierz _form-generator.zip_, rozpakuj i dodaj ją forderu plugins w twoim motywie.<br>
Następnie aktywuj wtyczkę z poziomu admina w WordPress<br>
![image](https://github.com/user-attachments/assets/cd80918f-89a4-48dc-b81d-d3d66c4365ca)
<br>
## Jak działa generator w edytorze
Po dodaniu wtyczki FormGenerator jest dostępny jako blok w edytorze<br>
![image](https://github.com/user-attachments/assets/13eb21ab-30f2-4267-80a2-79aa6f8cfd62)<br>
Po dodaniu pojawia się blok generatora<br>
![image](https://github.com/user-attachments/assets/ea9dc0ac-b1cd-4258-8731-e4d27ca844e2)
<br>
Można w nim dodać action dla formularza, oraz pojedyńcze inputy, po dodaniu inputu pojawia się on wyżej, można go usunąć<br>
![image](https://github.com/user-attachments/assets/d68988a1-d6f8-467f-ac3c-6a990005a4be)<br>
Nie można ddodać pustego inputu, pojawia się error<br>
![image](https://github.com/user-attachments/assets/e987c1b4-d6fc-476e-8b9f-76558c75efbb)<br>
## Jak działa formularz na stronie
Do strony zostaje dodany prosty formularz<br>
![image](https://github.com/user-attachments/assets/1a960440-8a7b-472b-bf63-a39d5a4624ef)<br>
Po wysyłce pod nim pojawia się wiadomość, czy dane zostały przesłane do endpointu<br>
Error<br>
![image](https://github.com/user-attachments/assets/7a206103-4e4d-4d42-81d0-08e4396f76ef)
<br>
Poprawne dane<br>
![image](https://github.com/user-attachments/assets/8e17f182-1cdd-4133-ab1c-8a94afd99bb6)
## Blok generatora
Blok został stworzony przy wykorzystaniu Komponentów WordPressa
## Generowanie formularza
Formularz jest generowany po stronie PHP, w pliku _render.php_.
Do dynamicznego tworzenia inputów wykorzystałam wzorzez projektowy Builder, najbardziej odpowiada temu co chcę osiągnąć, w przyszłości rozbudowa kodu będzie łatwiejsza
Renderowanie odbywa się w funkcji _fg_form_generator_render()_.<br>
Jako parametr funkcja przyjmuje atrybuty stworzone w edytorze.<br>
![image](https://github.com/user-attachments/assets/f12f9f9a-53e6-4060-a055-a1be8d6abbb9)
<br>
_fg_form_generator_render()_ jest przekazana jako render_callback podczas rejestrowania bloku, dlatego może się odwoływać do atrybutów<br>
![image](https://github.com/user-attachments/assets/c4f32693-dd34-4ccb-89e0-2f82aba6d549)
<br>
Pobieran zapisane i stworzone inputy z atrybutów bloku, następnie za pomocą pętli buduję je przygotowaną mechaniką
### InputBuilder
Przyjmuje przesłane dane, po czym buduje z nich input. Obecnie rozróżniam typ textarea i input, w przyszłości można zozbudować builder o kilka funkcji build(), na przykład dla typu select, żeby to zrobić należy rozbudować również blok generatora<br>
![image](https://github.com/user-attachments/assets/0d1aaad5-baaf-4d0f-901f-647c274f3c0e)
### Director
Inputy tworze poprzez Director'a<br>
![image](https://github.com/user-attachments/assets/4cd32489-3efd-4c2f-8924-0b8ea9cab2f1)
## Wysyłka i walidacja formularza
### Customowy skrypt do wysyłki formularza
Do formularza endpoint końcowy jest przekazywany za pomocą action, żeby móc przed wysyłką sprawdzić poprawność pól oraz wyświetlić komunikat, dodałam kod js.
W handleForm() tworzę obiekt z danymi z formularza, dodaaję również action<br>
![image](https://github.com/user-attachments/assets/0ae2dad6-f152-46fe-a541-4a05d2394290)
<br>
Następnie za pomocą fetch przesyłam dane do pliku validator.php
Na podstawie odpowiedzi generuję wiadomość, z errorem lub thankYou
### Walidacja
Po dostaniu danych, sprawdzam format w jakim zostały przesłane, oczekuję JSON<br>
![image](https://github.com/user-attachments/assets/9ef4f943-ab02-424d-a57f-f061cbb42fed)
<br>
Przygotowałam walidację dla 3 typów inputów, name, email i description<br>
![image](https://github.com/user-attachments/assets/50903028-4900-466c-ba9e-3fe49f4d7030)
<br>
Po walidacji przesyłam odpowiedź do js<br>
![image](https://github.com/user-attachments/assets/82e67107-96d9-45a6-a017-cfd7fa0c7607)
<br>
oraz przesyłam dane dalej, do endpointu<br>
![image](https://github.com/user-attachments/assets/a2c4f6be-2d43-4a0d-978b-bf1302faddcf)
## Ideas
W przyszłości wtyczkę można rozbudować o następne typy inputów oraz dodatkowe elementy.
### ACF
W tym przypadku nie chciałam używać wtyczki ACF, jednak w przyszłości, przy rozbudowie projektu chciałabym ją wykorzystać w następujący sposób:
1. Dodać nowy panel w Admin menu, w którym będzie znajdowała się cała mechanika tworzenia formularzy. Każdy formularz reprezentowałby inny typ, np. Contact form
2. Z bloku zniknąłby generator formularza, zastąpiłabym go selectem konkretnego typu folmularza
3. Dodałabym dodatkowe opcje, jak nagłówek formularza, możliwość dodania zgód, customowych wiadomości po wysyłce formularza.
