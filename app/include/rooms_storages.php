<?php

if(!empty($_GET['id'])) {
 $id = $_GET['id'];
 switch($id) {
  case 1:        //sala _MW Magazyn Wejściowy 
    $tab = array(
      '1'=>'sala'
      );
    echo json_encode($tab);
  break;

  case 2:        //sala A 1.01 Sala BLS 
    $tab = array(
      '2'=>'sala'
      );
    echo json_encode($tab);
  break;

  case 3:        //sala A 1.02 Sala ALS 
    $tab = array(
      '3'=>'sala',
      '4'=>'Wózek Wielofunkcyjny 01',
      '5'=>'pomieszczenie kontrolne',
      '6'=>'szafa w pomieszczeniu kontrolnym'
      );
    echo json_encode($tab);
  break;

  case 4:        //sala A 1.05 Umiejętności techniczne - położnictwo 
    $tab = array(
      '7'=>'sala',
      '8'=>'łazienka',
      '9'=>'Szafa 01-M',
      '10'=>'Szafka górna 1-M',
      '11'=>'Szafka górna 2-M',
      '12'=>'Szafka górna 3-M',
      '13'=>'Szafka górna 4-M',
      '14'=>'Szafka górna 5-M',
      '15'=>'Szafka górna 6-M',
      '16'=>'Szafka dolna 1-M',
      '17'=>'Szafka dolna 2-M',
      '18'=>'Szafka dolna 3-M',
      '19'=>'Szafka dolna 4-M',
      '20'=>'Szafka dolna 5-M',
      '21'=>'Szafka dolna 6-M',
      '22'=>'Szafka podblatowa 1-M',
      '23'=>'Szafka podblatowa 2-M',
      '24'=>'Blat roboczy 1-C'
      );
    echo json_encode($tab);
  break;

  case 5:        //sala A 1.06 Umiejętności pielęgniarskie 
    $tab = array(
      '25'=>'sala',
      '26'=>'Szafka wisząca 1-M',
      '27'=>'Szafka wisząca 2-M',
      '28'=>'Szafka wisząca 3-M',
      '29'=>'Szafka wisząca 4-M',
      '30'=>'Szafka podblatowa 01-M',
      '31'=>'Szafka podblatowa 02-M',
      '32'=>'Szafka podblatowa 03-M',
      '33'=>'Szafka podblatowa 04-M',
      '34'=>'Szafka podblatowa 05-M',
      '35'=>'Blat roboczy 1-C'
      );
    echo json_encode($tab);
  break;

  case 6:        //sala A 1.07 Umiejętności techniczne - pielęgniarstwo 
    $tab = array(
      '36'=>'sala',
      '37'=>'Szafka wisząca 1-M',
      '38'=>'Szafka wisząca 2-M',
      '39'=>'Szafka wisząca 3-M',
      '40'=>'Szafka podblatowa 01-M',
      '41'=>'Szafka podblatowa 02-M',
      '42'=>'Szafka podblatowa 03-M',
      '43'=>'Szafka podblatowa 04-M',
      '44'=>'Blat roboczy 1-C',
      '45'=>'Stanowisko noworodkowe 1-M',
      '46'=>'Stanowisko noworodkowe 2-M'
      );
    echo json_encode($tab);
  break;

  case 7:        //sala A 1.08 Komunikacja sal 
    $tab = array(
      '47'=>'korytarz',
      '48'=>'Szafa A 1-M',
      '49'=>'Szafa A 2-M',
      '50'=>'Szafa B 1-M',
      '51'=>'Szafa magazynowa B 2-M',
      '52'=>'Szafa magazynowa B 3-M',
      '53'=>'Szafa magazynowa B 4-M',
      '54'=>'Szafa magazynowa B 5-M',
      '55'=>'Szafa magazynowa B 6-M',
      '56'=>'Szafa magazynowa B 7-M',
      '57'=>'Szafa magazynowa B 8-M',
      '58'=>'Nadstawka magazynowa B 5-M',
      '59'=>'Nadstawka magazynowa B 6-M',
      '60'=>'Nadstawka magazynowa B 7-M',
      '61'=>'Nadstawka magazynowa B 8-M',
      '62'=>'Szafka wisząca 1-M',
      '63'=>'Szafka wisząca 2-M',
      '64'=>'Szafka wisząca 3-M',
      '65'=>'Blat roboczy 1-M',
      '66'=>'Szafka podblatowa 1-M',
      '67'=>'Szafka podblatowa 2-M',
      '68'=>'Szafka podblatowa 3-M',
      '69'=>'Szafka podblatowa 4-M'
      );
    echo json_encode($tab);
  break;

  case 8:        //sala A 1.09 Sala umiejętności położniczych 
    $tab = array(
      '70'=>'sala',
      '71'=>'Szafa 1-M',
      '72'=>'Szafa 2-M',
      '73'=>'Szafa 3-M',
      '74'=>'Szafa 4-M',
      '75'=>'Szafa 5-M'
      );
    echo json_encode($tab);
  break;

  case 9:        //sala B 1.01 Wysoka Wierność Pielęgniarstwo 
    $tab = array(
      '76'=>'sala',
      '77'=>'Szafka wisząca 1-C',
      '78'=>'Szafka wisząca 2-C',
      '79'=>'Szafka wisząca 3-C',
      '80'=>'Szafka wisząca 4-C',
      '81'=>'Szafka wisząca 5-C',
      '82'=>'Szafka wisząca 6-M',
      '83'=>'Szafka wisząca 7-M',
      '84'=>'Szafka wisząca 8-M',
      '85'=>'Blat roboczy 1-C',
      '86'=>'Szafka podblatowa 1-C',
      '87'=>'Szafka podblatowa 2-C',
      '88'=>'Szafka podblatowa 3-C',
      '89'=>'Szafka podblatowa 4-C',
      '90'=>'Szafka podblatowa 5-C',
      '91'=>'Szafka podblatowa 6-C',
      '92'=>'Szafka podblatowa 7-C',
      '93'=>'Szafka podblatowa 8-C'
      );
    echo json_encode($tab);
  break;

  case 15:        //sala B 1.02 Pomieszczenie kontrolne (B 1.01) 
    $tab = array(
      '120'=>'sala'
      );
    echo json_encode($tab);
  break;

  case 10:        //sala B 1.05 Wysoka Wierność Położnictwo 
    $tab = array(
      '94'=>'sala',
      '95'=>'Szafka wisząca 1-C',
      '96'=>'Szafka wisząca 2-C',
      '97'=>'Szafka wisząca 3-C',
      '98'=>'Szafka wisząca 4-C',
      '99'=>'Szafka wisząca 5-C',
      '100'=>'Szafka wisząca 6-C',
      '101'=>'Blat roboczy 1-C',
      '102'=>'Szafka podblatowa 1-C',
      '103'=>'Szafka podblatowa 2-C',
      '104'=>'Szafka podblatowa 3-C',
      '105'=>'Szafka podblatowa 4-C',
      '106'=>'Szafka podblatowa 5-C',
      '107'=>'Szafka podblatowa 6-C'
      );
    echo json_encode($tab);
  break;

  case 11:        //sala B 1.15 OSCE Pie 01 
    $tab = array(
      '108'=>'sala',
      '109'=>'Blat roboczy 1-C',
      '110'=>'Szafka podblatowa 1-C',
      '111'=>'Szafka podblatowa 2-C',
      '112'=>'Szafka podblatowa 3-M'
      );
    echo json_encode($tab);
  break;

  case 12:        //sala B 1.16 OSCE Pie 02 
    $tab = array(
      '113'=>'sala',
      '114'=>'Blat roboczy 1-C',
      '115'=>'Szafka podblatowa 1-C',
      '116'=>'Szafka podblatowa 2-C',
      '117'=>'Szafka podblatowa 3-M'
      );
    echo json_encode($tab);
  break;

  case 13:        //sala B 1.17 OSCE Pie 03 
    $tab = array(
      '118'=>'sala'
      );
    echo json_encode($tab);
  break;

  case 14:        //sala B 1.18 OSCE Pie 04 
    $tab = array(
      '119'=>'sala'
      );
    echo json_encode($tab);
  break;

 }
}
?>