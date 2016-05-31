<?php
namespace User\Controller;
use Think\Controller;

/**
 * @author Zhou Yuyang <1009465756@qq.com> 12:28 2016/1/23
 * @copyright 2105-2018 SRCMS 
 * @homepage http://www.src.pw
 * @version 1.5
 */


class ChangeController extends BaseController{
    /**
     * 显示更改密码页面
     * @return [type] [description]
     */
    public function index()
    {
		$tmodel= M('setting');
		$title = $tmodel->where('id=1')->select();
		$this->assign('title', $title);
        $this->display();
    }

     /**
     * 修改密码流程
     */
    public function change()
    {
		//验证请求方式
        if(!IS_POST)$this->error("非法请求");
		
		$code = I('verify','','strtolower');
		
        //验证验证码是否正确
        if(!($this->check_verify($code))){
            $this->error('验证码错误');
        }
		
        $member = M('member');
        $id = session('userId');
		$oldpassword =I('post.oldpassword');
        $password =I('post.password');
		
        //验证原密码
        $user = $member->where(array('id'=>$id))->find();
		
        if($user['password'] != md5(md5(md5($user['salt']).md5($oldpassword)."HRSRC")."OPENSISE")) {
            $this->error('旧密码不正确 :(') ;
        }
		
        //验证账户是否管理员        
		if($user['type'] == 2){
            $this->error('前台无法修改管理员密码 :(') ;
        }
    
	$password = md5(md5(md5($user['salt']).md5($password)."HRSRC")."OPENSISE");
	$member-> password=$password;
    $result = $member->where(array('id'=>$id))->save();
	 if($result){  
        $this->success("修改成功",U('login/logout'));
        }else{  
         $this->error('修改失败 :(') ;
        }  
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
	
}