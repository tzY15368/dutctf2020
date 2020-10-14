<?php
class Models extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->database();
        $this->load->dbforge();
    }

    public function search($name,$num){
        if(strstr($name,'\'')||strstr($name,'"')||strstr($num,'\'')||strstr($num,'"')){
            die();
        }
        //$sql = "SELECT * FROM vol_time WHERE name = ? AND stu_id = ?";
        $sql = "SELECT * FROM vt_bak WHERE name = ? AND stu_id = ?";
        //$query = $this->db->query($sql, array('蔡田', 201302030));
        $query = $this->db->query($sql,array($name,$num));
        return $query;
    }
    public function countrows(){
        return $this->db->count_all('vt_bak');
    }
    public function add_line($line){
        foreach($line as $l){
            if(strstr($l,'\'')||strstr($l,'"')){
                return false;
            }
        }
        $data = array(
            "num"=>$line[0],
            'name'=>$line[1],
            'sex'=>$line[2],
            'faculty'=>$line[3],
            'stu_id'=>$line[4],
            'phone'=>"",
            'time'=>$line[5],
            'activity_name'=>$line[6],
            'activity_faculty'=>$line[7],
            'team'=>$line[8],
            'activity_time'=>$line[9],
            'duty_person'=>$line[10],
            'duty_person_phone'=>""
        );
        if($line[0] && $line[1])
            $res = $this->db->insert("vt",$data);
        else{
            return false;
        }
        if($res)
            return true;
        else    
            return false;
    }

    public function create_table(){
        $fields=array(
            'num'=>array(
                'type'=>'INT'
                ),
            'name'=>array(
                'type'=>'TEXT',
                'null'=>TRUE
                ),
            'sex'=>array(
                'type'=>'TEXT',
                'null'=>TRUE 
                ),
            'faculty'=>array(       //院系
                'type'=>'TEXT'
                ),
            'stu_id'=>array(
                'type'=>'INT',
                ),
            'phone'=>array(
                'type'=>'TEXT',
                'null'=>TRUE 
                ),
            'time'=>array(
                'type'=>'FLOAT',
                'null'=>TRUE 
                ),
            'activity_name'=>array(
                'type'=>'TEXT',
                'null'=>TRUE 
                ),
            'activity_faculty'=>array(
                'type'=>'TEXT',
                'null'=>TRUE 
                ),
            'team'=>array(
                'type'=>'TEXT',
                'null'=>TRUE 
                ),
            'activity_time'=>array(
                'type'=>'TEXT',
                'null'=>TRUE 
                ),
            'duty_person'=>array(
                'type'=>'TEXT',
                'null'=>TRUE 
                ),
            'duty_person_phone'=>array(
                'type'=>'TEXT',
                'null'=>TRUE 
                ),
            );
            $this->dbforge->add_field('id');
            $this->dbforge->add_field($fields);
            return $this->dbforge->create_table("vt",TRUE);
    }
    
}