<?php
namespace LarDebug\Collectors;
use Illuminate\Support\Facades\DB;


class QueryCollector implements ICollector{
    protected $db;
    protected $connection;
    protected $queries = [];
    protected $onQueryExecutedEvent;

    public function __construct($db){
        $this->db = $db;
        $this->connection = $this->db->connection();
        $this->connection->listen(function($query){
            $this->addToQueries($query);
            $this->dispatchQueryExecutedEvent($query);
        });
    }
    public function collect(){
        return $this->queries;
    }
   
    protected function addToQueries($query){
       array_push( $this->queries,[
        'sql'=>$this->addBindingsToQuery($query->sql,$query->bindings),
        'time'=>$query->time,
       ]);
    }
    protected function dispatchQueryExecutedEvent($query){
        if(isset($this->onQueryExecutedEvent) && $this->onQueryExecutedEvent instanceof Closure){
            call_user_func($this->onQueryExecutedEvent,$query);
        }
    }
    protected function addBindingsToQuery($query,$bindings){
        $fullQuery = '';
        $queryParts = explode('?',$query);
        for($i=0;$i<count($queryParts);$i++){
            $fullQuery .= $queryParts[$i];
            if( isset($bindings[$i])){
                $fullQuery.= $bindings[$i];
            }
        }
        return $fullQuery;
    }
    public function listenToQueryExecutedEvent($callback){
        $this->onQueryExecutedEvent = $callback;
    }
}