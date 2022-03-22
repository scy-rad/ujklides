<?php
//php artisan db:seed --class=PlDaysMonthsTableSeeder

use Illuminate\Database\Seeder;


class PlDaysMonthsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
		DB::table('pl_days')->truncate();
		DB::table('pl_months')->truncate();

        $status = DB::table('pl_days')
        ->insert(['id' => 1, 'pl_day' => 'Niedziela', 'pl_day_short' => 'Ni']);
        $status = DB::table('pl_days')
        ->insert(['id' => 2, 'pl_day' => 'Poniedziałek', 'pl_day_short' => 'Pn']);
        $status = DB::table('pl_days')
        ->insert(['id' => 3, 'pl_day' => 'Wtorek', 'pl_day_short' => 'Wt']);
        $status = DB::table('pl_days')
        ->insert(['id' => 4, 'pl_day' => 'Środa', 'pl_day_short' => 'Śr']);
        $status = DB::table('pl_days')
        ->insert(['id' => 5, 'pl_day' => 'Czwartek', 'pl_day_short' => 'Cz']);
        $status = DB::table('pl_days')
        ->insert(['id' => 6, 'pl_day' => 'Piątek', 'pl_day_short' => 'Pt']);
        $status = DB::table('pl_days')
        ->insert(['id' => 7, 'pl_day' => 'Sobota', 'pl_day_short' => 'Sb']);


        $status = DB::table('pl_months')
        ->insert(['id' => 1, 'pl_month' => 'Styczeń', 'pl_month_short' => 'Sty', 'roma_month' => 'I']);
        $status = DB::table('pl_months')
        ->insert(['id' => 2, 'pl_month' => 'Luty', 'pl_month_short' => 'Lut', 'roma_month' => 'II']);
        $status = DB::table('pl_months')
        ->insert(['id' => 3, 'pl_month' => 'Marzec', 'pl_month_short' => 'Mar', 'roma_month' => 'III']);
        $status = DB::table('pl_months')
        ->insert(['id' => 4, 'pl_month' => 'Kwiecień', 'pl_month_short' => 'Kwi', 'roma_month' => 'IV']);
        $status = DB::table('pl_months')
        ->insert(['id' => 5, 'pl_month' => 'Maj', 'pl_month_short' => 'Maj', 'roma_month' => 'V']);
        $status = DB::table('pl_months')
        ->insert(['id' => 6, 'pl_month' => 'Czerwiec', 'pl_month_short' => 'Cze', 'roma_month' => 'VI']);
        $status = DB::table('pl_months')
        ->insert(['id' => 7, 'pl_month' => 'Lipiec', 'pl_month_short' => 'Lip', 'roma_month' => 'VII']);
        $status = DB::table('pl_months')
        ->insert(['id' => 8, 'pl_month' => 'Sierpień', 'pl_month_short' => 'Sie', 'roma_month' => 'VIII']);
        $status = DB::table('pl_months')
        ->insert(['id' => 9, 'pl_month' => 'Wrzesień', 'pl_month_short' => 'Wrz', 'roma_month' => 'IX']);
        $status = DB::table('pl_months')
        ->insert(['id' => 10, 'pl_month' => 'Październik', 'pl_month_short' => 'Paź', 'roma_month' => 'X']);
        $status = DB::table('pl_months')
        ->insert(['id' => 11, 'pl_month' => 'Listopad', 'pl_month_short' => 'Lis', 'roma_month' => 'XI']);
        $status = DB::table('pl_months')
        ->insert(['id' => 12, 'pl_month' => 'Grudzień', 'pl_month_short' => 'Gru', 'roma_month' => 'XII']);



    }
}
