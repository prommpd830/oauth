<?php namespace App\Controllers;
 
use App\Models\UserModel;
use Codeigniter\Controller;
 
class Auth extends BaseController
{
    private $userModel = NULL;
    private $googleClient = NULL;
    private $facebook = NULL;
    private $fb_helper = NULL;

    public function __construct()
    {
        require_once APPPATH. "libraries/vendor/autoload.php";
        $this->userModel = new UserModel();
        // api google
        $this->googleClient = new \Google_Client();
        $this->googleClient->setClientId("946921145835-ctdhaf5j7doqgjdthn3u5r3gndhlkibq.apps.googleusercontent.com");
        $this->googleClient->setClientSecret("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");
        // $this->googleClient->setRedirectUri("https://b7f4-140-213-51-229.ngrok.io/public/auth/google");
        $this->googleClient->setRedirectUri("http://localhost:8080/auth/google");
        $this->googleClient->addScope("email");
        $this->googleClient->addScope("profile");
        // api facebook
        $this->facebook =  new \Facebook\Facebook([
            'app_id'  => '2304593789688731',
            'app_secret'  => '697d9815236698d17e515de6bbdf1217',
            'default_graph_version' => 'v12.0'
        ]);
        $this->fb_helper = $this->facebook->getRedirectLoginHelper();
        // validation
        $this->validation =  \Config\Services::validation();
    }

    public function register()
    {
        if ($this->request->getMethod() == 'get') {
            $data = [
                'title' => 'Register',
                'validation' => $this->validation
            ];
            return view('auth/register', $data);
        }

        // Set Rules
        $rules = [
            'username' => [
                'rules' => 'required'
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.user_email]',
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $name  = $this->request->getPost('username');
        $email      = $this->request->getPost('email');
        $password   = $this->request->getPost('password');
 
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
 
        $dataRegister = [
            'user_username' => $name,
            'user_email' => $email,
            'user_password' => $password_hash,
            'created_at' => date("Y-m-d H:i:s")
        ];
 
        $register = $this->userModel->insertUserData($dataRegister);

        if ($register = true) {
            return redirect('login')->with('success', 'Register Successfully. Please Login!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Register Failed. Try Again!');
        }

    }
 
    public function login()
    {
        if ($this->request->getMethod() == 'get') {
            $fb_permission = ['email'];
            $data = [
                'title' => 'Login',
                'validation' => $this->validation,
                'googleUrl' => $this->googleClient->createAuthUrl(),
                // 'facebookUrl' => $this->fb_helper->getLoginUrl('https://b7f4-140-213-51-229.ngrok.io/public/auth/facebook?', $fb_permission)
                'facebookUrl' => $this->fb_helper->getLoginUrl('http://localhost:8080/auth/facebook?', $fb_permission)
            ];

            return view('auth/login', $data);
        }

        // Set Rules
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $email      = $this->request->getPost('email');
        $password   = $this->request->getPost('password');

        $cek_login = $this->userModel->cekLogin($email);
 
        if (!$cek_login) {
           return redirect()->back()->withInput()->with('error', 'Your account is not define!');
        } else {
            if(password_verify($password, $cek_login['user_password'])){
                $userdata = [
                    'oauth_id' => $cek_login['id'],
                    'user_username' => $cek_login['user_username'], 
                    'user_email' => $cek_login['user_email'] , 
                    'user_img' => base_url('assets/img/'.$cek_login['user_img'])
                ];

                // set session user
                session()->set("LoggedUserData", $userdata);
         
                // return $this->respond($token);
                return redirect()->to(base_url());
            } else {
                return redirect()->back()->withInput()->with('error', 'Your password is wrong!');
            }
        }
    }

    public function google()
    {
        // get token
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getVar('code'));

        if(!isset($token['error'])){

            // set session token
            $this->googleClient->setAccessToken($token['access_token']);
            session()->set("AccessToken", $token['access_token']);

            $googleService = new \Google_Service_Oauth2($this->googleClient);
            $data = $googleService->userinfo->get();
            $currentDateTime = date("Y-m-d H:i:s");

            if($this->userModel->isAlreadyRegister($data['id'])){
                //User ALready Login and want to Login Again
                $userdata = [
                    'oauth_id' => $data['id'],
                    'user_username' => $data['givenName']. " ".$data['familyName'], 
                    'user_email' => $data['email'], 
                    'user_img' => $data['picture'], 
                    'updated_at' => $currentDateTime
                ];

                $this->userModel->updateUserData($userdata, $data['id']);
            }else{
                //new User want to Login
                $userdata = [
                    'oauth_id' => $data['id'],
                    'user_username' => $data['givenName']. " ".$data['familyName'], 
                    'user_email' => $data['email'], 
                    'user_img' => $data['picture'], 
                    'created_at' => $currentDateTime
                ];

                $this->userModel->insertUserData($userdata);
            }
            session()->set("LoggedUserData", $userdata);

        }else{
            return redirect('login')->with('error', 'Error has been occured!');
        }
        //Successfull Login
        return redirect()->to(base_url());
    }

    public function facebook()
    {
        if($this->request->getVar('state')){
            $this->fb_helper->getPersistentDataHandler()->set('state', $this->request->getVar('state'));
        }

        if($this->request->getVar('code')){
            if(session()->get("access_token")){
                $access_token = session()->get('access_token');
            }else{
                $access_token = $this->fb_helper->getAccessToken();
                session()->set("access_token", $access_token);
                $this->facebook->setDefaultAccessToken(session()->get('access_token'));
            }
            $graph_response = $this->facebook->get('/me?fields=name,email', $access_token);
            $fb_user_info = $graph_response->getGraphUser();
            // print_r($fb_user_info);die;
            if(!empty($fb_user_info)){

                if ($this->userModel->isAlreadyRegister($fb_user_info['id'])) {
                    $userdata = array(
                        'oauth_id'=>$fb_user_info['id'],
                        'user_img' => 'http://graph.facebook.com/'.$fb_user_info['id'].'/picture',
                        'user_username' => $fb_user_info['name'],
                        'user_email' => $fb_user_info['email'],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $this->userModel->updateUserData($userdata, $fb_user_info['id']);
                } else {
                    $userdata = array(
                        'oauth_id'=>$fb_user_info['id'],
                        'user_img' => 'http://graph.facebook.com/'.$fb_user_info['id'].'/picture',
                        'user_username' => $fb_user_info['name'],
                        'user_email' => $fb_user_info['email'],
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    $this->userModel->insertUserData($userdata);
                }
                
                session()->set('LoggedUserData', $userdata);
            }
        }else{
            session()->setFlashData('error', 'Something Wrong');
            return redirect('login');
        }
        return redirect()->to(base_url());
    }

    public function logout()
    {
        session()->remove('LoggedUserData');
        session()->remove('AccessToken');
        return redirect('login')->with('success', 'You are logout!');
    }
 
}