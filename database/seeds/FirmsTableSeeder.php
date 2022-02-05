<?php
//php artisan db:seed --class=FirmsTableSeeder

use Illuminate\Database\Seeder;

class FirmsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        $role = new \App\Roles();
        $role->roles_name = 'Admin';
        $role->save();
        */

        $firm = new \App\ServiceFirm();
        $firm->firm_name    = 'SimEdu';
        $firm->firm_name2   = '';
        $firm->address_1    = '';
        $firm->address_2    = '';
        $firm->email        = '';
        $firm->phone        = '';
        $firm->status       = 1; //active
        $firm->save();
        
        
            $firmmail = new \App\ServiceMail();
            $firmmail->service_firm_id  = $firm->id;
            $firmmail->title            = 'mgr';
            $firmmail->name             = 'Konrad Jaklik';
            $firmmail->address_1        = '';
            $firmmail->address_2        = '';
            $firmmail->email            = 'k.jaklik@simedu.pl';
            $firmmail->phone            = '781-600-863';
            $firmmail->phone2           = '';
            $firmmail->description      = '';
            $firmmail->status           = 1;    //aktywny
            $firmmail->save();

        $firm = new \App\ServiceFirm();
        $firm->firm_name    = 'Laerdal Polska';
        $firm->firm_name2   = '';
        $firm->address_1    = '';
        $firm->address_2    = '';
        $firm->email        = '';
        $firm->phone        = '';
        $firm->status       = 1; //active
        $firm->save();
        
            $firmmail = new \App\ServiceMail();
            $firmmail->service_firm_id  = $firm->id;
            $firmmail->title            = 'mgr';
            $firmmail->name             = 'Michał Nieszporek';
            $firmmail->address_1        = '';
            $firmmail->address_2        = '';
            $firmmail->email            = 'michal.nieszporek@laerdal.com';
            $firmmail->phone            = '721-327-880';
            $firmmail->phone2           = '';
            $firmmail->description      = '';
            $firmmail->status           = 1;    //aktywny
            $firmmail->save();
            
    
        
    }
}
