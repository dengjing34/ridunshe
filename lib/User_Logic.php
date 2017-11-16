<?php
//dengjing34@vip.qq.com
class User_Logic {
    
    function login($username, $password) {
        $result = false;
        if (trim($username) == '' || trim($password) == '') throw new Exception('用户名或密码未填写', 001);
        $user = new User();
        $user->userName = $username;
        $user->password = md5($username . $password);
        try {
            $result = $user->find(array('limit' => '0,1'));
            if (empty($result)) {
                throw new Exception('用户名或密码错误', 002);
            } elseif ($result[0]->status != User::STATUS_ACTIVE) {
                throw new Exception('抱歉,您的帐户已经停用', 003);
            } else {
                $role = new Role();
                $role->id = $result[0]->roleId;
                try {
                    $role->load();
                } catch (Exception $exc) {
                    throw new Exception('抱歉,您的用具权限有问题', 005);
                }                
                Cookie::set('_userName', $username, 86400);
                Cookie::set('_userId', $result[0]->id, 86400);
                Cookie::set('_userRole', $result[0]->role, 86400);
                Cookie::set('_userRoleId', $result[0]->roleId, 86400);
                Cookie::set('_userAccess', $role->access, 86400);
                $result = true;
            }
        } catch (Exception $e) {
            throw new Exception('用户名或密码错误', 004);
        }
        return $result;
    }

    function quit() {
        foreach (array('_userName', '_userId', '_userRole', '_userRoleId', '_userAccess') as $v) {
            Cookie::delete($v);
        }
    }
}
?>
