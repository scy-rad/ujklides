<?php

use Illuminate\Database\Seeder;

use App\Room;
use App\RoomStorage;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    function Add_Room($aF_Center_id, $aF_room_type, $aF_number, $aF_xp_code, $aF_photo, $aF_name, $aF_Description, $aF_status)
        {
        $zmEQ = new Room();
        $zmEQ->room_type_id = $aF_room_type;
        $zmEQ->center_id = $aF_Center_id;
        $zmEQ->room_number = $aF_number;
        $zmEQ->room_xp_code = $aF_xp_code;
        if ($aF_photo!='') $zmEQ->room_photo = $aF_photo;
        $zmEQ->room_name = $aF_name;
        $zmEQ->room_description = $aF_Description;
        $zmEQ->room_status = $aF_status;
        $zmEQ->save();
        return $zmEQ->id;
        }

    function Add_Storage($aF_room, $aF_type, $aF_number, $aF_name, $aF_Description, $aF_shelf,$aF_sort)
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

        $Center_id=1;
        $room_active=1;
        $room_noactive=0;

        $ir=Add_Room($Center_id, 3, '_MW', '', '', 'Magazyn Wejściowy', 'Magazyn sprzętów przychodzących',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);

        $ir=Add_Room($Center_id, 1, 'A 1.05', 'CM_A1.05', 'a105/a1.05a.jpg', 'Umiejętności techniczne - położnictwo', 'Sala umiejętności technicznych - położnictwo',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            $sort_count=1;
            Add_Storage($ir, $storage_cwicz_magaz, 'łazienka', 'łazienka', 'łazienka', 1, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafa-01', 'Szafa 01-M', 'Szafa magazynowa 1', 6, $sort_count++);
            for ($i=1; $i<=6; $i++)
                Add_Storage($ir, $storage_magazynowy, 'szafka G'.str_pad($i, 2, 0, STR_PAD_LEFT), 'Szafka górna '.$i.'-M', 'Szafka górna magazynowa '.$i, 3, $sort_count++);
            for ($i=1; $i<=6; $i++)
                Add_Storage($ir, $storage_magazynowy, 'szafka D'.str_pad($i, 2, 0, STR_PAD_LEFT), 'Szafka dolna '.$i.'-M', 'Szafka dolna magazynowa '.$i, 2, $sort_count++);
            for ($i=1; $i<=2; $i++)
                Add_Storage($ir, $storage_magazynowy, 'szafka P'.str_pad($i, 2, 0, STR_PAD_LEFT), 'Szafka podblatowa '.$i.'-M', 'Szafka podblatowa magazynowa '.$i, 2, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'blat 01', 'Blat roboczy 1-C', 'Blat roboczy ćwiczeniowy 1', 1, $sort_count++);
        $ir=Add_Room($Center_id, 1, 'A 1.06',  'CM_A1.06','a106/a1.06a.jpg', 'Umiejętności pielęgniarskie', 'Sala umiejętności pielęgniarskich',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            $sort_count=1;
            for ($i=1; $i<=4; $i++)
                Add_Storage($ir, $storage_magazynowy, 'szafka W'.str_pad($i, 2, 0, STR_PAD_LEFT), 'Szafka wisząca '.$i.'-M', 'Szafka wisząca magazynowa '.$i, 2, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafka P01', 'Szafka podblatowa 01-M', 'Szafka podblatowa magazynowa 01', 3, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafka P02', 'Szafka podblatowa 02-M', 'Szafka podblatowa magazynowa 02', 2, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafka P03', 'Szafka podblatowa 03-M', 'Szafka podblatowa magazynowa 03', 3, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafka P04', 'Szafka podblatowa 04-M', 'Szafka podblatowa magazynowa 04', 3, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafka P05', 'Szafka podblatowa 05-M', 'Szafka podblatowa magazynowa 05', 2, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'blat 01', 'Blat roboczy 1-C', 'Blat roboczy ćwiczeniowy 1', 1, $sort_count++);

        $ir=Add_Room($Center_id, 1, 'A 1.07', 'CM_A1.07', 'a107/a1.07c.jpg', 'Umiejętności techniczne - pielęgniarstwo', 'Sala umiejętności technicznych - pielęgniarstwo',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            $sort_count=1;
            for ($i=1; $i<=3; $i++)
                Add_Storage($ir, $storage_magazynowy, 'szafka W'.str_pad($i, 2, 0, STR_PAD_LEFT), 'Szafka wisząca '.$i.'-M', 'Szafka wisząca magazynowa '.$i, 2, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafka P01', 'Szafka podblatowa 01-M', 'Szafka podblatowa magazynowa 01', 2, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafka P02', 'Szafka podblatowa 02-M', 'Szafka podblatowa magazynowa 02', 2, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafka P03', 'Szafka podblatowa 03-M', 'Szafka podblatowa magazynowa 03', 3, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafka P04', 'Szafka podblatowa 04-M', 'Szafka podblatowa magazynowa 04', 2, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'blat 01', 'Blat roboczy 1-C', 'Blat roboczy ćwiczeniowy 1', 2, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'S01', 'Stanowisko noworodkowe 1-M', 'Stanowisko noworodkowe magazynowe 1', 1, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'S02', 'Stanowisko noworodkowe 2-M', 'Stanowisko noworodkowe magazynowe 2', 5, $sort_count++);

        $ir=Add_Room($Center_id, 4, 'A 1.08', '', 'a108/a1.08wejscie.jpg', 'Komunikacja sal', 'Komunikacja sal - pielęgniarstwo i położnictwo',$room_noactive);
            Add_Storage($ir, $storage_magazynowy, 'korytarz', 'korytarz', 'korytarz', 1, 0);
            $sort_count=1;
            Add_Storage($ir, $storage_magazynowy, 'szafa A01', 'Szafa A 1-M', 'Szafa magazynowa A 1-M', 6, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafa A02', 'Szafa A 2-M', 'Szafa magazynowa A 2-M', 5, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafa A01', 'Szafa B 1-M', 'Szafa magazynowa B 1-M', 6, $sort_count++);
            for ($i=2; $i<=8; $i++)
                Add_Storage($ir, $storage_magazynowy, 'szafa B'.str_pad($i, 2, 0, STR_PAD_LEFT), 'Szafa magazynowa B '.$i.'-M', 'Szafa magazynowa B '.$i, 5, $sort_count++);
            for ($i=5; $i<=8; $i++)
                Add_Storage($ir, $storage_magazynowy, 'nadstawka B'.str_pad($i, 2, 0, STR_PAD_LEFT), 'Nadstawka magazynowa B '.$i.'-M', 'Nadstawka magazynowa B '.$i, 2, $sort_count++);
            for ($i=1; $i<=3; $i++)
                Add_Storage($ir, $storage_magazynowy, 'szafka W'.str_pad($i, 2, 0, STR_PAD_LEFT), 'Szafka wisząca '.$i.'-M', 'Szafka wisząca magazynowa '.$i, 2, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'blat 01', 'Blat roboczy 1-M', 'Blat roboczy magazynowy 1', 1, $sort_count++);
            for($i=1;$i<=2;$i++)
                Add_Storage($ir, $storage_magazynowy, 'szafka P'.str_pad($i, 2, 0, STR_PAD_LEFT), 'Szafka podblatowa '.$i.'-M', 'Szafka podblatowa magazynowa '.$i, 2, $sort_count++);
            for($i=3;$i<=4;$i++)
                Add_Storage($ir, $storage_magazynowy, 'szafka P'.str_pad($i, 2, 0, STR_PAD_LEFT), 'Szafka podblatowa '.$i.'-M', 'Szafka podblatowa magazynowa '.$i, 3, $sort_count++);


        $ir=Add_Room($Center_id, 1, 'A 1.09', 'CM_A1.09', 'a109/a1.09a.jpg', 'Sala umiejętności położniczych', 'Sala umiejętności położniczych',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            $sort_count=1;
            Add_Storage($ir, $storage_magazynowy, 'szafa 01', 'Szafa 1-M', 'Szafa magazynowa 1-M', 6, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafa 02', 'Szafa 2-M', 'Szafa magazynowa 2-M', 4, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafa 03', 'Szafa 3-M', 'Szafa magazynowa 3-M', 5, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafa 04', 'Szafa 4-M', 'Szafa magazynowa 4-M', 5, $sort_count++);
            Add_Storage($ir, $storage_magazynowy, 'szafa 05', 'Szafa 5-M', 'Szafa magazynowa 5-M', 1, $sort_count++);

        $ir=Add_Room($Center_id, 1, 'B 1.12', 'CM_B1.12_OSCE', 'b112/b1.12a.jpg', 'Sala OSCE', 'Sala OSCE',$room_noactive);
        $ir=Add_Room($Center_id, 1, 'B 1.13', 'CM_B1.13_OSCE', 'b113/b1.13a.jpg', 'Sala OSCE', 'Sala OSCE',$room_noactive);
        $ir=Add_Room($Center_id, 1, 'B 1.14', 'CM_B1.14_OSCE', 'b114/b1.14a.jpg', 'Sala OSCE', 'Sala OSCE',$room_noactive);
        $ir=Add_Room($Center_id, 1, 'B 1.15', 'CM_B1.15_OSCE', 'b115/b1.15a.jpg', 'Sala OSCE', 'Sala OSCE',$room_noactive);
        $ir=Add_Room($Center_id, 1, 'B 1.16', 'CM_B1.16_OSCE', 'b116/b1.16a.jpg', 'Sala OSCE', 'Sala OSCE',$room_noactive);
        $ir=Add_Room($Center_id, 1, 'B 1.17', 'CM_B1.17_OSCE', 'b117/b1.17a.jpg', 'Sala OSCE', 'Sala OSCE',$room_noactive);
        $ir=Add_Room($Center_id, 1, 'B 1.18', 'CM_B1.18_OSCE', 'b118/b1.18a.jpg', 'Sala OSCE', 'Sala OSCE',$room_noactive);

        $ir=Add_Room($Center_id, 1, 'B 1.25', 'CM_B1.25', 'b125/b1.25a.jpg', 'Sala B 1.25', 'Sala B 1.25',$room_noactive);


        $ir=Add_Room($Center_id, 11, 'B 1.01', 'CM_B1.01', 'b101/b1.01a.jpg', 'Wysoka Wierność Pielęgniarstwo', 'Sala Wysokiej Wierności Opieki Pielęgniarskiej',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            $sort_count=1;
            for ($i=1; $i<=5; $i++)
                Add_Storage($ir, $storage_cwiczeniowy, 'szafka W'.str_pad($i, 2, 0, STR_PAD_LEFT), 'Szafka wisząca '.$i.'-C', 'Szafka wisząca ćwiczeniowa '.$i, 2, $sort_count++);
            for ($i=6; $i<=8; $i++)
                Add_Storage($ir, $storage_magazynowy, 'szafka W'.str_pad($i, 2, 0, STR_PAD_LEFT), 'Szafka wisząca '.$i.'-M', 'Szafka wisząca magazynowa '.$i, 2, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'blat 01', 'Blat roboczy 1-C', 'Blat roboczy ćwiczeniowy 1', 1, $sort_count++);
            for($i=1;$i<=4;$i++) {
                Add_Storage($ir, $storage_cwiczeniowy, 'szafka D'.str_pad($i*2-1, 2, 0, STR_PAD_LEFT), 'Szafka podblatowa '.($i*2-1).'-C', 'Szafka podblatowa ćwiczeniowa '.($i*2-1), 2, $sort_count++);
                Add_Storage($ir, $storage_cwiczeniowy, 'szafka D'.str_pad($i*2, 2, 0, STR_PAD_LEFT), 'Szafka podblatowa '.($i*2).'-C', 'Szafka podblatowa ćwiczeniowa '.($i*2), 3, $sort_count++);
                }
        $ir=Add_Room($Center_id, 2, 'B 1.02', '', 'b102/b1.02.jpg', 'Pomieszczenie kontrolne (B 1.01)', 'pomieszczenie kontrolne przy sali B 1.01',$room_noactive);
            Add_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);

        $ir=Add_Room($Center_id, 2, 'B 1.03', '', 'b103/b1.03.jpg', 'Pomieszczenie kontrolne (B 1.05)', 'pomieszczenie kontrolne przy sali B 1.05',$room_noactive);
            Add_Storage($ir, $storage_magazynowy, 'sala', 'sala', 'sala', 1, 0);

        $ir=Add_Room($Center_id, 11, 'B 1.05', 'CM_B1.05', 'b105/b1.05a.jpg', 'Wysoka Wierność Położnictwo', 'Sala Wysokiej Wierności Położnicza',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            $sort_count=1;
            for ($i=1; $i<=6; $i++) 
                Add_Storage($ir, $storage_cwiczeniowy, 'szafka W'.str_pad($i, 2, 0, STR_PAD_LEFT), 'Szafka wisząca '.$i.'-C', 'Szafka wisząca ćwiczeniowa '.$i, 2, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'blat 01', 'Blat roboczy 1-C', 'Blat roboczy ćwiczeniowy 1', 1, $sort_count++);
            for($i=1;$i<=3;$i++) {
                Add_Storage($ir, $storage_cwiczeniowy, 'szafka P'.str_pad($i*2-1, 2, 0, STR_PAD_LEFT), 'Szafka podblatowa '.($i*2-1).'-C', 'Szafka podblatowa ćwiczeniowa '.($i*2-1), 2, $sort_count++);
                Add_Storage($ir, $storage_cwiczeniowy, 'szafka P'.str_pad($i*2, 2, 0, STR_PAD_LEFT), 'Szafka podblatowa '.($i*2).'-C', 'Szafka podblatowa ćwiczeniowa '.($i*2), 3, $sort_count++);
                }
        $ir=Add_Room($Center_id, 3, 'B 1.06', '', 'b106/b1.06.jpg', 'mag-L', 'Magazyn (pomieszczenie lewe)',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
        $ir=Add_Room($Center_id, 3, 'B 1.07', '', 'b107/b1.07.jpg', 'mag-S', 'Magazyn (pomieszczenie środkowe)',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
        $ir=Add_Room($Center_id, 3, 'B 1.08', '', 'b108/b1.08.jpg', 'mag-K', 'Magazyn-komunikacja (pomieszczenie przejściowe)',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'korytarz', 'korytarz', 'korytarz', 1, 0);
        $ir=Add_Room($Center_id, 3, 'B 1.09', '', 'b109/b1.09.jpg', 'mag-P', 'Magazyn (pomieszczenie prawe)',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
        $ir=Add_Room($Center_id, 4, 'B 1.11', '', 'b111/b1.11.jpg', 'OSCE-KomPol', 'komunikacja OSCE Położnictwo',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'korytarz', 'korytarz', 'korytarz', 1, 0);

        $ir=Add_Room($Center_id, 1, 'B 1.12', 'CM_B1.12_CSM_II', 'b112/b1.12a.jpg', 'OSCE Poł 01', 'Sala Egzaminacyjna OSCE - położnictwo 01',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            $sort_count=1;
            Add_Storage($ir, $storage_cwiczeniowy, 'blat 01', 'Blat roboczy 1-C', 'Blat roboczy ćwiczeniowy 1', 1, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'szafka P01', 'Szafka podblatowa 1-C', 'Szafka podblatowa ćwiczeniowa 1', 2, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'szafka P02', 'Szafka podblatowa 2-C', 'Szafka podblatowa ćwiczeniowa 2', 3, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'szafka P03', 'Szafka podblatowa 3-M', 'Szafka podblatowa magazynowa 3', 2, $sort_count++);

        $ir=Add_Room($Center_id, 1, 'B 1.13', 'CM_B1.13_CSM_II', 'b113/b1.13a.jpg', 'OSCE Poł 02', 'Sala Egzaminacyjna OSCE - położnictwo 02',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            $sort_count=1;
            Add_Storage($ir, $storage_cwiczeniowy, 'blat 01', 'Blat roboczy 1-C', 'Blat roboczy ćwiczeniowy 1', 1, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'szafka P01', 'Szafka podblatowa 1-C', 'Szafka podblatowa ćwiczeniowa 1', 2, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'szafka P02', 'Szafka podblatowa 2-C', 'Szafka podblatowa ćwiczeniowa 2', 3, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'szafka P03', 'Szafka podblatowa 3-M', 'Szafka podblatowa magazynowa 3', 2, $sort_count++);

        $ir=Add_Room($Center_id, 2, 'B 1.14', '', 'b114/b1.14a.jpg', 'OSCE kontrolka', 'Sala Egzaminacyjna OSCE - kontrolka',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);

        $ir=Add_Room($Center_id, 11, 'B 1.15', 'CM_B1.15_CSM_II', 'b115/b1.15a.jpg', 'OSCE Pie 01', 'Sala Egzaminacyjna OSCE - pielęgniarstwo 01',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            $sort_count=1;
            Add_Storage($ir, $storage_cwiczeniowy, 'blat 01', 'Blat roboczy 1-C', 'Blat roboczy ćwiczeniowy 1', 1, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'szafka P01', 'Szafka podblatowa 1-C', 'Szafka podblatowa ćwiczeniowa 1', 2, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'szafka P02', 'Szafka podblatowa 2-C', 'Szafka podblatowa ćwiczeniowa 2', 3, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'szafka P03', 'Szafka podblatowa 3-M', 'Szafka podblatowa magazynowa 3', 2, $sort_count++);

        $ir=Add_Room($Center_id, 11, 'B 1.16', 'CM_B1.16_CSM_II', 'b116/b1.16a.jpg', 'OSCE Pie 02', 'Sala Egzaminacyjna OSCE - pielęgniarstwo 02',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
            $sort_count=1;
            Add_Storage($ir, $storage_cwiczeniowy, 'blat 01', 'Blat roboczy 1-C', 'Blat roboczy ćwiczeniowy 1', 1, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'szafka P01', 'Szafka podblatowa 1-C', 'Szafka podblatowa ćwiczeniowa 1', 2, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'szafka P02', 'Szafka podblatowa 2-C', 'Szafka podblatowa ćwiczeniowa 2', 3, $sort_count++);
            Add_Storage($ir, $storage_cwiczeniowy, 'szafka P03', 'Szafka podblatowa 3-M', 'Szafka podblatowa magazynowa 3', 2, $sort_count++);
        $ir=Add_Room($Center_id, 11, 'B 1.17', 'CM_B1.17_CSM_II', 'b117/b1.17a.jpg', 'OSCE Pie 03', 'Sala Egzaminacyjna OSCE - pielęgniarstwo 03',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
        $ir=Add_Room($Center_id, 11, 'B 1.18', 'CM_B1.18_CSM_II', 'b118/b1.18a.jpg', 'OSCE Pie 04', 'Sala Egzaminacyjna OSCE - pielęgniarstwo 04',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'sala', 'sala', 'sala', 1, 0);
        $ir=Add_Room($Center_id, 4, 'B 1.19', '', 'b119/b1.19.jpg', 'OSCE-KomPiel', 'komunikacja OSCE Pielęgniarstwo',$room_noactive);
            Add_Storage($ir, $storage_cwiczeniowy, 'korytarz', 'korytarz', 'korytarz', 1, 0);

    }
}
