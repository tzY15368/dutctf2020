<?php 

ini_set("display_errors", "On"); 
error_reporting(E_ALL | E_STRICT);
session_start();
class result{
    public $name ='';
    public $data ='';
    public $all_time='';
}
class Vol extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->model("Models");
        $this->load->helper('url_helper');
        $this->load->database();
        $this->load->dbforge();
    }
    
    public function index(){
        $this->load->view('search.html');
    }
    public function admin(){
        if($_SERVER['REQUEST_METHOD']==="GET"){
            if(isset($_SESSION['admin'])){
                $rows = $this->Models->countrows();
                $data['msg']='当前记录数：'.$rows;
                $this->load->view('upload.php',$data);
                $this->load->view('upload-footer.html');
                return;
            } else {
                $this->load->view("index.html");
            }   
        } else if($_SERVER['REQUEST_METHOD']==="POST") {
            
            $username = $_POST["email"];
            $password = $_POST["password"];
            $sql = "SELECT * from user WHERE account='$username' AND psw='$password' LIMIT 0,1";
            //echo $sql;
            $query = $this->db->query($sql);
            $row = $query->result();
            if($row){
                $_SESSION['admin']=1;
                $result = array("success"=>true,"details"=>"");
            }else {
                $error = $this->db->error();
                $result = array("success"=>false,"details"=>$error);
            }
            echo json_encode($result);
        }
    }
    public function search(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if (isset($_SESSION['request_time']) && (floatval(microtime(true)) - floatval($_SESSION['request_time'])) < floatval(1.00)){
                echo "NOSPAM"; 
                return 1;
            }
            $_SESSION['request_time']=microtime(true);
            $name = $_POST['name'];
            $num = $_POST['number'];             
            $num = intval($num);    //转下数据类型
            
            $query = $this->Models->search($name,$num);   

            $res = new result();  
            $res->name = $name;
            $res->all_time = 0;
                
            if($query->result_array()==null)
                return 0;
            foreach($query->result_array() as $row){    
                $res->data = $res->data."<tr> <td><p>".$row['id']."</p></td> <td><p>".$row['name']."</p></td> <td><p>".$row['faculty']."</p></td> <td><p>".$row['stu_id']."</p></td> <td><p>".$row['time']."</p></td> <td><p>".$row['activity_faculty']."</p></td> <td><p>".$row['activity_name']."</p></td> <td><p>".$row['duty_person']."</p></td></tr>";
                
                $res->all_time = $res->all_time+$row['time'];
            }
            echo json_encode($res);
        } else {
            //header('Location:https://www.dutbit.com/vol_time/');
        }
    }
}