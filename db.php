<?php
include 'config.php';
require  'src/Medoo.php';

use Medoo\Medoo;

class DB extends Medoo{
    protected $pkey=[
        'name'=>'',
        'val'=>0
    ];
    protected $name="";
    protected $db="";
    public $data=array();
    public $method ="post";
    public static $error="";
    public static function getConn(){
        global $database;
        if(!class_exists('mysqli')){
            return "Connection failed: mysqli was not dedected";
        }
        $test=new mysqli($database['server'], $database['username'], $database['password'], $database['name']);
        if($test->connect_error){
            $test->close();
            return "Connection failed: ".$test->connect_error;

        }
    }
    public function getData(){
        global $database;
        if(!is_array($database['tables'][$this->name]))return false;
        $fields=array_keys($database['tables'][$this->name]);
        foreach($fields as $field){
            switch($this->method){
                case "post":
                    $item=array_keys($_POST);
                    foreach($item as $dat){
                        $pos=strpos($dat,'_prolo',0);
                        if($pos!==false){
                            $key=substr($dat,0,$pos);
                            if($key===$field){
                                $op=substr($dat,$pos, strlen($dat));
                                switch($op){
                                    case '_proloadd':
                                        $this->data[$key.'[+]']= $_POST[$dat];
                                    break;
                                    case '_prolosub':
                                        $this->data[$key.'[-]']= $_POST[$dat];
                                    break;
                                    case '_prolomult':
                                        $this->data[$key.'[*]']= $_POST[$dat];
                                    break;
                                    case '_prolodiv':
                                        $this->data[$key."[/]"]= $_POST[$dat];
                                    break;
                                }
                            break;
                            }
                        }
                    }
                    if(isset($_POST[$field])){
                        $this->data[$field]= $_POST[$field];
                    }
                break;
                case "get":
                    $item=array_keys($_GET);
                    foreach($item as $dat){
                        $pos=strpos($dat,'_prolo',0);
                        if($pos!==false){
                            $key=substr($dat,0,$pos);
                            if($key===$field){
                                $op=substr($dat,$pos, strlen($dat));
                                switch($op){
                                    case '_proloadd':
                                        $this->data[$key.'[+]']= $_GET[$dat];
                                    break;
                                    case '_prolosub':
                                        $this->data[$key.'[-]']= $_GET[$dat];
                                    break;
                                    case '_prolomult':
                                        $this->data[$key.'[*]']= $_GET[$dat];
                                    break;
                                    case '_prolodiv':
                                        $this->data[$key."[/]"]= $_GET[$dat];
                                    break;
                                }
                            break;
                            }
                        }
                    }
                    if(isset($_GET[$field])){
                        $this->data[$field]= $_GET[$field];
                    }
                break;
            }
        };
        //
        return $this->data;
    }
    public function col(){
        return array_keys($this->data);
    }
    public function getName()
    {
        return $this->name;
    }
    public function addData($key, $val)
    {
       $this->data["$key"]=$val;
    }
    public function validate(){
        global $database;
        foreach($this->col() as $item){
            if(empty($this->data[$item]) || is_null($item)){
                $config=array_change_key_case($database['tables']["$this->name"]["$item"], CASE_UPPER); 
                if(array_search("NOT NULL",$config) &&!array_search("AUTO_INCREMENT",$config)){
                    $this->alert("ERROR! The field $item : ".$this->data["$item"]."can't be null.");
                    return false;
                }
            }
        }
        return true;
    }
    public function alert($prompt="", $type="danger"){
        
        echo '<div class="alert alert-'.$type.' position-fixed w-100 my-3" role="alert" style="z-index: 99;">'.$prompt.'
        <button type="button" class="close" data-dismiss="alert" arial-lable="close">
            <span arial-hidden="true">&times;</span>
        </button>
        </div>';
       
    }

}
class Table extends DB{


    public function __construct($name="", $create=true) {
        global $database;
        $this->name=$name;
        
        $this->db = new Medoo([
            'database_type' => $database['type'],
            'database_name' => $database['name'],
            'server' => $database['server'],
            'username' => $database['username'],
            'password' => $database['password'],
        ]);
        $tbl_name = $database['tables'][$this->name];
        $keys = array_keys($tbl_name);

        for($i=0; $i<sizeof($keys); $i++)
        {
            if(array_key_exists("prolo_config",$tbl_name[$keys[$i]]))
                unset($tbl_name[$keys[$i]]["prolo_config"]);
        }
            
        $create?$this->db->create($this->name, $tbl_name):$create=false;
    }

    public function add(){
        if($this->validate()){

            if(sizeof($this->data)>0){
                if($this->db->insert($this->name, $this->data)){
                    return true;
                }else {
                    return false;
                } 
            }else {
                return false;
            }
        }return false;
    }

    public function update($id='id',$key=''){
        if($this->validate()){

            if(sizeof($this->data)>0){
                $val=$this->data["$id"];
                if($this->db->update($this->name, $this->data,["$id$key" => $val])){
                    return true;
                }else
                {
                    return false;
                } 
            }else {
                return false;
            }
        }return false;
    }

    public function select($col=null){
        if(is_null($col)) $col="*";
        return $this->db->select($this->name, $col);
    }

    public function get($id='id'){
        $datas=$this->db->get($this->name, "*", [
            "$id" => $this->data["$id"]
        ]);
        if($datas){
            return $datas;
        }
        return false;
    }
    
    public function delete($id='id'){
        $datas=$this->db->delete($this->name, [
            "AND" => [
                "$id" => $this->data["$id"]
            ]
        ]);
        if($datas>0){
            return true;
        }
        return false;
    }
    
    public function child($name, $id='id')
    {
        return $this->db->select($name,"*",  [
            "$id" => $this->data["id"]
        ]);
    }

    public function rowHeader($custom=null)
    {
        $code = '<thead><tr>';
        global $database, $admin;
        is_null($custom)? $fields = array_keys($database["tables"][$this->name]): $fields = $custom;
        if(array_key_exists("table_view", $admin[$this->name]) && is_null($custom))   
            $fields=$admin[$this->name]["table_view"];   
        foreach($fields as $field)
            $code = $code.'<th scope="col">'.$field.'</th>';
        $code=$code.'</tr></thead>';

        return $code;
    }

    public function rowFields($custom=null)
    {
        $code = '<tr>';
        global $database, $admin;
        is_null($custom)? $fields = array_keys($database["tables"][$this->name]): $fields = $custom;
        if(array_key_exists("table_view", $admin[$this->name]) && is_null($custom))   
            $fields=$admin[$this->name]["table_view"];
        foreach($fields as $field)
        {   
            if($field=='id')
                $code = $code.'<form method="POST"><input type="hidden" name="table_name" value="'.$this->name.'"/><td scope="col"><input class="btn btn-sm btn-default text-primary" type="submit" name="id" value="<%%DATA('.$field.')%%>"/></td></form>';
            else
                $code = $code.'<td scope="col"><%%DATA('.$field.')%%></td>';
        }
        $code=$code.'</tr>';

        return $code;
    }

    public function form($class='')
    {
        
        global $database;
        $fields = array_keys($database['tables'][$this->name]);
        $code='
            <form method="POST" class="'.$class.'" enctype="multipart/form-data">
                <h2>Add '.ucfirst($this->name).'</h2>
                <input type="hidden" name="table_name" value="'.$this->name.'"/>
                <div class="md-form mt-0">
                    ID: <input type="text" name="id" value="<%%DATA(id)%%>" readonly/>
                    <p></p>
                </div>
        ';
        for($i=0; $i<sizeof($fields); $i++)
        {
            global $database;

            $field_code='<div class="form-group">';
            $type="text";
            $caption = ucfirst($fields[$i]);
            if(($fields[$i])=='id') continue;

            if(array_key_exists("prolo_config",$database['tables'][$this->name][$fields[$i]]))
            {   
                array_key_exists("caption", $database['tables'][$this->name][$fields[$i]]['prolo_config'])? $caption = $database['tables'][$this->name][$fields[$i]]['prolo_config']['caption'] : $caption = $caption;
                array_key_exists("type", $database['tables'][$this->name][$fields[$i]]['prolo_config'])? $type = $database['tables'][$this->name][$fields[$i]]['prolo_config']['type'] : $caption = $caption;

            }

            $field_code=$field_code.'<label for="'.$database['tables'][$this->name][$fields[$i]].'">'.$caption.':</label>';

            switch($type)
            {
                case 'image':
                    $field_code=$field_code.='
                        <input type="file" name="'.$fields[$i].'"/><br/>
                        <p></p>
                        <img src="<%%DATA('.$fields[$i].')%%>" width="auto" height="150px"/>
                        ';
                break;
                case 'select':
                    $source="row";
                    $data=null;
                    $items='';
                    array_key_exists("source", $database['tables'][$this->name][$fields[$i]]['prolo_config'])? $source = $database['tables'][$this->name][$fields[$i]]['prolo_config']['source'] : $source = $source;
                    switch($source)
                    {
                        case 'raw':
                            array_key_exists("data", $database['tables'][$this->name][$fields[$i]]['prolo_config'])? $data = $database['tables'][$this->name][$fields[$i]]['prolo_config']['data'] : $data = [];
                        break;
                        case 'table':
                            $tbl_name=array_key_exists("table", $database['tables'][$this->name][$fields[$i]]['prolo_config']);
                            $tbl_data=array_key_exists("data", $database['tables'][$this->name][$fields[$i]]['prolo_config']);
                            if($tbl_name && $tbl_data){
                                $tbl_name=$database['tables'][$this->name][$fields[$i]]['prolo_config']["table"];
                                $tbl_data=$database['tables'][$this->name][$fields[$i]]['prolo_config']["data"];

                                $data=$this->db->select($tbl_name,$tbl_data);
                            }
                        break;
                    }
                    
                    foreach($data as $val)
                    {
                        if( is_array($val))
                        {
                            $data_keys = array_keys($val);
                            $items=$items.'<option value="'.$val[$data_keys[0]].'">'.$val[$data_keys[1]].'</option>';
                        }
                        else{
                            $items=$items.'<option value="'.$val.'">'.$val.'</option>';
                        }
                    }
                    $field_code=$field_code.'<select class="form-control" name="'.$fields[$i].'" value="<%%DATA('.$fields[$i].')%%>">';
                    $field_code=$field_code.$items;
                    $field_code=$field_code.'</select>';
                break;
                default:
                    $field_code=$field_code.'<input type="'.$type.'" class="form-control" name="'.$fields[$i].'" value="<%%DATA('.$fields[$i].')%%>"/>';
                }
                $field_code=$field_code.'</div>';
                $code=$code.$field_code;
        }

        $code=$code.'
            <input type="submit" name="save" class="btn btn-outline-success"  value="save"/>
            <input type="submit" name="new" class="btn btn-outline-secondary mx-4"  value="new"/>
            <input type="submit" name="delete" class="btn btn-danger mx-4 float-right"  value="delete"/>
        </form>
        ';
        return $code;
    }

};

?>