<?php
//php71-cli artisan db:seed --class=GalleryTableSeeder
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

            make_item_gallery("UJK/S/0007608/2013", "stanowisko do pielęgnacji noworodków Techmed SdPN", "stanowisko do pielęgnacji noworodków Techmed SdPN", "galerie/st_pielegnacji_noworodka");
            make_item_gallery("UJK/N/0103717/2019", "podnośnik - zestaw sprzętu do pielęgnacji pacjenta Linak CBJH 1700 0000 004", "podnośnik - zestaw sprzętu do pielęgnacji pacjenta Linak CBJH 1700 0000 004", "galerie/podnosnik_elektryczny");
            make_item_gallery("UJK/S/0009061/2019", "manekin Laerdal Simman 3G", "manekin Laerdal Simman 3G", "galerie/simman3g");
            make_item_gallery("UJK/N/0100715/2013-1", "stadia rozwoju płodu ludzkiego 9 modeli 3B stadia-9", "stadia rozwoju płodu ludzkiego 9 modeli 3B stadia-9", "galerie/modele_plodu_01");
            make_item_gallery("UJK/N/0100715/2013-2", "stadia rozwoju płodu ludzkiego 9 modeli 3B stadia-9", "stadia rozwoju płodu ludzkiego 9 modeli 3B stadia-9", "galerie/modele_plodu_02");
            make_item_gallery("XYZ 00 460", "modele deformacji czaszki", "modele deformacji czaszki", "galerie/deformacje_czaszek");
            make_item_gallery("UJK/S/0007359/2012", "zestaw do pozoracji ran", "zestaw do pozoracji ran", "galerie/pozoracje_0007359");
            make_item_gallery("UJK/S/0007370/2012", "zestaw do pozoracji ran", "zestaw do pozoracji ran", "galerie/pozoracje_0007370");
            make_item_gallery("xx-664", "trenażer opatrywania ran - noga Vata Vinnie Venous Insufficiency Leg", "trenażer opatrywania ran - noga Vata Vinnie Venous Insufficiency Leg", "galerie/pozoracje_0027569");
            make_item_gallery("xx-665", "model skóry z patologią oparzeń Denoyer-Geppert", "model skóry z patologią oparzeń Denoyer-Geppert", "galerie/pozoracje_0027569");
            make_item_gallery("UJK/N/0027569/2010", "zestaw do pozoracji ran", "zestaw do pozoracji ran", "galerie/pozoracje_0027569");
            make_item_gallery("UJK/N/0103694/2019-2", "system audio video kb-port (komputer)", "system audio video kb-port (komputer)", "galerie/pozoracje_0027569");
            make_item_gallery("UJK/N/0103695/2019-2", "system audio video kb-port (komputer)", "system audio video kb-port (komputer)", "galerie/pozoracje_0027569");
            make_item_gallery("UJK/N/0103696/2019-2", "system audio video kb-port (komputer)", "system audio video kb-port (komputer)", "galerie/pozoracje_0027569");
            make_item_gallery("UJK/N/0103697/2019-2", "system audio video kb-port (komputer)", "system audio video kb-port (komputer)", "galerie/pozoracje_0027569");
            make_item_gallery("UJK/N/0103698/2019-2", "system audio video kb-port (komputer)", "system audio video kb-port (komputer)", "galerie/pozoracje_0027569");
            make_item_gallery("UJK/N/0103699/2019-2", "system audio video kb-port (komputer)", "system audio video kb-port (komputer)", "galerie/pozoracje_0027569");
            make_item_gallery("Lekarski 000", "Trenażer do badania oka Rouilly Adam AR 403", "Trenażer do badania oka Rouilly Adam AR 403", "galerie/trenazer_oka_ar403");
            make_item_gallery("Lekarski 001", "Trenażer do badania oka Rouilly Adam AR 403", "Trenażer do badania oka Rouilly Adam AR 403", "galerie/trenazer_oka_ar403");
            make_item_gallery("Lekarski 002", "Trenażer do badania oka Rouilly Adam AR 403", "Trenażer do badania oka Rouilly Adam AR 403", "galerie/trenazer_oka_ar403");
            make_item_gallery("Lekarski 003", "Symulator endoskopii GI-Bronch Mentor 3D Systems GI-Bronch Mentor", "Symulator endoskopii GI-Bronch Mentor 3D Systems GI-Bronch Mentor", "galerie/bronch_mentor");
            make_item_gallery("Lekarski 004", "Symulator USG U/S Mentor 3D Systems U/S Mentor", "Symulator USG U/S Mentor 3D Systems U/S Mentor", "galerie/us_mentor");
            make_item_gallery("Lekarski 005", "trenażer dostępu dożylnego - tułów Laerdal Laerdal IV Torso", "trenażer dostępu dożylnego - tułów Laerdal Laerdal IV Torso", "galerie/laerdal_iv_torso");
            make_item_gallery("Lekarski 006", "trenażer dostępu dożylnego - tułów Laerdal Laerdal IV Torso", "trenażer dostępu dożylnego - tułów Laerdal Laerdal IV Torso", "galerie/laerdal_iv_torso");
            make_item_gallery("Lekarski 007", "trenażer dostępu dożylnego - tułów Laerdal Laerdal IV Torso", "trenażer dostępu dożylnego - tułów Laerdal Laerdal IV Torso", "galerie/laerdal_iv_torso");
            make_item_gallery("Lekarski 008", "trenażer do konikotomii Nasco Delux 135", "trenażer do konikotomii Nasco Delux 135", "galerie/konikotomia_delux135");
            make_item_gallery("Lekarski 009", "trenażer do konikotomii Nasco Delux 135", "trenażer do konikotomii Nasco Delux 135", "galerie/konikotomia_delux135");
            make_item_gallery("Lekarski 010", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "galerie/intubacja_larry_lf03685");
            make_item_gallery("Lekarski 011", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "galerie/intubacja_larry_lf03685");
            make_item_gallery("Lekarski 012", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "galerie/intubacja_larry_lf03685");
            make_item_gallery("Lekarski 013", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "galerie/intubacja_larry_lf03685");
            make_item_gallery("Lekarski 014", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "trenażer do nauki intubacji Airway Larry Nasco LF 03685", "galerie/intubacja_larry_lf03685");
            make_item_gallery("Lekarski 015", "fantom laryngologiczny Nasco LF 01019", "fantom laryngologiczny Nasco LF 01019", "galerie/trenazer_laryngologiczny");
            make_item_gallery("Lekarski 016", "fantom laryngologiczny Nasco LF 01019", "fantom laryngologiczny Nasco LF 01019", "galerie/trenazer_laryngologiczny");
            make_item_gallery("Lekarski 017", "fantom laryngologiczny Nasco LF 01019", "fantom laryngologiczny Nasco LF 01019", "galerie/trenazer_laryngologiczny");
            make_item_gallery("Lekarski 020", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "galerie/noworodek_lf01400");
            make_item_gallery("Lekarski 021", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "galerie/noworodek_lf01400");
            make_item_gallery("Lekarski 022", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "galerie/noworodek_lf01400");
            make_item_gallery("Lekarski 023", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "galerie/noworodek_lf01400");
            make_item_gallery("Lekarski 024", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "Fantom noworodka do RKO i procedur pielęgniarskich Nasco LF 01400", "galerie/noworodek_lf01400");
            make_item_gallery("Lekarski 025", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "galerie/dziecko_udr_do_lf03609");
            make_item_gallery("Lekarski 026", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "galerie/dziecko_udr_do_lf03609");
            make_item_gallery("Lekarski 027", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "galerie/dziecko_udr_do_lf03609");
            make_item_gallery("Lekarski 028", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "galerie/dziecko_udr_do_lf03609");
            make_item_gallery("Lekarski 029", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "Trenażer udrażniania DO - dziecko Nasco LF 03609", "galerie/dziecko_udr_do_lf03609");
            make_item_gallery("Lekarski 030", "Jednostka zasilająca Ponta-Agila Drager Ponta-Agila", "Jednostka zasilająca Ponta-Agila Drager Ponta-Agila", "galerie/kolumna_ponta");
            make_item_gallery("Lekarski 031", "Jednostka zasilająca Movita Drager Movita", "Jednostka zasilająca Movita Drager Movita", "galerie/kolumna_movita");
            make_item_gallery("Lekarski 032", "Lampa operacyjna Polaris 100/200 Drager Polaris 100/200", "Lampa operacyjna Polaris 100/200 Drager Polaris 100/200", "galerie/lampa_polaris_200_0026");
            make_item_gallery("Lekarski 033", "Stanowisko do znieczulenia Primus Drager Primus", "Stanowisko do znieczulenia Primus Drager Primus", "galerie/primus");
            make_item_gallery("xx-803", "Noga niewydolności tętniczej Vata 0555", "Noga niewydolności tętniczej Vata 0555", "galerie/annie_0555");

            
put_photos(1, "stanowisko_0007608-1.jpg;stanowisko_0007608-2.jpg;stanowisko_0007608-3.jpg", "Promiennik podczerwieni; zabudowa meblowa stanowiska;umywalka", "Promiennik podczerwieni; zabudowa meblowa stanowiska;umywalka");
put_photos(2, "podnosnik_elektryczny_0103717_01.jpg;podnosnik_elektryczny_0103717_02.jpg", "podnośnik elektryczny - mechanizm;podnośnik elektryczny - płachta", "podnośnik elektryczny - mechanizm;podnośnik elektryczny - płachta");
put_photos(3, "simman3gp_a.jpg;simman3gp_b.jpg;simman3gp_c.jpg;simman3gp_d.jpg;simman3gp_e.jpg", "Simman 3G;oko;głowa z wypustkami potnymi;stale założony ciśnieniomierz;stale założony wenflon", "widok ogólny;przybliżenie oka;Wypustki potne umożłiwiają symulację pocenia się;stale założony ciśnieniomierz;stale założony wenflon");
put_photos(4, "model_plodu_1m_0100715.jpg;model_plodu_2m_0100715.jpg;model_plodu_3m_0100715.jpg;model_plodu_4m_0100715.jpg;model_plodu_5m_0100715.jpg;model_plodu_5mb_0100715.jpg;model_plodu_5mp_0100715.jpg;model_plodu_5mp2_0100715.jpg;model_plodu_7m_0100715.jpg", "model płodu - 1 miesiąc;model płodu - 2 miesiąc;model płodu - 3 miesiąc;model płodu - 4 miesiąc;model płodu - 5 miesiąc;model płodu - 5 miesiąc - bliźniaki;model płodu - 5 miesiąc - rotacja;model płodu - 5 miesiąc - rotacja 2;model płodu - 7 miesiąc", "model płodu - 1 miesiąc;model płodu - 2 miesiąc;model płodu - 3 miesiąc;model płodu - 4 miesiąc;model płodu - 5 miesiąc;model płodu - 5 miesiąc - bliźniaki;model płodu - 5 miesiąc - rotacja;model płodu - 5 miesiąc - rotacja 2;model płodu - 7 miesiąc");
put_photos(5, "model_plodu_1m_0100715x.jpg;model_plodu_2m_0100715x.jpg;model_plodu_3m_0100715x.jpg;model_plodu_4m_0100715x.jpg;model_plodu_5m_0100715x.jpg;model_plodu_5mb_0100715x.jpg;model_plodu_5mp_0100715x.jpg;model_plodu_5mp2_0100715x.jpg;model_plodu_7m_0100715x.jpg", "model płodu - 1 miesiąc;model płodu - 2 miesiąc;model płodu - 3 miesiąc;model płodu - 4 miesiąc;model płodu - 5 miesiąc;model płodu - 5 miesiąc - bliźniaki;model płodu - 5 miesiąc - rotacja;model płodu - 5 miesiąc - rotacja 2;model płodu - 7 miesiąc", "model płodu - 1 miesiąc;model płodu - 2 miesiąc;model płodu - 3 miesiąc;model płodu - 4 miesiąc;model płodu - 5 miesiąc;model płodu - 5 miesiąc - bliźniaki;model płodu - 5 miesiąc - rotacja;model płodu - 5 miesiąc - rotacja 2;model płodu - 7 miesiąc");
put_photos(6, "deformacje_czaszki_a1.jpg;deformacje_czaszki_a2.jpg;deformacje_czaszki_b1.jpg;deformacje_czaszki_b2.jpg;deformacje_czaszki_c1.jpg;deformacje_czaszki_c2.jpg;deformacje_czaszki_d1.jpg;deformacje_czaszki_d2.jpg;deformacje_czaszki_e1.jpg;deformacje_czaszki_e2.jpg;deformacje_czaszki_f1.jpg", "deformacja czaszki a1;deformacja czaszki a2;deformacja czaszki b1;deformacja czaszki b2;deformacja czaszki c1;deformacja czaszki c2;deformacja czaszki d1;deformacja czaszki d2;deformacja czaszki e1;deformacja czaszki e2;deformacja czaszki f1", "model deformacji czaszki a1;model deformacji czaszki a2;model deformacji czaszki b1;model deformacji czaszki b2;model deformacji czaszki c1;model deformacji czaszki c2;model deformacji czaszki d1;model deformacji czaszki d2;model deformacji czaszki e1;model deformacji czaszki e2;model deformacji czaszki f1");
put_photos(7, "pozoracje_0007359_01.jpg;pozoracje_0007359_02.jpg;pozoracje_0007359_03.jpg;pozoracje_0007359_04.jpg", "pozoracje 01; pozoracje 02;pozoracje 03;pozoracje 04", "pozoracje 01; pozoracje 02;pozoracje 03;pozoracje 04");
put_photos(8, "pozoracje_0007370_01.jpg;pozoracje_0007370_02.jpg", "pozoracje 01; pozoracje 02", "pozoracje 01; pozoracje 02");
put_photos(9, "trenazer_rany_noga_665b.jpg", "trenażer nogi", "trenażer nogi");
put_photos(10, "model_skory_opazenia_666b.jpg", "model skóry", "model skóry");
put_photos(11, "pozoracje_0027569_1.jpg;pozoracje_0027569_2.jpg;pozoracje_0027569_3.jpg", "pozoracje 01; pozoracje 02; pozoracje 03", "pozoracje 01; pozoracje 02; pozoracje 03");
put_photos(12, "monitoring_b118_0103694-2a.jpg;monitoring_b118_0103694-2b.jpg;monitoring_b118_0103694-2c.jpg;monitoring_b118_0103694-2d.jpg", "monitor;komputer;mikser;słuchawki z mikrofonem", "monitor;komputer;mikser;słuchawki z mikrofonem");
put_photos(13, "monitoring_b118_0103695-2a.jpg;monitoring_b118_0103695-2b.jpg;monitoring_b118_0103695-2c.jpg;monitoring_b118_0103695-2d.jpg", "monitor;komputer;mikser;słuchawki z mikrofonem", "monitor;komputer;mikser;słuchawki z mikrofonem");
put_photos(14, "monitoring_b118_0103696-2a.jpg;monitoring_b118_0103696-2b.jpg;monitoring_b118_0103696-2c.jpg;monitoring_b118_0103696-2d.jpg", "monitor;komputer;mikser;słuchawki z mikrofonem", "monitor;komputer;mikser;słuchawki z mikrofonem");
put_photos(15, "monitoring_b118_0103697-2a.jpg;monitoring_b118_0103697-2b.jpg;monitoring_b118_0103697-2c.jpg;monitoring_b118_0103697-2d.jpg", "monitor;komputer;mikser;słuchawki z mikrofonem", "monitor;komputer;mikser;słuchawki z mikrofonem");
put_photos(16, "monitoring_b118_0103698-2a.jpg;monitoring_b118_0103698-2b.jpg;monitoring_b118_0103698-2c.jpg;monitoring_b118_0103698-2d.jpg", "monitor;komputer;mikser;słuchawki z mikrofonem", "monitor;komputer;mikser;słuchawki z mikrofonem");
put_photos(17, "monitoring_b118_0103699-2a.jpg;monitoring_b118_0103699-2b.jpg;monitoring_b118_0103699-2c.jpg;monitoring_b118_0103699-2d.jpg", "monitor;komputer;mikser;słuchawki z mikrofonem", "monitor;komputer;mikser;słuchawki z mikrofonem");
put_photos(18, "trenazer_oka_01.jpg;trenazer_oka_02.jpg;trenazer_oka_03.jpg;trenazer_oka_54650.jpg", "komplet;zbliżenie oka;zbliżenie na ustawienia;numer seryjny", "komplet;zbliżenie oka;zbliżenie na ustawienia;numer seryjny");
put_photos(19, "trenazer_oka_01.jpg;trenazer_oka_02.jpg;trenazer_oka_03.jpg;trenazer_oka_54650.jpg", "komplet;zbliżenie oka;zbliżenie na ustawienia;numer seryjny", "komplet;zbliżenie oka;zbliżenie na ustawienia;numer seryjny");
put_photos(20, "trenazer_oka_01.jpg;trenazer_oka_02.jpg;trenazer_oka_03.jpg;trenazer_oka_54650.jpg", "komplet;zbliżenie oka;zbliżenie na ustawienia;numer seryjny", "komplet;zbliżenie oka;zbliżenie na ustawienia;numer seryjny");
put_photos(21, "bronch_mentor_01.jpg;bronch_mentor_02.jpg;bronch_mentor_03.jpg;bronch_mentor_04.jpg;bronch_mentor_05.jpg;bronch_mentor_06.jpg;bronch_mentor_07.jpg;bronch_mentor_08.jpg;bronch_mentor_09.jpg;bronch_mentor_10.jpg;bronch_mentor_11.jpg;bronch_mentor_12.jpg", "widok ogólny symulatora;symulator zasłonięty płachtą ochronną;PENTAX - endoskop jednoportowy;PENTAX - endoskop trzyportowy;PENTAX - bronchoskop;peały do robienia prześwietlenia i zdjęć;złącze do podłączenia endoskopów;złącza do podłączania narzędzi endoskopowych;złącza podłączeń info-elektrycznych;numer seryjny;numer seryjny komputera;mata pod nogi", "widok ogólny symulatora;symulator zasłonięty płachtą ochronną;PENTAX - endoskop jednoportowy;PENTAX - endoskop trzyportowy;PENTAX - bronchoskop;peały do robienia prześwietlenia i zdjęć;złącze do podłączenia endoskopów;złącza do podłączania narzędzi endoskopowych;złącza podłączeń info-elektrycznych;numer seryjny;numer seryjny komputera;mata pod nogi");
put_photos(22, "us_mentor_01.jpg;us_mentor_02.jpg;us_mentor_03.jpg;us_mentor_04.jpg;us_mentor_05.jpg;us_mentor_06.jpg;us_mentor_07.jpg;us_mentor_08.jpg;us_mentor_09.jpg;us_mentor_10.jpg;us_mentor_11.jpg;us_mentor_12.jpg;us_mentor_13.jpg;us_mentor_14.jpg", "widok prawie podłączonego symulatora;widok torsu;widok torsu - wejście dolne;moduł interfejsów;sonda USG;komputer;podłączenie torsu do modułu interfejsów;moduł interfejsów - wyprowadzenia tylne;moduł interfejsów - podłączenie przednie 1;moduł interfejsów - podłączenie przednie 2,3,4;stojak do głowicy USG;numer seryjny symulatora;numer seryjny symulatora;numer seryjny komputera", "widok prawie podłączonego symulatora;widok torsu;widok torsu - wejście dolne;moduł interfejsów;sonda USG;komputer;podłączenie torsu do modułu interfejsów;moduł interfejsów - wyprowadzenia tylne;moduł interfejsów - podłączenie przednie 1;moduł interfejsów - podłączenie przednie 2,3,4;stojak do głowicy USG;numer seryjny symulatora;numer seryjny symulatora;numer seryjny komputera");
put_photos(23, "laerdal_iv_torso_01.jpg;laerdal_iv_torso_02.jpg;laerdal_iv_torso_03.jpg", "widok trenażera na walizce transportowej;witok trenażera ze zdjętą pokrywą;walizka transportowa", "widok trenażera na walizce transportowej;witok trenażera ze zdjętą pokrywą;walizka transportowa");
put_photos(24, "laerdal_iv_torso_01.jpg;laerdal_iv_torso_02.jpg;laerdal_iv_torso_03.jpg", "widok trenażera na walizce transportowej;witok trenażera ze zdjętą pokrywą;walizka transportowa", "widok trenażera na walizce transportowej;witok trenażera ze zdjętą pokrywą;walizka transportowa");
put_photos(25, "laerdal_iv_torso_01.jpg;laerdal_iv_torso_02.jpg;laerdal_iv_torso_03.jpg", "widok trenażera na walizce transportowej;witok trenażera ze zdjętą pokrywą;walizka transportowa", "widok trenażera na walizce transportowej;witok trenażera ze zdjętą pokrywą;walizka transportowa");
put_photos(26, "trenazer_konikotomii_delux135_01.jpg;trenazer_konikotomii_delux135_02.jpg;trenazer_konikotomii_delux135_03.jpg;trenazer_konikotomii_delux135_04.jpg;trenazer_konikotomii_delux135_05.jpg;trenazer_konikotomii_delux135_sn_01.jpg", "kompletny zestaw trenażera; trenażer konikotomii; wymienna chrząstka i drogi oddechowe - dziecko;dodatkowe skóry;torba tranbsportowa;numer seryjny", "kompletny zestaw trenażera; trenażer konikotomii; wymienna chrząstka i drogi oddechowe - dziecko;dodatkowe skóry;torba tranbsportowa;numer seryjny");
put_photos(27, "trenazer_konikotomii_delux135_01.jpg;trenazer_konikotomii_delux135_02.jpg;trenazer_konikotomii_delux135_03.jpg;trenazer_konikotomii_delux135_04.jpg;trenazer_konikotomii_delux135_05.jpg;trenazer_konikotomii_delux135_sn_02.jpg", "kompletny zestaw trenażera; trenażer konikotomii; wymienna chrząstka i drogi oddechowe - dziecko;dodatkowe skóry;torba tranbsportowa;numer seryjny", "kompletny zestaw trenażera; trenażer konikotomii; wymienna chrząstka i drogi oddechowe - dziecko;dodatkowe skóry;torba tranbsportowa;numer seryjny");
put_photos(28, "trenazer_intubacji_larry_01.jpg;trenazer_intubacji_larry_02.jpg;trenazer_intubacji_larry_03.jpg;trenazer_intubacji_larry_04.jpg;trenazer_intubacji_larry_05.jpg;trenazer_intubacji_larry_06.jpg;trenazer_intubacji_larry_sn_01.jpg", "trenażer intubacji z walizką transportową;trenażer intubacji;walizka transportowa;lurykant;zatyczki do czyszczenia;strzykawki do uzyskania laryngospazmu i obrzęku języka;numer seryjny", "trenażer intubacji z walizką transportową;trenażer intubacji;walizka transportowa;lurykant;zatyczki do czyszczenia;strzykawki do uzyskania laryngospazmu i obrzęku języka;numer seryjny");
put_photos(29, "trenazer_intubacji_larry_01.jpg;trenazer_intubacji_larry_02.jpg;trenazer_intubacji_larry_03.jpg;trenazer_intubacji_larry_04.jpg;trenazer_intubacji_larry_05.jpg;trenazer_intubacji_larry_06.jpg;trenazer_intubacji_larry_sn_02.jpg", "trenażer intubacji z walizką transportową;trenażer intubacji;walizka transportowa;lurykant;zatyczki do czyszczenia;strzykawki do uzyskania laryngospazmu i obrzęku języka;numer seryjny", "trenażer intubacji z walizką transportową;trenażer intubacji;walizka transportowa;lurykant;zatyczki do czyszczenia;strzykawki do uzyskania laryngospazmu i obrzęku języka;numer seryjny");
put_photos(30, "trenazer_intubacji_larry_01.jpg;trenazer_intubacji_larry_02.jpg;trenazer_intubacji_larry_03.jpg;trenazer_intubacji_larry_04.jpg;trenazer_intubacji_larry_05.jpg;trenazer_intubacji_larry_06.jpg;trenazer_intubacji_larry_sn_03.jpg", "trenażer intubacji z walizką transportową;trenażer intubacji;walizka transportowa;lurykant;zatyczki do czyszczenia;strzykawki do uzyskania laryngospazmu i obrzęku języka;numer seryjny", "trenażer intubacji z walizką transportową;trenażer intubacji;walizka transportowa;lurykant;zatyczki do czyszczenia;strzykawki do uzyskania laryngospazmu i obrzęku języka;numer seryjny");
put_photos(31, "trenazer_intubacji_larry_01.jpg;trenazer_intubacji_larry_02.jpg;trenazer_intubacji_larry_03.jpg;trenazer_intubacji_larry_04.jpg;trenazer_intubacji_larry_05.jpg;trenazer_intubacji_larry_06.jpg;trenazer_intubacji_larry_sn_04.jpg", "trenażer intubacji z walizką transportową;trenażer intubacji;walizka transportowa;lurykant;zatyczki do czyszczenia;strzykawki do uzyskania laryngospazmu i obrzęku języka;numer seryjny", "trenażer intubacji z walizką transportową;trenażer intubacji;walizka transportowa;lurykant;zatyczki do czyszczenia;strzykawki do uzyskania laryngospazmu i obrzęku języka;numer seryjny");
put_photos(32, "trenazer_intubacji_larry_01.jpg;trenazer_intubacji_larry_02.jpg;trenazer_intubacji_larry_03.jpg;trenazer_intubacji_larry_04.jpg;trenazer_intubacji_larry_05.jpg;trenazer_intubacji_larry_06.jpg;trenazer_intubacji_larry_sn_05.jpg", "trenażer intubacji z walizką transportową;trenażer intubacji;walizka transportowa;lurykant;zatyczki do czyszczenia;strzykawki do uzyskania laryngospazmu i obrzęku języka;numer seryjny", "trenażer intubacji z walizką transportową;trenażer intubacji;walizka transportowa;lurykant;zatyczki do czyszczenia;strzykawki do uzyskania laryngospazmu i obrzęku języka;numer seryjny");
put_photos(33, "trenazer_laryngologiczny_01.jpg;trenazer_laryngologiczny_02.jpg;trenazer_laryngologiczny_03.jpg;trenazer_laryngologiczny_04.jpg;trenazer_laryngologiczny_sn_01.jpg", "trenażer laryngologiczny - zestaw;trenażer laryngologiczny w walizce transportowej;walizka transportowa;lubrykanty i woskowiny;numer seryjny", "trenażer laryngologiczny - zestaw;trenażer laryngologiczny w walizce transportowej;walizka transportowa;lubrykanty i woskowiny;numer seryjny");
put_photos(34, "trenazer_laryngologiczny_01.jpg;trenazer_laryngologiczny_02.jpg;trenazer_laryngologiczny_03.jpg;trenazer_laryngologiczny_04.jpg;trenazer_laryngologiczny_sn_02.jpg", "trenażer laryngologiczny - zestaw;trenażer laryngologiczny w walizce transportowej;walizka transportowa;lubrykanty i woskowiny;numer seryjny", "trenażer laryngologiczny - zestaw;trenażer laryngologiczny w walizce transportowej;walizka transportowa;lubrykanty i woskowiny;numer seryjny");
put_photos(35, "trenazer_laryngologiczny_01.jpg;trenazer_laryngologiczny_02.jpg;trenazer_laryngologiczny_03.jpg;trenazer_laryngologiczny_04.jpg;trenazer_laryngologiczny_sn_03.jpg", "trenażer laryngologiczny - zestaw;trenażer laryngologiczny w walizce transportowej;walizka transportowa;lubrykanty i woskowiny;numer seryjny", "trenażer laryngologiczny - zestaw;trenażer laryngologiczny w walizce transportowej;walizka transportowa;lubrykanty i woskowiny;numer seryjny");
put_photos(36, "noworodek_lf01400_01.jpg;noworodek_lf01400_02.jpg;noworodek_lf01400_03.jpg;noworodek_lf01400_04.jpg;noworodek_lf01400_05.jpg;noworodek_lf01400_06.jpg;noworodek_lf01400_07.jpg;noworodek_lf01400_08.jpg;noworodek_lf01400_09.jpg;noworodek_lf01400_10.jpg;noworodek_lf01400_11.jpg;noworodek_lf01400_12.jpg;noworodek_lf01400_13.jpg;noworodek_lf01400_14.jpg;noworodek_lf01400_sn_693.jpg", "widok kompletnego zestawu;noworodek - widok z przodu;noworodek - widok z tyłu;noworodek - górne drogi oddechowe;noworodek - płuca (jedne unoszące się jednostronnie);podłączenia żylne oraz skóry doni i stóp; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;noworodek - widok po złożeniu;lubrykat i puder;rezerwuar na krew;sztuczna krew; instrukcja;Numer seryjny", "widok kompletnego zestawu;noworodek - widok z przodu;noworodek - widok z tyłu;noworodek - górne drogi oddechowe;noworodek - płuca (jedne unoszące się jednostronnie);podłączenia żylne oraz skóry doni i stóp; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;noworodek - widok po złożeniu;lubrykat i puder;rezerwuar na krew;sztuczna krew; instrukcja;Numer seryjny");
put_photos(37, "noworodek_lf01400_01.jpg;noworodek_lf01400_02.jpg;noworodek_lf01400_03.jpg;noworodek_lf01400_04.jpg;noworodek_lf01400_05.jpg;noworodek_lf01400_06.jpg;noworodek_lf01400_07.jpg;noworodek_lf01400_08.jpg;noworodek_lf01400_09.jpg;noworodek_lf01400_10.jpg;noworodek_lf01400_11.jpg;noworodek_lf01400_12.jpg;noworodek_lf01400_13.jpg;noworodek_lf01400_14.jpg;noworodek_lf01400_sn_694.jpg", "widok kompletnego zestawu;noworodek - widok z przodu;noworodek - widok z tyłu;noworodek - górne drogi oddechowe;noworodek - płuca (jedne unoszące się jednostronnie);podłączenia żylne oraz skóry doni i stóp; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;noworodek - widok po złożeniu;lubrykat i puder;rezerwuar na krew;sztuczna krew; instrukcja;Numer seryjny", "widok kompletnego zestawu;noworodek - widok z przodu;noworodek - widok z tyłu;noworodek - górne drogi oddechowe;noworodek - płuca (jedne unoszące się jednostronnie);podłączenia żylne oraz skóry doni i stóp; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;noworodek - widok po złożeniu;lubrykat i puder;rezerwuar na krew;sztuczna krew; instrukcja;Numer seryjny");
put_photos(38, "noworodek_lf01400_01.jpg;noworodek_lf01400_02.jpg;noworodek_lf01400_03.jpg;noworodek_lf01400_04.jpg;noworodek_lf01400_05.jpg;noworodek_lf01400_06.jpg;noworodek_lf01400_07.jpg;noworodek_lf01400_08.jpg;noworodek_lf01400_09.jpg;noworodek_lf01400_10.jpg;noworodek_lf01400_11.jpg;noworodek_lf01400_12.jpg;noworodek_lf01400_13.jpg;noworodek_lf01400_14.jpg;noworodek_lf01400_sn_695.jpg", "widok kompletnego zestawu;noworodek - widok z przodu;noworodek - widok z tyłu;noworodek - górne drogi oddechowe;noworodek - płuca (jedne unoszące się jednostronnie);podłączenia żylne oraz skóry doni i stóp; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;noworodek - widok po złożeniu;lubrykat i puder;rezerwuar na krew;sztuczna krew; instrukcja;Numer seryjny", "widok kompletnego zestawu;noworodek - widok z przodu;noworodek - widok z tyłu;noworodek - górne drogi oddechowe;noworodek - płuca (jedne unoszące się jednostronnie);podłączenia żylne oraz skóry doni i stóp; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;noworodek - widok po złożeniu;lubrykat i puder;rezerwuar na krew;sztuczna krew; instrukcja;Numer seryjny");
put_photos(39, "noworodek_lf01400_01.jpg;noworodek_lf01400_02.jpg;noworodek_lf01400_03.jpg;noworodek_lf01400_04.jpg;noworodek_lf01400_05.jpg;noworodek_lf01400_06.jpg;noworodek_lf01400_07.jpg;noworodek_lf01400_08.jpg;noworodek_lf01400_09.jpg;noworodek_lf01400_10.jpg;noworodek_lf01400_11.jpg;noworodek_lf01400_12.jpg;noworodek_lf01400_13.jpg;noworodek_lf01400_14.jpg;noworodek_lf01400_sn_696.jpg", "widok kompletnego zestawu;noworodek - widok z przodu;noworodek - widok z tyłu;noworodek - górne drogi oddechowe;noworodek - płuca (jedne unoszące się jednostronnie);podłączenia żylne oraz skóry doni i stóp; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;noworodek - widok po złożeniu;lubrykat i puder;rezerwuar na krew;sztuczna krew; instrukcja;Numer seryjny", "widok kompletnego zestawu;noworodek - widok z przodu;noworodek - widok z tyłu;noworodek - górne drogi oddechowe;noworodek - płuca (jedne unoszące się jednostronnie);podłączenia żylne oraz skóry doni i stóp; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;noworodek - widok po złożeniu;lubrykat i puder;rezerwuar na krew;sztuczna krew; instrukcja;Numer seryjny");
put_photos(40, "noworodek_lf01400_01.jpg;noworodek_lf01400_02.jpg;noworodek_lf01400_03.jpg;noworodek_lf01400_04.jpg;noworodek_lf01400_05.jpg;noworodek_lf01400_06.jpg;noworodek_lf01400_07.jpg;noworodek_lf01400_08.jpg;noworodek_lf01400_09.jpg;noworodek_lf01400_10.jpg;noworodek_lf01400_11.jpg;noworodek_lf01400_12.jpg;noworodek_lf01400_13.jpg;noworodek_lf01400_14.jpg;noworodek_lf01400_sn_697.jpg", "widok kompletnego zestawu;noworodek - widok z przodu;noworodek - widok z tyłu;noworodek - górne drogi oddechowe;noworodek - płuca (jedne unoszące się jednostronnie);podłączenia żylne oraz skóry doni i stóp; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;noworodek - widok po złożeniu;lubrykat i puder;rezerwuar na krew;sztuczna krew; instrukcja;Numer seryjny", "widok kompletnego zestawu;noworodek - widok z przodu;noworodek - widok z tyłu;noworodek - górne drogi oddechowe;noworodek - płuca (jedne unoszące się jednostronnie);podłączenia żylne oraz skóry doni i stóp; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;; wada cewy nerwowej, przepuklina pępowinowa i drożna pępowina;noworodek - widok po złożeniu;lubrykat i puder;rezerwuar na krew;sztuczna krew; instrukcja;Numer seryjny");
put_photos(41, "dziecko_udr_do_lf03609_01.jpg;dziecko_udr_do_lf03609_02.jpg;dziecko_udr_do_lf03609_03.jpg;dziecko_udr_do_lf03609_04.jpg;dziecko_udr_do_lf03609_05.jpg;dziecko_udr_do_lf03609_06.jpg;dziecko_udr_do_lf03609_07.jpg;dziecko_udr_do_lf03609_08.jpg;dziecko_udr_do_lf03609_09.jpg;dziecko_udr_do_lf03609_sn_532.jpg", "komplet z walizką transportową;widok trenażera;trenażer z obróconą głową;lubrykant;zatyczki do czyszczenia;gąbka ochronna do przechowywania i transportu;zapakowany trenażer;zapakowany trenażer;certyfikat i instrukcja;numer seryjny", "komplet z walizką transportową;widok trenażera;trenażer z obróconą głową;lubrykant;zatyczki do czyszczenia;gąbka ochronna do przechowywania i transportu;zapakowany trenażer;zapakowany trenażer;certyfikat i instrukcja;numer seryjny");
put_photos(42, "dziecko_udr_do_lf03609_01.jpg;dziecko_udr_do_lf03609_02.jpg;dziecko_udr_do_lf03609_03.jpg;dziecko_udr_do_lf03609_04.jpg;dziecko_udr_do_lf03609_05.jpg;dziecko_udr_do_lf03609_06.jpg;dziecko_udr_do_lf03609_07.jpg;dziecko_udr_do_lf03609_08.jpg;dziecko_udr_do_lf03609_09.jpg;dziecko_udr_do_lf03609_sn_533.jpg", "komplet z walizką transportową;widok trenażera;trenażer z obróconą głową;lubrykant;zatyczki do czyszczenia;gąbka ochronna do przechowywania i transportu;zapakowany trenażer;zapakowany trenażer;certyfikat i instrukcja;numer seryjny", "komplet z walizką transportową;widok trenażera;trenażer z obróconą głową;lubrykant;zatyczki do czyszczenia;gąbka ochronna do przechowywania i transportu;zapakowany trenażer;zapakowany trenażer;certyfikat i instrukcja;numer seryjny");
put_photos(43, "dziecko_udr_do_lf03609_01.jpg;dziecko_udr_do_lf03609_02.jpg;dziecko_udr_do_lf03609_03.jpg;dziecko_udr_do_lf03609_04.jpg;dziecko_udr_do_lf03609_05.jpg;dziecko_udr_do_lf03609_06.jpg;dziecko_udr_do_lf03609_07.jpg;dziecko_udr_do_lf03609_08.jpg;dziecko_udr_do_lf03609_09.jpg;dziecko_udr_do_lf03609_sn_538.jpg", "komplet z walizką transportową;widok trenażera;trenażer z obróconą głową;lubrykant;zatyczki do czyszczenia;gąbka ochronna do przechowywania i transportu;zapakowany trenażer;zapakowany trenażer;certyfikat i instrukcja;numer seryjny", "komplet z walizką transportową;widok trenażera;trenażer z obróconą głową;lubrykant;zatyczki do czyszczenia;gąbka ochronna do przechowywania i transportu;zapakowany trenażer;zapakowany trenażer;certyfikat i instrukcja;numer seryjny");
put_photos(44, "dziecko_udr_do_lf03609_01.jpg;dziecko_udr_do_lf03609_02.jpg;dziecko_udr_do_lf03609_03.jpg;dziecko_udr_do_lf03609_04.jpg;dziecko_udr_do_lf03609_05.jpg;dziecko_udr_do_lf03609_06.jpg;dziecko_udr_do_lf03609_07.jpg;dziecko_udr_do_lf03609_08.jpg;dziecko_udr_do_lf03609_09.jpg;dziecko_udr_do_lf03609_sn_539.jpg", "komplet z walizką transportową;widok trenażera;trenażer z obróconą głową;lubrykant;zatyczki do czyszczenia;gąbka ochronna do przechowywania i transportu;zapakowany trenażer;zapakowany trenażer;certyfikat i instrukcja;numer seryjny", "komplet z walizką transportową;widok trenażera;trenażer z obróconą głową;lubrykant;zatyczki do czyszczenia;gąbka ochronna do przechowywania i transportu;zapakowany trenażer;zapakowany trenażer;certyfikat i instrukcja;numer seryjny");
put_photos(45, "dziecko_udr_do_lf03609_01.jpg;dziecko_udr_do_lf03609_02.jpg;dziecko_udr_do_lf03609_03.jpg;dziecko_udr_do_lf03609_04.jpg;dziecko_udr_do_lf03609_05.jpg;dziecko_udr_do_lf03609_06.jpg;dziecko_udr_do_lf03609_07.jpg;dziecko_udr_do_lf03609_08.jpg;dziecko_udr_do_lf03609_09.jpg;dziecko_udr_do_lf03609_sn_541.jpg", "komplet z walizką transportową;widok trenażera;trenażer z obróconą głową;lubrykant;zatyczki do czyszczenia;gąbka ochronna do przechowywania i transportu;zapakowany trenażer;zapakowany trenażer;certyfikat i instrukcja;numer seryjny", "komplet z walizką transportową;widok trenażera;trenażer z obróconą głową;lubrykant;zatyczki do czyszczenia;gąbka ochronna do przechowywania i transportu;zapakowany trenażer;zapakowany trenażer;certyfikat i instrukcja;numer seryjny");
put_photos(46, "kolumna_ponta_agila_01.jpg;kolumna_ponta_agila_02.jpg;kolumna_agila_l_03.jpg;kolumna_agila_l_04.jpg;kolumna_agila_l_05.jpg;kolumna_agila_l_06.jpg;kolumna_agila_l_ramie_do_wlewow.jpg;kolumna_agila_l_ramie_do_wlewow_tab.jpg;kolumna_agila_p_01.jpg;kolumna_agila_p_02.jpg;kolumna_agila_p_03.jpg;kolumna_agila_p_04.jpg;kolumna_agila_p_ramie_obrotowe.jpg;kolumna_agila_p_ramie_obrotowe_tab.jpg;kolumna_pointa_agila_sn.jpg", "widok na całą kolumnę;oświetlenie górne kolumny;kolumna lewa;kolumna lewa - gniazda z przodu;kolumna lewa - gniazda pod spodem;kolumna lewa - gniazda z tyłu;ramię do wlewów na kolumnie lewej;ramię do wlewów na kolumnie lewej (tabliczka);kolumna prawa - lewa strona;kolumna prawa - przód;kolumna prawa - prawa strona;kolumna prawa - tył;ramię obrotowe na kolumnie prawej;ramię obrotowe na kolumnie prawej (tabliczka);numer seryjny", "widok na całą kolumnę;oświetlenie górne kolumny;kolumna lewa;kolumna lewa - gniazda z przodu;kolumna lewa - gniazda pod spodem;kolumna lewa - gniazda z tyłu;ramię do wlewów na kolumnie lewej;ramię do wlewów na kolumnie lewej (tabliczka);kolumna prawa - lewa strona;kolumna prawa - przód;kolumna prawa - prawa strona;kolumna prawa - tył;ramię obrotowe na kolumnie prawej;ramię obrotowe na kolumnie prawej (tabliczka);numer seryjny");
put_photos(47, "kolumna_movita_01.jpg;kolumna_movita_02.jpg;kolumna_movita_03.jpg;kolumna_movita_04.jpg;kolumna_movita_05.jpg;kolumna_movita_06.jpg;kolumna_movita_07.jpg;kolumna_movita_lampka.jpg;kolumna_movita_ramie_do_iniekcji_01.jpg;kolumna_movita_ramie_do_iniekcji_02.jpg;kolumna_movita_ramie_obrotowe_a_01.jpg;kolumna_movita_ramie_obrotowe_a_02.jpg;kolumna_movita_ramie_obrotowe_b_01.jpg;kolumna_movita_ramie_obrotowe_b_02.jpg;kolumna_movita_sn.jpg", "kolumna movita - widok z przodu;kolumna movita - widok z lewej;kolumna movita - widok z tyłu;kolumna movita - widok z prawej;kolumna movita - widok z lewej;kolumna movita - widok z tyłu;kolumna movita - widok z prawej;lampka;ramię do iniekcji;ramię do iniekcji - numer seryjny;ramię obrotowe;ramię obrotowe - numer seryjny;ramię obrotowe;ramię obrotowe - numer seryjny;kolumna movita - numer seryjny", "kolumna movita - widok z przodu;kolumna movita - widok z lewej;kolumna movita - widok z tyłu;kolumna movita - widok z prawej;kolumna movita - widok z lewej;kolumna movita - widok z tyłu;kolumna movita - widok z prawej;lampka;ramię do iniekcji;ramię do iniekcji - numer seryjny;ramię obrotowe;ramię obrotowe - numer seryjny;ramię obrotowe;ramię obrotowe - numer seryjny;kolumna movita - numer seryjny");
put_photos(48, "lampa_operacyjna_polaris_100_1.jpg;lampa_operacyjna_polaris_100_sn_lampa.jpg;lampa_operacyjna_polaris_100_sn_ramie.jpg", "lampa operacyjna Polaris na ramieniu sufitowym;lampa operacyjna Polaris - numer seryjny lampy;lampa operacyjna Polaris - numer seryjny ramienia", "lampa operacyjna Polaris na ramieniu sufitowym;lampa operacyjna Polaris - numer seryjny lampy;lampa operacyjna Polaris - numer seryjny ramienia");
put_photos(49, "stanowisko_do_znieczulania_01.jpg;stanowisko_do_znieczulania_02.jpg;stanowisko_do_znieczulania_03.jpg;stanowisko_do_znieczulania_04.jpg;stanowisko_do_znieczulania_08.jpg;stanowisko_do_znieczulania_09.jpg;stanowisko_do_znieczulania_10.jpg;stanowisko_do_znieczulania_11.jpg;stanowisko_do_znieczulania_12.jpg;stanowisko_do_znieczulania_13.jpg;stanowisko_do_znieczulania_14.jpg;stanowisko_do_znieczulania_dozownik_tlenu.jpg;stanowisko_do_znieczulania_odssys_01.jpg;stanowisko_do_znieczulania_odssys_02.jpg;stanowisko_do_znieczulania_03.jpg;stanowisko_do_znieczulania_04.jpg;stanowisko_do_znieczulania_sn.jpg", "Stanowisko do znieczulania - widok z przodu;Stanowisko do znieczulania - zbliżenie;Stanowisko do znieczulania - widok monitora;uchwyt na parowniki do anestetyków;filtr powietrza;system ogrzewania powietrza;rury do oddychania;podłączenie gazów;wyprowadzenia tylne;podłączenia sygnałowe;podłączenie zasilania;dozownik tlenu;system do odsysania wydzieliny z DO;system do odsysania wydzieliny z DO;system do odsysania - numer serjny;system odsysania - zbiornik na wydzieliny;Primus - numer seryjny", "Stanowisko do znieczulania - widok z przodu;Stanowisko do znieczulania - zbliżenie;Stanowisko do znieczulania - widok monitora;uchwyt na parowniki do anestetyków;filtr powietrza;system ogrzewania powietrza;rury do oddychania;podłączenie gazów;wyprowadzenia tylne;podłączenia sygnałowe;podłączenie zasilania;dozownik tlenu;system do odsysania wydzieliny z DO;system do odsysania wydzieliny z DO;system do odsysania - numer serjny;system odsysania - zbiornik na wydzieliny;Primus - numer seryjny");
put_photos(50, "annie_0555_804_a.jpg;annie_0555_804_b.jpg;annie_0555_804_c.jpg;annie_0555_804_d.jpg", "widok 1;widok2;widok układów sterujących;etykieta", "widok 1;widok2;widok układów sterujących;etykieta");



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
            $ret_gal=make_room_gallery("A 1.05","Sala umiejętności technicznych - położnictwo","Sala umiejętności technicznych - położnictwo","rooms/a105");
            put_photos($ret_gal,"a1.05a.jpg","Sala umiejętności technicznych - położnictwo","Sala umiejętności technicznych - położnictwo");
            put_photos($ret_gal,"a1.05b.jpg","Sala umiejętności technicznych - położnictwo","Sala umiejętności technicznych - położnictwo");
            put_photos($ret_gal,"a1.05c.jpg","Sala umiejętności technicznych - położnictwo","Sala umiejętności technicznych - położnictwo");
            put_photos($ret_gal,"a1.05d.jpg","Sala umiejętności technicznych - położnictwo","Sala umiejętności technicznych - położnictwo");
            put_photos($ret_gal,"a1.05e.jpg","Sala umiejętności technicznych - położnictwo","Sala umiejętności technicznych - położnictwo");
            put_photos($ret_gal,"a1.05f.jpg","Sala umiejętności technicznych - położnictwo","Sala umiejętności technicznych - położnictwo");
            $ret_gal=make_room_gallery("A 1.06","Sala umiejętności pielęgniarskich","Sala umiejętności pielęgniarskich","rooms/a106");
            put_photos($ret_gal,"a1.06a.jpg","Sala umiejętności pielęgniarskich","Sala umiejętności pielęgniarskich");
            put_photos($ret_gal,"a1.06b.jpg","Sala umiejętności pielęgniarskich","Sala umiejętności pielęgniarskich");
            put_photos($ret_gal,"a1.06c.jpg","Sala umiejętności pielęgniarskich","Sala umiejętności pielęgniarskich");
            put_photos($ret_gal,"a1.06d.jpg","Sala umiejętności pielęgniarskich","Sala umiejętności pielęgniarskich");
            $ret_gal=make_room_gallery("A 1.07","Sala umiejętności technicznych - pielęgniarstwo","Sala umiejętności technicznych - pielęgniarstwo","rooms/a107");
            put_photos($ret_gal,"a1.07a.jpg","Sala umiejętności technicznych - pielęgniarstwo","Sala umiejętności technicznych - pielęgniarstwo");
            put_photos($ret_gal,"a1.07b.jpg","Sala umiejętności technicznych - pielęgniarstwo","Sala umiejętności technicznych - pielęgniarstwo");
            put_photos($ret_gal,"a1.07c.jpg","Sala umiejętności technicznych - pielęgniarstwo","Sala umiejętności technicznych - pielęgniarstwo");
            put_photos($ret_gal,"a1.07d.jpg","Sala umiejętności technicznych - pielęgniarstwo","Sala umiejętności technicznych - pielęgniarstwo");
            $ret_gal=make_room_gallery("A 1.09","Sala umiejętności położniczych","Sala umiejętności położniczych","rooms/a109");
            put_photos($ret_gal,"a1.09a.jpg","Sala umiejętności położniczych","Sala umiejętności położniczych");
            put_photos($ret_gal,"a1.09b.jpg","Sala umiejętności położniczych","Sala umiejętności położniczych");
            put_photos($ret_gal,"a1.09c.jpg","Sala umiejętności położniczych","Sala umiejętności położniczych");
            put_photos($ret_gal,"a1.09d.jpg","Sala umiejętności położniczych","Sala umiejętności położniczych");
            $ret_gal=make_room_gallery("B 1.01","Wysoka wierność pielęgniarstwo","Wysoka wierność pielęgniarstwo","rooms/b101");
            put_photos($ret_gal,"b1.01a.jpg","Wysoka wierność pielęgniarstwo","Wysoka wierność pielęgniarstwo");
            put_photos($ret_gal,"b1.01b.jpg","Wysoka wierność pielęgniarstwo","Wysoka wierność pielęgniarstwo");
            put_photos($ret_gal,"b1.01c.jpg","Wysoka wierność pielęgniarstwo","Wysoka wierność pielęgniarstwo");
            put_photos($ret_gal,"b1.01d.jpg","Wysoka wierność pielęgniarstwo","Wysoka wierność pielęgniarstwo");
            put_photos($ret_gal,"b1.01e.jpg","Wysoka wierność pielęgniarstwo","Wysoka wierność pielęgniarstwo");
            put_photos($ret_gal,"b1.01f.jpg","Wysoka wierność pielęgniarstwo","Wysoka wierność pielęgniarstwo");
            $ret_gal=make_room_gallery("B 1.05","Wysoka wierność położnictwo","Wysoka wierność położnictwo","rooms/b105");
            put_photos($ret_gal,"b1.05a.jpg","Wysoka wierność położnictwo","Wysoka wierność położnictwo");
            put_photos($ret_gal,"b1.05b.jpg","Wysoka wierność położnictwo","Wysoka wierność położnictwo");
            put_photos($ret_gal,"b1.05c.jpg","Wysoka wierność położnictwo","Wysoka wierność położnictwo");
            put_photos($ret_gal,"b1.05d.jpg","Wysoka wierność położnictwo","Wysoka wierność położnictwo");
            put_photos($ret_gal,"b1.05e.jpg","Wysoka wierność położnictwo","Wysoka wierność położnictwo");
            put_photos($ret_gal,"b1.05f.jpg","Wysoka wierność położnictwo","Wysoka wierność położnictwo");
            put_photos($ret_gal,"b1.05g.jpg","Wysoka wierność położnictwo","Wysoka wierność położnictwo");
            put_photos($ret_gal,"b1.05h.jpg","Wysoka wierność położnictwo","Wysoka wierność położnictwo");
            put_photos($ret_gal,"b1.05i.jpg","Wysoka wierność położnictwo","Wysoka wierność położnictwo");
            $ret_gal=make_room_gallery("B 1.06","Magazyn - L","Magazyn - L","rooms/b106");
            put_photos($ret_gal,"b1.06a.jpg","Magazyn - L","Magazyn - L");
            put_photos($ret_gal,"b1.06b.jpg","Magazyn - L","Magazyn - L");
            $ret_gal=make_room_gallery("B 1.07","Magazyn - C","Magazyn - C","rooms/b107");
            put_photos($ret_gal,"b1.07a.jpg","Magazyn - C","Magazyn - C");
            put_photos($ret_gal,"b1.07b.jpg","Magazyn - C","Magazyn - C");
            put_photos($ret_gal,"b1.07c.jpg","Magazyn - C","Magazyn - C");
            $ret_gal=make_room_gallery("B 1.09","Magazyn - P","Magazyn - P","rooms/b109");
            put_photos($ret_gal,"b1.09a.jpg","Magazyn - P","Magazyn - P");
            put_photos($ret_gal,"b1.09b.jpg","Magazyn - P","Magazyn - P");
            put_photos($ret_gal,"b1.09c.jpg","Magazyn - P","Magazyn - P");
            put_photos($ret_gal,"b1.09d.jpg","Magazyn - P","Magazyn - P");
            $ret_gal=make_room_gallery("B 1.12","OSCE  - położnictwo 12","OSCE  - położnictwo 12","rooms/b112");
            put_photos($ret_gal,"b1.12a.jpg","OSCE  - położnictwo 12","OSCE  - położnictwo 12");
            put_photos($ret_gal,"b1.12b.jpg","OSCE  - położnictwo 12","OSCE  - położnictwo 12");
            put_photos($ret_gal,"b1.12c.jpg","OSCE  - położnictwo 12","OSCE  - położnictwo 12");
            put_photos($ret_gal,"b1.12d.jpg","OSCE  - położnictwo 12","OSCE  - położnictwo 12");
            $ret_gal=make_room_gallery("B 1.13","OSCE  - położnictwo 13","OSCE  - położnictwo 13","rooms/b113");
            put_photos($ret_gal,"b1.13a.jpg","OSCE  - położnictwo 13","OSCE  - położnictwo 13");
            put_photos($ret_gal,"b1.13b.jpg","OSCE  - położnictwo 13","OSCE  - położnictwo 13");
            put_photos($ret_gal,"b1.13c.jpg","OSCE  - położnictwo 13","OSCE  - położnictwo 13");
            put_photos($ret_gal,"b1.13d.jpg","OSCE  - położnictwo 13","OSCE  - położnictwo 13");
            $ret_gal=make_room_gallery("B 1.14","OSCE  - pomieszczenie kontrolne","OSCE  - pomieszczenie kontrolne","rooms/b114");
            put_photos($ret_gal,"b1.14a.jpg","OSCE  - pomieszczenie kontrolne","OSCE  - pomieszczenie kontrolne");
            put_photos($ret_gal,"b1.14b.jpg","OSCE  - pomieszczenie kontrolne","OSCE  - pomieszczenie kontrolne");
            put_photos($ret_gal,"b1.14c.jpg","OSCE  - pomieszczenie kontrolne","OSCE  - pomieszczenie kontrolne");
            put_photos($ret_gal,"b1.14d.jpg","OSCE  - pomieszczenie kontrolne","OSCE  - pomieszczenie kontrolne");
            $ret_gal=make_room_gallery("B 1.15","OSCE  - pielęgniarstwo 15","OSCE  - pielęgniarstwo 15","rooms/b115");
            put_photos($ret_gal,"b1.15a.jpg","OSCE  - pielęgniarstwo 15","OSCE  - pielęgniarstwo 15");
            put_photos($ret_gal,"b1.15b.jpg","OSCE  - pielęgniarstwo 15","OSCE  - pielęgniarstwo 15");
            put_photos($ret_gal,"b1.15c.jpg","OSCE  - pielęgniarstwo 15","OSCE  - pielęgniarstwo 15");
            put_photos($ret_gal,"b1.15d.jpg","OSCE  - pielęgniarstwo 15","OSCE  - pielęgniarstwo 15");
            put_photos($ret_gal,"b1.15e.jpg","OSCE  - pielęgniarstwo 15","OSCE  - pielęgniarstwo 15");
            $ret_gal=make_room_gallery("B 1.16","OSCE  - pielęgniarstwo 16","OSCE  - pielęgniarstwo 16","rooms/b116");
            put_photos($ret_gal,"b1.16a.jpg","OSCE  - pielęgniarstwo 16","OSCE  - pielęgniarstwo 16");
            put_photos($ret_gal,"b1.16b.jpg","OSCE  - pielęgniarstwo 16","OSCE  - pielęgniarstwo 16");
            put_photos($ret_gal,"b1.16c.jpg","OSCE  - pielęgniarstwo 16","OSCE  - pielęgniarstwo 16");
            put_photos($ret_gal,"b1.16d.jpg","OSCE  - pielęgniarstwo 16","OSCE  - pielęgniarstwo 16");
            put_photos($ret_gal,"b1.16e.jpg","OSCE  - pielęgniarstwo 16","OSCE  - pielęgniarstwo 16");
            $ret_gal=make_room_gallery("B 1.17","OSCE  - pielęgniarstwo 17","OSCE  - pielęgniarstwo 17","rooms/b117");
            put_photos($ret_gal,"b1.17a.jpg","OSCE  - pielęgniarstwo 17","OSCE  - pielęgniarstwo 17");
            put_photos($ret_gal,"b1.17b.jpg","OSCE  - pielęgniarstwo 17","OSCE  - pielęgniarstwo 17");
            put_photos($ret_gal,"b1.17c.jpg","OSCE  - pielęgniarstwo 17","OSCE  - pielęgniarstwo 17");
            put_photos($ret_gal,"b1.17d.jpg","OSCE  - pielęgniarstwo 17","OSCE  - pielęgniarstwo 17");
            put_photos($ret_gal,"b1.17e.jpg","OSCE  - pielęgniarstwo 17","OSCE  - pielęgniarstwo 17");
            $ret_gal=make_room_gallery("B 1.18","OSCE  - pielęgniarstwo 18","OSCE  - pielęgniarstwo 18","rooms/b118");
            put_photos($ret_gal,"b1.18a.jpg","OSCE  - pielęgniarstwo 18","OSCE  - pielęgniarstwo 18");
            put_photos($ret_gal,"b1.18b.jpg","OSCE  - pielęgniarstwo 18","OSCE  - pielęgniarstwo 18");
            put_photos($ret_gal,"b1.18c.jpg","OSCE  - pielęgniarstwo 18","OSCE  - pielęgniarstwo 18");
            put_photos($ret_gal,"b1.18d.jpg","OSCE  - pielęgniarstwo 18","OSCE  - pielęgniarstwo 18");
            put_photos($ret_gal,"b1.18e.jpg","OSCE  - pielęgniarstwo 18","OSCE  - pielęgniarstwo 18");
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
            
    }
}
