<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fault;
use App\Item;
use Illuminate\Support\Facades\Auth;

class FaultController extends Controller
{
    public function create(Int $item_id)
    {
    if (!Auth::user()->hasRole('magazynier')) 
        return view('error',['head'=>'błąd wywołania funkcji create kontrolera Fault','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być magazynierem']);
    $item=Item::where('id',$item_id)->first();
    return view('items.show', compact('item'), ['do_what'=>'fault','fault_option'=>'create']);
    
    }

    public function store(Request $request)
    {   
        if (Auth::user()->roles()->count()==0) 
            return view('error',['head'=>'błąd wywołania funkcji store kontrolera Fault','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz posiadać jakąkolwiek rolę']);
        $item=Item::where('id',$request->item_id)->first();

        if ( ($request->fault_title=="") || ($request->notification_description==""))
            return view('items.show', compact('item'), ['do_what'=>'fault','fault_option'=>'showall']);
        $zmEQ = new Fault();
        $zmEQ->fault_title=$request->fault_title;
        $zmEQ->notification_description=$request->notification_description;
        $zmEQ->item_id=$request->item_id;
        $zmEQ->start_date=date('Y-m-d H:i:s');
        $zmEQ->notifier_id=Auth::id();
        //$zmEQ->close_date=date('Y-m-d H:i:s');
        $zmEQ->repair_description='';
        //$zmEQ->repairer_id=7;
        
        $zmEQ->save();
        //$fault=Fault::where('id',$zmEQ->id)->first();
        

        return view('items.show', compact('item'), ['do_what'=>'fault','fault_option'=>'show','fault_id'=>$zmEQ->id]);
    }

    public function showall(Int $id_item)
    {
        if (!Auth::user()->hasRole('magazynier')) 
            return view('error',['head'=>'błąd wywołania funkcji showall kontrolera Fault','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być magazynierem']);
        
        $item=Item::where('id',$id_item)->first();
        return view('items.show', compact('item'), ['do_what'=>'fault','fault_option'=>'showall']);
    }

    public function show(Int $id_fault)
    {
        if (Auth::user()->roles()->count()==0) 
            return view('error',['head'=>'błąd wywołania funkcji show kontrolera Fault','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz posiadać jakąkolwiek rolę']);
        
        $fault=Fault::where('id',$id_fault)->first();
        $item=Item::where('id',$fault->item_id)->first();
        return view('items.show', compact('item'), ['do_what'=>'fault','fault_option'=>'show','fault_id'=>$id_fault]);
    }

    public function edit(Int $id_fault)
    {
        if (!Auth::user()->hasRole('magazynier')) 
            return view('error',['head'=>'błąd wywołania funkcji edit kontrolera Fault','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być magazynierem']);

        $fault=Fault::where('id',$id_fault)->first();
        $item=Item::where('id',$fault->item_id)->first();
        return view('items.show', compact('item'), ['do_what'=>'fault','fault_option'=>'edit','fault_id'=>$id_fault]);
    }
    public function close(Int $id_fault)
    {
        if (!Auth::user()->hasRole('magazynierX')) 
            return view('error',['head'=>'błąd wywołania funkcji kontrolera Fault','title'=>'funkcja Close','description'=>'ta funkcja chyba nie powinna działać...']);

        $fault=Fault::where('id',$id_fault)->first();
        $item=Item::where('id',$fault->item_id)->first();
        return view('items.show', compact('item'), ['do_what'=>'fault','fault_option'=>'close','fault_id'=>$id_fault]);
    }

    public function update(Request $request)
    {
        if (!Auth::user()->hasRole('magazynier')) 
            return view('error',['head'=>'błąd wywołania funkcji update kontrolera Fault','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być magazynierem']);

        $zmEQ = Fault::where('id',$request->fault_id)->first();

        $zmEQ->fault_title=$request->fault_title;
        $zmEQ->notification_description=$request->notification_description;
        if ($request->repair_description=='') $request->repair_description=' ';
            $zmEQ->repair_description=$request->repair_description;

        if ($request->action=='close')
            {
            $zmEQ->close_date=date('Y-m-d H:i:s');
            $zmEQ->repairer_id=Auth::id();
            $zmEQ->fault_status=100;
            }
        $zmEQ->save();

        $fault=Fault::where('id',$request->fault_id)->first();
        $item=Item::where('id',$fault->item_id)->first();

        return view('items.show', compact('item'), ['do_what'=>'fault','fault_option'=>'show','fault_id'=>$request->fault_id]);
    }
}
