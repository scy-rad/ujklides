<?php
//php71-cli artisan db:seed --class=ScenariosTableSeeder
use Illuminate\Database\Seeder;
use App\Scenario;
use App\User;
use App\StudentSubject;
use App\ScenarioForSubject;
use App\ScenarioFile;
use App\Simmed;
use App\ScenarioForSimmed;

class ScenariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		function add_scenario($aF_scenario_author, $aF_scenario_type, $aF_scenario_name, $aF_scenario_code, $aF_scenario_main_problem, $aF_scenario_description, $aF_for_subjects, $aF_files)
			{
			$author_id = User::where('name',$aF_scenario_author)->first()->id;
			//$subject_id = StudentSubject::where('student_subject_name','Opieka paliatywna')->first()->id;

			$zmEQ = new Scenario();
			$zmEQ->scenario_author_id=$author_id;
			$zmEQ->scenario_type=$aF_scenario_type;
			$zmEQ->scenario_name=$aF_scenario_name;
			$zmEQ->scenario_code=$aF_scenario_code;
			$zmEQ->scenario_main_problem=$aF_scenario_main_problem;
			$zmEQ->scenario_description=$aF_scenario_description;
			$zmEQ->save();

			foreach ($aF_for_subjects as $aF_subjects)
				{
				$student_subject_id = StudentSubject::where('student_subject_name',$aF_subjects)->first()->id;

				$zmSU = new ScenarioForSubject();
				$zmSU->scenario_id=$zmEQ->id;
				$zmSU->student_subject_id=$student_subject_id;
				$zmSU->save();
				}

			$type=1;
			/*
			foreach ($aF_files as $aF_file)
				{
				$zmEF = new ScenarioFile();
				$zmEF->scenario_id=$zmEQ->id;
				$zmEF->scenario_file_filename=$aF_file;
				$zmEF->scenario_file_title=substr($aF_file, 0, -5);
				$zmEF->scenario_file_type=$type++;
				$zmEF->save();
				}
			*/

			}

		function SimScen($aF_date,$aF_time,$aF_leader,$aF_simcode)
			{
			$leader_id = User::where('name',$aF_leader)->first()->id;
			$simmed_id = Simmed::where('simmed_date',$aF_date)->where('simmed_time_begin',$aF_time)->where('simmed_leader_id',$leader_id)->first()->id;

			foreach ($aF_simcode as $aF_code)
				{
				$scenario_id=Scenario::where('scenario_code',$aF_code)->first()->id;
				
				$zmEQ = new ScenarioForSimmed();
				$zmEQ->scenario_id=$scenario_id;
				$zmEQ->simmed_id=$simmed_id;
				$zmEQ->save();

				}
			}


			$scen_id=add_scenario("jsikorska","2","13 letni rowerzysta","PED-ROW13","Uraz wielonarządowy (uraz głowy, jamy brzusznej, kończyny górnej lewej).","13 letni Grześ przywieziony przez Zespół Ratownictwa Medycznego na Szpitalny Oddział Ratunkowy po upadku na rowerze. Zgłasza ból kończyny górnej lewej. Podsypiający. Nie pamięta okoliczności zdarzenia. Brak rodziców przy przyjęciu.",["Pielęgniarstwo pediatryczne"],["2020-11 - Sikorska Julia - 13 letni rowerzysta.docx","2020-11 - Sikorska Julia - 13 letni rowerzysta - checklista.docx"]);
			$scen_id=add_scenario("jsikorska","2","Mała Zosia","PED-DRG02","Możliwość wystąpienia drgawek gorączkowych.","2 letnia Zosia została przywieziona do szpitala przez babcię, która nie jest prawnym opiekunem dziecka, rodzice na wyjeździe poza miastem. Od godzin porannych dziecko ma suchy kaszel, wiec babcia zabrała dziecko do lekarza, który wydał skierowanie do szpitala z diagnozą: Ostre zapalenie nosa i gardła.",["Pielęgniarstwo pediatryczne"],["2020-11 - Sikorska Julia - Mala Zosia.docx","2020-11 - Sikorska Julia - Mala Zosia - checklista.docx"]);
			$scen_id=add_scenario("jsikorska","2","Mały Krzyś","PED-DUS03","Rozpoznanie napadu ostrego zapalenia krtani. Przerwanie napadu duszności.","Krzyś lat 3 zgłosił się o 3 w nocy na Szpitalny Oddział Ratunkowy wraz z rodzicami, ponieważ zauważyli, że dziecko zaczeło się dusić. Przed snem dziecko zdrowe, bez objawów infekcji. Po wyjściu na zewnątrz oddech dziecka znacznie się poprawił.",["Pielęgniarstwo pediatryczne"],["2020-11 - Sikorska Julia - Maly Krzys.docx","2020-11 - Sikorska Julia - Maly Krzys - checklista.docx"]);
			$scen_id=add_scenario("jsikorska","2","Śniadanie","PED-OPA04","Ogrzanie organizmu. Jałowe zabezpieczenie rany. Niedopuszczenie do wstrząsu hipowolemicznego.","Kuba lat 4 przywieziony do szpitala przez rodziców. Stan po oparzeniu gorącą herbatą klatki piersiowej oraz brzucha podczas śniadania. Rana chłodzona wodą – dziecko polewane prysznicem.",["Pielęgniarstwo pediatryczne"],["2020-11 - Sikorska Julia - Sniadanie.docx","2020-11 - Sikorska Julia - Sniadanie - checklista.docx"]);
			$scen_id=add_scenario("azaworska","2","Omdlenie Chłopca","PED-OMD10","Postępowanie z dzieckiem po omdleniu podczas pobierania krwi do badań.","Chłopiec lat 10  przebywający w oddziale pediatrycznym celem diagnostyki niskorosłości. Przyjęty bez dolegliwości, tryb przyjęcia do szpitala: planowy. Pozostaje pod opieką poradni alergologicznej (uczulenie na sierść psa i kota), nie przyjmuje żadnych leków na stałe. Matka nieobecna przy chłopcu ponieważ opiekuje się młodszym rodzeństwem w domu. Chłopiec na czczo, wezwany do gabinetu zabiegowego celem pobrania krwi. Pielęgniarka pobrała zlecone badania krwi, porządkuje zestaw,  opisuje probówki , w tym czasie nawiązuje kontakt werbalny i wzrokowy z pacjentem po badaniu. Nagle chłopiec uskarża się na mdłości i zawroty głowy. Skóra spocona, blada, zasinione usta. Oddech przyspieszony. Chłopiec traci przytomność:. brak kontaktu słownego i wzrokowego, zwiotczenie i osunięcie się dziecka ku dołowi.",["Pielęgniarstwo pediatryczne"],["2020-11 - Zatorska-Winiarska Agnieszka - Omdlenie chlopca.docx","2020-11 - Zatorska-Winiarska Agnieszka - Omdlenie chlopca - checklista.docx"]);
			$scen_id=add_scenario("azaworska","2","Padaczka dziewczynki","PED-PAD12","Stan drgawkowy.","12 - letnia dziewczynka, z rozpoznaną w 5 roku życia (po przebytym w okresie niemowlęcym ropnym zapaleniu opon mózgowo-rdzeniowych), została przywieziona do szpitala przez zespół ratownictwa medycznego z powodu napadu drgawek. Dziewczynka została znaleziona w szkole przez koleżanki z klasy na podłodze, na korytarzu. Z. Z relacji nauczyciela dziewczynka na stałe przyjmowała leki p/padaczkowe: Depakine 2x500mg Dziewczynka senna, zdezorientowana,  oddała bezwiednie mocz, brak zewnętrznych obrażeń ciała. Zespół RM założył kaniulę dożylną, podano doodbytniczo Relsed. Dziewczynka przyjęta do szpitala do oddziału neurologii dziecięcej . Z wywiadu od matki (która została powiadomiona o zdarzeniu przez nauczycielkę) wiadomo, wywiadu od kilku dni : infekcja przewodu pokarmowego, wymioty ze dziewczynka w ciągu ostatnich dni miała 2 napady 4-5 minutowe,  toniczno-kloniczne, podczas których utraciła przytomność , leki przyjmowała mimo wymiotów, dziewczynka miała zaplanowaną wizytę u pediatry.",["Pielęgniarstwo pediatryczne"],["2020-11 - Zatorska-Winiarska Agnieszka - Padaczka dziewczynki.docx","2020-11 - Zatorska-Winiarska Agnieszka - Padaczka dziewczynki - checklista.docx"]);
			$scen_id=add_scenario("mkaczmarczyk","1","Deficyty w starości – niedowład + niedosłuch + cukrzyca","NPE-DEF01","Trudności w samodzielnym poruszaniu się osoby starszej wynikające z procesu starzenia oraz deficytów zdrowia w obrębie układu ruchu i narządów zmysłu oraz mowy.","U pacjenta geriatrycznego z ograniczeniami w samodzielnym poruszaniu się w wyniku przebytego udaru mózgu – niedowład + niedosłyszenie + cukrzyca. Zlecono badanie usg jamy brzusznej. Pacjent jest na czczo. Przygotuj pacjenta do badania oraz zaprowadź do pracowni diagnostycznej, która znajduje się na II piętrze w p. B.2.16. ",["Pielęgnowanie niepełnosprawnych"],["2020-10-Kamusinska Elzbieta, Kaczmarczyk Malgorzata - Deficyty w starosci scenariusz 1.docx","2020-10-Kamusinska Elzbieta, Kaczmarczyk Malgorzata - Deficyty w starosci scenariusz 1 - checklista.docx"]);
			$scen_id=add_scenario("mkaczmarczyk","1","Deficyty w starości – zwyrodnienia stawów + jaskra","NPE-DEF02","Trudności w samodzielnym poruszaniu się osoby starszej wynikające z procesu starzenia oraz deficytów zdrowia w obrębie układu ruchu i narządów zmysłu oraz mowy.","U pacjenta geriatrycznego z ograniczeniami w samodzielnym poruszaniu się w wyniku trudności w lokomocji z powodu zwyrodnienia stawów + jaskra (zaburzenia pola widzenia). Zlecono badanie rtg klatki piersiowej. Pacjent jest na czczo. Przygotuj pacjenta do badania oraz zaprowadź do pracowni diagnostycznej, która znajduje się na II piętrze w p. B.2.16. ",["Pielęgnowanie niepełnosprawnych"],["2020-10-Kamusinska Elzbieta, Kaczmarczyk Malgorzata - Deficyty w starosci scenariusz 2.docx","2020-10-Kamusinska Elzbieta, Kaczmarczyk Malgorzata - Deficyty w starosci scenariusz 2 - checklista.docx"]);
			$scen_id=add_scenario("mkaczmarczyk","1","Deficyty w starości - niedowład + afazja","NPE-DEF03","Trudności w samodzielnym poruszaniu się osoby starszej wynikające z procesu starzenia oraz deficytów zdrowia w obrębie układu ruchu i narządów zmysłu oraz mowy.","U pacjenta geriatrycznego z ograniczeniami w samodzielnym poruszaniu się w wyniku przebytego udaru mózgu - niedowład + afazja (rozumie mowę, ale nie mówi, wydaj dźwięki – sylaby, które są nieadekwatne do sytuacji). Zlecono z badanie TK głowy z kontrastem. Pacjent jest na czczo. Przygotuj pacjenta do badania oraz zaprowadź do pracowni diagnostycznej, która znajduje się na II piętrze w p. B.2.16.",["Pielęgnowanie niepełnosprawnych"],["2020-10-Kamusinska Elzbieta, Kaczmarczyk Malgorzata - Deficyty w starosci scenariusz 3.docx","2020-10-Kamusinska Elzbieta, Kaczmarczyk Malgorzata - Deficyty w starosci scenariusz 3 - checklista.docx"]);
			$scen_id=add_scenario("akaleta","1","Pacjent w stanie terminalnym","PAL-TER01","Cierpienie w chorobie nowotworowej.","Pacjent Witold Maćkowiak, lat 68, przebywa w hospicjum  z powodu rozsianego procesu nowotworowego. Przyczyna pierwotna – nieoperacyjny rak drobnokomórkowy płuc. Pacjent choruje od 5 lat, był poddawany chemioterapii, jednak proces nowotworowy postępuje. Od 3 m-cy chory przebywa w oddziale opieki paliatywnej ze względu na silne dolegliwości bólowe i brak możliwości rodziny do sprawowania nad nim opieki. Ma tylko żonę – niepełnosprawną, po amputacji kończyny dolnej, bez wykształcenia medycznego.",["Opieka paliatywna"],["2020-10 - Kaleta Agnieszka - Pacjent terminalny.docx","2020-10 - Kaleta Agnieszka - Pacjent terminalny - checklista.docx"]);
			$scen_id=add_scenario("alesiak","1","Pacjentka w stanie terminalnym","PAL-TER02","Brak wiedzy rodziny na temat opieki nad pacjentem w stanie terminalnym.","Pacjentka Alicja  Nowakowska, lat 88, od 3 tygodni przebywa w oddziale wewnętrznym z powodu przebytego zapalenia płuc. Główną jednostką chorobową jest zaawansowany nieoperacyjny  nowotwór przełyku. Pacjentka choruje od 2 lat, otrzymała 3 cykle chemioterapii. Przed pobytem w szpitalu  był chodząca i nie wymagała całodobowej opieki. Miała dietę płynną. W chwili obecnej chora leżąca, ma problemy. Karmiona przez sondę/PEG-a ze względu na zaburzenia połykania spowodowane guzem. Leczy sią na nadciśnienie i cukrzycę. Przyjmuje leki p/bólowe w formie plastrów, leki p/zakrzepowe, leki obniżające ciśnienie tętnicze krwi oraz insulinę Wymaga pomocy podczas wszystkich czynności higienicznych i pielęgnacyjnych. Mieszka z córką i zięciem w domku jednorodzinnym. ",["Opieka paliatywna"],["2020-10 - Lesiak Aneta - Pacjent w stanie  terminalnym - v00.docx","2020-10 - Lesiak Aneta - Pacjent terminalny - checklista.docx"]);

SimScen("2020-11-02","08:00","jsikorska",["PED-ROW13","PED-DRG02","PED-DUS03","PED-OPA04"]);
SimScen("2020-11-02","08:00","ekaminska",["NPE-DEF01","NPE-DEF02","NPE-DEF03"]);
SimScen("2020-11-02","14:45","azaworska",["PED-OMD10","PED-PAD12"]);
SimScen("2020-11-03","07:30","ekamusinska",["NPE-DEF01","NPE-DEF02","NPE-DEF03"]);
SimScen("2020-11-03","08:00","jsikorska",["PED-ROW13","PED-DRG02","PED-DUS03","PED-OPA04"]);
SimScen("2020-11-04","14:45","azaworska",["PED-OMD10","PED-PAD12"]);
SimScen("2020-11-05","08:00","ekamusinska",["NPE-DEF01","NPE-DEF02","NPE-DEF03"]);
SimScen("2020-11-09","11:00","jsikorska",["PED-ROW13","PED-DRG02","PED-DUS03","PED-OPA04"]);
SimScen("2020-11-10","07:30","ekamusinska",["NPE-DEF01","NPE-DEF02","NPE-DEF03"]);
SimScen("2020-11-12","08:00","ekamusinska",["NPE-DEF01","NPE-DEF02","NPE-DEF03"]);
SimScen("2020-11-12","14:45","azaworska",["PED-OMD10","PED-PAD12"]);
SimScen("2020-11-13","08:00","ekamusinska",["NPE-DEF01","NPE-DEF02","NPE-DEF03"]);
SimScen("2020-11-16","14:45","azaworska",["PED-OMD10","PED-PAD12"]);
SimScen("2020-11-18","08:00","jsikorska",["PED-ROW13","PED-DRG02","PED-DUS03","PED-OPA04"]);
SimScen("2020-11-19","14:45","azaworska",["PED-OMD10","PED-PAD12"]);
SimScen("2020-11-20","08:00","jsikorska",["PED-ROW13","PED-DRG02","PED-DUS03","PED-OPA04"]);
SimScen("2020-11-24","08:00","jsikorska",["PED-ROW13","PED-DRG02","PED-DUS03","PED-OPA04"]);
SimScen("2020-12-14","12:30","alesiak",["PAL-TER01","PAL-TER02"]);
SimScen("2020-12-14","15:30","alesiak",["PAL-TER01","PAL-TER02"]);
SimScen("2021-03-12","08:00","jsikorska",["PED-ROW13","PED-DRG02","PED-DUS03","PED-OPA04"]);
SimScen("2021-03-12","11:00","jsikorska",["PED-ROW13","PED-DRG02","PED-DUS03","PED-OPA04"]);
SimScen("2021-03-19","08:00","jsikorska",["PED-ROW13","PED-DRG02","PED-DUS03","PED-OPA04"]);
SimScen("2021-03-19","11:00","jsikorska",["PED-ROW13","PED-DRG02","PED-DUS03","PED-OPA04"]);
SimScen("2021-03-26","08:00","jsikorska",["PED-ROW13","PED-DRG02","PED-DUS03","PED-OPA04"]);
SimScen("2021-03-26","11:00","jsikorska",["PED-ROW13","PED-DRG02","PED-DUS03","PED-OPA04"]);


    }
}
