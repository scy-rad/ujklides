<?php
//php artisan db:seed --class=ReviewsTableSeeder

use Illuminate\Database\Seeder;


use App\ReviewTemplate;
use App\Review;
use App\ItemGroup;
use App\Item;


class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        function make_review_template($aF_group_name,$aF_template_title,$aF_review_type,$aF_period_days,$aF_days_before,$aF_days_after,$aF_template_body,$aF_revtemp_status)
        {
            $zmEQ = new ReviewTemplate();
            $zmEQ->item_group_id    =ItemGroup::where('item_group_name',$aF_group_name)->first()->id;
            $zmEQ->template_title   =$aF_template_title;
            $zmEQ->review_type      =$aF_review_type;    //1 - inny;  2 - miesięczny; 3 - półroczny;    4 - roczny;       51 - producenta (<50 - użytkownika powtarzalny)
            $zmEQ->period_days      =$aF_period_days;
            $zmEQ->days_before      =$aF_days_before;
            $zmEQ->days_after       =$aF_days_after;
            $zmEQ->template_body    =$aF_template_body;
            $zmEQ->revtemp_status   =$aF_revtemp_status;    //1-aktywny;    0-nieaktywny
            $zmEQ->save();
            return $zmEQ->id;
        }

        function make_review($aF_review_template_id,$aF_review_title,$aF_inventory_number,$aF_start_date,$aF_start_date_from,$aF_start_date_to,$aF_rev_status)
        {
            $zmEQ = new Review();
            $zmEQ->review_template_id   =$aF_review_template_id;
            $zmEQ->review_title     =$aF_review_title;
            $zmEQ->item_id          =Item::where('item_inventory_number',$aF_inventory_number)->first()->id;
            $zmEQ->start_date       =$aF_start_date;
            $zmEQ->start_date_from  =$aF_start_date_from;
            $zmEQ->start_date_to    =$aF_start_date_to;
            $zmEQ->review_body      ='';
            $zmEQ->rev_status       =$aF_rev_status;    //1-do zaplanowania;    2-zaplanowany;    100-zrealizowany
            $zmEQ->save();
            return $zmEQ->id;
        }

        $id_tmpl=make_review_template('SimMan 3G','przegląd roczny',4,365,31,14,'<ol>Sprawdzić wszystkie elementy, takie jak:<li>głowę</li><li>ręce</li><li>nogi</li><li>tułów</li><li>monitor pacjenta</li><li>komputer instruktora</li></ol>',1);

        $id_tmpl=make_review_template('SimMan 3G','przegląd semestralny',3,182,45,31,'<ol>Sprawdzić główne elementy, takie jak:<li>głowę</li><li>ręce</li><li>monitor pacjenta</li><li>komputer instruktora</li></ol>',1);

        $id_tmpl=make_review_template('SimMan 3G','przegląd miesięczny',2,31,7,7,'<ol>Sprawdzić najważniejsze elementy, takie jak:<li>monitor pacjenta</li><li>komputer instruktora</li></ol>',1);
            make_review($id_tmpl,'przegląd miesięczny','UJK/S/0009134/2020',date('Y-m-d',strtotime("+7 day")),date('Y-m-d'),date('Y-m-d',strtotime("+14 day")),2);
            make_review($id_tmpl,'przegląd miesięczny','UJK/S/0009135/2020',date('Y-m-d',strtotime("+7 day")),date('Y-m-d'),date('Y-m-d',strtotime("+14 day")),2);
            make_review($id_tmpl,'przegląd miesięczny','UJK/S/0009136/2020',date('Y-m-d',strtotime("+7 day")),date('Y-m-d'),date('Y-m-d',strtotime("+14 day")),2);
            make_review($id_tmpl,'przegląd miesięczny','UJK/S/0009137/2020',date('Y-m-d',strtotime("+7 day")),date('Y-m-d'),date('Y-m-d',strtotime("+14 day")),2);

        $id_tmpl=make_review_template('SimMan 3G','przegląd producenta',51,730,91,0,'Przegląd gwarancyjny',1);
            make_review($id_tmpl,'przegląd gwarancyjny','UJK/S/0009134/2020',date('Y-m-d',strtotime("+91 day")),date('Y-m-d'),date('Y-m-d',strtotime("+91 day")),1);
            make_review($id_tmpl,'przegląd gwarancyjny','UJK/S/0009135/2020',date('Y-m-d',strtotime("+91 day")),date('Y-m-d'),date('Y-m-d',strtotime("+91 day")),1);
            make_review($id_tmpl,'przegląd gwarancyjny','UJK/S/0009136/2020',date('Y-m-d',strtotime("+91 day")),date('Y-m-d'),date('Y-m-d',strtotime("+91 day")),1);
            make_review($id_tmpl,'przegląd gwarancyjny','UJK/S/0009137/2020',date('Y-m-d',strtotime("+91 day")),date('Y-m-d'),date('Y-m-d',strtotime("+91 day")),1);

    }
}
