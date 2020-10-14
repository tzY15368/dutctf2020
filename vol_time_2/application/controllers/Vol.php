<?php 
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
        echo "方向错了！";
    }
    public function search(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if (isset($_SESSION['request_time']) && (floatval(microtime(true)) - floatval($_SESSION['request_time'])) < floatval(1.00)){
                echo "NOSPAM"; 
                return 1;
            }
            $_SESSION['request_time']=microtime(true);
            $num = $_POST['number'];             
            //$num = intval($num);    //转下数据类型
            $array = array('table','union','and','or','load_file','create','delete','select','update','sleep','alter','drop','truncate','from','max','min','order','limit');
            foreach ($array as $value){
                if (substr_count($num, $value) > 0){
                    exit('包含敏感关键字！');
                }
            }
            $con=mysqli_connect("localhost","v2","aA_iul453_v2","vol_time_2");
// 检查连接
if (!$con)
{
    die("连接错误: " . mysqli_connect_error());
}
            $num = strip_tags($num);
            $sql = "SELECT * from vt_bak WHERE stu_id={$num} LIMIT 1";
            //$query = $this->Models->search($name,$num);   
            $query = $this->db->query($sql);
            $res = new result();  
            $res->all_time = 0;
            $result = $con->query($sql);
            //print_r($result->fetch_all());
            if($query->result_array()==null)
                return 0;
            foreach($result->fetch_all() as $row){
                $res->name = $row[1];
                $res->data = $res->data."<tr> <td><p>".$row[0]."</p></td> <td><p>".$row[1]."</p></td> <td><p>".$row[3]."</p></td> <td><p>".$row[4]."</p></td> <td><p>".$row[5]."</p></td> <td><p>".$row[7]."</p></td> <td><p>".$row[6]."</p></td> <td><p>".$row[10]."</p></td></tr>";
                
                $res->all_time = $res->all_time+$row[5];
            }
            echo json_encode($res);
        } else {
            //header('Location:https://www.dutbit.com/vol_time/');
        }
    }
}