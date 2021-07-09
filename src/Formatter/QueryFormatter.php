<?php


namespace LarDebug\Formatter;


class QueryFormatter{
    public function format($sql,$bindings){
        $fullQuery = '';
        $queryParts = explode('?',$sql);
        for($i=0;$i<count($queryParts);$i++){
            $fullQuery .= $queryParts[$i];
            if( isset($bindings[$i])){
                $fullQuery.= $bindings[$i];
            }
        }
        return $fullQuery;
    }
}