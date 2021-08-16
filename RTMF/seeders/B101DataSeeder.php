<?php
use Illuminate\Database\Seeder;

use App\Rooms;
use App\RoomStorages;
use App\Fittings;
use App\ThingTypes;
use App\Things;

use App\Devices;
use App\Devitems;

use App\EquipmentTypes;
use App\Equipments;
use App\EquipmentRoomStorages;

use Illuminate\Support\Facades\DB;

class B101DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		
		$Room_id = Rooms::where('rooms_number','B 1.01')->first()->id;
		$Room_storage_id = RoomStorages::where([['room_storages_room_id',$Room_id],['room_storages_number','0']])->first()->id;

        ////////////////////////////////////////////////////
		///
		///     F I T T I N G S
		///
		////////////////////////////////////////////////////

        $zmEQ = new Fittings();
        $zmEQ->fittings_room_id = $Room_id;
		$zmEQ->fittings_photo = 'drager_wu_253_a_0103607.jpg';
        $zmEQ->fittings_name = 'panel nadłóżkowy';
        $zmEQ->fittings_model = 'Drager WU-253-A';
        $zmEQ->fittings_inventory_number = 'UJK/N/0103607/2019';
        $zmEQ->fittings_description = 'Poziomy ścienny panel zasilający';
        $zmEQ->save();

        $zmEQ = new Fittings();
        $zmEQ->fittings_room_id = $Room_id;
		$zmEQ->fittings_photo = 'drager_wu_253_a_0103608.jpg';
        $zmEQ->fittings_name = 'panel nadłóżkowy';
        $zmEQ->fittings_model = 'Drager WU-253-A';
        $zmEQ->fittings_inventory_number = 'UJK/N/0103608/2019';
        $zmEQ->fittings_description = 'Poziomy ścienny panel zasilający';
        $zmEQ->save();

        $zmEQ = new Fittings();
        $zmEQ->fittings_room_id = $Room_id;
		$zmEQ->fittings_photo = 'drager_wu_253_a_0103609.jpg';
        $zmEQ->fittings_name = 'panel nadłóżkowy';
        $zmEQ->fittings_model = 'Drager WU-253-A';
        $zmEQ->fittings_inventory_number = 'UJK/N/0103609/2019';
        $zmEQ->fittings_description = 'Poziomy ścienny panel zasilający';
        $zmEQ->save();

        $zmEQ = new Fittings();
        $zmEQ->fittings_room_id = $Room_id;
		$zmEQ->fittings_photo = 'telefon_6941.jpg';
        $zmEQ->fittings_name = 'telefon stacjonarny 6941';
        $zmEQ->fittings_model = 'Cisco CP6021';
        $zmEQ->fittings_inventory_number = '---';
        $zmEQ->fittings_description = 'Telefon przykręcony do ściany';
        $zmEQ->save();
		
		
		////////////////////////////////////////////////////
		///
		///     R O O M   S T O R A G E S
		///
		////////////////////////////////////////////////////
		$storage_cwiczeniowy = 1;
        $storage_magazynowy  = 2;
        $storage_cwicz_magaz = 3;
		$sort_count          = 1;


		$zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = $storage_cwiczeniowy;
        $zmEQ->room_storages_room_id = $Room_id;
        $zmEQ->room_storages_number = 'WR01';
        $zmEQ->room_storages_name = 'Wózek Reanimacyjny 01';
        $zmEQ->room_storages_description = 'Wózek Reanimacyjy 01';
        $zmEQ->room_storages_shelf_count = 2;
        $zmEQ->room_storages_sort = $sort_count++;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();
		
		for ($i=1; $i<=3; $i++)
		{
			$zmEQ = new RoomStorages();
			$zmEQ->room_storages_type_id = $storage_cwiczeniowy;
			$zmEQ->room_storages_room_id = $Room_id;
			$zmEQ->room_storages_number = 'W'.str_pad($i, 2, 0, STR_PAD_LEFT).'C';
			$zmEQ->room_storages_name = 'Szafka wisząca '.$i.'-C';
			$zmEQ->room_storages_description = 'Szafka wisząca ćwiczeniowa '.$i;
			$zmEQ->room_storages_shelf_count = 2;
			$zmEQ->room_storages_sort = $sort_count++;
			$zmEQ->room_storages_status = 1;
			$zmEQ->save();
		}
		for ($i=4; $i<=8; $i++)
		{
			$zmEQ = new RoomStorages();
			$zmEQ->room_storages_type_id = $storage_magazynowy;
			$zmEQ->room_storages_room_id = $Room_id;
			$zmEQ->room_storages_number = 'W'.str_pad($i, 2, 0, STR_PAD_LEFT).'M';
			$zmEQ->room_storages_name = 'Szafka wisząca '.$i.'-M';
			$zmEQ->room_storages_description = 'Szafka wisząca magazynowa '.$i;
			$zmEQ->room_storages_shelf_count = 2;
			$zmEQ->room_storages_sort = $sort_count++;
			$zmEQ->room_storages_status = 1;
			$zmEQ->save();
		}


			$zmEQ = new RoomStorages();
			$zmEQ->room_storages_type_id = $storage_cwiczeniowy;
			$zmEQ->room_storages_room_id = $Room_id;
			$zmEQ->room_storages_number = 'B01C';
			$zmEQ->room_storages_name = 'Blat roboczy 1-C';
			$zmEQ->room_storages_description = 'Blat roboczy ćwiczeniowy 1';
			$zmEQ->room_storages_shelf_count = 1;
			$zmEQ->room_storages_sort = $sort_count++;
			$zmEQ->room_storages_status = 1;
			$zmEQ->save();




			$zmEQ = new RoomStorages();
			$zmEQ->room_storages_type_id = $storage_cwiczeniowy;
			$zmEQ->room_storages_room_id = $Room_id;
			$zmEQ->room_storages_number = 'D01C';
			$zmEQ->room_storages_name = 'Szafka podblatowa 1-C';
			$zmEQ->room_storages_description = 'Szafka podblatowa ćwiczeniowa 1';
			$zmEQ->room_storages_shelf_count = 2;
			$zmEQ->room_storages_sort = $sort_count++;
			$zmEQ->room_storages_status = 1;
			$zmEQ->save();
			
			$zmEQ = new RoomStorages();
			$zmEQ->room_storages_type_id = $storage_cwiczeniowy;
			$zmEQ->room_storages_room_id = $Room_id;
			$zmEQ->room_storages_number = 'D02C';
			$zmEQ->room_storages_name = 'Szafka podblatowa 2-C';
			$zmEQ->room_storages_description = 'Szafka podblatowa ćwiczeniowa 2';
			$zmEQ->room_storages_shelf_count = 3;
			$zmEQ->room_storages_sort = $sort_count++;
			$zmEQ->room_storages_status = 1;
			$zmEQ->save();

		for($i=2;$i<=4;$i++)
		{
			$zmEQ = new RoomStorages();
			$zmEQ->room_storages_type_id = $storage_cwiczeniowy;
			$zmEQ->room_storages_room_id = $Room_id;
			$zmEQ->room_storages_number = 'D'.str_pad($i*2-1, 2, 0, STR_PAD_LEFT).'C';
			$zmEQ->room_storages_name = 'Szafka podblatowa '.($i*2-1).'-C';
			$zmEQ->room_storages_description = 'Szafka podblatowa ćwiczeniowa '.($i*2-1);
			$zmEQ->room_storages_shelf_count = 2;
			$zmEQ->room_storages_sort = $sort_count++;
			$zmEQ->room_storages_status = 1;
			$zmEQ->save();
			
			$zmEQ = new RoomStorages();
			$zmEQ->room_storages_type_id = $storage_cwiczeniowy;
			$zmEQ->room_storages_room_id = $Room_id;
			$zmEQ->room_storages_number = 'D'.str_pad($i*2, 2, 0, STR_PAD_LEFT).'C';
			$zmEQ->room_storages_name = 'Szafka podblatowa '.$i.'-C';
			$zmEQ->room_storages_description = 'Szafka podblatowa ćwiczeniowa '.$i;
			$zmEQ->room_storages_shelf_count = 3;
			$zmEQ->room_storages_sort = $sort_count++;
			$zmEQ->room_storages_status = 1;
			$zmEQ->save();
		}
		
			$zmEQ = new RoomStorages();
			$zmEQ->room_storages_type_id = $storage_cwiczeniowy;
			$zmEQ->room_storages_room_id = $Room_id;
			$zmEQ->room_storages_number = 'D09C';
			$zmEQ->room_storages_name = 'Szafka podblatowa 9-C';
			$zmEQ->room_storages_description = 'Szafka podblatowa ćwiczeniowa 9';
			$zmEQ->room_storages_shelf_count = 2;
			$zmEQ->room_storages_sort = $sort_count++;
			$zmEQ->room_storages_status = 1;
			$zmEQ->save();

		
		
        ////////////////////////////////////////////////////
		///
		///     T H I N G S
		///
		////////////////////////////////////////////////////
        
        $Type_id = ThingTypes::where('thing_types_name','łóżko szpitalne')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		$zmEQ->things_photo='nano_le_12_0008023.jpg';
        $zmEQ->things_producent='Famed';
        $zmEQ->things_model='NANO LE-12';
        $zmEQ->things_inventory_number='UJK/S/0008023/2019';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();

        $Type_id = ThingTypes::where('thing_types_name','leżanka stacjonarna')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		$zmEQ->things_photo='ls_s01_0103712.jpg';
        $zmEQ->things_producent='Medicor-Pol';
        $zmEQ->things_model='LS-S01';
        $zmEQ->things_inventory_number='UJK/N/0103712/2019';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();

        $Type_id = ThingTypes::where('thing_types_name','wózek do przewożenia chorych')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		$zmEQ->things_photo='wp_03_1_0103577.jpg';
        $zmEQ->things_producent='Famed';
        $zmEQ->things_model='WP-03.1';
        $zmEQ->things_inventory_number='UJK/N/0103577/2019';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();

        $Type_id = ThingTypes::where('thing_types_name','wózek inwalidzki')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		$zmEQ->things_photo='breezy_90_0103715.jpg';
        $zmEQ->things_producent='Breezy';
        $zmEQ->things_model='Breezy 90';
        $zmEQ->things_inventory_number='UJK/N/0103715/2019';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();

        $Type_id = ThingTypes::where('thing_types_name','wózek wielofunkcyjny reanimacyjny')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		$zmEQ->things_photo='ren_06_ST_0103579.jpg';
        $zmEQ->things_producent='Tech-med';
        $zmEQ->things_model='REN-06 / ST';
        $zmEQ->things_inventory_number='UJK/N/0103579/2019';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();

        $Type_id = ThingTypes::where('thing_types_name','wózek wielofunkcyjny reanimacyjny')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		$zmEQ->things_photo='ren_06_ST_0103578.jpg';
        $zmEQ->things_producent='Tech-med';
        $zmEQ->things_model='REN-06 / ST';
        $zmEQ->things_inventory_number='UJK/N/0103578/2019';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();

        $Type_id = ThingTypes::where('thing_types_name','szafka przyłóżkowa')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		$zmEQ->things_photo='sp_02_2_0103576.jpg';
        $zmEQ->things_producent='Famed';
        $zmEQ->things_model='SP-02.2';
        $zmEQ->things_inventory_number='UJK/N/0103576/2019';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();

        $Type_id = ThingTypes::where('thing_types_name','stojak do kroplówki')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		$zmEQ->things_photo='stojak_b101.jpg';
        $zmEQ->things_producent='x';
        $zmEQ->things_model='x';
        $zmEQ->things_inventory_number='---';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();

        $Type_id = ThingTypes::where('thing_types_name','wózek oddziałowy')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		$zmEQ->things_photo='wozek_oddzialowy_0025970.jpg';
        $zmEQ->things_producent='x';
        $zmEQ->things_model='x';
        $zmEQ->things_inventory_number='UJK/N/0025970/2003';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();

        $Type_id = ThingTypes::where('thing_types_name','wózek zabiegowy')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		//$zmEQ->things_photo='';
        $zmEQ->things_producent='x';
        $zmEQ->things_model='x';
        $zmEQ->things_inventory_number='---';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();
		
		$Type_id = ThingTypes::where('thing_types_name','pompa infuzyjna')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		$zmEQ->things_photo='en_s7_smart_0103604.jpg';
        $zmEQ->things_producent='Enmind';
        $zmEQ->things_model='EN-S7 Smart';
        $zmEQ->things_inventory_number='UJK/N/0103604/2019';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();
		
		$Type_id = ThingTypes::where('thing_types_name','ssak elektryczny')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		$zmEQ->things_photo='dynamic_ii_0103602.jpg';
        $zmEQ->things_producent='Cheiron';
        $zmEQ->things_model='Dynamic II';
        $zmEQ->things_inventory_number='UJK/N/0103602/2019';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();
		
		$Type_id = ThingTypes::where('thing_types_name','aparat EKG')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		$zmEQ->things_photo='ascardgreen_v_06_101_0103706.jpg';
        $zmEQ->things_producent='Aspel';
        $zmEQ->things_model='AsCARDGreen v.06.101';
        $zmEQ->things_inventory_number='UJK/N/0103706/2019';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();

		$Type_id = ThingTypes::where('thing_types_name','respirator')->first();
        $zmEQ = new Things();
        $zmEQ->things_thing_types_id = $Type_id->id;
        $zmEQ->things_room_id = $Room_id;
		$zmEQ->things_photo='savina_300_0009034.jpg';
        $zmEQ->things_producent='Drager';
        $zmEQ->things_model='Savina 300';
        $zmEQ->things_inventory_number='UJK/S/0009034/2019';
        $zmEQ->things_description = 'xyz';
        $zmEQ->save();
		
        ////////////////////////////////////////////////////
		///
		///     D E V I C E S
		///
		////////////////////////////////////////////////////

		
		$sp_Simman3G = Devices::where('devices_name','Simman 3G')->first();    
        $zmEQ = new Devitems();
        $zmEQ->devitems_device_id = $sp_Simman3G->id;
        $zmEQ->devitems_room_storage_id = $Room_storage_id;
        $zmEQ->devitems_serial_number = 'No Serial';
        $zmEQ->devitems_inventory_number = 'INW XYZ/2019';
        $zmEQ->devitems_purchase_date = '2019-09-01';
        $zmEQ->devitems_warranty_date = '2022-08-31';
        $zmEQ->devitems_description = '';
        $zmEQ->devitems_status =1;
        $zmEQ->save();
		
		////////////////////////////////////////////////////
		///
		///     E Q U I P M E N T S
		///
		////////////////////////////////////////////////////    
        function put_equipment($equipment_type,$equipment_model,$equipment_room,$equipment_planned)
            {
            $EqT_id = EquipmentTypes::where('equipment_types_name',$equipment_type)->first()->id;
            if ($equipment_model==NULL)
                $Ers_equipment_id = Equipments::where('equipments_equipment_types_id',$EqT_id)->first()->id;
            else
                $Ers_equipment_id = Equipments::where('equipments_model', $equipment_model)->first()->id;                
            $zmEQ = new EquipmentRoomStorages();
            $zmEQ->ers_equipment_id = $Ers_equipment_id;
            $zmEQ->ers_room_storage_id = $equipment_room;
            $zmEQ->ers_planned = $equipment_planned;
            $zmEQ->ers_current=$zmEQ->ers_planned;
            $zmEQ->save();
            }
		
		$Room_storage_id = RoomStorages::where([['room_storages_room_id',$Room_id],['room_storages_number','WR01']])->first()->id;
        

        put_equipment('stetoskop', NULL, $Room_storage_id, 6);
        put_equipment('pulsoksymetr', NULL, $Room_storage_id, 1);
        put_equipment('staza automatyczna', NULL, $Room_storage_id, 1);
        put_equipment('worek samorozprężalny', NULL, $Room_storage_id, 1);
        put_equipment('glukometr', 'One Touch', $Room_storage_id, 1);
        put_equipment('glukometr', 'Superior', $Room_storage_id, 1);
        put_equipment('nożyczki','nożyczki wzmacniane', $Room_storage_id, 1);
        put_equipment('nożyczki', 'nożyczki ratownicze', $Room_storage_id, 1);
        put_equipment('termometr', 'termometr douszny', $Room_storage_id, 1);
        put_equipment('termometr', 'termometr bezdotykowy', $Room_storage_id, 1);
        put_equipment('bańki', 'bańki próżniowe', $Room_storage_id, 1);
        put_equipment('dzbanek plastikowy', NULL, $Room_storage_id, 1);
        put_equipment('google ochronne', NULL, $Room_storage_id, 1);
        put_equipment('narzędzia chirurgiczne', 'kleszcze do otrzewnej', $Room_storage_id, 1);
        put_equipment('narzędzia chirurgiczne', 'kleszcze naczyniowe', $Room_storage_id, 1);
        put_equipment('narzędzia chirurgiczne', 'kleszcze x', $Room_storage_id, 1);
        put_equipment('narzędzia chirurgiczne', 'kleszcze y', $Room_storage_id, 1);
        put_equipment('narzędzia chirurgiczne', 'penseta anatomiczna prosta', $Room_storage_id, 1);
        put_equipment('miski', 'miska nerkowata metalowa', $Room_storage_id, 1);
        put_equipment('miski', 'miska nerkowata plastikowa', $Room_storage_id, 1);
        put_equipment('miski', 'miska okrągła plastikowa', $Room_storage_id, 1);
        put_equipment('podstawki, uchwyty', 'podstawka do kieliszków', $Room_storage_id, 1);
        put_equipment('rurki ustno-gardłowe', NULL, $Room_storage_id, 1);
        put_equipment('termofor', NULL, $Room_storage_id, 1);
/*  */

    }
}


