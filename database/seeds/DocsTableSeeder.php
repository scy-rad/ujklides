<?php

use Illuminate\Database\Seeder;

use App\Doc;
use App\Libraries;
use App\ItemGroup;

class DocsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $x_simman = ItemGroup::where('item_group_name','manekin Laerdal Simman 3G')->first();
        
        $zmEQ = new Doc();
        $zmEQ->doc_title = 'Opis producenta';
        $zmEQ->doc_subtitle = 'Opis funkcji realizowanych przez symulator SimMan 3G (ze strony producenta)';
        $zmEQ->doc_description = '<p>SimMan 3G to udoskonalony symulator pacjenta, służący do prezentacji objaw&oacute;w neurologicznych i fizjologicznych. Łączy łatwość obsługi z innowacyjną technologią, umożliwiającą m. in. automatyczne rozpoznawanie lek&oacute;w.</p>
        <p>&nbsp;</p>
        <h3>R&oacute;żne umiejętności/cechy związane z drogami oddechowymi:</h3>
        <ul>
        <li>Możliwość regulacji otwarcia/zamknięcia dr&oacute;g oddechowych; regulacja automatyczna lub ręczna</li>
        <li>Odchylenie głowy / uniesienie podbr&oacute;dka</li>
        <li>Odciągnięcie żuchwy z jej wyluksowaniem</li>
        <li>Odsysanie (ustne i nosowo-gardłowe)</li>
        <li>Wentylacja za pomocą worka samorozprężalnego</li>
        <li>Intubacja ustno-tchawicza</li>
        <li>Intubacja nosowo-tchawicza</li>
        <li>Wprowadzanie Combitube, rurki LMA oraz innych urządzeń do udrażniania dr&oacute;g oddechowych</li>
        <li>Intubacja rurką dotchawiczą</li>
        <li>Intubacja wsteczna</li>
        <li>Intubacja fiberoskopowa</li>
        <li>Przeztchawicza wentylacja strumieniowa</li>
        <li>Konikopunkcja</li>
        <li>Konikotomia</li>
        <li>Zmienna podatność płuc
        <ul>
        <li>4 ustawienia</li>
        </ul>
        </li>
        <li>Zmienny op&oacute;r dr&oacute;g oddechowych
        <ul>
        <li>4 ustawienia</li>
        </ul>
        </li>
        <li>Intubacja prawego oskrzela gł&oacute;wnego</li>
        <li>Rozdęcie żołądka</li>
        <li>Łączność z symulacjami wentylacyjnymi innych firm</li>
        </ul>
        <h3>Powikłania związane z drogami oddechowymi:</h3>
        <ul>
        <li>Wykrywanie prawidłowego ułożenia głowy</li>
        <li>Nie można intubować / można wentylować</li>
        <li>Nie można intubować / nie można wentylować</li>
        <li>Obrzęk języka</li>
        <li>Obrzęk gardła</li>
        <li>Skurcz krtani</li>
        <li>Zmniejszony zakres ruchu szyi</li>
        <li>Szczękościsk</li>
        </ul>
        <h3>Akcja oddechowa:</h3>
        <ul>
        <li>Symulowany oddech spontaniczny</li>
        <li>Obustronne i jednostronne unoszenie się i opadanie klatki piersiowej</li>
        <li>Wydech CO2</li>
        <li>Prawidłowe i nieprawidłowe szmery oddechowe
        <ul>
        <li>5 miejsc osłuchiwania z przodu</li>
        <li>6 miejsc osłuchiwania z tyłu</li>
        </ul>
        </li>
        <li>Nasycenie tlenem wraz z krzywą</li>
        </ul>
        <h3>Powikłania oddechowe:</h3>
        <ul>
        <li>Sinica</li>
        <li>Torakocenteza igłowa &ndash; obustronna</li>
        <li>Jednostronny i obustronny ruch klatki piersiowej</li>
        <li>Jednostronne, obustronne i płatowe szmery oddechowe</li>
        <li>Wprowadzanie drenu klatki piersiowej &ndash; obustronne</li>
        </ul>
        <h3>Akcja serca:</h3>
        <ul>
        <li>Bogata biblioteka EKG</li>
        <li>Tony serca &ndash; cztery lokalizacje z przodu</li>
        <li>Monitorowanie rytmu EKG (4 przewody)</li>
        <li>Wyświetlanie 12-odprowadzeniowego EKG</li>
        <li>Defibrylacja i kardiowersja</li>
        <li>Stymulacja</li>
        </ul>
        <h3>Układ krążenia:</h3>
        <ul>
        <li>Ciśnienie krwi mierzone ręcznie poprzez osłuchiwanie według metody Korotkowa</li>
        <li>Tętna na tętnicach szyjnej, udowej, ramiennej, promieniowej, grzbietowej stopy, podkolanowej i tylnej piszczelowej zsynchronizowane z EKG</li>
        <li>Siła tętna zmienna wraz z ciśnieniem krwi</li>
        <li>Urządzenie wykrywa i rejestruje palpacyjne badanie tętna</li>
        </ul>
        <h3>Dostęp naczyniowy:</h3>
        <ul>
        <li>Dostęp dożylny (prawe ramię)</li>
        <li>Dostęp doszpikowy (piszczel)</li>
        <li>System automatycznego rozpoznawania lek&oacute;w</li>
        </ul>
        <h3>RKO:</h3>
        <ul>
        <li>Zgodna z wytycznymi z 2015 roku</li>
        <li>Uciśnięcia klatki piersiowej w trakcie RKO generują wyczuwalne tętno, wykres falowy ciśnienia krwi oraz artefakty EKG</li>
        <li>Realistyczna głębokość uciśnięć i op&oacute;r klatki piersiowej</li>
        <li>Wykrywanie głębokości, zwolnienia i częstotliwości uciśnięć</li>
        <li>Informacje zwrotne o jakości wykonywanej RKO w czasie rzeczywistym</li>
        </ul>
        <h3>Oczy:</h3>
        <ul>
        <li>Mruganie &ndash; wolne, prawidłowe, szybkie i jednostronne</li>
        <li>Otwarte, zamknięte, częściowo otwarte</li>
        <li>Akomodacja źrenic:
        <ul>
        <li>synchroniczna/asynchroniczna</li>
        <li>prawidłowa i spowolniona szybkość reakcji</li>
        </ul>
        </li>
        </ul>
        <h3>Inne funkcje:</h3>
        <ul>
        <li>Drgawki / drżenia pęczkowe</li>
        <li>Krwawienie
        <ul>
        <li>Symulacja krwawienia w wielu miejscach</li>
        <li>Tętnicze i żylne</li>
        <li>Parametry życiowe automatycznie reagują na utratę krwi i terapię</li>
        <li>Działa z wieloma modułami ran oraz zestawami sztucznych obrażeń</li>
        </ul>
        </li>
        <li>Wydalanie moczu (zmienne)</li>
        <li>Cewnikowanie cewnikiem Foleya</li>
        <li>Wydzieliny
        <ul>
        <li>Oczy, uszy, nos, jama ustna</li>
        <li>Krew, śluz, PMR itp.</li>
        </ul>
        </li>
        <li>Diaforeza</li>
        <li>Odgłosy perystaltyki &ndash; cztery kwadranty</li>
        <li>Głos pacjenta
        <ul>
        <li>Nagrane dźwięki</li>
        <li>Dźwięki niestandardowe</li>
        <li>Instruktor może bezprzewodowo symulować głos pacjenta</li>
        </ul>
        </li>
        <li>Komunikacja instruktora
        <ul>
        <li>Kilku instruktor&oacute;w komunikuje się za pomocą zintegrowanej technologii VoIP</li>
        </ul>
        </li>
        </ul>
        <h3>Farmakologia:</h3>
        <ul>
        <li>System automatycznego rozpoznawania lek&oacute;w rozpoznaje lek i dawkę</li>
        <li>Szeroka gama receptur lek&oacute;w</li>
        <li>Automatyczne lub programowalne reakcje fizjologiczne</li>
        </ul>
        <h3>Cechy systemu:</h3>
        <ul>
        <li>Kontrola nad wieloma manekinami za pomocą jednego interfejsu</li>
        <li>Sterowanie symulacjami z dowolnego miejsca w sieci</li>
        <li>Wiele interfejs&oacute;w może sterować jedną symulacją / obserwować jedną symulację</li>
        <li>Tryb ręczny
        <ul>
        <li>Dokładna kontrola &bdquo;w locie&rdquo;</li>
        <li>Tworzenie i programowanie scenariuszy niestandardowych</li>
        <li>Tworzenie zdarzeń niestandardowych</li>
        <li>Przeprowadzanie wstępnie przygotowanych scenariuszy</li>
        </ul>
        </li>
        <li>Tryb automatyczny
        <ul>
        <li>Modele fizjologiczne i farmakologiczne obsługują wstępnie przygotowane symulacje</li>
        <li>Wyjątkowe, proste elementy sterujące zwiększają/zmniejszają stopień trudności i tempo</li>
        </ul>
        </li>
        <li>Sterowanie symulacją:
        <ul>
        <li>Szybko w prz&oacute;d</li>
        <li>Pauza</li>
        <li>Przewiń wstecz</li>
        <li>Zapisz/Przywr&oacute;ć</li>
        <li>Profile Editor</li>
        <li>Przewidywanie przyszłości i wyświetlanie wynik&oacute;w pacjenta</li>
        <li>Zintegrowane podsumowanie wideo</li>
        <li>Rejestracja danych</li>
        <li>Komentarze instruktora</li>
        </ul>
        </li>
        </ul>
        <h3>Monitor pacjenta:</h3>
        <ul>
        <li>Bezprzewodowy</li>
        <li>Bardzo duże możliwości konfiguracji</li>
        <li>Zawartość:
        <ul>
        <li>EKG (2 przebiegi)</li>
        <li>SpO2</li>
        <li>CO2</li>
        <li>ABP</li>
        <li>Ośrodkowe ciśnienie żylne (CVP)</li>
        <li>Ciśnienie śr&oacute;dczaszkowe (ICP)</li>
        <li>Środek znieczulający</li>
        <li>PH</li>
        <li>PTC</li>
        <li>PAP</li>
        <li>PCWP</li>
        <li>NIBP</li>
        <li>TOF</li>
        <li>Pojemność minutowa</li>
        <li>Temperatura (wewnętrzna i obwodowa)</li>
        <li>Dodatkowe i programowalne parametry</li>
        </ul>
        </li>
        <li>Wyświetlanie RTG</li>
        <li>Wyświetlanie 12-odprowadzeniowego EKG</li>
        <li>Niestandardowe wyświetlanie obrazu</li>
        <li>Niestandardowe wyświetlanie wideo</li>
        </ul>';
        $zmEQ->doc_date = "2020-10-01";
        $zmEQ->doc_status = 1;
        $zmEQ->save();
        $zmEQ->item_group()->attach($x_simman->id);



        $zmEQ = new Doc();
       $zmEQ->doc_title = 'Parametry konfigurowalne';
        $zmEQ->doc_subtitle = 'Wykaz konfigurowalnych parametrów manekina SimMan 3G';
        $zmEQ->doc_description = '<p style="font-weight: 400;"><strong>TWARZ</strong></p>
        <p style="font-weight: 400;">Oczy:</p>
        <ul>
        <li style="font-weight: 400;">Zamknięte</li>
        <li style="font-weight: 400;">W połowie otwarte</li>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Szeroko otwarte</span></li>
        <li style="font-weight: 400;">Prawe otwarte, lewe zamknięte</li>
        <li style="font-weight: 400;">Prawe zamknięte, lewe otwarte</li>
        <li style="font-weight: 400;">Prawe otwarte, lewe w połowie otwarte</li>
        <li style="font-weight: 400;">Lewe otwarte, prawe w połowie otwarte</li>
        </ul>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">Źrenice (można ustawić każdą źrenicę osobno, lub łącznie):</p>
        <ul>
        <li style="font-weight: 400;">Małe</li>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Średnie</span></li>
        <li style="font-weight: 400;">Duże</li>
        </ul>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">Mruganie (nie działa przy zamkniętych powiekach):</p>
        <ul>
        <li style="font-weight: 400;">Nie mruga</li>
        <li style="font-weight: 400;">Sporadyczne mruganie</li>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Prawidłowe mruganie</span></li>
        <li style="font-weight: 400;">Częste mruganie</li>
        <li style="font-weight: 400;">Lewe 1 raz (dodatkowe mrugnięcie, po kt&oacute;rym wraca poprzednie ustawienie)</li>
        <li style="font-weight: 400;">Prawe 1 raz (dodatkowe mrugnięcie, po kt&oacute;rym wraca poprzednie ustawienie)</li>
        <li style="font-weight: 400;">Dwoje 1 raz (dodatkowe mrugnięcie, po kt&oacute;rym wraca poprzednie ustawienie)</li>
        </ul>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">Reakcja na&nbsp;&nbsp;światło:</p>
        <ul>
        <li style="font-weight: 400;">Brak reakcji</li>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Prawidłowa reakcja</span></li>
        <li style="font-weight: 400;">Powolna reakcja</li>
        <li style="font-weight: 400;">Prawe reaguje, lewe brak reakcji</li>
        <li style="font-weight: 400;">Prawe brak reakcji, lewe reaguje</li>
        </ul>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>ODGŁOSY CIAŁA:</strong></p>
        <p style="font-weight: 400;">Serce:</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Prawidłowy</span></li>
        <li style="font-weight: 400;">Zwężenie aorty</li>
        <li style="font-weight: 400;">Wypadnięcie płatka zastawki mitralnej</li>
        <li style="font-weight: 400;">Szmer rozkurczowy</li>
        <li style="font-weight: 400;">Niedomykalność i zwężenie zastawki aortalnej</li>
        <li style="font-weight: 400;">Zapalenie osierdzia</li>
        <li style="font-weight: 400;">Szmer skurczowy</li>
        <li style="font-weight: 400;">Niedomykalność i zwężenie zastawki aortalnej</li>
        <li style="font-weight: 400;">Zapalenie osierdzia</li>
        <li style="font-weight: 400;">Szmer skurczowy</li>
        <li style="font-weight: 400;">Niewydolność zastawki aortalnej</li>
        <li style="font-weight: 400;">Austin Flint Murmur</li>
        <li style="font-weight: 400;">OS70</li>
        <li style="font-weight: 400;">Friction Rub</li>
        </ul>
        <p style="font-weight: 400;">Płuca (osobno Lewe i Praw lub oba jednocześnie):</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Prawidłowy</span></li>
        <li style="font-weight: 400;">Szorstki wysoki świst oddechowy</li>
        <li style="font-weight: 400;">Świszczące</li>
        <li style="font-weight: 400;">Trzaski</li>
        <li style="font-weight: 400;">Trzaski podstawowe</li>
        <li style="font-weight: 400;">Odoskrzelowe zapalenie płuc</li>
        <li style="font-weight: 400;">Zapalenie dolnego płata płuc</li>
        <li style="font-weight: 400;">Zaostrzenie POChP</li>
        <li style="font-weight: 400;">Świsty</li>
        <li style="font-weight: 400;">Pleural Rub</li>
        <li style="font-weight: 400;">Rzężenie</li>
        <li style="font-weight: 400;">Zapalenie płuc</li>
        <li style="font-weight: 400;">Coarse Crackles</li>
        <li style="font-weight: 400;">Fine Crackles</li>
        </ul>
        <p style="font-weight: 400;">Jelito:</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Prawidłowy</span></li>
        <li style="font-weight: 400;">Nadczynność</li>
        <li style="font-weight: 400;">Obniżona aktywność</li>
        <li style="font-weight: 400;">Burczenie w brzuchu</li>
        <li style="font-weight: 400;">Hiperaktywność pooper.</li>
        <li style="font-weight: 400;">Biegunka spowodowana nadaktywnością jelit</li>
        <li style="font-weight: 400;">Zatwardzenie spowodowane hipoaktywnością jelit</li>
        <li style="font-weight: 400;">Niedrożność porażenna</li>
        <li style="font-weight: 400;">No sound</li>
        </ul>
        <p style="font-weight: 400;">Serce &ndash; odgłosy niestandardowe:</p>
        <p style="font-weight: 400;">Ustawiane osobno dla każdego z obszaru:</p>
        <p style="font-weight: 400;">Serce &ndash; obszar aorty (ustawienie głośności od 0% do 100% co 10 %)</p>
        <p style="font-weight: 400;">Serce &ndash; powierzchnia tr&oacute;jdzielnej&nbsp;&nbsp;(ustawienie głośności od 0% do 100% co 10 %)</p>
        <p style="font-weight: 400;">Serce &ndash; powierzchnia płuc (ustawienie głośności od 0% do 100% co 10 %)</p>
        <p style="font-weight: 400;">Serce &ndash; powierzchnia mitralnej (ustawienie głośności od 0% do 100% co 10 %)</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Prawidłowy</span></li>
        <li style="font-weight: 400;">Zwężenie aorty</li>
        <li style="font-weight: 400;">Szmer Austina Flinta</li>
        <li style="font-weight: 400;">Szmer tarcia</li>
        <li style="font-weight: 400;">Wypadnięcie płatka zastawki mitralnej</li>
        <li style="font-weight: 400;">Szmer skurczowy</li>
        <li style="font-weight: 400;">Szmer rozkurczowy</li>
        <li style="font-weight: 400;">OS70</li>
        <li style="font-weight: 400;">Szmer Stillsa</li>
        <li style="font-weight: 400;">Ubytek w przegrodzie międzykomorowej</li>
        <li style="font-weight: 400;">Ubytek w przegrodzie międzyprzedsionkowej</li>
        <li style="font-weight: 400;">Zwężenie tętnicy płucnej</li>
        <li style="font-weight: 400;">Szmer holosystoliczny</li>
        <li style="font-weight: 400;">Wczesny szmer skurczowy</li>
        <li style="font-weight: 400;">Galop</li>
        <li style="font-weight: 400;">Ciągły szmer</li>
        <li style="font-weight: 400;">Brak dźwięku</li>
        </ul>
        <p style="font-weight: 400;">Jelito &ndash; odgłosy niestandardowe:</p>
        <p style="font-weight: 400;">Jelito &ndash; Odgłos perystaltyki jelit (ustawienie głośności od 0% do 100% co 10 %)</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Prawidłowy</span></li>
        <li style="font-weight: 400;">Normalne jelito, 20 lat</li>
        <li style="font-weight: 400;">Normalne jelito, 60 lat</li>
        <li style="font-weight: 400;">Nadczynność</li>
        <li style="font-weight: 400;">Nadaktywne jelito, 16 lat</li>
        <li style="font-weight: 400;">Burczenie w brzuchu</li>
        <li style="font-weight: 400;">Obniżona aktywność</li>
        <li style="font-weight: 400;">Hipoaktywne 50s</li>
        <li style="font-weight: 400;">Hipoaktywne jelito, kodeina</li>
        <li style="font-weight: 400;">Hipoaktywne jelito po operacji</li>
        <li style="font-weight: 400;">Hipoaktywne jelito przed operacją</li>
        <li style="font-weight: 400;">Zesp&oacute;ł jelita drażliwego</li>
        <li style="font-weight: 400;">Wrzodzejące zapalenie jelit</li>
        <li style="font-weight: 400;">Choroba Leśniowskiego-Crohna</li>
        <li style="font-weight: 400;">Biegunka</li>
        <li style="font-weight: 400;">Brak dźwięku</li>
        </ul>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">Płuca &ndash; odgłosy niestandardowe:</p>
        <p style="font-weight: 400;">Ustawiane osobno dla każdego z obszaru:</p>
        <p style="font-weight: 400;">Płuca prz&oacute;d &ndash; prawy g&oacute;rny płat (ustawienie głośności od 0% do 100% co 10 %)</p>
        <p style="font-weight: 400;">Płuca prz&oacute;d &ndash; prawy środkowy płat (ustawienie głośności od 0% do 100% co 10 %)</p>
        <p style="font-weight: 400;">Płuca prz&oacute;d &ndash; prawy dolny płat (ustawienie głośności od 0% do 100% co 10 %)</p>
        <p style="font-weight: 400;">Płuca prz&oacute;d &ndash; lewy g&oacute;rny płat (ustawienie głośności od 0% do 100% co 10 %)</p>
        <p style="font-weight: 400;">Płuca prz&oacute;d &ndash; lewy dolny płat (ustawienie głośności od 0% do 100% co 10 %)</p>
        <p style="font-weight: 400;">Płuca tył &ndash; prawy g&oacute;rny płat (ustawienie głośności od 0% do 100% co 10 %)</p>
        <p style="font-weight: 400;">Płuca tył &ndash; prawy dolny płat (ustawienie głośności od 0% do 100% co 10 %)</p>
        <p style="font-weight: 400;">Płuca tył &ndash; lewy g&oacute;rny płat (ustawienie głośności od 0% do 100% co 10 %)</p>
        <p style="font-weight: 400;">Płuca tył &ndash; lewy dolny płat (ustawienie głośności od 0% do 100% co 10 %)</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Prawidłowy</span></li>
        <li style="font-weight: 400;">Rzężenia grubobańkowe</li>
        <li style="font-weight: 400;">Rzężenia drobnobańkowe</li>
        <li style="font-weight: 400;">Tarcie opłucnowe</li>
        <li style="font-weight: 400;">Zapalenie płuc</li>
        <li style="font-weight: 400;">Bulgoczące rzężenie</li>
        <li style="font-weight: 400;">Rzężenie</li>
        <li style="font-weight: 400;">Szorstki wysoki świst oddechowy</li>
        <li style="font-weight: 400;">Świsty</li>
        <li style="font-weight: 400;">Brak dźwięku</li>
        </ul>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">Podczas badania osłuchowego w trakcie symulacji można wyłączyć wewnętrzne źr&oacute;dła hałasu (takie jak kompresor) na 30 sekund (lub wielokrotność)</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">Przerwa osłuchowa (włączona lub&nbsp;<span style="background-color: #fbeeb8;">wyłączona</span>)</p>
        <p style="font-weight: 400;">Głośność ton&oacute;w Korotkowa &ndash; ustawiana w zakresie 0-9 &ndash;&nbsp;<span style="background-color: #fbeeb8;">domyślnie 5</span></p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>UKŁAD ODDECHOWY:</strong></p>
        <p style="font-weight: 400;">Stan początkowy:</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Prawidłowy</span></li>
        <li style="font-weight: 400;">Nie można zaintubować/Można wentylować (+obrzęk języka:Max, niedrożność gardła, zmniejszony zakres ruchu szyi)</li>
        <li style="font-weight: 400;">Nie można zaintubować/Nie można wentylować (+obrzęk języka:Max, niedrożność gardła, zmniejszony zakres ruchu szyi, % oporności: 100)</li>
        <li style="font-weight: 400;">Obustronna odma opłucowa prężna (+obrzęk języka:Max, niedrożność gardła, zmniejszony zakres ruchu szyi, % oporności: 100, naprężenie obu pęcherzy odmy prężnej)</li>
        <li style="font-weight: 400;">Lewostronna odma opłucowa prężna (+obrzęk języka:Max, niedrożność gardła, zmniejszony zakres ruchu szyi, % oporności Lewego płuca: 100, naprężenie lewego pęcherza odmy prężnej)</li>
        <li style="font-weight: 400;">Prawostronna odma opłucowa prężna (+obrzęk języka:Max, niedrożność gardła, zmniejszony zakres ruchu szyi, % oporności Prawego płuca: 100, naprężenie prawego pęcherza odmy prężnej)</li>
        </ul>
        <p style="font-weight: 400;">Ustawienia:</p>
        <ul>
        <li style="font-weight: 400;">% oporności - osobno dla płuca lewego i prawego w zakresie 0-100 co ok. 33 (<span style="background-color: #fbeeb8;">0</span>, 33, 67, 100)</li>
        <li style="font-weight: 400;">Podatność % - w zakresie 100-0 co ok. 33 (<span style="background-color: #fbeeb8;">100</span>, 67, 33, 0)</li>
        <li style="font-weight: 400;">Wypełnienie żołądka (<span style="background-color: #fbeeb8;">TAK</span>&nbsp;/ NIE)</li>
        <li style="font-weight: 400;">Wydech CO2&nbsp;&nbsp;(TAK /&nbsp;<span style="background-color: #fbeeb8;">NIE</span>)</li>
        <li style="font-weight: 400;">Zapadnięcie języka&nbsp;&nbsp;(TAK /&nbsp;<span style="background-color: #fbeeb8;">NIE</span>)</li>
        <li style="font-weight: 400;">Przycisk wypuść powietrze (otwiera zawory w celu wypuszczenia powietrza z żołądka &ndash; przycisk &bdquo;działa&rdquo; 15 sekund i ponownie staje się aktywny)</li>
        </ul>
        <p style="font-weight: 400;">Sterowanie graficzne:</p>
        <p style="font-weight: 400;">Obrzęk języka:</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Normal</span></li>
        <li style="font-weight: 400;">Half</li>
        <li style="font-weight: 400;">Max</li>
        </ul>
        <p style="font-weight: 400;">Szczękościsk</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Nieaktywny</span></li>
        <li style="font-weight: 400;">Aktywny</li>
        </ul>
        <p style="font-weight: 400;">Niedrożność dr&oacute;g oddechowych spowodowanych ciałem obcym</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Nieaktywna</span></li>
        <li style="font-weight: 400;">Aktywna</li>
        </ul>
        <p style="font-weight: 400;">Niedrożność gardła</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Nieaktywna</span></li>
        <li style="font-weight: 400;">Aktywna</li>
        </ul>
        <p style="font-weight: 400;">Skurcz krtani</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">None</span></li>
        <li style="font-weight: 400;">Full</li>
        </ul>
        <p style="font-weight: 400;">Prawy pęcherz odmy prężnej</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Wyłączony</span></li>
        <li style="font-weight: 400;">Naprężenie</li>
        </ul>
        <p style="font-weight: 400;">Lewy pęcherz odmy prężnej</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Wyłączony</span></li>
        <li style="font-weight: 400;">Naprężenie</li>
        </ul>
        <p style="font-weight: 400;">Zmniejszony zakres ruchu szyi</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Nieaktywny</span></li>
        <li style="font-weight: 400;">Aktywny</li>
        </ul>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>KRĄŻENIE I PŁYNY</strong></p>
        <p style="font-weight: 400;">Konwulsje:</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Wyłączone</span></li>
        <li style="font-weight: 400;">Kloniczne</li>
        <li style="font-weight: 400;">Toniczno-kloniczne</li>
        </ul>
        <p style="font-weight: 400;">Puls: [ <span style="background-color: #fbeeb8;">prawidłowy</span> / słaby / nieobecny ]</p>
        <ul>
        <li style="font-weight: 400;">Tętno w prawej nodze</li>
        <li style="font-weight: 400;">Tętno w prawej ręce</li>
        <li style="font-weight: 400;">Tętno centralne</li>
        <li style="font-weight: 400;">Tętno w lewej ręce</li>
        <li style="font-weight: 400;">Tętno w lewej nodze</li>
        </ul>
        <p style="font-weight: 400;">G&oacute;rny port (wymaga podłączenia zbiornika krwi):</p>
        <ul>
        <li style="font-weight: 400;">Włączony/<span style="background-color: #fbeeb8;">wyłączony</span></li>
        <li style="font-weight: 400;">Tętnicze/Żylne</li>
        <li style="font-weight: 400;">Regulowane w zakresie od 0 do 100% (co 5%)</li>
        </ul>
        <p style="font-weight: 400;">Dolny port (wymaga podłączenia zbiornika krwi):</p>
        <ul>
        <li style="font-weight: 400;">Włączony/<span style="background-color: #fbeeb8;">wyłączony</span></li>
        <li style="font-weight: 400;">Tętnicze/Żylne</li>
        <li style="font-weight: 400;">Regulowane w zakresie od 0 do 100% (co 5%)</li>
        </ul>
        <p style="font-weight: 400;">Wydzieliny:</p>
        <ul>
        <li style="font-weight: 400;">Mocz:</li>
        </ul>
        <p style="font-weight: 400;">o&nbsp;&nbsp;&nbsp;&nbsp;W<span style="background-color: #fbeeb8;">yłączony</span></p>
        <p style="font-weight: 400;">o&nbsp;&nbsp;&nbsp;&nbsp;Prawidłowy</p>
        <p style="font-weight: 400;">o&nbsp;&nbsp;&nbsp;&nbsp;Wielomocz</p>
        <p style="font-weight: 400;">o&nbsp;&nbsp;&nbsp;&nbsp;Mikcja</p>
        <ul>
        <li style="font-weight: 400;">Pot [ TAK / <span style="background-color: #fbeeb8;">NIE</span> ]</li>
        <li style="font-weight: 400;">Oczy [ TAK / <span style="background-color: #fbeeb8;">NIE</span> ]</li>
        <li style="font-weight: 400;">Jama ustna [ TAK / <span style="background-color: #fbeeb8;">NIE</span> ]</li>
        <li style="font-weight: 400;">Uszy [ TAK / <span style="background-color: #fbeeb8;">NIE</span> ]</li>
        <li style="font-weight: 400;">Nos [ TAK / <span style="background-color: #fbeeb8;">NIE</span> ]</li>
        <li style="font-weight: 400;">Piana [ TAK / <span style="background-color: #fbeeb8;">NIE</span> ] (włączenie powoduje wyłączenie wszystkich innych płyn&oacute;w)</li>
        </ul>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>&nbsp;</strong></p>
        <p style="font-weight: 400;"><strong>PARAMETRY MONITORA</strong></p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>Edycja rytmu serca:</strong></p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">Zakładka umożliwia podgląd zapisu każdego z dwunastu odprowadzeń EKG osobno. Można ponadto zapisać dwa stany symulatora &ndash; wykonywany i oczekiwany &ndash; i dowolnie się między nimi przełączać.</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">Rytmy podstawowe mogą być modyfikowane w zakresie częstości akcji serca (CzAS) i Skurczu dodatkowego (SD) (wybrane)</p>
        <p style="font-weight: 400;">Rytm podstawowy:</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Rytm zatokowy</span>&nbsp;&nbsp;&nbsp;&nbsp;(CzAS&nbsp;<span style="background-color: #fbeeb8;">80</span>&nbsp;&ndash; 20-200) (SD)</li>
        <li style="font-weight: 400;">Zatokowy 60 lat&nbsp;&nbsp;&nbsp;(CzAS&nbsp;80&nbsp;&ndash; 20-260) (SD)</li>
        <li style="font-weight: 400;">Rytm zatokowy podczas niedokrwienia (CzAS&nbsp;80&nbsp;&ndash; 20-200) (SD)</li>
        <li style="font-weight: 400;">Rytm zatokowy po niedokrwieniu (CzAS&nbsp;80&nbsp;&ndash; 20-200) (SD)</li>
        <li style="font-weight: 400;">Zatokowy z ostrym zawałem serca ściany dolnej z uniesieniem odcinka ST (CzAS&nbsp;80&nbsp;&ndash; 20-200) (SD) (uniesienie odcinka ST: 0,3, 1/3,&nbsp;2/3, 3/3)</li>
        <li style="font-weight: 400;">Zatokowy z ostrym zawałem serca ściany przedniej z uniesieniem odcinka ST (CzAS&nbsp;80&nbsp;&ndash; 20-200) (SD) (uniesienie odcinka ST: 0,3, 1/3,&nbsp;2/3, 3/3)</li>
        <li style="font-weight: 400;">Zatokowy p&oacute;źny z ostrym zawałem serca ściany przedniej (CzAS&nbsp;80&nbsp;&ndash; 20-200) (SD)</li>
        <li style="font-weight: 400;">Zatokowy z blokiem lewej odnogi pęczka Hisa (CzAS&nbsp;80&nbsp;&ndash; 20-200) (SD)</li>
        <li style="font-weight: 400;">Zatokowy z blokiem prawej odnogi pęczka Hisa (CzAS&nbsp;80&nbsp;&ndash; 20-200) (SD)</li>
        <li style="font-weight: 400;">Zatokowy z przerostem lewej komory (CzAS&nbsp;80&nbsp;&ndash; 20-200) (SD)</li>
        <li style="font-weight: 400;">Zatokowy z hiperkaliemią (CzAS&nbsp;80&nbsp;&ndash; 20-200) (SD) (Nasilenie: 0,3, 1/3,&nbsp;2/3, 3/3)</li>
        <li style="font-weight: 400;">Zatokowy, zesp&oacute;ł WPW (CzAS&nbsp;80&nbsp;&ndash; 20-200) (SD)</li>
        <li style="font-weight: 400;">Zatokowy, przerost prawego serca (CzAS&nbsp;80&nbsp;&ndash; 20-200) (SD)</li>
        <li style="font-weight: 400;">Zatokowy, z przerostem prawej komory (CzAS&nbsp;80&nbsp;&ndash; 20-200) (SD)</li>
        <li style="font-weight: 400;">Zatokowy, wydłużenie QT (CzAS&nbsp;80&nbsp;&ndash; 20-200) (SD)</li>
        <li style="font-weight: 400;">Blok AV 1. stopnia (CzAS&nbsp;80&nbsp;&ndash; 20-115) (SD)</li>
        <li style="font-weight: 400;">Blok AV 2. stopnia typu 1&nbsp;&nbsp;(CzAS&nbsp;40&nbsp;&ndash; 33-88) (SD bez PAC/PJC) (Przewodnictwo:&nbsp;&nbsp;3:2, 4:3, 5:4)</li>
        <li style="font-weight: 400;">Blok AV 2. stopnia typu 2&nbsp;&nbsp;(CzAS&nbsp;30&nbsp;&ndash; 25-83) (SD bez PAC/PJC) (Przewodnictwo:&nbsp;&nbsp;2:1, 3:2, 4:3)</li>
        <li style="font-weight: 400;">Blok AV 3. Stopnia (CzAS&nbsp;30&nbsp;&ndash; 10-50)</li>
        <li style="font-weight: 400;">Zatrzymanie akcji kom&oacute;r serca (CzAS&nbsp;0) (bez r&oacute;żnych)</li>
        <li style="font-weight: 400;">Częstoskurcz nadkomorowy (SVT) (CzAS&nbsp;180&nbsp;&ndash; 90-260)</li>
        <li style="font-weight: 400;">Tachykardia przedsionkowa z wędr&oacute;wką stymulatora (CzAS&nbsp;140&nbsp;&ndash; 90-260)</li>
        <li style="font-weight: 400;">Trzepotanie przedsionk&oacute;w (AF) (CzAS&nbsp;0) (Częstość akcji / Przewodnictwo:&nbsp;&nbsp;75/4:1, 100/3:1, 150/2:1)</li>
        <li style="font-weight: 400;">Migotanie przedsionk&oacute;w (AFib) (CzAS&nbsp;160&nbsp;&ndash; 10-240)</li>
        <li style="font-weight: 400;">Rym węzłowy (CzAS&nbsp;50&nbsp;&ndash; 40-220)</li>
        <li style="font-weight: 400;">Wyłącznie komorowy (CzAS&nbsp;70&nbsp;&ndash; 4-100)</li>
        <li style="font-weight: 400;">Częstoskurcz komorowy typu 1 (VT typ 1) (CzAS&nbsp;180&nbsp;&ndash; 120-240)</li>
        <li style="font-weight: 400;">Częstoskurcz komorowy typu 2 (VT typ 2) (CzAS&nbsp;180&nbsp;&ndash; 120-240)</li>
        <li style="font-weight: 400;">Torsade de pointes (CzAS&nbsp;200&nbsp;&ndash; 120-320)</li>
        <li style="font-weight: 400;">Migotanie kom&oacute;r (VF) (CzAS&nbsp;0) (Amplituda: płynna regulacja)</li>
        <li style="font-weight: 400;">Asystolia (Asystolia CzAS&nbsp;0&nbsp;/ Załamki CzAS&nbsp;0&nbsp;/ Agonalny CzAS&nbsp;10-40)</li>
        <li style="font-weight: 400;">Stymulacja komorowa (CzAS&nbsp;80&nbsp;&ndash; 50-150)</li>
        <li style="font-weight: 400;">Sekwencja A-V rozrusznika (CzAS&nbsp;80&nbsp;&ndash; 50-150)</li>
        <li style="font-weight: 400;">Utrata stymulacji (CzAS&nbsp;80&nbsp;&ndash; 50-150)</li>
        </ul>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">Ustawienia Skurczu dodatkowego:</p>
        <ul>
        <li style="font-weight: 400;"><span style="background-color: #fbeeb8;">Brak</span></li>
        <li style="font-weight: 400;">Jednoogniskowe przedwczesne pobudzenie komorowe (PVC)</li>
        <li style="font-weight: 400;">Sprzężony przedwczesny skurcz komorowy</li>
        <li style="font-weight: 400;">PVC R-na-T</li>
        <li style="font-weight: 400;">PAC/PJC</li>
        </ul>
        <p style="font-weight: 400;">Częstotliwość skurczu dodatkowego regulowana jest płynnie</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">Artefakty:</p>
        <ul>
        <li style="font-weight: 400;">50/60 Hz [ TAK / <span style="background-color: #fbeeb8;">NIE</span> ]</li>
        <li style="font-weight: 400;">Mięśniowe [ TAK / <span style="background-color: #fbeeb8;">NIE </span>]</li>
        </ul>
        <p style="font-weight: 400;">R&oacute;żne:</p>
        <ul>
        <li style="font-weight: 400;">EMD/PEA [ TAK / <span style="background-color: #fbeeb8;">NIE</span> ]</li>
        </ul>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>Ustawianie tętna:</strong></p>
        <p style="font-weight: 400;">Wartość od 20 do 200 (<span style="background-color: #fbeeb8;">domyślnie 80</span>)</p>
        <p style="font-weight: 400;">Można ustawić czas &bdquo;dochodzenia&rdquo; do nowo zadanego tętna na jedną z następujących wartości:</p>
        <p style="font-weight: 400;">20, 40 sekund; 1, 2, 3, 4, 5, 6, 8, 10 minut.</p>
        <p style="font-weight: 400;">Przejście może się odbywać liniowo, łagodnie liniowo, logarytmicznie lub wykładniczo</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>Ustawianie saturacji:</strong></p>
        <p style="font-weight: 400;">Wartość od 0% do 100% <span style="background-color: #fbeeb8;">(domyślnie 98%</span>)</p>
        <p style="font-weight: 400;">Można ustawić czas &bdquo;dochodzenia&rdquo; do nowo zadanej saturacji na jedną z następujących wartości:</p>
        <p style="font-weight: 400;">20, 40 sekund; 1, 2, 3, 4, 5, 6, 8, 10 minut.</p>
        <p style="font-weight: 400;">Przejście może się odbywać liniowo, łagodnie liniowo, logarytmicznie lub wykładniczo</p>
        <p style="font-weight: 400;">Ponadto można ustawić pr&oacute;g, przy kt&oacute;rym zaczyna się sinica</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>Ustawianie temperatury obwodowej:</strong></p>
        <p style="font-weight: 400;">Wartość od 5 do 45 (<span style="background-color: #fbeeb8;">domyślnie 36,1</span>)</p>
        <p style="font-weight: 400;">Można ustawić czas &bdquo;dochodzenia&rdquo; do nowo zadanej temperatury na jedną z następujących wartości:</p>
        <p style="font-weight: 400;">20, 40 sekund; 1, 2, 3, 4, 5, 6, 8, 10 minut.</p>
        <p style="font-weight: 400;">Przejście może się odbywać liniowo, łagodnie liniowo, logarytmicznie lub wykładniczo</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>Ustawianie ciśnienia tętniczego:</strong></p>
        <ul>
        <li style="font-weight: 400;">Skurczowe: Wartość od 0 do 300 (<span style="background-color: #fbeeb8;">domyślnie 120</span>)</li>
        <li style="font-weight: 400;">Rozkurczowe: Wartość od 0 do 290 (<span style="background-color: #fbeeb8;">domyślnie 80</span>)</li>
        </ul>
        <p style="font-weight: 400;">Zaznaczenie opcji &bdquo;sprzężony&rdquo; spowoduje, że podczas zmian pomiędzy tymi ciśnieniami utrzymywana będzie stała r&oacute;żnica.</p>
        <p style="font-weight: 400;">Można ustawić czas &bdquo;dochodzenia&rdquo; do nowo zadanego ciśnienia na jedną z następujących wartości:</p>
        <p style="font-weight: 400;">20, 40 sekund; 1, 2, 3, 4, 5, 6, 8, 10 minut.</p>
        <p style="font-weight: 400;">Przejście może się odbywać liniowo, łagodnie liniowo, logarytmicznie lub wykładniczo.</p>
        <p style="font-weight: 400;">Wykres monitora ciśnienia tętniczego można zmienić na &bdquo;płaska linia&rdquo;.</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>Ustawianie temperatury krwi:</strong></p>
        <p style="font-weight: 400;">Wartość od 5 do 45 (d<span style="background-color: #fbeeb8;">omyślnie 37,2</span>)</p>
        <p style="font-weight: 400;">Można ustawić czas &bdquo;dochodzenia&rdquo; do nowo zadanej temperatury na jedną z następujących wartości:</p>
        <p style="font-weight: 400;">20, 40 sekund; 1, 2, 3, 4, 5, 6, 8, 10 minut.</p>
        <p style="font-weight: 400;">Przejście może się odbywać liniowo, łagodnie liniowo, logarytmicznie lub wykładniczo</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>Ustawianie ciśnienia płucnego:</strong></p>
        <ul>
        <li style="font-weight: 400;">Skurczowe: Wartość od 0 do 100 (<span style="background-color: #fbeeb8;">domyślnie 25</span>)</li>
        <li style="font-weight: 400;">Rozkurczowe: Wartość od 0 do 95 (<span style="background-color: #fbeeb8;">domyślnie 12</span>)</li>
        </ul>
        <p style="font-weight: 400;">Zaznaczenie opcji &bdquo;sprzężony&rdquo; spowoduje, że podczas zmian pomiędzy tymi ciśnieniami utrzymywana będzie stała r&oacute;żnica.</p>
        <ul>
        <li style="font-weight: 400;">Zaklinowanie: Wartość od 0 do 100 (<span style="background-color: #fbeeb8;">domyślnie 9</span>)</li>
        </ul>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">Można ustawić czas &bdquo;dochodzenia&rdquo; do nowo zadanego ciśnienia na jedną z następujących wartości:</p>
        <p style="font-weight: 400;">20, 40 sekund; 1, 2, 3, 4, 5, 6, 8, 10 minut.</p>
        <p style="font-weight: 400;">Przejście może się odbywać liniowo, łagodnie liniowo, logarytmicznie lub wykładniczo</p>
        <p style="font-weight: 400;">Wykres monitora ciśnienia płucnego można zmienić na &bdquo;płaska linia&rdquo;.</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>Ustawianie pojemności minutowej:</strong></p>
        <p style="font-weight: 400;">Wartość od 0 do 12 (<span style="background-color: #fbeeb8;">domyślnie 5,6</span>)</p>
        <p style="font-weight: 400;">Można ustawić czas &bdquo;dochodzenia&rdquo; do nowo zadanej pojemności na jedną z następujących wartości:</p>
        <p style="font-weight: 400;">20, 40 sekund; 1, 2, 3, 4, 5, 6, 8, 10 minut.</p>
        <p style="font-weight: 400;">Przejście może się odbywać liniowo, łagodnie liniowo, logarytmicznie lub wykładniczo</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>Ustawianie CO2:</strong></p>
        <ul>
        <li style="font-weight: 400;">etCO2: Wartość od 0 do 150 (<span style="background-color: #fbeeb8;">domyślnie 34</span>)</li>
        </ul>
        <p style="font-weight: 400;">Można ustawić czas &bdquo;dochodzenia&rdquo; do nowo zadanej pojemności na jedną z następujących wartości:<br />20, 40 sekund; 1, 2, 3, 4, 5, 6, 8, 10 minut.</p>
        <p style="font-weight: 400;">Przejście może się odbywać liniowo, łagodnie liniowo, logarytmicznie lub wykładniczo</p>
        <ul>
        <li style="font-weight: 400;">inCO2: Wartość od&nbsp;<span style="background-color: #fbeeb8;">0</span>&nbsp;do 150</li>
        </ul>
        <p style="font-weight: 400;">Wykres monitora CO2 można ustawiać w zakresie:</p>
        <ul>
        <li style="font-weight: 400;">Wygaśnięcie:&nbsp;<span style="background-color: #fbeeb8;">3</span>, 2, 1</li>
        <li style="font-weight: 400;">Wdychanie:&nbsp;<span style="background-color: #fbeeb8;">3</span>, 2, 1</li>
        </ul>
        <p style="font-weight: 400;">lub zmienić na &bdquo;płaska linia&rdquo;.</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>Ustawianie częstości oddechowej:</strong></p>
        <p style="font-weight: 400;">Wartość od 0 do 60 (<span style="background-color: #fbeeb8;">domyślnie 12</span>)</p>
        <p style="font-weight: 400;">Można ustawić czas &bdquo;dochodzenia&rdquo; do nowo zadanej pojemności na jedną z następujących wartości:</p>
        <p style="font-weight: 400;">20, 40 sekund; 1, 2, 3, 4, 5, 6, 8, 10 minut.</p>
        <p style="font-weight: 400;">Przejście może się odbywać liniowo, łagodnie liniowo, logarytmicznie lub wykładniczo</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>Ustawianie nieinwazyjnego pomiaru ciśnienia krwi&nbsp;</strong><strong>(sprzężone z ciśnieniem tętniczym)</strong><strong>:</strong></p>
        <ul>
        <li style="font-weight: 400;">Skurczowe: Wartość od 0 do 300 (<span style="background-color: #fbeeb8;">domyślnie 120</span>)</li>
        <li style="font-weight: 400;">Rozkurczowe: Wartość od 0 do 290 (<span style="background-color: #fbeeb8;">domyślnie 80</span>)</li>
        </ul>
        <p style="font-weight: 400;">Zaznaczenie opcji &bdquo;sprzężony&rdquo; spowoduje, że podczas zmian pomiędzy tymi ciśnieniami utrzymywana będzie stała r&oacute;żnica.</p>
        <p style="font-weight: 400;">Można ustawić czas &bdquo;dochodzenia&rdquo; do nowo zadanego ciśnienia na jedną z następujących wartości:</p>
        <p style="font-weight: 400;">20, 40 sekund; 1, 2, 3, 4, 5, 6, 8, 10 minut.</p>
        <p style="font-weight: 400;">Przejście może się odbywać liniowo, łagodnie liniowo, logarytmicznie lub wykładniczo.</p>
        <p style="font-weight: 400;">Można ustawić czas trwania pomiaru w zakresie od 0 do 20 sekund.</p>
        <p style="font-weight: 400;">Można ustawić głośność dźwięku w zakresie od 0 do&nbsp;<span style="background-color: #fbeeb8;">100%</span>&nbsp;co 10%.</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>Ustawianie TOF:</strong></p>
        <ul>
        <li style="font-weight: 400;">TOF %: Wartość od 0 do&nbsp;<span style="background-color: #fbeeb8;">100%</span></li>
        <li style="font-weight: 400;">TOF: Wartość od 0 do&nbsp;<span style="background-color: #fbeeb8;">4</span>&nbsp;(ustawiane co 1)</li>
        </ul>
        <p style="font-weight: 400;">Można ustawić czas &bdquo;dochodzenia&rdquo; do nowo zadanej pojemności na jedną z następujących wartości:</p>
        <p style="font-weight: 400;">20, 40 sekund; 1, 2, 3, 4, 5, 6, 8, 10 minut.</p>
        <p style="font-weight: 400;">Przejście może się odbywać liniowo, łagodnie liniowo, logarytmicznie lub wykładniczo</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;"><strong>Ustawianie parametr&oacute;w gazu:</strong></p>
        <ul>
        <li style="font-weight: 400;">inO2: Wartość od 0 do 100% (<span style="background-color: #fbeeb8;">domyślnie&nbsp;21%</span>)</li>
        <li style="font-weight: 400;">etO2: Wartość od 0 do 100% (<span style="background-color: #fbeeb8;">domyślnie&nbsp;16%</span>)</li>
        </ul>
        <p style="font-weight: 400;">Zaznaczenie opcji &bdquo;sprzężony&rdquo; spowoduje, że podczas zmian pomiędzy tymi wartościami utrzymywana będzie stała r&oacute;żnica.</p>
        <ul>
        <li style="font-weight: 400;">inN2O: Wartość od 0 do 100% (<span style="background-color: #fbeeb8;">domyślnie&nbsp;0%</span>)</li>
        <li style="font-weight: 400;">etN2O: Wartość od 0 do 100% (<span style="background-color: #fbeeb8;">domyślnie&nbsp;0%</span>)</li>
        </ul>
        <p style="font-weight: 400;">Zaznaczenie opcji &bdquo;sprzężony&rdquo; spowoduje, że podczas zmian pomiędzy tymi wartościami utrzymywana będzie stała r&oacute;żnica.</p>
        <p style="font-weight: 400;">&nbsp;</p>
        <p style="font-weight: 400;">&nbsp;</p>';
        $zmEQ->doc_date = "2020-10-01";
        $zmEQ->doc_status = 1;
        $zmEQ->save();
        $zmEQ->item_group()->attach($x_simman->id);
     
    }
}
