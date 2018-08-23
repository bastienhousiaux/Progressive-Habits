<?php

    $bdd = new PDO('mysql:host=localhost;dbname=project_manager_app;charset=utf8', 'root', '');

    function queryResponseToArray($queryResponse){
        $responseArray=array();
        while($row=$queryResponse->fetch(PDO::FETCH_ASSOC)){

            array_push($responseArray,$row);
        }
        return $responseArray;
    }

    function format_keys($keysValues){
        return implode(",",array_keys($keysValues));
    }

    function format_values($keysValues){
        return implode(",",array_map(function($el){
            if(is_string($el)){
                return "'".$el."'";
            }else{
                return $el;
            }
            
        },array_values($keysValues)));
    }

    function format_update_values($keysValues){
        return implode(",",
            array_map(function($key,$value){
                return "$key=".((is_string($value))?"'".$value."'":$value);
            },array_keys($keysValues),$keysValues)
        );
    }

    function format_joins($joins){
        $joinsString="";
        foreach($joins as $join){
            $joinsString.=" JOIN ". $join[0] . " ON " . $join[1] . " = " . $join[2];
        }
        return $joinsString;
    }


    function select($table,$champs=["*"],$where=1,$orderBy=1){
        global $bdd;
        return json_encode(queryResponseToArray($bdd->query("SELECT ".implode(",",$champs)." FROM $table WHERE $where")));
    }

    function selectJoin($table,$champs=["*"],$joins=[],$where=1,$orderBy=1){
        global $bdd;
        return json_encode(queryResponseToArray(
            $bdd->query(
                "SELECT " .implode(",",$champs. " FROM $table " . format_joins($joins) . " WHERE $where")
            )
            ));
    }

    function selectOne($table,$champs=["*"],$where=1){
        global $bdd;
        return json_encode($bdd->query("SELECT ".implode(",",$champs)." FROM $table WHERE $where")->fetch(PDO::FETCH_ASSOC));
    }

    function insert($table,$values){
        global $bdd;
        return json_encode($bdd->query("INSERT INTO $table(".format_keys($values).") VALUES(".format_values($values).")"));
    }

    function update($table,$values,$where=1,$restrict=null){
        global $bdd;
        if($restrict){
            $filteredValues=[];
            foreach($restrict as $key){
                if(array_key_exists($key,$values)) $filteredValues[$key]=$values[$key];
            }
        }else{
            $filteredValues=$values;
        }
        return json_encode($bdd->query("UPDATE $table SET ".format_update_values($filteredValues)." WHERE $where"));
    }

    function delete($table,$where){
        global $bdd;
        return json_encode($bdd->query("DELETE FROM $table WHERE $where"));
    }


?>