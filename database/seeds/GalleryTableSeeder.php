<?php
//php artisan db:seed --class=GalleryTableSeeder
use Illuminate\Database\Seeder;

use App\Gallery;
use App\GalleryPhoto;
use App\GalleryForItem;
use App\GalleryForRoom;

class GalleryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        function make_group_gallery($aF_group, $aF_name, $aF_description, $aF_folder)
        {
        $Group_id = App\ItemGroup::where('item_group_name',$aF_group)->first()->id;

        $zmEQ = new Gallery();
        $zmEQ->gallery_name=$aF_name;
        $zmEQ->gallery_description=$aF_description;
        $zmEQ->gallery_folder=$aF_folder;
        //$zmEQ->item_galleries_type->default(1);
        //$zmEQ->item_galleries_sort->default(1);
        //$zmEQ->item_galleries_status->default(1);
        $zmEQ->save();

        $zmER = new GalleryForGroup();
        $zmER->gallery_id   = $zmEQ->id;
        $zmER->item_group_id = $Group_id;
        $zmER->save();

        return $zmEQ->id;
        }


        function make_item_gallery($aF_item, $aF_name, $aF_description, $aF_folder)
        {
        $Item_id = App\Item::where('item_inventory_number',$aF_item)->first()->id;

        $zmEQ = new Gallery();
        $zmEQ->gallery_name=$aF_name;
        $zmEQ->gallery_description=$aF_description;
        $zmEQ->gallery_folder=$aF_folder;
        //$zmEQ->item_galleries_type->default(1);
        //$zmEQ->item_galleries_sort->default(1);
        //$zmEQ->item_galleries_status->default(1);
        $zmEQ->save();

        $zmER = new GalleryForItem();
        $zmER->gallery_id   = $zmEQ->id;
        $zmER->item_id = $Item_id;
        $zmER->save();

        return $zmEQ->id;
        }

        function make_room_gallery($aF_room, $aF_name, $aF_description, $aF_folder)
        {
        $Room_id=App\Room::where('room_number',$aF_room)->first()->id;
        $zmEQ = new Gallery();
        $zmEQ->gallery_name=$aF_name;
        $zmEQ->gallery_description=$aF_description;
        $zmEQ->gallery_folder=$aF_folder;
        $zmEQ->save();

        $zmER = new GalleryForRoom();
        $zmER->gallery_id   = $zmEQ->id;
        $zmER->room_id = $Room_id;
        $zmER->save();

        return $zmEQ->id;
        }


        function put_photos($aF_gallery, $aF_photos_name, $aF_photos_title, $aF_photos_description)
            {

            $xF_name_array=explode(';',$aF_photos_name);
            $xF_title_array=explode(';',$aF_photos_title);
            $xF_descr_array=explode(';',$aF_photos_description);

            if ((count($xF_name_array)==count($xF_title_array)) && count($xF_title_array)==count($xF_descr_array))
                {
                for ($i=0;$i<count($xF_name_array);$i++)
                    {
                    //echo "$xF_name_array[$i] | $xF_title_array[$i] | $xF_descr_array[$i]  \n";

                    $zmEQ = new GalleryPhoto();
                    $zmEQ->gallery_id=$aF_gallery;
                    $zmEQ->gallery_photo_name=$xF_name_array[$i];
                    $zmEQ->gallery_photo_title=$xF_title_array[$i];
                    $zmEQ->gallery_photo_description=$xF_descr_array[$i];
                    //$zmEQ->gallery_photo_sort->default(1);
                    //$zmEQ->gallery_photo_status->default(1);
                    $zmEQ->save();

                    }
                //echo "\n wstawiono \n\n";
                }
            else
                {
                echo "\n\n###############\nNIE POSZLO\n###############\n\n";
                echo " -> Nazw   : ".count($xF_name_array)."\n";
                echo " -> Tytulow: ".count($xF_title_array)."\n";
                echo " -> Opisow : ".count($xF_descr_array)."\n\n";
                echo print_r($xF_name_array)."\n\n";
                echo "\n\n###############\nNIE NIE NIE\n###############\n\n";
                }
            }

            $id_gall=make_item_gallery("UJK/S/0009107/2020","Symulator endoskopii GI-Bronch Mentor 3D Systems GI-Bronch Mentor", "Symulator endoskopii GI-Bronch Mentor 3D Systems GI-Bronch Mentor", "galerie/bronch_mentor");
            put_photos($id_gall, "bronch_mentor_01.jpg", "widok ogólny symulatora", "widok ogólny symulatora");
            put_photos($id_gall, "bronch_mentor_02.jpg", "symulator zasłonięty płachtą ochronną", "symulator zasłonięty płachtą ochronną");
            put_photos($id_gall, "bronch_mentor_03.jpg", "PENTAX - endoskop jednoportowy", "PENTAX - endoskop jednoportowy");
            put_photos($id_gall, "bronch_mentor_04.jpg", "PENTAX - endoskop trzyportowy", "PENTAX - endoskop trzyportowy");
            put_photos($id_gall, "bronch_mentor_05.jpg", "PENTAX - bronchoskop", "PENTAX - bronchoskop");
            put_photos($id_gall, "bronch_mentor_06.jpg", "peały do robienia prześwietlenia i zdjęć", "peały do robienia prześwietlenia i zdjęć");
            put_photos($id_gall, "bronch_mentor_07.jpg", "złącze do podłączenia endoskopów", "złącze do podłączenia endoskopów");
            put_photos($id_gall, "bronch_mentor_08.jpg", "złącza do podłączania narzędzi endoskopowych", "złącza do podłączania narzędzi endoskopowych");
            put_photos($id_gall, "bronch_mentor_09.jpg", "złącza podłączeń info-elektrycznych", "złącza podłączeń info-elektrycznych");
            put_photos($id_gall, "bronch_mentor_10.jpg", "numer seryjny", "numer seryjny");
            put_photos($id_gall, "bronch_mentor_11.jpg", "numer seryjny komputera", "numer seryjny komputera");
            put_photos($id_gall, "bronch_mentor_12.jpg", "mata pod nogi", "mata pod nogi");
          
          $id_gall=make_item_gallery("UJK/S/0009106/2020","Symulator USG U/S Mentor 3D Systems U/S Mentor", "Symulator USG U/S Mentor 3D Systems U/S Mentor", "galerie/us_mentor");
            put_photos($id_gall, "us_mentor_01.jpg", "widok prawie podłączonego symulatora", "widok prawie podłączonego symulatora");
            put_photos($id_gall, "us_mentor_02.jpg", "widok torsu", "widok torsu");
            put_photos($id_gall, "us_mentor_03.jpg", "widok torsu - wejście dolne", "widok torsu - wejście dolne");
            put_photos($id_gall, "us_mentor_04.jpg", "moduł interfejsów", "moduł interfejsów");
            put_photos($id_gall, "us_mentor_05.jpg", "sonda USG", "sonda USG");
            put_photos($id_gall, "us_mentor_06.jpg", "komputer", "komputer");
            put_photos($id_gall, "us_mentor_07.jpg", "podłączenie torsu do modułu interfejsów", "podłączenie torsu do modułu interfejsów");
            put_photos($id_gall, "us_mentor_08.jpg", "moduł interfejsów - wyprowadzenia tylne", "moduł interfejsów - wyprowadzenia tylne");
            put_photos($id_gall, "us_mentor_09.jpg", "moduł interfejsów - podłączenie przednie 1", "moduł interfejsów - podłączenie przednie 1");
            put_photos($id_gall, "us_mentor_10.jpg", "moduł interfejsów - podłączenie przednie 2,3,4", "moduł interfejsów - podłączenie przednie 2,3,4");
            put_photos($id_gall, "us_mentor_11.jpg", "stojak do głowicy USG", "stojak do głowicy USG");
            put_photos($id_gall, "us_mentor_12.jpg", "numer seryjny symulatora", "numer seryjny symulatora");
            put_photos($id_gall, "us_mentor_13.jpg", "numer seryjny symulatora", "numer seryjny symulatora");
            put_photos($id_gall, "us_mentor_14.jpg", "numer seryjny komputera", "numer seryjny komputera");
          
          
          
          
          
          
          
          
          
          $id_gall=make_group_gallery("trenażer do nauki intubacji Airway Larry Nasco LF 03685","trenażer do nauki intubacji Airway Larry Nasco LF 03685", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "galerie/intubacja_larry_lf03685");
            put_photos($id_gall, "trenazer_intubacji_larry_01.jpg", "trenażer intubacji z walizką transportową", "trenażer intubacji z walizką transportową");
            put_photos($id_gall, "trenazer_intubacji_larry_02.jpg", "trenażer intubacji", "trenażer intubacji");
            put_photos($id_gall, "trenazer_intubacji_larry_03.jpg", "walizka transportowa", "walizka transportowa");
            put_photos($id_gall, "trenazer_intubacji_larry_04.jpg", "lubrykant", "lubrykant");
            put_photos($id_gall, "trenazer_intubacji_larry_05.jpg", "zatyczki do czyszczenia", "zatyczki do czyszczenia");
            put_photos($id_gall, "trenazer_intubacji_larry_06.jpg", "strzykawki do uzyskania laryngospazmu i obrzęku języka", "strzykawki do uzyskania laryngospazmu i obrzęku języka");
          $id_gall=make_item_gallery("UJK/N/0104182/2020","trenażer do nauki intubacji Airway Larry Nasco LF 03685", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "galerie/intubacja_larry_lf03685");
            put_photos($id_gall, "trenazer_intubacji_larry_sn_01.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104183/2020","trenażer do nauki intubacji Airway Larry Nasco LF 03685", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "galerie/intubacja_larry_lf03685");
            put_photos($id_gall, "trenazer_intubacji_larry_sn_02.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104184/2020","trenażer do nauki intubacji Airway Larry Nasco LF 03685", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "galerie/intubacja_larry_lf03685");
            put_photos($id_gall, "trenazer_intubacji_larry_sn_03.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104185/2020","trenażer do nauki intubacji Airway Larry Nasco LF 03685", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "galerie/intubacja_larry_lf03685");
            put_photos($id_gall, "trenazer_intubacji_larry_sn_04.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104186/2020","trenażer do nauki intubacji Airway Larry Nasco LF 03685", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "galerie/intubacja_larry_lf03685");
            put_photos($id_gall, "trenazer_intubacji_larry_sn_05.jpg", "numer seryjny", "numer seryjny");
          
          $id_gall=make_group_gallery("trenażer do konikotomii Nasco Delux 135","trenażer do konikotomii Nasco Delux 135", "trenażer do konikotomii Nasco Delux 135", "galerie/konikotomia_delux135");
            put_photos($id_gall, "trenazer_konikotomii_delux135_01.jpg", "kompletny zestaw trenażera", "kompletny zestaw trenażera");
            put_photos($id_gall, "trenazer_konikotomii_delux135_02.jpg", "trenażer konikotomii", "trenażer konikotomii");
            put_photos($id_gall, "trenazer_konikotomii_delux135_03.jpg", "wymienna chrząstka i drogi oddechowe - dziecko", "wymienna chrząstka i drogi oddechowe - dziecko");
            put_photos($id_gall, "trenazer_konikotomii_delux135_04.jpg", "dodatkowe skóry", "dodatkowe skóry");
            put_photos($id_gall, "trenazer_konikotomii_delux135_05.jpg", "torba transportowa", "torba transportowa");
          $id_gall=make_item_gallery("UJK/N/0104197/2020","trenażer do konikotomii Nasco Delux 135", "trenażer do konikotomii Nasco Delux 135", "galerie/konikotomia_delux135");
            put_photos($id_gall, "trenazer_konikotomii_delux135_sn_01.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104198/2020","trenażer do konikotomii Nasco Delux 135", "trenażer do konikotomii Nasco Delux 135", "galerie/konikotomia_delux135");
            put_photos($id_gall, "trenazer_konikotomii_delux135_sn_02.jpg", "numer seryjny", "numer seryjny");
          
          $id_gall=make_group_gallery("trenażer dostępu dożylnego - tułów Laerdal Laerdal IV Torso","trenażer dostępu dożylnego - tułów Laerdal Laerdal IV Torso", "trenażer dostępu dożylnego - tułów Laerdal Laerdal IV Torso", "galerie/laerdal_iv_torso");
            put_photos($id_gall, "laerdal_iv_torso_01.jpg", "widok trenażera na walizce transportowej", "widok trenażera na walizce transportowej");
            put_photos($id_gall, "laerdal_iv_torso_02.jpg", "widok trenażera ze zdjętą pokrywą", "widok trenażera ze zdjętą pokrywą");
            put_photos($id_gall, "laerdal_iv_torso_03.jpg", "walizka transportowa", "walizka transportowa");
          
          $id_gall=make_group_gallery("fantom laryngologiczny Nasco LF 01019","fantom laryngologiczny Nasco LF 01019", "fantom laryngologiczny Nasco LF 01019", "galerie/trenazer_laryngologiczny");
            put_photos($id_gall, "trenazer_laryngologiczny_01.jpg", "kompletny zestaw trenażera", "kompletny zestaw trenażera");
            put_photos($id_gall, "trenazer_laryngologiczny_02.jpg", "widok w walizce transportowej", "widok w walizce transportowej");
            put_photos($id_gall, "trenazer_laryngologiczny_03.jpg", "walizka transportowa", "walizka transportowa");
            put_photos($id_gall, "trenazer_laryngologiczny_04.jpg", "woski i lubrykant", "woski i lubrykant");
          $id_gall=make_item_gallery("UJK/N/0104202/2020","fantom laryngologiczny Nasco LF 01019", "fantom laryngologiczny Nasco LF 01019", "galerie/trenazer_laryngologiczny");
            put_photos($id_gall, "trenazer_laryngologiczny_sn_01.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104203/2020","fantom laryngologiczny Nasco LF 01019", "fantom laryngologiczny Nasco LF 01019", "galerie/trenazer_laryngologiczny");
            put_photos($id_gall, "trenazer_laryngologiczny_sn_02.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104204/2020","fantom laryngologiczny Nasco LF 01019", "fantom laryngologiczny Nasco LF 01019", "galerie/trenazer_laryngologiczny");
            put_photos($id_gall, "trenazer_laryngologiczny_sn_02.jpg", "numer seryjny", "numer seryjny");
          
          $id_gall=make_group_gallery("Trenażer do badania oka Rouilly Adam AR 403","Trenażer do badania oka Rouilly Adam AR 403", "Trenażer do badania oka Rouilly Adam AR 403", "galerie/trenazer_oka_ar403");
            put_photos($id_gall, "trenazer_oka_01.jpg", "komplet", "komplet");
            put_photos($id_gall, "trenazer_oka_02.jpg", "zbliżenie oka", "zbliżenie oka");
            put_photos($id_gall, "trenazer_oka_03.jpg", "zbliżenie na ustawienia", "zbliżenie na ustawienia");
          $id_gall=make_item_gallery("UJK/N/0104199/2020","Trenażer do badania oka Rouilly Adam AR 403", "Trenażer do badania oka Rouilly Adam AR 403", "galerie/trenazer_oka_ar403");
            put_photos($id_gall, "trenazer_oka_54650.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104200/2020","Trenażer do badania oka Rouilly Adam AR 403", "Trenażer do badania oka Rouilly Adam AR 403", "galerie/trenazer_oka_ar403");
            put_photos($id_gall, "trenazer_oka_55032.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104201/2020","Trenażer do badania oka Rouilly Adam AR 403", "Trenażer do badania oka Rouilly Adam AR 403", "galerie/trenazer_oka_ar403");
            put_photos($id_gall, "trenazer_oka_54947.jpg", "numer seryjny", "numer seryjny");
          
          
          $id_gall=make_item_gallery("UJK/S/0009167/2020","Jednostka zasilająca Drager Ponta-Agila", "Jednostka zasilająca Drager Ponta-Agila", "galerie/kolumna_ponta_oit");
            put_photos($id_gall, "kolumna_ponta_agila_01.jpg", "widok na całą kolumnę", "widok na całą kolumnę");
            put_photos($id_gall, "kolumna_ponta_agila_02.jpg", "oświetlenie górne kolumny", "oświetlenie górne kolumny");
            put_photos($id_gall, "kolumna_agila_l_01.jpg", "kolumna lewa", "kolumna lewa");
            put_photos($id_gall, "kolumna_agila_l_02.jpg", "kolumna lewa - gniazda z przodu", "kolumna lewa - gniazda z przodu");
            put_photos($id_gall, "kolumna_agila_l_03.jpg", "kolumna lewa - gniazda pod spodem", "kolumna lewa - gniazda pod spodem");
            put_photos($id_gall, "kolumna_agila_l_04.jpg", "kolumna lewa - gniazda z tyłu", "kolumna lewa - gniazda z tyłu");
            put_photos($id_gall, "kolumna_agila_l_ramie_do_wlewow.jpg", "ramię do wlewów na kolumnie lewej", "ramię do wlewów na kolumnie lewej");
            put_photos($id_gall, "kolumna_agila_l_ramie_do_wlewow_tab.jpg", "ramię do wlewów na kolumnie lewej (tabliczka)", "ramię do wlewów na kolumnie lewej (tabliczka)");
            put_photos($id_gall, "kolumna_agila_p_01.jpg", "kolumna prawa - lewa strona", "kolumna prawa - lewa strona");
            put_photos($id_gall, "kolumna_agila_p_02.jpg", "kolumna prawa - przód", "kolumna prawa - przód");
            put_photos($id_gall, "kolumna_agila_p_03.jpg", "kolumna prawa - prawa strona", "kolumna prawa - prawa strona");
            put_photos($id_gall, "kolumna_agila_p_04.jpg", "kolumna prawa - tył", "kolumna prawa - tył");
            put_photos($id_gall, "kolumna_agila_p_ramie_obrotowe.jpg", "ramię obrotowe na kolumnie prawej", "ramię obrotowe na kolumnie prawej");
            put_photos($id_gall, "kolumna_agila_p_ramie_obrotowe_tab.jpg", "ramię obrotowe na kolumnie prawej (tabliczka)", "ramię obrotowe na kolumnie prawej (tabliczka)");
            put_photos($id_gall, "kolumna_pointa_agila_sn.jpg", "numer seryjny", "numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009166/2020","Jednostka zasilająca Drager Ponta-Agila", "Jednostka zasilająca Drager Ponta-Agila", "galerie/kolumna_ponta_oit_ped");
            put_photos($id_gall, "kolumna_ponta_agila_ped_01.jpg", "widok na całą kolumnę z mostem", "widok na całą kolumnę z mostem");
            put_photos($id_gall, "kolumna_ponta_agila_ped_02.jpg", "widok kolumny z przodu", "widok kolumny z przodu");
            put_photos($id_gall, "kolumna_ponta_agila_ped_03.jpg", "widok kolumny z prawej strony", "widok kolumny z prawej strony");
            put_photos($id_gall, "kolumna_ponta_agila_ped_04.jpg", "widok kolumny z lewej strony", "widok kolumny z lewej strony");
            put_photos($id_gall, "kolumna_ponta_agila_ped_05.jpg", "widok kolumny z tyłu", "widok kolumny z tyłu");
            put_photos($id_gall, "kolumna_ponta_agila_ped_sn.jpg", "numer seryjny", "numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009168/2020","Panel ścienny Drager Gemina Duo", "Panel ścienny Drager Gemina Duo", "galerie/panel_germinaduo");
            put_photos($id_gall, "panel_geminaduo_06_01.jpg", "widok z prawej strony", "widok z prawej strony");
            put_photos($id_gall, "panel_geminaduo_06_02.jpg", "widok z lewej strony", "widok z lewej strony");
            put_photos($id_gall, "panel_geminaduo_06_03.jpg", "numer seryjny", "numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009169/2020","Panel ścienny Drager Gemina Duo", "Panel ścienny Drager Gemina Duo", "galerie/panel_germinaduo");
            put_photos($id_gall, "panel_geminaduo_04_01.jpg", "widok z prawej strony", "widok z prawej strony");
            put_photos($id_gall, "panel_geminaduo_04_02.jpg", "widok z lewej strony", "widok z lewej strony");
            put_photos($id_gall, "panel_geminaduo_04_03.jpg", "numer seryjny", "numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009170/2020","Panel ścienny Drager Gemina Duo", "Panel ścienny Drager Gemina Duo", "galerie/panel_germinaduo");
            put_photos($id_gall, "panel_geminaduo_05_01.jpg", "widok z prawej strony", "widok z prawej strony");
            put_photos($id_gall, "panel_geminaduo_05_02.jpg", "widok z lewej strony", "widok z lewej strony");
            put_photos($id_gall, "panel_geminaduo_05_03.jpg", "numer seryjny", "numer seryjny");
          
          
          
          
          $id_gall=make_group_gallery("Trenażer udrażniania DO - dziecko Nasco LF 03609","Trenażer udrażniania DO - dziecko Nasco LF 03609", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "galerie/dziecko_udr_do_lf03609");
            put_photos($id_gall, "dziecko_udr_do_lf03609_01.jpg", "komplet z walizką transportową", "komplet z walizką transportową");
            put_photos($id_gall, "dziecko_udr_do_lf03609_02.jpg", "widok trenażera", "widok trenażera");
            put_photos($id_gall, "dziecko_udr_do_lf03609_03.jpg", "trenażer z obróconą głową", "trenażer z obróconą głową");
            put_photos($id_gall, "dziecko_udr_do_lf03609_04.jpg", "lubrykant", "lubrykant");
            put_photos($id_gall, "dziecko_udr_do_lf03609_05.jpg", "zatyczki do czyszczenia", "zatyczki do czyszczenia");
            put_photos($id_gall, "dziecko_udr_do_lf03609_06.jpg", "gąbka ochronna do przechowywania i transportu", "gąbka ochronna do przechowywania i transportu");
            put_photos($id_gall, "dziecko_udr_do_lf03609_07.jpg", "zapakowany trenażer", "zapakowany trenażer");
            put_photos($id_gall, "dziecko_udr_do_lf03609_08.jpg", "zapakowany trenażer", "zapakowany trenażer");
            put_photos($id_gall, "dziecko_udr_do_lf03609_09.jpg", "certyfikat i instrukcja", "certyfikat i instrukcja");
          $id_gall=make_item_gallery("UJK/N/0104187/2020","Trenażer udrażniania DO - dziecko Nasco LF 03609", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "galerie/dziecko_udr_do_lf03609");
            put_photos($id_gall, "dziecko_udr_do_lf03609_sn_532.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104188/2020","Trenażer udrażniania DO - dziecko Nasco LF 03609", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "galerie/dziecko_udr_do_lf03609");
            put_photos($id_gall, "dziecko_udr_do_lf03609_sn_533.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104189/2020","Trenażer udrażniania DO - dziecko Nasco LF 03609", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "galerie/dziecko_udr_do_lf03609");
            put_photos($id_gall, "dziecko_udr_do_lf03609_sn_538.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104190/2020","Trenażer udrażniania DO - dziecko Nasco LF 03609", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "galerie/dziecko_udr_do_lf03609");
            put_photos($id_gall, "dziecko_udr_do_lf03609_sn_539.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104191/2020","Trenażer udrażniania DO - dziecko Nasco LF 03609", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "galerie/dziecko_udr_do_lf03609");
            put_photos($id_gall, "dziecko_udr_do_lf03609_sn_541.jpg", "numer seryjny", "numer seryjny");
          
          $id_gall=make_group_gallery("Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400","Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "galerie/noworodek_lf01400");
            put_photos($id_gall, "noworodek_lf01400_01.jpg", "widok kompletnego zestawu", "widok kompletnego zestawu");
            put_photos($id_gall, "noworodek_lf01400_02.jpg", "noworodek - widok z przodu", "noworodek - widok z przodu");
            put_photos($id_gall, "noworodek_lf01400_03.jpg", "noworodek - widok z tyłu", "noworodek - widok z tyłu");
            put_photos($id_gall, "noworodek_lf01400_04.jpg", "noworodek - górne drogi oddechowe", "noworodek - górne drogi oddechowe");
            put_photos($id_gall, "noworodek_lf01400_05.jpg", "noworodek - płuca (jedne unoszące się jednostronnie)", "noworodek - płuca (jedne unoszące się jednostronnie)");
            put_photos($id_gall, "noworodek_lf01400_06.jpg", "podłączenia żylne oraz skóry doni i stóp", "podłączenia żylne oraz skóry doni i stóp");
            put_photos($id_gall, "noworodek_lf01400_07.jpg", "wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina", "wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina");
            put_photos($id_gall, "noworodek_lf01400_08.jpg", "wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina", "wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina");
            put_photos($id_gall, "noworodek_lf01400_09.jpg", "noworodek - widok po złożeniu", "noworodek - widok po złożeniu");
            put_photos($id_gall, "noworodek_lf01400_10.jpg", "lubrykat i puder", "lubrykat i puder");
            put_photos($id_gall, "noworodek_lf01400_11.jpg", "rezerwuar na krew", "rezerwuar na krew");
            put_photos($id_gall, "noworodek_lf01400_12.jpg", "sztuczna krew", "sztuczna krew");
            put_photos($id_gall, "noworodek_lf01400_13.jpg", "certyfikat i instrukcja", "certyfikat i instrukcja");
          $id_gall=make_item_gallery("UJK/N/0104192/2020","Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "galerie/noworodek_lf01400");
            put_photos($id_gall, "noworodek_lf01400_sn_693.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104193/2020","Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "galerie/noworodek_lf01400");
            put_photos($id_gall, "noworodek_lf01400_sn_694.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104194/2020","Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "galerie/noworodek_lf01400");
            put_photos($id_gall, "noworodek_lf01400_sn_695.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104195/2020","Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "galerie/noworodek_lf01400");
            put_photos($id_gall, "noworodek_lf01400_sn_696.jpg", "numer seryjny", "numer seryjny");
          $id_gall=make_item_gallery("UJK/N/0104196/2020","Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "galerie/noworodek_lf01400");
            put_photos($id_gall, "noworodek_lf01400_sn_697.jpg", "numer seryjny", "numer seryjny");
          
          
          
          
          
          
          
          
          
          
          
          $id_gall=make_item_gallery("UJK/S/0009164/2020","Jednostka zasilająca Drager Movita", "Jednostka zasilająca Drager Movita", "galerie/kolumna_movita");
            put_photos($id_gall, "kolumna_movita_01.jpg", "kolumna movita - widok z przodu", "kolumna movita - widok z przodu");
            put_photos($id_gall, "kolumna_movita_02.jpg", "kolumna movita - widok z lewej", "kolumna movita - widok z lewej");
            put_photos($id_gall, "kolumna_movita_03.jpg", "kolumna movita - widok z tyłu", "kolumna movita - widok z tyłu");
            put_photos($id_gall, "kolumna_movita_04.jpg", "kolumna movita - widok z prawej", "kolumna movita - widok z prawej");
            put_photos($id_gall, "kolumna_movita_05.jpg", "kolumna movita - widok z lewej", "kolumna movita - widok z lewej");
            put_photos($id_gall, "kolumna_movita_06.jpg", "kolumna movita - widok z tyłu", "kolumna movita - widok z tyłu");
            put_photos($id_gall, "kolumna_movita_07.jpg", "kolumna movita - widok z prawej", "kolumna movita - widok z prawej");
            put_photos($id_gall, "kolumna_movita_lampka.jpg", "lampka", "lampka");
            put_photos($id_gall, "kolumna_movita_ramie_do_iniekcji_01.jpg", "ramię do iniekcji", "ramię do iniekcji");
            put_photos($id_gall, "kolumna_movita_ramie_do_iniekcji_02.jpg", "ramię do iniekcji - numer seryjny", "ramię do iniekcji - numer seryjny");
            put_photos($id_gall, "kolumna_movita_ramie_obrotowe_a_01.jpg", "ramię obrotowe", "ramię obrotowe");
            put_photos($id_gall, "kolumna_movita_ramie_obrotowe_a_02.jpg", "ramię obrotowe - numer seryjny", "ramię obrotowe - numer seryjny");
            put_photos($id_gall, "kolumna_movita_ramie_obrotowe_b_01.jpg", "ramię obrotowe", "ramię obrotowe");
            put_photos($id_gall, "kolumna_movita_ramie_obrotowe_b_02.jpg", "ramię obrotowe - numer seryjny", "ramię obrotowe - numer seryjny");
            put_photos($id_gall, "kolumna_movita_sn.jpg", "kolumna movita - numer seryjny", "kolumna movita - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009163/2020","Stanowisko do znieczulenia Drager Primus", "Stanowisko do znieczulenia Drager Primus", "galerie/primus");
            put_photos($id_gall, "stanowisko_do_znieczulania_01.jpg", "Stanowisko do znieczulania - widok z przodu", "Stanowisko do znieczulania - widok z przodu");
            put_photos($id_gall, "stanowisko_do_znieczulania_02.jpg", "Stanowisko do znieczulania - zbliżenie", "Stanowisko do znieczulania - zbliżenie");
            put_photos($id_gall, "stanowisko_do_znieczulania_03.jpg", "Stanowisko do znieczulania - widok monitora", "Stanowisko do znieczulania - widok monitora");
            put_photos($id_gall, "stanowisko_do_znieczulania_04.jpg", "uchwyt na parowniki do anestetyków", "uchwyt na parowniki do anestetyków");
            put_photos($id_gall, "stanowisko_do_znieczulania_08.jpg", "filtr powietrza", "filtr powietrza");
            put_photos($id_gall, "stanowisko_do_znieczulania_09.jpg", "system ogrzewania powietrza", "system ogrzewania powietrza");
            put_photos($id_gall, "stanowisko_do_znieczulania_10.jpg", "rury do oddychania", "rury do oddychania");
            put_photos($id_gall, "stanowisko_do_znieczulania_11.jpg", "podłączenie gazów", "podłączenie gazów");
            put_photos($id_gall, "stanowisko_do_znieczulania_12.jpg", "wyprowadzenia tylne", "wyprowadzenia tylne");
            put_photos($id_gall, "stanowisko_do_znieczulania_13.jpg", "podłączenia sygnałowe", "podłączenia sygnałowe");
            put_photos($id_gall, "stanowisko_do_znieczulania_14.jpg", "podłączenie zasilania", "podłączenie zasilania");
            put_photos($id_gall, "stanowisko_do_znieczulania_dozownik_tlenu.jpg", "dozownik tlenu", "dozownik tlenu");
            put_photos($id_gall, "stanowisko_do_znieczulania_odssys_01.jpg", "system do odsysania wydzieliny z DO", "system do odsysania wydzieliny z DO");
            put_photos($id_gall, "stanowisko_do_znieczulania_odssys_02.jpg", "system do odsysania wydzieliny z DO", "system do odsysania wydzieliny z DO");
            put_photos($id_gall, "stanowisko_do_znieczulania_odssys_03.jpg", "system do odsysania wydzieliny z DO", "system do odsysania wydzieliny z DO");
            put_photos($id_gall, "stanowisko_do_znieczulania_odssys_04.jpg", "system do odsysania - numer serjny", "system do odsysania - numer serjny");
            put_photos($id_gall, "stanowisko_do_znieczulania_sn.jpg", "Primus - numer seryjny", "Primus - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009165/2020","Infinity Drager: Kokpit systemu opieki doraźnej C500", "Infinity Drager: Kokpit systemu opieki doraźnej C500", "galerie/infinity_c500");
            put_photos($id_gall, "infinity_kokpit_500_01.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_02.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_03.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_04.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_05.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_p1_a.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_p1_b.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_p1_c.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_p1_d.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_p2_a.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_p2_b.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_p3_a.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_p3_b.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_sn_1.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_sn_2.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
            put_photos($id_gall, "infinity_kokpit_500_zasilacz.jpg", "Kokpit systemu opieki doraźnej C500", "Kokpit systemu opieki doraźnej C500");
          
          $id_gall=make_item_gallery("UJK/S/0009165/2020 - 5","Infinity Drager: Interfejs pulsoksymetru Rainbow Mount Kit", "Infinity Drager: Interfejs pulsoksymetru Rainbow Mount Kit", "xxx");
            put_photos($id_gall, "xxx01.jpg", "xxx", "xxx");
          
          $id_gall=make_item_gallery("UJK/S/0009165/2020 - 4","Infinity Drager: Stacja dokująca monitora pacjenta M500", "Infinity Drager: Stacja dokująca monitora pacjenta M500", "galerie/infinity_m500");
            put_photos($id_gall, "infinity_stac_dok_m500_01.jpg", "stacja dokująca M500", "stacja dokująca M500");
            put_photos($id_gall, "infinity_stac_dok_m500_02.jpg", "stacja dokująca M500", "stacja dokująca M500");
            put_photos($id_gall, "infinity_stac_dok_m500_03.jpg", "stacja dokująca M500", "stacja dokująca M500");
            put_photos($id_gall, "infinity_stac_dok_m500_04.jpg", "stacja dokująca M500", "stacja dokująca M500");
          
          $id_gall=make_item_gallery("UJK/S/0009165/2020 - 3","Infinity Drager: Monitor pacjenta M540", "Infinity Drager: Monitor pacjenta M540", "galerie/infinity_m540");
            put_photos($id_gall, "infinity_mon_pac_m540_01.jpg", "monitor pacjenta M540", "monitor pacjenta M540");
            put_photos($id_gall, "infinity_mon_pac_m540_02.jpg", "monitor pacjenta M540", "monitor pacjenta M540");
            put_photos($id_gall, "infinity_mon_pac_m540_03.jpg", "monitor pacjenta M540", "monitor pacjenta M540");
          
          $id_gall=make_item_gallery("UJK/S/0009165/2020 - 2","Infinity Drager: Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan", "Infinity Drager: Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan", "galerie/infinity_tofscan");
            put_photos($id_gall, "tofscan01.jpg", "Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan", "Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan");
            put_photos($id_gall, "tofscan02.jpg", "Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan", "Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan");
            put_photos($id_gall, "tofscan03.jpg", "Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan", "Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan");
            put_photos($id_gall, "tofscan04.jpg", "Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan", "Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan");
            put_photos($id_gall, "tofscan05.jpg", "Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan", "Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan");
            put_photos($id_gall, "tofscan06.jpg", "Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan", "Aparat do monitorowania blokady nerwowo-mięśniowej ToFscan");
          
          
          
          
          
          $id_gall=make_item_gallery("UJK/S/0009154/2020","Inkubator Babytherm 8010", "Inkubator Babytherm 8010", "galerie/babytherm");
            put_photos($id_gall, "inkubator_babytherm_8010_01.jpg", "Inkubator Babytherm 8010", "Inkubator Babytherm 8010");
            put_photos($id_gall, "inkubator_babytherm_8010_02.jpg", "Inkubator Babytherm 8010", "Inkubator Babytherm 8010");
            put_photos($id_gall, "inkubator_babytherm_8010_03.jpg", "Inkubator Babytherm 8010", "Inkubator Babytherm 8010");
            put_photos($id_gall, "inkubator_babytherm_8010_04.jpg", "Inkubator Babytherm 8010", "Inkubator Babytherm 8010");
            put_photos($id_gall, "inkubator_babytherm_8010_sn.jpg", "Inkubator Babytherm 8010 - numer seryjny", "Inkubator Babytherm 8010 - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009162/2020","Lampa operacyjna Polaris 100/200 Drager Polaris 100/200", "Lampa operacyjna Polaris 100/200 Drager Polaris 100/200", "galerie/lampa_polaris_200_0026");
            put_photos($id_gall, "lampa_operacyjna_polaris_100_01.jpg", "lampa operacyjna Polaris na ramieniu sufitowym", "lampa operacyjna Polaris na ramieniu sufitowym");
            put_photos($id_gall, "lampa_operacyjna_polaris_100_sn_lampa.jpg", "lampa operacyjna Polaris - numer seryjny lampy", "lampa operacyjna Polaris - numer seryjny lampy");
            put_photos($id_gall, "lampa_operacyjna_polaris_100_sn_ramie.jpg", "lampa operacyjna Polaris - numer seryjny ramienia", "lampa operacyjna Polaris - numer seryjny ramienia");
          
          $id_gall=make_item_gallery("UJK/N/0104294/2020","Lampa zabiegowa mobilna Polaris 50 Mobil", "Lampa zabiegowa mobilna Polaris 50 Mobil", "galerie/lampa_polaris_50");
            put_photos($id_gall, "lampa_polaris_50_mobil_01.jpg", "lampa operacyjna Polaris na ramieniu sufitowym", "lampa operacyjna Polaris na ramieniu sufitowym");
            put_photos($id_gall, "lampa_polaris_50_mobil_01_sn.jpg", "lampa operacyjna Polaris - numer seryjny lampy", "lampa operacyjna Polaris - numer seryjny lampy");
            put_photos($id_gall, "lampa_polaris_50_mobil_02_sn.jpg", "lampa operacyjna Polaris - numer seryjny lampy", "lampa operacyjna Polaris - numer seryjny lampy");
          
          $id_gall=make_group_gallery("Lampa zabiegowa mobilna Polaris 50 Mobil","Lampa zabiegowa mobilna Polaris 50 Mobil", "Lampa zabiegowa mobilna Polaris 50 Mobil", "galerie/lampa_polaris_50");
            put_photos($id_gall, "lampa_zabiegowa_polaris_50_mobil_01.jpg", "lampa operacyjna Polaris na ramieniu sufitowym", "lampa operacyjna Polaris na ramieniu sufitowym");
          
          $id_gall=make_item_gallery("UJK/N/0104294/2020","Lampa zabiegowa mobilna Polaris 50 Mobil", "Lampa zabiegowa mobilna Polaris 50 Mobil", "galerie/lampa_polaris_50");
            put_photos($id_gall, "lampa_zabiegowa_polaris_50_mobil_01_sn.jpg", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104295/2020","Lampa zabiegowa mobilna Polaris 50 Mobil", "Lampa zabiegowa mobilna Polaris 50 Mobil", "galerie/lampa_polaris_50");
            put_photos($id_gall, "lampa_zabiegowa_polaris_50_mobil_02_sn.jpg", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104296/2020","Lampa zabiegowa mobilna Polaris 50 Mobil", "Lampa zabiegowa mobilna Polaris 50 Mobil", "galerie/lampa_polaris_50");
            put_photos($id_gall, "lampa_zabiegowa_polaris_50_mobil_03_sn.jpg", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104297/2020","Lampa zabiegowa mobilna Polaris 50 Mobil", "Lampa zabiegowa mobilna Polaris 50 Mobil", "galerie/lampa_polaris_50");
            put_photos($id_gall, "lampa_zabiegowa_polaris_50_mobil_04_sn.jpg", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104298/2020","Lampa zabiegowa mobilna Polaris 50 Mobil", "Lampa zabiegowa mobilna Polaris 50 Mobil", "galerie/lampa_polaris_50");
            put_photos($id_gall, "lampa_zabiegowa_polaris_50_mobil_05_sn.jpg", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104299/2020","Lampa zabiegowa mobilna Polaris 50 Mobil", "Lampa zabiegowa mobilna Polaris 50 Mobil", "galerie/lampa_polaris_50");
            put_photos($id_gall, "lampa_zabiegowa_polaris_50_mobil_06_sn.jpg", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104300/2020","Lampa zabiegowa mobilna Polaris 50 Mobil", "Lampa zabiegowa mobilna Polaris 50 Mobil", "galerie/lampa_polaris_50");
            put_photos($id_gall, "lampa_zabiegowa_polaris_50_mobil_07_sn.jpg", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104301/2020","Lampa zabiegowa mobilna Polaris 50 Mobil", "Lampa zabiegowa mobilna Polaris 50 Mobil", "galerie/lampa_polaris_50");
            put_photos($id_gall, "lampa_zabiegowa_polaris_50_mobil_08_sn.jpg", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny", "lampa operacyjna Polaris na ramieniu sufitowym - numer seryjny");
          
          $id_gall=make_group_gallery("AED Trainer 2","AED Trainer 2", "AED Trainer 2", "galerie/defibrylator_treningowy_laerdal_2");
            put_photos($id_gall, "defibrylator_treningowy_laerdal_2_01.jpg", "Defibrylator treningowy Laerdal 2", "Defibrylator treningowy Laerdal 2");
          
          $id_gall=make_item_gallery("UJK/N/0104220/2020","AED Trainer 2", "AED Trainer 2", "galerie/defibrylator_treningowy_laerdal_2");
            put_photos($id_gall, "defibrylator_treningowy_laerdal_2_01_sn.jpg", "Defibrylator treningowy Laerdal 2 - brak numeru seryjnego", "Defibrylator treningowy Laerdal 2 - brak numeru seryjnego");
          
          $id_gall=make_item_gallery("UJK/N/0104221/2020","AED Trainer 2", "AED Trainer 2", "galerie/defibrylator_treningowy_laerdal_2");
            put_photos($id_gall, "defibrylator_treningowy_laerdal_2_02_sn.jpg", "Defibrylator treningowy Laerdal 2 - brak numeru seryjnego", "Defibrylator treningowy Laerdal 2 - brak numeru seryjnego");
          
          $id_gall=make_item_gallery("UJK/N/0104222/2020","AED Trainer 2", "AED Trainer 2", "galerie/defibrylator_treningowy_laerdal_2");
            put_photos($id_gall, "defibrylator_treningowy_laerdal_2_03_sn.jpg", "Defibrylator treningowy Laerdal 2 - brak numeru seryjnego", "Defibrylator treningowy Laerdal 2 - brak numeru seryjnego");
          
          $id_gall=make_item_gallery("UJK/N/0104223/2020","AED Trainer 2", "AED Trainer 2", "galerie/defibrylator_treningowy_laerdal_2");
            put_photos($id_gall, "defibrylator_treningowy_laerdal_2_04_sn.jpg", "Defibrylator treningowy Laerdal 2 - brak numeru seryjnego", "Defibrylator treningowy Laerdal 2 - brak numeru seryjnego");
          
          $id_gall=make_group_gallery("Resusci Anne QCPR","Resusci Anne QCPR", "Resusci Anne QCPR", "galerie/resusci_anne_qcpr_torso");
            put_photos($id_gall, "resusci_anne_qcpr_01.jpg", "Fantom BLS dorosłego - Resusci Anne QCPR", "Fantom BLS dorosłego - Resusci Anne QCPR");
          
          $id_gall=make_item_gallery("UJK/N/0104208/2020","Resusci Anne QCPR", "Resusci Anne QCPR", "galerie/resusci_anne_qcpr_torso");
            put_photos($id_gall, "resusci_anne_qcpr_sn_29.jpg", "Resusci Anne QCPR - numer seryjny", "Resusci Anne QCPR - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104209/2020","Resusci Anne QCPR", "Resusci Anne QCPR", "galerie/resusci_anne_qcpr_torso");
            put_photos($id_gall, "resusci_anne_qcpr_sn_30.jpg", "Resusci Anne QCPR - numer seryjny", "Resusci Anne QCPR - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104210/2020","Resusci Anne QCPR", "Resusci Anne QCPR", "galerie/resusci_anne_qcpr_torso");
            put_photos($id_gall, "resusci_anne_qcpr_sn_31.jpg", "Resusci Anne QCPR - numer seryjny", "Resusci Anne QCPR - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104211/2020","Resusci Anne QCPR", "Resusci Anne QCPR", "galerie/resusci_anne_qcpr_torso");
            put_photos($id_gall, "resusci_anne_qcpr_sn_32.jpg", "Resusci Anne QCPR - numer seryjny", "Resusci Anne QCPR - numer seryjny");
          
          $id_gall=make_group_gallery("Resusci Baby QCPR","Resusci Baby QCPR", "Resusci Baby QCPR", "galerie/resusci_baby_qcpr");
            put_photos($id_gall, "resusci_baby_qcpr_01.jpg", "Fantom BLS niemowlaka - Resusci Baby QCPR", "Fantom BLS niemowlaka - Resusci Baby QCPR");
          
          $id_gall=make_item_gallery("UJK/N/0104216/2020","Resusci Baby QCPR", "Resusci Baby QCPR", "galerie/resusci_baby_qcpr");
            put_photos($id_gall, "resusci_baby_qcpr_sn_01.jpg", "Resusci Baby QCPR - numer seryjny", "Resusci Baby QCPR - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104217/2020","Resusci Baby QCPR", "Resusci Baby QCPR", "galerie/resusci_baby_qcpr");
            put_photos($id_gall, "resusci_baby_qcpr_sn_04.jpg", "Resusci Baby QCPR - numer seryjny", "Resusci Baby QCPR - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104218/2020","Resusci Baby QCPR", "Resusci Baby QCPR", "galerie/resusci_baby_qcpr");
            put_photos($id_gall, "resusci_baby_qcpr_sn_19.jpg", "Resusci Baby QCPR - numer seryjny", "Resusci Baby QCPR - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104219/2020","Resusci Baby QCPR", "Resusci Baby QCPR", "galerie/resusci_baby_qcpr");
            put_photos($id_gall, "resusci_baby_qcpr_sn_22.jpg", "Resusci Baby QCPR - numer seryjny", "Resusci Baby QCPR - numer seryjny");
          
          $id_gall=make_group_gallery("Resusci Junior QCPR","Resusci Junior QCPR", "Resusci Junior QCPR", "galerie/resusci_junior_qcpr");
            put_photos($id_gall, "resusci_baby_qcpr_01.jpg", "Fantom BLS dziecka - Resusci Junior QCPR", "Fantom BLS dziecka - Resusci Junior QCPR");
          
          $id_gall=make_item_gallery("UJK/N/0104212/2020","Resusci Junior QCPR", "Resusci Junior QCPR", "galerie/resusci_junior_qcpr");
            put_photos($id_gall, "resusci_baby_qcpr_sn_01.jpg", "Resusci Junior QCPR - numer seryjny", "Resusci Junior QCPR - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104213/2020","Resusci Junior QCPR", "Resusci Junior QCPR", "galerie/resusci_junior_qcpr");
            put_photos($id_gall, "resusci_baby_qcpr_sn_04.jpg", "Resusci Junior QCPR - numer seryjny", "Resusci Junior QCPR - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104214/2020","Resusci Junior QCPR", "Resusci Junior QCPR", "galerie/resusci_junior_qcpr");
            put_photos($id_gall, "resusci_baby_qcpr_sn_19.jpg", "Resusci Junior QCPR - numer seryjny", "Resusci Junior QCPR - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/N/0104215/2020","Resusci Junior QCPR", "Resusci Junior QCPR", "galerie/resusci_junior_qcpr");
            put_photos($id_gall, "resusci_baby_qcpr_sn_22.jpg", "Resusci Junior QCPR - numer seryjny", "Resusci Junior QCPR - numer seryjny");
          
          $id_gall=make_group_gallery("Simman 3G","Simman 3G", "Simman 3G", "galerie/simman_3g");
            put_photos($id_gall, "simman_3g_01.jpg", "SimMan 3G", "SimMan 3G");
          
          $id_gall=make_item_gallery("UJK/S/0009136/2020","Simman 3G", "Simman 3G", "galerie/simman_3g");
            put_photos($id_gall, "simman_3g_sn_918.jpg", "SimMan 3G - numer seryjny", "SimMan 3G - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009137/2020","Simman 3G", "Simman 3G", "galerie/simman_3g");
            put_photos($id_gall, "simman_3g_sn_878.jpg", "SimMan 3G - numer seryjny", "SimMan 3G - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009134/2020","Simman 3G", "Simman 3G", "galerie/simman_3g");
            put_photos($id_gall, "simman_3g_sn_919.jpg", "SimMan 3G - numer seryjny", "SimMan 3G - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009135/2020","Simman 3G", "Simman 3G", "galerie/simman_3g");
            put_photos($id_gall, "simman_vascular_sn_020.jpg", "SimMan Vascular - numer seryjny", "SimMan Vascular - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009141/2020","Super Tory S2220", "Super Tory S2220", "galerie/super_torry");
            put_photos($id_gall, "super_torry_01.jpg", "Super Torry", "Super Torry");
            put_photos($id_gall, "super_torry_02.jpg", "Super Torry", "Super Torry");
            put_photos($id_gall, "super_torry_sn_98.jpg", "Super Torry - numer seryjny", "Super Torry - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009140/2020","Victoria S2200", "Victoria S2200", "galerie/victoria");
            put_photos($id_gall, "victoria_01.jpg", "Victoria", "Victoria");
            put_photos($id_gall, "victoria_02.jpg", "Victoria", "Victoria");
            put_photos($id_gall, "victoria_sn_908.jpg", "Victoria - numer seryjny", "Victoria - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009139/2020","Pediatric Hal S2225 adv", "Pediatric Hal S2225 adv", "galerie/pediatric_hal_s2225");
            put_photos($id_gall, "hal_s2225_01.jpg", "Pediatric Hal S 2225", "Pediatric Hal S 2225");
            put_photos($id_gall, "hal_s2225_02.jpg", "Pediatric Hal S 2225", "Pediatric Hal S 2225");
            put_photos($id_gall, "hal_s2225_sn_35.jpg", "Pediatric Hal S 2225 - numer seryjny", "Pediatric Hal S 2225 - numer seryjny");
          
          $id_gall=make_item_gallery("UJK/S/0009138/2020","Pediatric Hal S3005", "Pediatric Hal S3005", "galerie/pediatric_hal_s3005");
            put_photos($id_gall, "hal_s3005_01.jpg", "Pediatric Hal S 3005", "Pediatric Hal S 3005");
            put_photos($id_gall, "hal_s3005_02.jpg", "Pediatric Hal S 3005", "Pediatric Hal S 3005");
            put_photos($id_gall, "hal_s3005_sn_35.jpg", "Pediatric Hal S 3005 - numer seryjny", "Pediatric Hal S 3005 - numer seryjny");

            
            $ret_gal=make_room_gallery("A 1.01","Sala BLS","Sala BLS","rooms/a101");
            put_photos($ret_gal,"a1.01a.jpg","sala BLS","sala BLS");
            put_photos($ret_gal,"a1.01b.jpg","sala BLS","sala BLS");
            put_photos($ret_gal,"a1.01c.jpg","sala BLS","sala BLS");
            put_photos($ret_gal,"a1.01d.jpg","sala BLS","sala BLS");
            $ret_gal=make_room_gallery("A 1.02","Sala ALS","Sala ALS","rooms/a102");
            put_photos($ret_gal,"a1.02a.jpg","sala ALS","sala ALS");
            put_photos($ret_gal,"a1.02b.jpg","sala ALS","sala ALS");
            put_photos($ret_gal,"a1.02c.jpg","sala ALS","sala ALS");
            put_photos($ret_gal,"a1.02d.jpg","sala ALS","sala ALS");
            put_photos($ret_gal,"a1.02-zblizenie-1.jpg","sala ALS","sala ALS");
            put_photos($ret_gal,"a1.02-zblizenie-2.jpg","sala ALS","sala ALS");
            put_photos($ret_gal,"a1.02-zblizenie-3.jpg","sala ALS","sala ALS");
            put_photos($ret_gal,"a1.02ster1.jpg","pomieszczenie kontrolne Sali ALS","pomieszczenie kontrolne Sali ALS");
            put_photos($ret_gal,"a1.02ster2.jpg","pomieszczenie kontrolne Sali ALS","pomieszczenie kontrolne Sali ALS");
            $ret_gal=make_room_gallery("B 3.34","sala lekarska 34","sala lekarska 34","rooms/b334");
            put_photos($ret_gal,"b3.34a.jpg","sala lekarska 34","sala lekarska 34");
            put_photos($ret_gal,"b3.34b.jpg","sala lekarska 34","sala lekarska 34");
            put_photos($ret_gal,"b3.34c.jpg","sala lekarska 34","sala lekarska 34");
            put_photos($ret_gal,"b3.34d.jpg","sala lekarska 34","sala lekarska 34");
            $ret_gal=make_room_gallery("B 3.35","kontrolka sali 34","kontrolka sali 34","rooms/b335");
            put_photos($ret_gal,"b3.35a.jpg","kontrolka sali 34","kontrolka sali 34");
            put_photos($ret_gal,"b3.35b.jpg","kontrolka sali 34","kontrolka sali 34");
            put_photos($ret_gal,"b3.35c.jpg","kontrolka sali 34","kontrolka sali 34");
            put_photos($ret_gal,"b3.35d.jpg","kontrolka sali 34","kontrolka sali 34");
            $ret_gal=make_room_gallery("B 3.36","kontrolka sali 37","kontrolka sali 37","rooms/b336");
            put_photos($ret_gal,"b3.36a.jpg","kontrolka sali 37","kontrolka sali 37");
            put_photos($ret_gal,"b3.36b.jpg","kontrolka sali 37","kontrolka sali 37");
            put_photos($ret_gal,"b3.36c.jpg","kontrolka sali 37","kontrolka sali 37");
            put_photos($ret_gal,"b3.36d.jpg","kontrolka sali 37","kontrolka sali 37");
            $ret_gal=make_room_gallery("B 3.37","sala lekarska 37","sala lekarska 37","rooms/b337");
            put_photos($ret_gal,"b3.37a.jpg","sala lekarska 37","sala lekarska 37");
            put_photos($ret_gal,"b3.37b.jpg","sala lekarska 37","sala lekarska 37");
            put_photos($ret_gal,"b3.37c.jpg","sala lekarska 37","sala lekarska 37");
            put_photos($ret_gal,"b3.37d.jpg","sala lekarska 37","sala lekarska 37");
            $ret_gal=make_room_gallery("B 3.38","sala konferencyjna","sala konferencyjna","rooms/b338");
            put_photos($ret_gal,"b3.38a.jpg","sala konferencyjna","sala konferencyjna");
            put_photos($ret_gal,"b3.38b.jpg","sala konferencyjna","sala konferencyjna");
            put_photos($ret_gal,"b3.38c.jpg","sala konferencyjna","sala konferencyjna");
            put_photos($ret_gal,"b3.38d.jpg","sala konferencyjna","sala konferencyjna");
            $ret_gal=make_room_gallery("C 2.07","sala intensywnej terapii","sala intensywnej terapii","rooms/c207");
            put_photos($ret_gal,"c2.07a.jpg","sala intensywnej terapii","sala intensywnej terapii");
            put_photos($ret_gal,"c2.07b.jpg","sala intensywnej terapii","sala intensywnej terapii");
            put_photos($ret_gal,"c2.07c.jpg","sala intensywnej terapii","sala intensywnej terapii");
            put_photos($ret_gal,"c2.07d.jpg","sala intensywnej terapii","sala intensywnej terapii");
            put_photos($ret_gal,"c2.07e.jpg","sala intensywnej terapii","sala intensywnej terapii");
            put_photos($ret_gal,"c2.07f.jpg","sala intensywnej terapii","sala intensywnej terapii");
            put_photos($ret_gal,"c2.07g.jpg","sala intensywnej terapii","sala intensywnej terapii");
            put_photos($ret_gal,"c2.07h.jpg","sala intensywnej terapii","sala intensywnej terapii");
            $ret_gal=make_room_gallery("C 2.08","kontrolka sali C 2.07","kontrolka sali C 2.07","rooms/c208");
            put_photos($ret_gal,"c2.08a.jpg","kontrolka sali C 2.07","kontrolka sali C 2.07");
            put_photos($ret_gal,"c2.08b.jpg","kontrolka sali C 2.07","kontrolka sali C 2.07");
            $ret_gal=make_room_gallery("C 2.09","sala operacyjna","sala operacyjna","rooms/c209");
            put_photos($ret_gal,"c2.09a.jpg","sala operacyjna","sala operacyjna");
            put_photos($ret_gal,"c2.09b.jpg","sala operacyjna","sala operacyjna");
            put_photos($ret_gal,"c2.09c.jpg","sala operacyjna","sala operacyjna");
            put_photos($ret_gal,"c2.09d.jpg","sala operacyjna","sala operacyjna");
            $ret_gal=make_room_gallery("C 2.10","kontrolka sali operacyjnej","kontrolka sali operacyjnej","rooms/c210");
            put_photos($ret_gal,"c2.10a.jpg","kontrolka sali operacyjnej","kontrolka sali operacyjnej");
            put_photos($ret_gal,"c2.10b.jpg","kontrolka sali operacyjnej","kontrolka sali operacyjnej");
            $ret_gal=make_room_gallery("C 2.11","sala przygotowania pacjenta","sala przygotowania pacjenta","rooms/c211");
            put_photos($ret_gal,"c2.11a.jpg","sala przygotowania pacjenta","sala przygotowania pacjenta");
            put_photos($ret_gal,"c2.11b.jpg","sala przygotowania pacjenta","sala przygotowania pacjenta");
            put_photos($ret_gal,"c2.11c.jpg","sala przygotowania pacjenta","sala przygotowania pacjenta");
            $ret_gal=make_room_gallery("D 0.09","Szpitalny Oddział Ratunkowy","","");
            put_photos($ret_gal,"d0.09a.jpg","Szpitalny Oddział Ratunkowy","Szpitalny Oddział Ratunkowy");
            put_photos($ret_gal,"d0.09b.jpg","Szpitalny Oddział Ratunkowy","Szpitalny Oddział Ratunkowy");
            put_photos($ret_gal,"d0.09c.jpg","Szpitalny Oddział Ratunkowy","Szpitalny Oddział Ratunkowy");
            put_photos($ret_gal,"d0.09d.jpg","Szpitalny Oddział Ratunkowy","Szpitalny Oddział Ratunkowy");
            put_photos($ret_gal,"d0.09e.jpg","Szpitalny Oddział Ratunkowy","Szpitalny Oddział Ratunkowy");
            $ret_gal=make_room_gallery("D 0.10","sala intensywnej terapii pediatryczna","","");
            put_photos($ret_gal,"d0.10a.jpg","sala intensywnej terapii pediatryczna","sala intensywnej terapii pediatryczna");
            put_photos($ret_gal,"d0.10b.jpg","sala intensywnej terapii pediatryczna","sala intensywnej terapii pediatryczna");
            put_photos($ret_gal,"d0.10c.jpg","sala intensywnej terapii pediatryczna","sala intensywnej terapii pediatryczna");
            put_photos($ret_gal,"d0.10d.jpg","sala intensywnej terapii pediatryczna","sala intensywnej terapii pediatryczna");
            put_photos($ret_gal,"d0.10e.jpg","sala intensywnej terapii pediatryczna","sala intensywnej terapii pediatryczna");
            $ret_gal=make_room_gallery("D 0.11 b","pomieszczenie kontrolne sali D 0.10","","");
            put_photos($ret_gal,"d0.11ba.jpg","pomieszczenie kontrolne sali D 0.10","pomieszczenie kontrolne sali D 0.10");
            $ret_gal=make_room_gallery("D 0.11 c","pomieszczenie kontrolne sali D 0.09","","");
            put_photos($ret_gal,"d0.11ca.jpg","pomieszczenie kontrolne sali D 0.09","pomieszczenie kontrolne sali D 0.09");
            $ret_gal=make_room_gallery("D 0.11 d","magazynek D","","");
            put_photos($ret_gal,"d0.11da.jpg","magazynek D","magazynek D");
            put_photos($ret_gal,"d0.11db.jpg","magazynek D","magazynek D");
            
    }
}
