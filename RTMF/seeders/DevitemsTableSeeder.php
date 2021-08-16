<?php

use Illuminate\Database\Seeder;

use App\Devitems;
use App\Devices;

class DevitemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $sp_Simman3G = Devices::where('devices_name','Simman 3G')->first();
    
        $zmEQ = new Devitems();
        $zmEQ->devitems_device_id = $sp_Simman3G->id;
        $zmEQ->devitems_room_storage_id = 4;
        $zmEQ->devitems_storage_shelf = 2;
        $zmEQ->devitems_serial_number = 'No Serial';
        $zmEQ->devitems_inventory_number = 'INW ABC/2019';
        $zmEQ->devitems_purchase_date = '2019-09-01';
        $zmEQ->devitems_warranty_date = '2022-08-31';
        $zmEQ->devitems_description = '';
        $zmEQ->devitems_status =1;
        $zmEQ->save();
     
    }
}
