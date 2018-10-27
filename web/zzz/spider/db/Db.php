<?php
/**
 * Created by PhpStorm.
 * User: Leonidax
 * Date: 2018/9/1
 * Time: 09:21
 */
namespace spider\db;
use \PDO;
use \PDOException;

class Db{
    private $pdo = null;
    public $table = null;
    public $attr = [];
    public $data = [];
    public $sql = '';

    function __construct(){
        $this->init();
    }

    public function init(){
        $host = 'localhost';
        $user = 'root';
        $pass = 'root';
        $db = 'car';
        $option = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_PERSISTENT => true
        ];
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass,$option);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();exit;
        }
    }

    private function setData($data){
        foreach($this->attr as $key){
            @$this->data[$key] = $data[$key];
        }
    }

    public function findAll($where, $field='*', $limit = '', $order = 'id DESC'){
        $this->sql = "SELECT $field FROM $this->table WHERE $where ORDER BY $order";
        if($limit) $this->sql .= " LIMIT $limit";
        $res = $this->pdo->query($this->sql);
        $list = [];
        while($row = $res->fetch()){
            $list[] = $row;
        }
        return $list;
    }

    public function update($data='',$where=''){
        if(isset($data['id'])){
            $where = 'id='.$data['id'];
        }
        if($data) $this->setData($data);
        unset($this->data['id']);
        $this->sql = 'UPDATE '.$this->table.' SET ';
        foreach($this->data as $key=>$value){
            $this->sql .= "`$key` = '$value',";
        }
        $this->sql = substr($this->sql,0,-1);
        if($where) $this->sql .= 'WHERE '.$where;
        $this->pdo->exec($this->sql);
    }

    public function findOne($where, $field='*'){
        $this->sql = "SELECT $field FROM $this->table WHERE $where LIMIT 1";
        $res = $this->pdo->query($this->sql);
        return $res->fetch();
    }

    public function insert($data=''){
        if($data) $this->setData($data);
        $key = '';
        $value = '';
        $this->sql = 'INSERT '.$this->table;
        unset($this->data['id']);
        foreach($this->data as $k=>$v){
            if($k == 'id') continue;
            $key .= "`$k`,";
            $value .= "'$v',";
        }
        $key = substr($key,0,-1);
        $value = substr($value, 0, -1);
        $this->sql .= " ($key) VALUES ($value)";
        $this->pdo->exec($this->sql);
        return $this->pdo->lastinsertid();
        $sql = 'SELECT LAST_INSERT_ID() as id;';
        $res = $this->pdo->query($sql);
        $res = $res->fetch();
        return $res['id'];
    }

    function __destruct(){
        $this->pdo = null;
    }
}