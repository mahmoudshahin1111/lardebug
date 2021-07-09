<?php
namespace LarDebug\Collectors;
use Illuminate\Support\Facades\DB;
use LarDebug\Formatter\QueryFormatter;


class QueryCollector implements ICollector{
    /**
     * database instance
     *
     * @var DB
     */
    protected $db;
    protected $connection;
    /**
     * executed queries
     *
     * @var array
     */
    protected $queries = [];
    /**
     * trigger callback for listeners
     *
     * @var array
     */
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
        return app(QueryFormatter::class)->format($query,$bindings);
    }
    public function listenToQueryExecutedEvent($callback){
        $this->onQueryExecutedEvent = $callback;
    }
}