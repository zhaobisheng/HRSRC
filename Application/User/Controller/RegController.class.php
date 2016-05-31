<?php
namespace User\Controller;
use Think\Controller;

/**
 * @author Zhou Yuyang <1009465756@qq.com> 11:28 2016/1/26
 * @copyright 2105-2018 SRCMS 
 * @homepage http://www.src.pw
 * @version 1.6
 */


/**
 * 注册页面
 */
class RegController extends Controller{
    /**
     * 用户列表
     * @return [type] [description]
     */
    public function index()
    {
		$tmodel= M('setting');
		$title = $tmodel->where('id=1')->select();
		$this->assign('title', $title);
       $this->display();
    }

   //验证码
    public function verify(){
		ob_clean();
		$config =  array(    'fontSize'    =>    30,    // 验证码字体大小
		    'length'      =>    3,     // 验证码位数 
			   'useNoise'    =>    false, // 关闭验证码杂点
			   );
        $Verify = new \Think\Verify($config);
        $Verify->codeSet = '1234567890abcdefghijklmnopqrstuvwxyz';
        $Verify->fontSize = 30;
        $Verify->length = 4;
        $Verify->entry();
    }
    protected function check_verify($code){
		$config =  array(    'fontSize'    =>    30,    // 验证码字体大小
		    'length'      =>    3,     // 验证码位数 
			   'useNoise'    =>    false, // 关闭验证码杂点
			   );
        $verify = new \Think\Verify($config);
        return $verify->check($code);
    }
  
    /**
     * 添加用户
     */
    public function add()
    {
        //默认显示添加表单
        if (!IS_POST) {
            $this->display();
        }
        if (IS_POST) {
            //如果用户提交数据
		  $data['salt'] = "";
		  $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		 for($num=0;$num<8;$num++)
		  {
			$RandNum = rand(0,strlen($chars)-1);  
			$data['salt'] .= $chars[$RandNum]; 
		  }         		
					
		$data['username'] = I('username');
		$data['email']= I('email');
        $data['password'] = I('password');
		$repassword= I('repassword');
		if($data['password'] != $repassword){ $this->error("两次密码不一致!");}
		
        $code = I('verify','','strtolower');
		
        //验证验证码是否正确
        if(!($this->check_verify($code))){
            $this->error('验证码错误');
        }
		
		$data['password'] = md5(md5(md5($data['salt']).md5($data['password'])."HRSRC")."OPENSISE");
		$data['create_at']=time();
		 $model = M("Member");
		// $model->field('username,email,salt,password')->create();
			//$model->data($data)->add();
			
			
            //if (!$model->field('username,email,salt,password,repassword')->create()) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                //$this->error($model->getError());
                //exit();
            ///} else {
				
                if ($model->field('username,email,salt,password,create_at')->data($data)->add()) {
		
		
		 $user = $model->where(array('username'=>$data['username']))->find();		
					
					  //更新登陆信息
        $date =array(
            'id' => $user['id'],
            'update_at' => time(),
            'login_ip' => get_client_ip(),
        );
        
        //如果数据更新成功  跳转到后台主页
        if($model->save($date)){
            session('userId',$user['id']);
            session('user',$user['username']);
			session('token',md5(time().$user['salt']));
        }	
				  $this->success("注册成功", U('index/index'));
                } else {
                    $this->error("注册失败");
                }
            //}
        }
    }
}
