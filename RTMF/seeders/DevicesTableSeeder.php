<?php

use Illuminate\Database\Seeder;

use App\Devices;
use App\Libraries;

class DevicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $sp_SymMulti = Libraries::where('lib_name','symulator multi')->first()->id;
        $sp_SymMono = Libraries::where('lib_name','symulator mono')->first()->id;
        $sp_Trenazer = Libraries::where('lib_name','trenażer')->first()->id;
        $sp_Pozoracje = Libraries::where('lib_name','pozoracje')->first()->id;
        $sp_Inne = Libraries::where('lib_name','inne')->first()->id;

        function add_device($F_type, $F_name, $F_photo, $F_desc)
        {
            $zmEQ = new Devices();
            $zmEQ->devices_lib_group_id = $F_type;
            $zmEQ->devices_photo = $F_photo;
            $zmEQ->devices_name = $F_name;
            $zmEQ->devices_description = $F_desc;
            $zmEQ->devices_status = 1;
            $zmEQ->save();
            return $zmEQ->id;
        }

        add_device($sp_SymMulti, 'Simman 3G', 'simman3g.jpg',
        '<p>SimMan 3G to udoskonalony symulator pacjenta, służący do prezentacji objaw&oacute;w neurologicznych i fizjologicznych. Łączy łatwość obsługi z innowacyjną technologią, umożliwiającą m. in. automatyczne rozpoznawanie lek&oacute;w.</p>');
        add_device($sp_Trenazer, 'ręka do wkłuć', 'reka_do_wkluc_001.jpg','Trenażer ręki do ćwiczenia wkłuć, ..................');
        add_device($sp_SymMono, 'Little Anne QCPR', 'little_anne_qcpr.jpg','Fantom do BLS');
        add_device($sp_SymMono, 'Little Junior QCPR', 'little_junior_qcpr.jpg','Fantom do BLS');
        add_device($sp_SymMono, 'Little Baby QCPR', 'little_baby_qcpr.jpg','Fantom do BLS');
        add_device($sp_Pozoracje, 'Zestaw sztucznych ran', 'zestaw_ran_001.jpg','Zestaw sztucznych ran do pozorowania urazów');
        add_device($sp_SymMono, 'Piersi do samobadania', 'piersi_do_samobadania.jpg','Sztuczne pierwsi do nauki samobadania');
        

    }
}
