<?php
//php artisan db:seed --class=PlikTableSeeder
use Illuminate\Database\Seeder;

use App\Plik;
use App\PlikForItem;
use App\PlikForGroup;
use App\PlikForRoom;

class PlikTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        function add_plik($aF_plik_directory,$aF_plik_name,$aF_plik_version,$aF_plik_title,$aF_type_name,$aF_plik_description,$aF_plik_status)
        {
            $type_id = App\PlikType::where('plik_type_name',$aF_type_name)->first()->id;
            $zmEQ = new Plik();
            $zmEQ->plik_directory=$aF_plik_directory;
            $zmEQ->plik_name=$aF_plik_name;
            $zmEQ->plik_version=$aF_plik_version;
            $zmEQ->plik_title=$aF_plik_title;
            $zmEQ->plik_type_id=$type_id;
            $zmEQ->plik_description=$aF_plik_description;
            $zmEQ->plik_status=$aF_plik_status;
            $zmEQ->save();
            return $zmEQ->id;
        }


        function add_plik_group($aF_group,$aF_plik_directory,$aF_plik_name,$aF_plik_version,$aF_plik_title,$aF_type_name,$aF_plik_description,$aF_plik_status)
        {
            $Group_id = App\ItemGroup::where('item_group_name',$aF_group)->first()->id;
            $plik_id=add_plik($aF_plik_directory,$aF_plik_name,$aF_plik_version,$aF_plik_title,$aF_type_name,$aF_plik_description,$aF_plik_status);
            $zmER = new PlikForGroup();
            $zmER->plik_id   = $plik_id;
            $zmER->item_group_id = $Group_id;
            $zmER->save();
        }

        function add_plik_room($aF_room,$aF_plik_directory,$aF_plik_name,$aF_plik_version,$aF_plik_title,$aF_type_name,$aF_plik_description,$aF_plik_status)
        {
            $Room_id=App\Rooms::where('rooms_number',$aF_room)->first()->id;

            $plik_id=add_plik($aF_plik_directory,$aF_plik_name,$aF_plik_version,$aF_plik_title,$aF_type_name,$aF_plik_description,$aF_plik_status);

            $zmER = new PlikForRoom();
            $zmER->plik_id   = $plik_id;
            $zmER->room_id = $Room_id;
            $zmER->save();

            return $zmEQ->id;
        }

        $Ftype = new \App\PlikType();
        $Ftype->plik_type_menu = '';
        $Ftype->plik_type_menu_code = '';
        $Ftype->plik_type_name = 'nieprzypisane';
        $Ftype->plik_type_sort = '1';
        $Ftype->save();
            $id_x = $Ftype->id;
        $Ftype = new \App\PlikType();
        $Ftype->plik_type_menu = 'regulaminy';
        $Ftype->plik_type_menu_code = 'regulations';
        $Ftype->plik_type_name = 'regulaminy';
        $Ftype->plik_type_sort = '10';
        $Ftype->save();

        $Ftype = new \App\PlikType();
        $Ftype->plik_type_menu = 'instrukcje prod.';
        $Ftype->plik_type_menu_code = 'instructions';
        $Ftype->plik_type_name = 'instrukcje producenta';
        $Ftype->plik_type_sort = '20';
        $Ftype->save();

        $Ftype = new \App\PlikType();
        $Ftype->plik_type_menu = 'instrukcje CSM';
        $Ftype->plik_type_menu_code = 'csminstructions';
        $Ftype->plik_type_name = 'instrukcje Centrum Symulacji Medycznej';
        $Ftype->plik_type_sort = '30';
        $Ftype->save();

        $Ftype = new \App\PlikType();
        $Ftype->plik_type_menu = 'Formularze';
        $Ftype->plik_type_menu_code = 'forms';
        $Ftype->plik_type_name = 'Formularze';
        $Ftype->plik_type_sort = '40';
        $Ftype->save();

        $Ftype = new \App\PlikType();
        $Ftype->plik_type_menu = 'procedury medyczne';
        $Ftype->plik_type_menu_code = 'procedures';
        $Ftype->plik_type_name = 'Procedury Medyczne';
        $Ftype->plik_type_sort = '50';
        $Ftype->save();

        $Ftype = new \App\PlikType();
        $Ftype->plik_type_menu = 'druki medyczne';
        $Ftype->plik_type_menu_code = 'prints';
        $Ftype->plik_type_name = 'Druki Medyczne';
        $Ftype->plik_type_sort = '60';
        $Ftype->save();



add_plik_group("Infinity Drager: Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan", "instr_prod/","aparat_monitorowania_blokady_nerwowej_ToFscan.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Infinity Drager: Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan","0");
add_plik_group("Infinity Drager: Stacja dokująca monitora pacjenta M500", "instr_prod/","InfinityM540Monitor-InfinityM500DockingStation.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Infinity Drager: Stacja dokująca monitora pacjenta M500","0");
add_plik_group("Infinity Drager: Interfejs pulsoksymetru Rainbow Mount Kit", "instr_prod/","infinity-rainbow-us.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Infinity Drager: Interfejs pulsoksymetru Rainbow Mount Kit","0");
add_plik_group("Infinity Drager: Kokpit systemu opieki doraźnej C500", "instr_prod/","kokpit_infinity_C500.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Infinity Drager: Kokpit systemu opieki doraźnej C500","0");
add_plik_group("Jednostka zasilająca Drager Movita", "instr_prod/","kolumna_movita.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Jednostka zasilająca Drager Movita","0");
add_plik_group("Jednostka zasilająca Drager Ponta-Agila", "instr_prod/","kolumna_ponta.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Jednostka zasilająca Drager Ponta-Agila","0");
add_plik_group("trenażer dostępu dożylnego - tułów Laerdal Laerdal IV Torso", "instr_prod/","Laerdal_IV_torso+arm+foot.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: trenażer dostępu dożylnego - tułów Laerdal Laerdal IV Torso","0");
add_plik_group("Infinity Drager: Monitor pacjenta M540", "instr_prod/","monitor_pacjenta_infinity_m540.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Infinity Drager: Monitor pacjenta M540","0");
add_plik_group("trenażer do konikotomii Nasco Delux 135", "instr_prod/","Nasco_101-135_trenazer_do_konikotomii_delux.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: trenażer do konikotomii Nasco Delux 135","0");
add_plik_group("fantom laryngologiczny Nasco LF 01019", "instr_prod/","Nasco_LF01019_trenazer_laryngologiczny.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: fantom laryngologiczny Nasco LF 01019","0");
add_plik_group("Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "instr_prod/","Nasco_LF01400_fantom_noworodka_PALS_i_pielegniarski.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400","0");
add_plik_group("Trenażer udrażniania DO - dziecko Nasco LF 03609", "instr_prod/","Nasco_LF03609_trenazer_udr_dr_odd_dziecko.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Trenażer udrażniania DO - dziecko Nasco LF 03609","0");
add_plik_group("trenażer do nauki intubacji Airway Larry Nasco LF 03685", "instr_prod/","Nasco_LF03685_trenazer_do_intubacji.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: trenażer do nauki intubacji Airway Larry Nasco LF 03685","0");
add_plik_group("Trenażer do badania oka Rouilly Adam AR 403", "instr_prod/","RouillyAdam_trenazer_do_badania_oka.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Trenażer do badania oka Rouilly Adam AR 403","0");
add_plik_group("Stanowisko do znieczulenia Drager Primus", "instr_prod/","stanowisko_do_znieczulenia_primus.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Stanowisko do znieczulenia Drager Primus","0");
add_plik_group("Pediatric Hal S2225 adv", "instr_prod/","Gaumard HAL S2225 (Franek) instrukcja.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Pediatric Hal S2225 adv","0");
add_plik_group("Pediatric Hal S3005", "instr_prod/","Gaumard HAL S3005 (Wojtek) instrukcja.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Pediatric Hal S3005","0");
add_plik_group("Super Tory S2220", "instr_prod/","Gaumard Super Tory S2220 instrukcja.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Super Tory S2220","0");
add_plik_group("Victoria S2200", "instr_prod/","Gaumard Victoria instrukcja.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Victoria S2200","0");
add_plik_group("Pediatric Hal S3005", "instr_prod/","Hal 3201 (medicus).pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Pediatric Hal S3005","0");
add_plik_group("Defibrylator Comen S5", "instr_prod/","Instrukcja defibrylator Comen S5_Pol.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Defibrylator Comen S5","0");
add_plik_group("EKG Comen H12", "instr_prod/","Instrukcja obslugi - EKG_H12.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: EKG Comen H12","0");
add_plik_group("Pompa objętościowa EN-V7 Smart", "instr_prod/","Instrukcja obslugi pompy przeplywowe EN-V7_PL.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Pompa objętościowa EN-V7 Smart","0");
add_plik_group("Pompa strzykawkowa EN-S7 Smart", "instr_prod/","Instrukcja obslugi pompy strzykawkowe EN-S7_PL.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Pompa strzykawkowa EN-S7 Smart","0");
add_plik_group("Ssak elektryczny Dynamic II", "instr_prod/","Instrukcja obslugi ssak Dynamic II.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Ssak elektryczny Dynamic II","0");
add_plik_group("KTG Comen C21", "instr_prod/","Kardiotokograf Comen C21C22_PL_w2.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: KTG Comen C21","0");
add_plik_group("zaawansowany symulator osoby dorosłej ALS", "instr_prod/","MegaCode Kelly SimPad.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: zaawansowany symulator osoby dorosłej ALS","0");
add_plik_group("zaawansowany symulator dziecka ALS", "instr_prod/","MegaCode Kid SimPad - PL.PDF","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: zaawansowany symulator dziecka ALS","0");
add_plik_group("Super Tory S2220", "instr_prod/","Newborn Tory (medicus).pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Super Tory S2220","0");
add_plik_group("Pediatric Hal S2225 adv", "instr_prod/","Pediatric HAL (medicus).pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Pediatric Hal S2225 adv","0");
add_plik_group("Symulator endoskopii GI-Bronch Mentor 3D Systems GI-Bronch Mentor", "instr_prod/","Simedu - BRONCH Mentor - Instrukcja obslugi 2020.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Symulator endoskopii GI-Bronch Mentor 3D Systems GI-Bronch Mentor","0");
add_plik_group("Symulator endoskopii GI-Bronch Mentor 3D Systems GI-Bronch Mentor", "instr_prod/","Simedu - GI Mentor - Instrukcja obslugi 2020.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Symulator endoskopii GI-Bronch Mentor 3D Systems GI-Bronch Mentor","0");
add_plik_group("Symulator USG U/S Mentor 3D Systems U/S Mentor", "instr_prod/","Simedu - US Mentor - Instrukcja obslugi 2020.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Symulator USG U/S Mentor 3D Systems U/S Mentor","0");
add_plik_group("Simman 3G", "instr_prod/","SimMan 3G (medicus).pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Simman 3G","0");
add_plik_group("Simman 3G", "instr_prod/","simman 3g - parametry do ustawienia.docx","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Simman 3G","0");
add_plik_group("Simman 3G", "instr_prod/","SimMan 3G Dfu_PL-bez wkluc mostkowych.pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Simman 3G","0");
add_plik_group("Victoria S2200", "instr_prod/","Victoria (medicus).pdf","v. 1","instrukcja producenta","instrukcje producenta","instrukcja: Victoria S2200","0");




        add_plik("","regulamin_csm.pdf","v. 1","regulamin CSM","regulaminy","regulamin Centrum Symulacji Medycznych","0");
        add_plik("","recycling_sprzetu_jednorazowego.pdf","v. 1","instrukcja odzyskiwania sprzętu jednorazowego","instrukcje Centrum Symulacji Medycznej","Instrukcja odzyskiwania sprzętu jednorazowego użytego podczas przebiegu symulacji medycznej w celu ponownego wykorzystania","0");
        add_plik("","formularz_symulacja.pdf","v. 1","formularz scenariusza symulacji","Formularze","Formularz scenariusza symulacji CSM UJK Kielce","0");

        add_plik("/druki","karta_obserwacji_kaniuli_obwodowej.pdf","v. 1","karta obserwacji kaniuli obwodowej","Druki Medyczne","karta obserwacji kaniuli obwodowej","0");
        add_plik("/druki","karta_goraczkowa.pdf","v. 1","karta gorączkowa","Druki Medyczne","karta gorączkowa","0");
        add_plik("/druki","karta_zlecen_lekarskich.pdf","v. 1","karta zleceń lekarskich","Druki Medyczne","karta zleceń lekarskich","0");
        add_plik("/druki","karta_patronazowa.pdf","v. 1","Karta wizyty patronażowej pielęgniarki POZ","Druki Medyczne","Karta wizyty patronażowej pielęgniarki POZ","0");
        add_plik("/druki","karta_uodpornienia.pdf","v. 1","karta uodpornienia","Druki Medyczne","karta uodpornienia","0");
        add_plik("/druki","karta_srodowiskowa_rodziny.pdf","v. 1","karta środowiskowa rodziny","Druki Medyczne","karta środowiskowa rodziny","0");
        add_plik("/druki","karta_obswerwacji_pacjenta_z_cewnikiem.pdf","v. 1","karta obswerwacji pacjenta z cewnikiem","Druki Medyczne","karta obswerwacji pacjenta z cewnikiem","0");


    }
}
