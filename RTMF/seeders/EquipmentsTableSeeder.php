<?php
use Illuminate\Database\Seeder;

use App\Rooms;
use App\RoomStorages;
use App\Equipments;
use App\EquipmentTypes;
use App\EquipmentRoomStorages;

class EquipmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
  {
    
    function add_EQ_type($F_name, $F_photo)
        {
        $zmEQ = new EquipmentTypes();
        $zmEQ->equipment_types_name = $F_name;
        $zmEQ->equipment_types_photo = $F_photo;
        $zmEQ->equipment_types_description = $zmEQ->equipment_types_name;
        $zmEQ->save();
        return $zmEQ->id;
        }
    
    function add_EQuipemnt($F_Type, $F_photo, $F_Producent, $F_model, $F_size, $F_desc)
        {
            $zmEX = new Equipments();
            $zmEX->equipments_equipment_types_id=$F_Type;
            if ($F_Producent!='') $zmEX->equipments_producent=$F_Producent;
            if ($F_photo!='') $zmEX->equipments_photo=$F_photo;
            if ($F_model!='') $zmEX->equipments_model=$F_model;
            if ($F_size!='') $zmEX->equipments_size=$F_size;
            if ($F_desc!='') $zmEX->equipments_description=$F_desc;
            $zmEX->save();
        }
    
    $tid = add_EQ_type('stetoskop', '_stetoskop.jpg');
        add_EQuipemnt($tid, '', '', '', '', ' ');
    $tid = add_EQ_type('nożyczki', '_scissors.jpg');
        add_EQuipemnt($tid, 'nozyczki_wzmacniane.jpg', '', 'nożyczki wzmacniane', '', ' ');
        add_EQuipemnt($tid, 'nozyczki_ratownicze.jpg', '', 'nożyczki ratownicze', '', ' ');
    $tid = add_EQ_type('pulsoksymetr', '_pulsoksymetr.jpg');
        add_EQuipemnt($tid, '', '', '', '', ' ');
    $tid = add_EQ_type('termometr', '_termometr.jpg');
        add_EQuipemnt($tid, 'termometr_bezdotykowy.jpg', '', 'termometr bezdotykowy', '', ' ');
        add_EQuipemnt($tid, 'termometr_douszny.jpg', '', 'termometr douszny', '', ' ');
        add_EQuipemnt($tid, '', '', 'elektroniczny', '', ' ');
    $tid = add_EQ_type('wstrzykiwacz insuliny', '_wstrzykiwacz_insuliny.jpg');
        add_EQuipemnt($tid, '', 'Polfa Tarchomin', 'Polhumin Pen', '', ' ');
    $tid = add_EQ_type('staza automatyczna', '_staza_automatyczna.jpg');
        add_EQuipemnt($tid, '', '', '', '', ' ');
    $tid = add_EQ_type('worek samorozprężalny', '_worek_samorozprezalny.jpg');
        add_EQuipemnt($tid, '', '', '', '', ' ');
    $tid = add_EQ_type('bańki', '_banki.jpg');
        add_EQuipemnt($tid, 'banki_prozniowe.jpg', '', 'bańki próżniowe', '', ' ');
        add_EQuipemnt($tid, 'banki_szklane_duze.jpg', '', 'bańki ogniowe', 'duże', ' ');
        add_EQuipemnt($tid, 'banki_szklane_male.jpg', '', 'bańki ogniowe', 'małe', ' ');
        add_EQuipemnt($tid, 'banki_akupunkturowe.jpg', '', 'gumowe bezogniowe bańki akupunkturowe', 'zestaw', ' ');
        add_EQuipemnt($tid, 'palnik_do_baniek.jpg', '', 'palnik denaturatowy do baniek', '', ' ');
    $tid = add_EQ_type('basen', 'basen_plastikowy.jpg');
        add_EQuipemnt($tid, '', '', '', '', ' ');
    $tid = add_EQ_type('ciśnieniomierz', '_cisnieniomierz.jpg');
        add_EQuipemnt($tid, '', '', '', '', ' ');
    $tid = add_EQ_type('glukometr', '_glukometr.jpg');
        add_EQuipemnt($tid, 'glukometr.jpg', 'One Touch', 'One Touch', '', ' ');
        add_EQuipemnt($tid, 'glukometr2.jpg', 'Superior', 'Superior', '', ' ');
    $tid = add_EQ_type('dzbanek plastikowy', 'dzbanek_plastikowy.jpg');
        add_EQuipemnt($tid, '', '', '', '', ' ');
    $tid = add_EQ_type('google ochronne', '_google_ochronne.jpg');
        add_EQuipemnt($tid, '', '', '', '', ' ');
    $tid = add_EQ_type('narzędzia chirurgiczne', '_narzedzia_chirurgiczne.jpg');
        add_EQuipemnt($tid, 'kleszcze_do_otrzewnej.jpg', '', 'kleszcze do otrzewnej', '', ' ');
        add_EQuipemnt($tid, 'kleszcze_naczyniowe.jpg', '', 'kleszcze naczyniowe', '', ' ');
        add_EQuipemnt($tid, 'kleszcze_x.jpg', '', 'kleszcze x', '', ' ');
        add_EQuipemnt($tid, 'kleszcze_y.jpg', '', 'kleszcze y', '', ' ');
        add_EQuipemnt($tid, 'penseta_anatomiczna_prosta.jpg', '', 'penseta anatomiczna prosta', '', ' ');
    $tid = add_EQ_type('miski', '_miski.jpg');
        add_EQuipemnt($tid, 'miska_nerkowata_metalowa.jpg', '', 'miska nerkowata metalowa', '', ' ');
        add_EQuipemnt($tid, 'miska_nerkowata_plastikowa.jpg', '', 'miska nerkowata plastikowa', '', ' ');
        add_EQuipemnt($tid, 'miska_plastikowa_25cm.jpg', '', 'miska okrągła plastikowa', '25cm', ' ');
        add_EQuipemnt($tid, 'miska_plastikowa_41cm.jpg', '', 'miska okrągła plastikowa', '41cm', ' ');
    $tid = add_EQ_type('podstawki, uchwyty', '_podstawki.jpg');
        add_EQuipemnt($tid, 'podstawka_do_kieliszkow.jpg', '', 'podstawka do kieliszków', '', ' ');
    $tid = add_EQ_type('pojemniki na leki, kieliszki', '_kieliszki.jpg');
        add_EQuipemnt($tid, 'kieliszki_szklane.jpg', '', 'kieliszki szklane', '', ' ');
    $tid = add_EQ_type('rurki ustno-gardłowe', 'rurki_ustnogardlowe.jpg.jpg');
        add_EQuipemnt($tid, '', '', '', 'komplet', ' ');
    $tid = add_EQ_type('termofor', 'termofor.jpg');
        add_EQuipemnt($tid, '', '', '', '', ' ');
    $tid = add_EQ_type('przecinarka tabletek', 'przecinarka_tabletek.jpg');
        add_EQuipemnt($tid, '', '', '', '', ' ');


            
    }
}

