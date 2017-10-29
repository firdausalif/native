<?php
/**
 * Created by PhpStorm.
 * User: .SuperNova's
 * Date: 28/10/2017
 * Time: 18:40
 */

//Built with love from /native
defined('BASEPATH') OR exit('Hayoo mau buka apaaaa??');
class Register extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
    }

    function index(){
        $data = array(
            'title'=> 'Native', //ini title yang ada di browser tuh yang di tab
            'isi' => 'pages/register', //halaman buat di load viewnya foldernya ada di views/pages/
            'nav' => 'nav.php', // itu buat load navigasi foldernya ada di views/layout/
        );
        $this->load->view('layout/wrapper',$data);
    }

    function register(){
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $repassword = $this->input->post('repassword');
        $name = $this->input->post('name');

        $nama_table = "users";

        $data_register = array( //simpen input tadi ke dalem array biar cepet masukin ke dbnya
            "username" => $username, //"username" itu nama column di database, "=>" ini nunjukin kalo valuenya sama dengan $username
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "email" => $email,
            "nama" => $name
        );

        $where = array(//ini cuman buat ngecek username sebelumnya ada engga
            'username' => $username
        );

        if($username == "" || $email == "" || $password == "" || $repassword == "" || $name ==""){
            echo json_encode(array('status' => 'fail', 'msg' => 'Harap isi semua input...'));
        }else{
            if ($password != $repassword){
                echo json_encode(array('status' => 'fail', 'msg' => 'Password dan repassword tidak sama...'));
            }else{
                $isUsername_exist = $this->home_model->is_UserExist($where);
                if(!$isUsername_exist){//check username yang diinput sudah terdaftar atau belum
                    //jika username input belum terdaftar
                    $this->db->insert($nama_table, $data_register);
                    echo json_encode(array('status' => 'ok', 'msg' => 'Akun berhasil didaftarkan...'));
                }else{
                    echo json_encode(array('status' => 'fail', 'msg' => 'Akun sudah didaftarkan sebelumnya...'));
                }
            }
        }
    }
}