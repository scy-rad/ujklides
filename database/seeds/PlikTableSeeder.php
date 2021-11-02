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

        function add_plik($aF_plik_directory,$aF_plik_name,$aF_plik_version,$aF_plik_title,$aF_type,$aF_plik_description,$aF_plik_status)
        {
            $zmEQ = new Plik();
            $zmEQ->plik_directory=$aF_plik_directory;
            $zmEQ->plik_name=$aF_plik_name;
            $zmEQ->plik_version=$aF_plik_version;
            $zmEQ->plik_title=$aF_plik_title;
            $zmEQ->plik_type_id=$aF_type;
            $zmEQ->plik_description=$aF_plik_description;
            $zmEQ->plik_status=$aF_plik_status;
            $zmEQ->save();
            return $zmEQ->id;
        }


        function add_plik_group($aF_group,$aF_plik_directory,$aF_plik_name,$aF_plik_version,$aF_plik_title,$aF_type,$aF_plik_description,$aF_plik_status)
        {
            $Group_id = App\ItemGroup::where('item_group_name',$aF_group)->first()->id;
            $plik_id=add_plik($aF_plik_directory,$aF_plik_name,$aF_plik_version,$aF_plik_title,$aF_type,$aF_plik_description,$aF_plik_status);
            $zmER = new PlikForGroup();
            $zmER->plik_id   = $plik_id;
            $zmER->item_group_id = $Group_id;
            $zmER->save();
        }

        function add_plik_room($aF_room,$aF_plik_directory,$aF_plik_name,$aF_plik_version,$aF_plik_title,$aF_type,$aF_plik_description,$aF_plik_status)
        {
            $Room_id=App\Rooms::where('rooms_number',$aF_room)->first()->id;

            $plik_id=add_plik($aF_plik_directory,$aF_plik_name,$aF_plik_version,$aF_plik_title,$aF_type,$aF_plik_description,$aF_plik_status);

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
        $Ftype->plik_type_name = 'nieprzypisane';
        $Ftype->plik_type_sort = '10';
        $Ftype->save();
            $id_reg = $Ftype->id;
        $Ftype = new \App\PlikType();
        $Ftype->plik_type_menu = 'instrukcje prod.';
        $Ftype->plik_type_menu_code = 'instructions';
        $Ftype->plik_type_name = 'instrukcje producenta';
        $Ftype->plik_type_sort = '20';
        $Ftype->save();
            $id_prod = $Ftype->id;
        $Ftype = new \App\PlikType();
        $Ftype->plik_type_menu = 'instrukcje CSM';
        $Ftype->plik_type_menu_code = 'csminstructions';
        $Ftype->plik_type_name = 'instrukcje Centrum Symulacji Medycznej';
        $Ftype->plik_type_sort = '30';
        $Ftype->save();
            $id_inscsm = $Ftype->id;
        $Ftype = new \App\PlikType();
        $Ftype->plik_type_menu = 'Formularze';
        $Ftype->plik_type_menu_code = 'forms';
        $Ftype->plik_type_name = 'Formularze Centrum Symulacji Medycznej';
        $Ftype->plik_type_sort = '40';
        $Ftype->save();
            $id_forms = $Ftype->id;
        $Ftype = new \App\PlikType();
        $Ftype->plik_type_menu = 'procedury medyczne';
        $Ftype->plik_type_menu_code = 'procedures';
        $Ftype->plik_type_name = 'Procedury Medyczne';
        $Ftype->plik_type_sort = '50';
        $Ftype->save();
            $id_proc = $Ftype->id;
        $Ftype = new \App\PlikType();
        $Ftype->plik_type_menu = 'druki medyczne';
        $Ftype->plik_type_menu_code = 'prints';
        $Ftype->plik_type_name = 'Druki Medyczne';
        $Ftype->plik_type_sort = '60';
        $Ftype->save();
            $id_druki = $Ftype->id;

        add_plik_group("SimMan 3G", "","instr_prod/simman3g_english.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: manekin Laerdal Simman 3G","0");
        add_plik_group("Trenażer do badania oka Rouilly Adam AR 403", "","instr_prod/RouillyAdam_trenazer_do_badania_oka.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: Trenażer do badania oka Rouilly Adam AR 403","0");
        add_plik_group("Symulator endoskopii GI-Bronch Mentor 3D Systems GI-Bronch Mentor", "","instr_prod/simedu_bronch_mentor_2020.pdf","v. 1","instrukcja producenta Bronch Mentor",$id_prod,"instrukcja: Symulator endoskopii GI-Bronch Mentor 3D Systems GI-Bronch Mentor","0");
        add_plik_group("Symulator endoskopii GI-Bronch Mentor 3D Systems GI-Bronch Mentor", "","instr_prod/simedu_gi_mentor_2020.pdf","v. 1","instrukcja producenta GI Mentor",$id_prod,"instrukcja: Symulator endoskopii GI-Bronch Mentor 3D Systems GI-Bronch Mentor","0");
        add_plik_group("Symulator USG U/S Mentor 3D Systems U/S Mentor", "","instr_prod/simedu_us_mentor_2020.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: Symulator USG U/S Mentor 3D Systems U/S Mentor","0");
        add_plik_group("trenażer dostępu dożylnego - tułów Laerdal Laerdal IV Torso", "","instr_prod/Laerdal_IV_torso+arm+foot.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: trenażer dostępu dożylnego - tułów Laerdal Laerdal IV Torso","0");
        add_plik_group("trenażer do konikotomii Nasco Delux 135", "","instr_prod/Nasco_101-135_trenazer_do_konikotomii_delux.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: trenażer do konikotomii Nasco Delux 135","0");
        add_plik_group("trenażer do nauki intubacji Airway Larry Nasco LF 03685", "","instr_prod/Nasco_LF03685_trenazer_do_intubacji.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: trenażer do nauki intubacji Airway Larry Nasco LF 03685","0");
        add_plik_group("fantom laryngologiczny Nasco LF 01019", "","instr_prod/Nasco_LF01019_trenazer_laryngologiczny.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: fantom laryngologiczny Nasco LF 01019","0");
        add_plik_group("Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "","instr_prod/Nasco_LF01400_fantom_noworodka_PALS_i_pielegniarski.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400","0");
        add_plik_group("Trenażer udrażniania DO - dziecko Nasco LF 03609", "","instr_prod/Nasco_LF03609_trenazer_udr_dr_odd_dziecko.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: Trenażer udrażniania DO - dziecko Nasco LF 03609","0");
        add_plik_group("Jednostka zasilająca Drager Ponta-Agila", "","instr_prod/kolumna_ponta.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: Jednostka zasilająca Ponta-Agila Drager Ponta-Agila","0");
        add_plik_group("Jednostka zasilająca Drager Movita", "","instr_prod/kolumna_movita.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: Jednostka zasilająca Movita Drager Movita","0");
        add_plik_group("Stanowisko do znieczulenia Drager Primus", "","instr_prod/stanowisko_do_znieczulenia_primus.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: Stanowisko do znieczulenia Primus Drager Primus","0");
        add_plik_group("Infinity Drager: Kokpit systemu opieki doraźnej C500", "","instr_prod/kokpit_infinity_C500.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: Kokpit systemu opieki doraźnej Drager Infinity C500","0");
        add_plik_group("Infinity Drager: Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan", "","instr_prod/aparat_monitorowania_blokady_nerwowej_ToFscan.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: Aparat do monitorowania blokady nerwowo-mięśniowej Drager ToFscan","0");
        add_plik_group("Infinity Drager: Monitor pacjenta M540", "","instr_prod/monitor_pacjenta_infinity_m540.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: Monitor pacjenta Infinity m540 Drager Infinity M540","0");
        add_plik_group("Infinity Drager: Stacja dokująca monitora pacjenta M500", "","instr_prod/InfinityM540Monitor-InfinityM500DockingStation.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: Stacja dokująca monitora pacjenta Infinity Drager Infinity M500","0");
        add_plik_group("Infinity Drager: Interfejs pulsoksymetru Rainbow Mount Kit", "","instr_prod/infinity-rainbow-us.pdf","v. 1","instrukcja producenta",$id_prod,"instrukcja: Interfejs pulsoksymetru Infinity Drager Infinity Rainbow Mount Kit","0");


        add_plik("","regulamin_csm.pdf","v. 1","regulamin CSM",$id_reg,"regulamin Centrum Symulacji Medycznych","0");
        add_plik("","recycling_sprzetu_jednorazowego.pdf","v. 1","instrukcja odzyskiwania sprzętu jednorazowego",$id_inscsm,"Instrukcja odzyskiwania sprzętu jednorazowego użytego podczas przebiegu symulacji medycznej w celu ponownego wykorzystania","0");
        add_plik("","formularz_symulacja.pdf","v. 1","formularz scenariusza symulacji",$id_forms,"Formularz scenariusza symulacji CSM UJK Kielce","0");

        add_plik("/druki","karta_obserwacji_kaniuli_obwodowej.pdf","v. 1","karta obserwacji kaniuli obwodowej",$id_druki,"karta obserwacji kaniuli obwodowej","0");
        add_plik("/druki","karta_goraczkowa.pdf","v. 1","karta gorączkowa",$id_druki,"karta gorączkowa","0");
        add_plik("/druki","karta_zlecen_lekarskich.pdf","v. 1","karta zleceń lekarskich",$id_druki,"karta zleceń lekarskich","0");
        add_plik("/druki","karta_patronazowa.pdf","v. 1","Karta wizyty patronażowej pielęgniarki POZ",$id_druki,"Karta wizyty patronażowej pielęgniarki POZ","0");
        add_plik("/druki","karta_uodpornienia.pdf","v. 1","karta uodpornienia",$id_druki,"karta uodpornienia","0");
        add_plik("/druki","karta_srodowiskowa_rodziny.pdf","v. 1","karta środowiskowa rodziny",$id_druki,"karta środowiskowa rodziny","0");
        add_plik("/druki","karta_obswerwacji_pacjenta_z_cewnikiem.pdf","v. 1","karta obswerwacji pacjenta z cewnikiem",$id_druki,"karta obswerwacji pacjenta z cewnikiem","0");


    }
}
