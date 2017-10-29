<?php
/**
 * Created by PhpStorm.
 * User: .SuperNova's
 * Date: 28/10/2017
 * Time: 18:40
 */

//Built with love from /native
defined('BASEPATH') OR exit('Hayoo mau buka apaaaa??');
class Home extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('account_model');
    }

    function index(){
        $token_err = $this->session->flashdata('token');
        $data = array(
            'title'=> 'Native', //ini title yang ada di browser tuh yang di tab
            'isi' => 'pages/home', //halaman buat di load viewnya foldernya ada di views/pages/
            'nav' => 'nav.php', // itu buat load navigasi foldernya ada di views/layout/,
            'userTable' => $this->home_model->getTable('users'), //contoh ngambil data dari db buat passing ke value data ini bakalan ditampilin di homepage
            'token_err' => $token_err
        );
        $this->load->view('layout/wrapper',$data);
    }

    function login(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $data = array(
            'username' => $username,
            'password' => $password
        );

        $where = array(
            'username' => $username
        );

        if($username == "" || $password == ""){
            echo json_encode(array('status' => 'fail', 'msg' => 'Harap masukkan semua input...'));
        }else{
            $isUsername_exist = $this->home_model->is_UserExist($where);
            if(!$isUsername_exist){
                echo json_encode(array('status' => 'fail', 'msg' => 'Username belum terdaftar....'));
            }else{
                $db_pass = $this->home_model->get_users_pass($username)->password;

                if(password_verify($password, $db_pass)){
                    $this->session->set_userdata('username', $username);

                    echo json_encode(array('status' => 'ok', 'msg' => 'Login berhasil tunggu sebentar....'));
                }else{
                    echo json_encode(array('status' => 'fail', 'msg' => 'Password yang dimasukkan salah....'));
                }
            }
        }
    }

    function logout()
    {
        if( $this->session->has_userdata('username')){
            unset(
                $_SESSION['username']
            );
            $this->session->sess_destroy();
        }
        redirect('');
    }

    function edit(){
        $id = $this->input->post('id');
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $repassword = $this->input->post('repassword');
        $name = $this->input->post('name');

        $where = array(
            'id' => $id
        );

        $whereUsername = array(
            'username' => $username
        );

        if($password == "" && $repassword ==""){
            $data_users = array(
                'username' => $username,
                'email' => $email,
                'nama' => $name
            );

            if($username == "" || $email == "" || $name == ""){
                echo json_encode(array('status' => 'fail', 'msg' => 'Harap masukkan username, email, dan nama anda...'));
            }else{
                $isUsername_exist =  $this->home_model->is_UserExistNotMe($whereUsername, $_SESSION['username']);
                if(!$isUsername_exist) {//check username yang diinput sudah terdaftar atau belum
                    $this->home_model->updateTable("users", $data_users, $where);
                    echo json_encode(array('status' => 'ok', 'msg' => 'Data berhasil diupdate, tunggu sebentar...'));
                }else{
                    echo json_encode(array('status' => 'fail', 'msg' => 'Username sudah terpakai'));
                }
            }
        }else if($password != "" && $repassword ==""){
            echo json_encode(array('status' => 'fail', 'msg' => 'Harap masukkan re-Password...'));
        }else if($password == "" && $repassword !=""){
            echo json_encode(array('status' => 'fail', 'msg' => 'Harap masukkan password anda...'));
        }else{
            $data_users = array(
                'username' => $username,
                'email' => $email,
                'nama' => $name,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            );

            $isUsername_exist = $this->home_model->is_UserExistNotMe($whereUsername, $_SESSION['username']);
            if(!$isUsername_exist) {//check username yang diinput sudah terdaftar atau belum
                $this->home_model->updateTable("users", $data_users, $where);
                echo json_encode(array('status' => 'ok', 'msg' => 'Data berhasil diupdate, tunggu sebentar...'));
            }else{
                echo json_encode(array('status' => 'fail', 'msg' => 'Username sudah terpakai'));
            }
        }
    }

    function delete(){
        $id = $id = $this->input->post('id');

        $whereId = array(
            'id' => $id
        );
        $this->db->delete('users', $whereId);
        echo json_encode(array('status' => 'ok', 'msg' => 'Data berhasil dihapus, tunggu sebentar...'));
    }

    function reqPass(){
        $email = $this->input->post('email');

        if($email == ""){
            echo json_encode(array('status' => 'fail', 'msg' => 'Harap masukkan email anda'));
        }else{

            $clean = $this->security->xss_clean($email);
            $userInfo = $this->account_model->getUserInfoByEmail($clean);
            if($userInfo) {//check username yang diinput sudah terdaftar atau belum
                $token = $this->account_model->insertToken($userInfo->id);
                $qstring = $this->base64url_encode($token);
                $url = site_url() . 'resetpassword/reset/token/' . $qstring;

                $data = array(
                    'link' => $url,
                    'nama' => $userInfo->nama
                );

                $this->sendmail($email, $data);
                echo json_encode(array('status' => 'ok', 'msg' => 'Link konfirmasi sudah dikirim ke alamat email anda'));
            }else{
                echo json_encode(array('status' => 'fail', 'msg' => 'Email tidak ditemukan'));
            }
        }
    }

    function sendmail($email,$data){
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://mail.defaultunj.com',
            'smtp_port' => 465,
            'smtp_user' => 'native@defaultunj.com',
            'smtp_pass' => 'asdasd123',
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1'
        );

        $massages = $this->load->view('email/resetpassword',$data,true);

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from('native@defaultunj.com');
        $this->email->to($email);
        $this->email->subject("Password reset confirmation...");
        $this->email->message($massages);

        return $this->email->send();
    }

    public function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64url_decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}