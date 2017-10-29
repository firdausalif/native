<?php
/**
 * Created by PhpStorm.
 * User: .SuperNova's
 * Date: 29/10/2017
 * Time: 7:36
 */
//Built with love from /native
defined('BASEPATH') OR exit('Hayoo mau buka apaaaa??');
class Resetpassword extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
        $this->load->model('account_model');
    }

    public function reset()
    {
        $token = $this->base64url_decode($this->uri->segment(4));
        $cleanToken = $this->security->xss_clean($token);

        $user_info = $this->account_model->isTokenValid($cleanToken); //either false or array();

        if(!$user_info){
            $this->session->set_flashdata('token','Token tidak valid atau kadaluarsa');
            redirect("Home");
        }

        $data = array(
            'title'=> 'Native - Reset Password', //ini title yang ada di browser tuh yang di tab
            'isi' => 'pages/resetpass', //halaman buat di load viewnya foldernya ada di views/pages/
            'nav' => 'nav.php',
            'nama'=>  $user_info->nama,
            'email'=> $user_info->email,
            'token'=> $this->base64url_encode($token)
        );

        $this->load->view('layout/wrapper',$data);
    }

    public function updatePass(){
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $repassword = $this->input->post('repassword');

        $where = array(
            'email' => $email
        );

        if ($password == "" || $repassword == "") {
            echo json_encode(array('status' => 'fail', 'msg' => 'Harap masukkan password/repassword'));
        }else{
            if($password == $repassword){
                $pass  = array(
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                );

                if(!$this->account_model->updatePassword($pass, $where)){
                    echo json_encode(array('status' => 'fail', 'msg' => 'Gagal update password'));
                }else{
                    echo json_encode(array('status' => 'Ok', 'msg' => 'Password berhasil diperbaharui silahkan login'));
                }
            }else{
                echo json_encode(array('status' => 'fail', 'msg' => 'Password dan repassword tidak cocok'));
            }
        }
    }

    public function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64url_decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}