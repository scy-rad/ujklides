<?php

//php71-cli artisan migrate:refresh --seed
//composer dump-autoload
//php71-cli artisan db:seed --class=ParamsTableSeeder
//php71-cli artisan make:model ScenarioFiles --all
//php71-cli artisan make:controller GalleryController --resource


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('LibrariesTableSeeder');
        $this->call('RolesTableSeeder');
        $this->call('UsersTableSeeder');
		$this->call('Users2TableSeeder');
        //$this->call('PagesTableSeeder');
        $this->call('RoomsTableSeeder');
        $this->call('RoomsLekTableSeeder');

        $this->call('SimmedsTableSeeder');
		$this->call('ScenariosTableSeeder');

        $this->call('ItemsTableSeeder');

        $this->call('DocsTableSeeder');

       $this->call('GalleryTableSeeder');
       $this->call('PlikTableSeeder');
       $this->call('ReviewsTableSeeder');

       $this->call('ParamsTableSeeder');
        
		}
}
