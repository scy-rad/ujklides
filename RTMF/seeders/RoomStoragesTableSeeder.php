<?php
//php71-cli artisan make:controller RoomStoragesController --resource --model=RoomStorages
use Illuminate\Database\Seeder;

use App\Rooms;
use App\RoomStorages;

class RoomStoragesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        ////////////////////////////////////////////////////
        $Room_id = Rooms::where('rooms_number','_MW')->first();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 2;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '0';
        $zmEQ->room_storages_name = 'magazyn';
        $zmEQ->room_storages_description = 'magazyn';
        $zmEQ->room_storages_shelf_count = 1;
        $zmEQ->room_storages_sort = 0;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();


        ////////////////////////////////////////////////////
        $Room_id = Rooms::where('rooms_number','B 1.01')->first();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 3;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '0';
        $zmEQ->room_storages_name = 'sala';
        $zmEQ->room_storages_description = 'sala';
        $zmEQ->room_storages_shelf_count = 1;
        $zmEQ->room_storages_sort = 0;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();
        
        ////////////////////////////////////////////////////
        $Room_id = Rooms::where('rooms_number','B 1.05')->first();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 3;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '0';
        $zmEQ->room_storages_name = 'sala';
        $zmEQ->room_storages_description = 'sala';
        $zmEQ->room_storages_shelf_count = 1;
        $zmEQ->room_storages_sort = 0;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 2;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '01W';
        $zmEQ->room_storages_name = 'Szafka wisząca 1-M';
        $zmEQ->room_storages_description = 'Szafka wisząca magazynowa 1';
        $zmEQ->room_storages_shelf_count = 2;
        $zmEQ->room_storages_sort = 1;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 2;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '01D';
        $zmEQ->room_storages_name = 'Szafka podblatowa 1-M';
        $zmEQ->room_storages_description = 'Szafka podblatowa magazynowa 1';
        $zmEQ->room_storages_shelf_count = 2;
        $zmEQ->room_storages_sort = 2;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 1;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '02W';
        $zmEQ->room_storages_name = 'Szafka wisząca 2-C';
        $zmEQ->room_storages_description = 'Szafka wisząca ćwiczeniowa 2';
        $zmEQ->room_storages_shelf_count = 2;
        $zmEQ->room_storages_sort = 3;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 1;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '02D';
        $zmEQ->room_storages_name = 'Szafka podblatowa 2-C';
        $zmEQ->room_storages_description = 'Szafka podblatowa ćwiczeniowa 2';
        $zmEQ->room_storages_shelf_count = 3;
        $zmEQ->room_storages_sort = 4;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();


        ////////////////////////////////////////////////////
        $Room_id = Rooms::where('rooms_number','B 1.15')->first();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 3;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '0';
        $zmEQ->room_storages_name = 'sala';
        $zmEQ->room_storages_description = 'sala';
        $zmEQ->room_storages_shelf_count = 1;
        $zmEQ->room_storages_sort = 0;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 1;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '01';
        $zmEQ->room_storages_name = 'Szafka podblatowa 1-C';
        $zmEQ->room_storages_description = 'Szafka podblatowa ćwiczeniowa 1';
        $zmEQ->room_storages_shelf_count = 2;
        $zmEQ->room_storages_sort = 1;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 1;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '02';
        $zmEQ->room_storages_name = 'Szafka podblatowa 2-C';
        $zmEQ->room_storages_description = 'Szafka podblatowa ćwiczeniowa 2';
        $zmEQ->room_storages_shelf_count = 3;
        $zmEQ->room_storages_sort = 2;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 2;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '03';
        $zmEQ->room_storages_name = 'Szafka podblatowa 3-M';
        $zmEQ->room_storages_description = 'Szafka podblatowa magazynowa 3';
        $zmEQ->room_storages_shelf_count = 2;
        $zmEQ->room_storages_sort = 3;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();


        ////////////////////////////////////////////////////
        $Room_id = Rooms::where('rooms_number','B 1.16')->first();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 3;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '0';
        $zmEQ->room_storages_name = 'sala';
        $zmEQ->room_storages_description = 'sala';
        $zmEQ->room_storages_shelf_count = 1;
        $zmEQ->room_storages_sort = 0;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 1;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '01';
        $zmEQ->room_storages_name = 'Szafka podblatowa 1-C';
        $zmEQ->room_storages_description = 'Szafka podblatowa ćwiczeniowa 1';
        $zmEQ->room_storages_shelf_count = 2;
        $zmEQ->room_storages_sort = 1;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 3;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '02';
        $zmEQ->room_storages_name = 'Szafka podblatowa 2-CM';
        $zmEQ->room_storages_description = 'Szafka podblatowa ćwiczeniowo-magazynowa 2';
        $zmEQ->room_storages_shelf_count = 3;
        $zmEQ->room_storages_sort = 2;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 2;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '03';
        $zmEQ->room_storages_name = 'Szafka podblatowa 3-M';
        $zmEQ->room_storages_description = 'Szafka podblatowa magazynowa 3';
        $zmEQ->room_storages_shelf_count = 2;
        $zmEQ->room_storages_sort = 3;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();


        ////////////////////////////////////////////////////
        $Room_id = Rooms::where('rooms_number','B 1.17')->first();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 3;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '0';
        $zmEQ->room_storages_name = 'sala';
        $zmEQ->room_storages_description = 'sala';
        $zmEQ->room_storages_shelf_count = 1;
        $zmEQ->room_storages_sort = 0;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();

        ////////////////////////////////////////////////////
        $Room_id = Rooms::where('rooms_number','B 1.18')->first();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 3;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '0';
        $zmEQ->room_storages_name = 'sala';
        $zmEQ->room_storages_description = 'sala';
        $zmEQ->room_storages_shelf_count = 1;
        $zmEQ->room_storages_sort = 0;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();


        ///////////////////////////////////////////////////
        $Room_id = Rooms::where('rooms_number','B 1.02')->first();

        $zmEQ = new RoomStorages();
        $zmEQ->room_storages_type_id = 3;
        $zmEQ->room_storages_room_id = $Room_id->id;
        $zmEQ->room_storages_number = '0';
        $zmEQ->room_storages_name = 'sala';
        $zmEQ->room_storages_description = 'sala';
        $zmEQ->room_storages_shelf_count = 1;
        $zmEQ->room_storages_sort = 0;
        $zmEQ->room_storages_status = 1;
        $zmEQ->save();
    }
}
