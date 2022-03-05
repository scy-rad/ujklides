<?php
//php artisan db:seed --class=RoomsLekTableSeeder
use Illuminate\Database\Seeder;

use App\Room;
use App\RoomStorage;

class RoomsLekTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    function Add2_Room($aF_Center_id, $aF_prop_tech_char, $aF_room_type, $aF_number, $aF_xp_code, $aF_photo, $aF_name, $aF_Description, $aF_status)
        {
        $zmEQ = new Room();
        $zmEQ->room_type_id = $aF_room_type;
        $zmEQ->center_id = $aF_Center_id;
        $zmEQ->room_number = $aF_number;
        $zmEQ->room_xp_code = $aF_xp_code;
        if ($aF_photo!='') $zmEQ->room_photo = $aF_photo;
        $zmEQ->room_name = $aF_name;

        
        $zmEQ->simmed_technician_character_propose_id=App\TechnicianCharacter::where('character_short',$aF_prop_tech_char)->first()->id;
        

        $filename=getcwd().'\\database\\seeds\\html\\'.strtolower(str_replace(' ','_',$aF_number)).'.html';
        if (file_exists($filename))
            {
            $zmEQ->room_description = file_get_contents($filename);
            echo "wczytano opis sali z pliku: $filename \n";
            }
        else
            $zmEQ->room_description = $aF_Description;

        $zmEQ->room_status = $aF_status;
        $zmEQ->save();
        return $zmEQ->id;
        }


    function Add2_Storage($aF_room, $aF_type, $aF_number, $aF_name, $aF_Description, $aF_shelf,$aF_sort)
		{
			$zmEQ = new RoomStorage();
			$zmEQ->room_storage_type_id = $aF_type;
			$zmEQ->room_id = $aF_room;
			$zmEQ->room_storage_number = $aF_number;
			$zmEQ->room_storage_name = $aF_name;
			$zmEQ->room_storage_description = $aF_Description;
			$zmEQ->room_storage_shelf_count = $aF_shelf;
			$zmEQ->room_storage_sort = $aF_sort;
			$zmEQ->room_storage_status = 1;
			$zmEQ->save();
		}


        $storage_cwiczeniowy = 1;
        $storage_magazynowy  = 2;
        $storage_cwicz_magaz = 3;

        $Center_id=2;
        $room_active=1;
        $room_noactive=0;



        $ir=Add2_Room($Center_id, 'free', 3, '_MWL', '', '', 'Magazyn Wejściowy Lekarski', 'Magazyn sprzętów przychodzących',$room_noactive);
            Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);

        $ir=Add2_Room($Center_id, 'free', 21, '_WIRT', 'CM_Centra_Symulacji', '', 'Wirtualne Centrum Symulacji', 'Wirtualne Centrum Symulacji',$room_noactive);

        $ir=Add2_Room($Center_id, 'prep', 21, 'A 1.01', 'CM_A1.01_BLS  ', 'a101/a1.01a.jpg', 'Sala BLS 01', 'Sala BLS (Basic Life Support) 01',$room_active);
            Add2_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
        $ir=Add2_Room($Center_id, 'prep', 21, 'A 1.02', 'CM_A1.02_ALS', 'a102/a1.02a.jpg', 'Sala ALS 02', 'Sala ALS (Advanced  Life Support) 02',$room_active);
            Add2_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            Add2_Storage($ir, $storage_cwiczeniowy, 'WR01', 'Wózek Wielofunkcyjny 01', 'Wózek Wielofunkcyjny Reanimacyjny', 6, 1);
            Add2_Storage($ir, $storage_magazynowy, 'kontrolka', 'pomieszczenie kontrolne', 'pomieszczenie kontrolne - sala', 1, 2);
            Add2_Storage($ir, $storage_magazynowy, 'szafa K01', 'szafa w pomieszczeniu kontrolnym', 'szafa dół', 6, 3);
            $sort_count=1;
            for ($i='A'; $i<='F'; $i++)
                Add2_Storage($ir, $storage_magazynowy, 'szafa '.$i, 'szafa '.$i, 'szafa magazynowa '.$i, 5, $sort_count++);
            for ($i='A'; $i<='H'; $i++)
                Add2_Storage($ir, $storage_cwiczeniowy, 'szafka '.$i, 'szafka '.$i, 'szafka '.$i, 5, $sort_count++);

            $ir=Add2_Room($Center_id, 'phone', 21, 'B 0.25', 'CM_B0.25_CSM', 'b025/b0.25a.jpg', 'Sala RatMed 0.25', 'Sala 0.25',$room_noactive);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'phone', 21, 'B 0.26', 'CM_B0.26_CSM', 'b026/b0.26a.jpg', 'Sala RatMed 0.26', 'Sala 0.26',$room_noactive);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'free', 21, 'B 0.28', 'CM_B0.28_CSM', 'b028/b0.28a.jpg', 'Sala BLS RatMed 28', 'Sala BLS (Basic Life Support) 28',$room_noactive);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'free', 21, 'B 0.29', 'CM_B0.29_CSM', 'b029/b0.29a.jpg', 'Karetka', 'Sala Karetki',$room_active);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'phone', 21, 'B 0.30', 'CM_B0.30_CSM', 'b030/b0.30a.jpg', 'Sala ALS RatMed 30', 'Sala ALS (Advanced  Life Support) 30',$room_noactive);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'ready', 21, 'RatMed', '', 'room.jpg', 'CSM ratownictwo medyczne', 'CSM ratownictwo medyczne',$room_noactive);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);

            $ir=Add2_Room($Center_id, 'free', 21, 'B 0.31', 'CM_B0.31_CIM', 'b031/b0.31a.jpg', 'Sala CIM 31', 'Sala CIM 31',$room_noactive);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
    
            $ir=Add2_Room($Center_id, 'free', 3, 'B 1.23', '', 'b123/b1.23a.jpg', 'Pokój techników 1', 'Pokój techników 1',$room_noactive);
                Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);

            $ir=Add2_Room($Center_id, 'free', 3, 'B 3.03', '', 'b303/b3.03a.jpg', 'Magazyn B 3.03', 'Magazyn B 3.03',$room_noactive);
                Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);
                $sort_count=1;
                for ($i=1; $i<=1; $i++)
                    Add2_Storage($ir, $storage_magazynowy, 'regał '.str_pad($i, 2, 0, STR_PAD_LEFT), 'regał '.str_pad($i, 2, 0, STR_PAD_LEFT), 'regał magazynowy '.str_pad($i, 2, 0, STR_PAD_LEFT), 5, $sort_count++);

            $ir=Add2_Room($Center_id, 'prep', 1, 'B 3.04', 'CM_B3.04_OSCE', 'b304/b3.04a.jpg', 'Sala OSCE 04', 'Sala egzaminacyjna OSCE 3.04',$room_active);
                Add2_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'free', 1, 'B 3.05', '', 'b305/b3.05a.jpg', 'kontrolka sal OSCE 04 i OSCE 06', 'pomieszczenie kontrolne dla sal OSCE 3.04 i OSCE 3.06',$room_active);
                Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'prep', 1, 'B 3.06', 'CM_B3.06_OSCE', 'b306/b3.06a.jpg', 'Sala OSCE 06', 'Sala egzaminacyjna OSCE 3.06',$room_active);
                Add2_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);

            $ir=Add2_Room($Center_id, 'prep', 1, 'B 3.07', 'CM_B3.07', 'b307/b3.07a.jpg', 'Sala um. klinieczne 07', 'Sala nauczania umiejętności klinicznych 3.07',$room_active);
                Add2_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'free', 1, 'B 3.08', '', 'b308/b3.08a.jpg', 'kontrolka sal um. klinicznych 07 i 09 ', 'pomieszczenie kontrolne dla sal nauczania umiejętności klinicznych 3.07 i OSCE 3.09',$room_active);
                Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'prep', 1, 'B 3.09', 'CM_B3.09', 'b309/b3.09a.jpg', 'Sala um. klinieczne 09', 'Sala nauczania umiejętności klinicznych 3.09',$room_active);
                Add2_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);

            $ir=Add2_Room($Center_id, 'prep', 1, 'B 3.10', 'CM_B3.10', 'b310/b3.10a.jpg', 'Sala z pac. stand. 10', 'Sala z pacejntem standaryzowanym 3.10',$room_active);
                Add2_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'free', 1, 'B 3.11', '', 'b311/b3.11a.jpg', 'kontrolka sal pac. stand. 10 i 12 ', 'pomieszczenie kontrolne dla sal z pacjentem standaryzowanym 3.10 i 3.12',$room_active);
                Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'prep', 1, 'B 3.12', 'CM_B3.12', 'b312/b3.12a.jpg', 'Sala z pac. stand. 12', 'Sala z pacejntem standaryzowanym 3.12',$room_active);
                Add2_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);


            $ir=Add2_Room($Center_id, 'free', 3, 'B 3.26', '', 'b326/b3.26a.jpg', 'Magazyn Głowny', 'Magazyn B 3.26',$room_noactive);
                Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);
                $sort_count=1;
                for ($i='A'; $i<='O'; $i++)
                    Add2_Storage($ir, $storage_magazynowy, 'regał '.$i, 'regał '.$i, 'regał magazynowy '.$i, 5, $sort_count++);

            $ir=Add2_Room($Center_id, 'free', 1, 'B 3.28', 'CM_B3.28', 'b328/b3.28a.jpg', 'Sala B 3.28', 'Sala B 3.28',$room_noactive);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'phone', 1, 'B 3.29', 'CM_B3.29', 'b329/b3.29a.jpg', 'Sala B 3.29', 'Sala B 3.29',$room_active);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'free', 3, 'B 3.30', '', 'b330/b3.30a.jpg', 'Pokój plan+tech', 'Pokój plan+tech',$room_noactive);
                Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'free', 3, 'B 3.31', '', 'b331/b3.31a.jpg', 'Pokój techników 2', 'Pokój techników 2',$room_noactive);
                Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'phone', 1, 'B 3.32', 'CM_B3.32', 'b332/b3.32a.jpg', 'Sala 3.32', 'Sala 3.32',$room_active);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);

            $ir=Add2_Room($Center_id, 'free', 1, 'B 3.33', 'CM_B3.33', 'b333/b3.33a.jpg', 'Sala 3.33', 'Sala 3.33',$room_active);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);

            $ir=Add2_Room($Center_id, 'phone', 1, 'B 3.34', 'CM_B3.34', 'b334/b3.34a.jpg', 'Sala 3.34', 'Sala 3.34',$room_active);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'free', 2, 'B 3.35', '', 'b335/b3.35a.jpg', 'kontrolka sali 3.34', 'pomieszczenie kontrolne dla sali 3.34',$room_active);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'free', 2, 'B 3.36', '', 'b336/b3.36a.jpg', 'kontrolka sali 3.37', 'pomieszczenie kontrolne dla sali 3.37',$room_active);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'phone', 1, 'B 3.37', 'CM_B3.37', 'b337/b3.37a.jpg', 'Sala VR', 'Sala Wirtualnej Rzeczywistości',$room_active);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'free', 1, 'B 3.38', 'CM_B3.38', 'b338/b3.38a.jpg', 'Sala umiejętności chirurgicznych', 'Sala umiejętności chirurgicznych',$room_noactive);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);

    //        $ir=Add2_Room($Center_id, 'free', 1, 'C 0.11', 'CM_C0.11', 'c011/c0.11a.jpg', 'Sala 0.11', 'Sala Karetki',$room_active);
    //            Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);

            $ir=Add2_Room($Center_id, 'free', 1, 'C 2.02', '', 'c202/c2.02a.jpg', 'Sala 2.02', 'Sala Debriefingu',$room_active);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);

            $ir=Add2_Room($Center_id, 'free', 3, 'C 2.04', '', 'c204/c2.04a.jpg', 'Magazyn C 2.04', 'Magazyn C 2.04',$room_noactive);
                Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);
                $sort_count=1;
                for ($i=1; $i<=2; $i++)
                    Add2_Storage($ir, $storage_magazynowy, 'szafa '.str_pad($i, 2, 0, STR_PAD_LEFT), 'szafa '.str_pad($i, 2, 0, STR_PAD_LEFT), 'szafa magazynowa '.str_pad($i, 2, 0, STR_PAD_LEFT), 5, $sort_count++);

            $ir=Add2_Room($Center_id, 'stay', 1, 'C 2.07', 'CM_C2.07', 'c207/c2.07a.jpg', 'Sala 2.07', 'Sala Intensywnej Terapii',$room_active);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'free', 2, 'C 2.08', '', 'c208/c2.08a.jpg', 'Kontrolka 2.07', 'Kontrolka sali C 2.07',$room_active);
                Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'stay', 1, 'C 2.09', 'CM_C2.09', 'c209/c2.09a.jpg', 'Sala operacyjna', 'Sala operacyjna',$room_active);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'free', 2, 'C 2.10', '', 'c210/c2.10a.jpg', 'Kontrolka 2.10', 'Kontrolka sali C 2.09',$room_active);
                Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);
            $ir=Add2_Room($Center_id, 'free', 1, 'C 2.11', 'CM_C2.11', 'c211/c2.11a.jpg', 'Sala przedoperacyjna', 'Sala przygotowania pacjenta do operacji',$room_active);
                Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);


        $ir=Add2_Room($Center_id, 'stay', 1, 'D 0.09', 'CM_D_0/9', 'd009/d0.09a.jpg', 'SOR', 'Szpitalny Oddział Ratunkowy',$room_active);
            Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $sort_count=1;
            Add2_Storage($ir, $storage_cwiczeniowy, 'blat', 'blat', 'blat', 1, $sort_count++);
            for ($i='A'; $i<='H'; $i++)
                Add2_Storage($ir, $storage_cwiczeniowy, 'szafka '.$i, 'szafka '.$i, 'szafka '.$i, 5, $sort_count++);
        $ir=Add2_Room($Center_id, 'stay', 1, 'D 0.10', 'CM_D_0/10', 'd010/d0.10a.jpg', 'OIT pediatryczny', 'Sala Intensywnej Terapii Dziecięcej',$room_active);
            Add2_Storage($ir, $storage_cwicz_magaz, 'sala', 'sala', 'sala', 1, 0);
            $sort_count=1;
            Add2_Storage($ir, $storage_cwiczeniowy, 'blat', 'blat', 'blat', 1, $sort_count++);
            for ($i='A'; $i<='H'; $i++)
                Add2_Storage($ir, $storage_cwiczeniowy, 'szafka '.$i, 'szafka '.$i, 'szafka '.$i, 5, $sort_count++);

        $ir=Add2_Room($Center_id, 'free', 2, 'D 0.11b', '', 'd011/d0.11ba.jpg', 'Kontrolka D0.09', 'Kontrolka sali D 0.09',$room_active);
            Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);
        $ir=Add2_Room($Center_id, 'free', 2, 'D 0.11c', '', 'd011/d0.11c1.jpg', 'Kontrolka D0.10', 'Kontrolka sali D 0.10',$room_active);
            Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);
        $ir=Add2_Room($Center_id, 'free', 3, 'D 0.11d', '', 'd011/d0.11da.jpg', 'Magazyn sal D0', 'Magazyn sal D0 - 09 i 10',$room_noactive);
            Add2_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);
            for ($i=1; $i<=4; $i++)
                Add2_Storage($ir, $storage_magazynowy, 'szuflada '.str_pad($i, 2, 0, STR_PAD_LEFT), 'szuflada '.str_pad($i, 2, 0, STR_PAD_LEFT), 'szuflada magazynowa '.str_pad($i, 2, 0, STR_PAD_LEFT), 1, $sort_count++);
            Add2_Storage($ir, $storage_magazynowy, 'szafka podblatowa', 'szafka podblatowa', 'szafka podblatowa', 1, 0);
            Add2_Storage($ir, $storage_magazynowy, 'blat roboczy', 'blat roboczy', 'blat roboczy', 1, 0);

    }
}
