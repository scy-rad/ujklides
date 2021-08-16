@extends('layouts.app')

<link href="{{ asset('css/_cards.css') }}" rel="stylesheet">

@section('title', 'scenariusz: '.$scenario->scenario_name)

@section('content')

<?php include(app_path().'/include/view_common.php'); ?>
<!--link href="{{ asset('css/ujklides.css') }}" rel="stylesheet"-->

<div class="clearfix"></div>

<?php 
    $scen_sub='';
    foreach ($scenario->subjects as $subject)
        {
        $scen_sub.='- '.$subject->student_subject_name.'<br>';
        }
    $scen_sub.='';
?>
<div class="row">
{{ kafelek(5, 'nazwa', $scenario->scenario_name, NULL) }}

{{ kafelek(4, 'przedmiot', $scen_sub, NULL) }}
{{ kafelek(3, 'autor', $scenario->author()->full_name(), NULL) }}
</div>
<div class="row">
{{ kafelek(12, 'główny problem', $scenario->scenario_main_problem, NULL) }}
</div>
<div class="row">
{{ kafelek(12, 'opis przypadku', $scenario->scenario_description, NULL) }}
</div>

<?php 
$pliki_list='<ul>';
$pliki_list.='<li>scenariusz symulacji (scenariusz_'. $scenario->id.'.doc)</li>';
foreach ($scenario->pliks as $plik)
{
    $pliki_list.='<li>'.$plik->plik_title.' ('.$plik->plik_directory.'/'.$plik->plik_name.')</li>';
}
$pliki_list.='</ul>';

?>

<div class="row">
     {{ kafelek(12, 'załączniki ', $pliki_list, NULL) }}
    </div>







@endsection