<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Review;
use App\ReviewTemplate;
use App\Item;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(Int $item_id)
    {
    if (!Auth::user()->hasRole('magazynier')) 
        return view('error',['head'=>'błąd wywołania funkcji create kontrolera Review','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być magazynierem']);

    $item=Item::where('id',$item_id)->first();
    return view('items.show', compact('item'), ['do_what'=>'fault','fault_option'=>'create']);
    
    }

    public function show(Int $id_review)
    {
    if (!Auth::user()->hasRole('magazynier')) 
        return view('error',['head'=>'błąd wywołania funkcji show kontrolera Review','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być magazynierem']);
        
        $review=Review::where('id',$id_review)->first();
        $item=Item::where('id',$review->item_id)->first();
        return view('items.show', compact('item'), ['do_what'=>'review','review_option'=>'show','review_id'=>$id_review]);
    }

    public function edit(Int $id_review)
    {
    if (!Auth::user()->hasRole('magazynier')) 
        return view('error',['head'=>'błąd wywołania funkcji edit kontrolera Review','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być magazynierem']);
    
        $review=Review::where('id',$id_review)->first();
        $item=Item::where('id',$review->item_id)->first();
        return view('items.show', compact('item'), ['do_what'=>'review','review_option'=>'edit','review_id'=>$id_review]);
    }

    public function update(Request $request)
    {
    if (!Auth::user()->hasRole('magazynier')) 
        return view('error',['head'=>'błąd wywołania funkcji update kontrolera Review','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być magazynierem']);

        $zmEQ = Review::where('id',$request->review_id)->first();
        $zmEQ->review_body=$request->review_body;
        $zmEQ->save();

        $item=Item::where('id',$zmEQ->item_id)->first();
        return view('items.show', compact('item'), ['do_what'=>'review','review_option'=>'show','review_id'=>$request->review_id]);
    }

    public function tryclose(Int $id_review)
    {
    if (!Auth::user()->hasRole('magazynier')) 
        return view('error',['head'=>'błąd wywołania funkcji tryclose kontrolera Review','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być magazynierem']);
    
        $review=Review::where('id',$id_review)->first();
        $item=Item::where('id',$review->item_id)->first();
        return view('items.show', compact('item'), ['do_what'=>'review','review_option'=>'tryclose','review_id'=>$id_review]);
    }

    public function close(Request $request)
    {
    if (!Auth::user()->hasRole('magazynier')) 
        return view('error',['head'=>'błąd wywołania funkcji close kontrolera Review','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być magazynierem']);

        $zmEQ = Review::where('id',$request->review_id)->first();
        $zmEQ->review_body=$zmEQ->review_body.' '.$request->review_template_id;

        $zmEQ->do_date=date('Y-m-d H:i:s');
        $zmEQ->reviewer_id=Auth::id();
        $zmEQ->rev_status=100;

        $zmEQ->save();

        $item=Item::where('id',$zmEQ->item_id)->first();


        $review_template=ReviewTemplate::where('id',$request->review_template_id)->first();
        $date_a=$review_template->next_start();
        $date_b=$review_template->next_start()+$review_template->days_before;
        $date_c=$review_template->next_start()+$review_template->days_before+$review_template->days_after;   

            $zmEQ = new Review();
            $zmEQ->review_template_id=$request->review_template_id;
            $zmEQ->review_title=$review_template->template_title;
            $zmEQ->item_id=$item->id;
            $zmEQ->start_date=date('Y-m-d',strtotime("+$date_b day"));
            $zmEQ->start_date_from=date('Y-m-d',strtotime("+$date_a day"));
            $zmEQ->start_date_to=date('Y-m-d',strtotime("+$date_c day"));
            $zmEQ->review_body='';
            $zmEQ->rev_status=1;            
            $zmEQ->save();
            
        return view('items.show', compact('item'), ['do_what'=>'review','review_option'=>'show','review_id'=>$request->review_id]);
            
    }

    

}
