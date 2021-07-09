<?php
namespace LarDebug\Collectors;
use Illuminate\Support\Facades\DB;
use LarDebug\Formatter\QueryFormatter;


class QueryCollector implements ICollector{

    /**
     * executed queries
     *
     * @var array
     */
    protected $queries = [];


    public function __construct(){

    }
    public function collect(){
        return $this->queries;
    }
   
    public function addQuery($sql,$bindings,$time){
       array_push( $this->queries,[
        'sql'=>app(QueryFormatter::class)->format($sql,$bindings),
        'time'=>$time,
       ]);
    }

}