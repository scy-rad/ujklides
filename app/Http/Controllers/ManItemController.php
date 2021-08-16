<?php
 
namespace App\Http\Controllers;

use App\ManItem;
use Illuminate\Http\Request;
use App\Room;
use Illuminate\Support\Facades\Auth;

class ManItemController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) //  metoda GET bez parametrów
    {
    if (!Auth::user()->hasRole('Operator Zasobów')) 
        return view('error',['head'=>'błąd wywołania funkcji index kontrolera ManItem','title'=>'brak uprawnień','description'=>'aby wykonać to działanie musisz być Operatorem Zasobów']);
        //echo 'app \ Http \ Controllers \ ManItemController \ index';
    $ManageItem = New ManItem();

        if ($request->action=='addinv')
            {
                $ret=$ManageItem->make_inventory($request->forroom);
                $ret_id=$ret->id;
                $ret_id=1;
                //$ret=$ManageItem->add_inventory_to_items($request->forroom, $ret_id);
                $ret=$ManageItem->add_inventory_to_items($request->forroom, $ret_id, $type=1, $descr='Opis ...', $dat='2012-12-21');

            }
        else
        {
            $ret='No ont';
        }

        $data['rooms']=room::all();
        $data['tab2']=['jeden'=>1, 'dwa'=>2, 'trzy'=>3];
        $data['tab3']='It for room '.$ret;
        //$data['tab3']='It for room '.$request->forroom;



        return view('manitems.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        echo 'app \ Http \ Controllers \ ManItemController \ create';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        echo 'app \ Http \ Controllers \ ManItemController \ store';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ManItem  $manItem
     * @return \Illuminate\Http\Response
     */
    public function show(ManItem $manItem)  //  metoda GET z parametrem
    {
        //
        echo 'app \ Http \ Controllers \ ManItemController \ show';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ManItem  $manItem
     * @return \Illuminate\Http\Response
     */
    public function edit(ManItem $manItem)
    {
        //
        echo 'app \ Http \ Controllers \ ManItemController \ edit';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ManItem  $manItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ManItem $manItem)
    {
        //
        echo 'app \ Http \ Controllers \ ManItemController \ update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ManItem  $manItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManItem $manItem)
    {
        //
        echo 'app \ Http \ Controllers \ ManItemController \ destroy';
    }
}
