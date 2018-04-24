<?php

    
    $eixo   = $_GET['eixo'];
    
    $result;
    $json = array();

    switch($eixo){
        case 0:
            require_once('EixoUm.php');
            foreach(EixoUm::getter_most_recent_year() as $result){
                if(!isset($json[$result->Numero]))
                    $json[$result->Numero] = array();
                    
                $json[$result->Numero][0] = $result->Ano;
            }
            break;
        case 1:
            require_once('EixoDois.php');
            foreach(EixoDois::getter_most_recent_year() as $result){

                if(!isset($json[$result->Numero]))
                    $json[$result->Numero] = array();
                    
                $json[$result->Numero][(string)$result->idOcupacao] = $result->Ano;
            }
            break;
        case 2:
            require_once('EixoTres.php');
            foreach(EixoTres::getter_most_recent_year() as $result){
                if(!isset($json[$result->Numero]))
                    $json[$result->Numero] = array();
                    
                $json[$result->Numero][0] = $result->Ano;
            }
            break;
        case 3:
            require_once('EixoQuatro.php');
            foreach(EixoQuatro::getter_most_recent_year() as $result){
                if(!isset($json[$result->Numero]))
                    $json[$result->Numero] = array();
                    
                $json[$result->Numero][(string)$result->Consumo] = $result->Ano;
            }
            break;
    }

    echo json_encode($json);


    