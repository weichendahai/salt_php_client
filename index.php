<?php

include './util/CurlUtil.php';

function auth() {
    $saltApiUrl       = 'http://127.0.0.1:8888/login';
    $saltApiUser      = 'saltapi';
    $saltApiPassword  = '123456';
    # params = {'eauth': 'pam', 'username': self.__user, 'password': self.__password}
    $authtype = 'pam';
    $params = ['eauth' => $authtype, 'username' => $saltApiUser, 'password' => $saltApiPassword];

    $url = $saltApiUrl;
    $curl = new CurlUtil($url);
    $curl->setPostFields($params);
    $curl->setTimeout(100);
    $data = $curl->execute();
    //$data = [
        //"http_code"=> 200,
            //'errno' => 0,
            //'errmsg' => '',
            //'data' => '{"return": [{"perms": [".*", "@wheel", "@runner", "@jobs"], "start": 1477534448.9316261, "token": "6a685ba6efce27d5f51f270ed54ca40dc2b3d8ce", "expire": 1477577648.931628, "user": "saltapi", "eauth": ""}]}',
    //];

    var_dump($data);

    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            
            $result = json_decode($data['data']);
            $credentials = $result->return[0];
            var_dump($credentials);

            $token  = $credentials->token;
            $expire = $credentials->expire;
            var_dump($token);
            var_dump($expire);
        }
    }
}

//string(40) "f21335a27aca233f0878c12ad5981ac1d7a5bba0"
//auth();

//def list_all_key(self):
        //params = {'client': 'wheel', 'fun': 'key.list_all'}
        //obj = urllib.urlencode(params)
        //content = self.postRequest(obj)
        //#minions = content['return'][0]['data']['return']['minions']
        //#minions_pre = content['return'][0]['data']['return']['minions_pre']
        //#return minions,minions_pre
        //minions = content['return'][0]['data']['return']
        //return minions

function postRequest (array $params=[], $prefix='/') {
    $saltApiUrl       = 'http://127.0.0.1:8888';
    $token = 'f21335a27aca233f0878c12ad5981ac1d7a5bba0';
    $url =  $saltApiUrl . $prefix;
    $curl = new CurlUtil($url);

    $httpHead = [
        'Accept: application/json',
        'X-Auth-Token: ' . $token,
        //'Content-Type: application/json'
    ]; 

    $curl->setHttpHeader($httpHead);
    if ($params != []) {
        $curl->setPostFields($params);
    }
    $curl->setTimeout(100);
    $data = $curl->execute();
    return $data;
}

function listAllKey() {
    //curl http://192.168.178.166:8888/ -H "Accept: application/x-yaml" -H "X-Auth-Token: f21335a27aca233f0878c12ad5981ac1d7a5bba0" -d client='wheel' -d fun='key.list_all'
    $params = ['client' => 'wheel', 'fun' => 'key.list_all'];
    $data = postRequest($params);

    $minions = [];
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $minions = $result->return[0]->data->return;
        }
    }
    var_dump($minions);
    return $minions;
}

     //params = {'client': 'wheel', 'fun': 'key.delete', 'match': node_name}
        //obj = urllib.urlencode(params)
        //content = self.postRequest(obj)
        //ret = content['return'][0]['data']['success']
        //return ret
function deleteKey($nodeName) {
    $params = ['client' => 'wheel', 'fun' => 'key.delete', 'match' => $nodeName];
    $data = postRequest($params);

    $ret = false;
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $ret = $result->return[0]->data->success;
        }
    }
    var_dump($ret);
    return $ret;
}
function acceptKey($nodeName) {
    $params = ['client' => 'wheel', 'fun' => 'key.accept', 'match' => $nodeName];
    $data = postRequest($params);

    var_dump($data);
    $ret = false;
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $ret = $result->return[0]->data->success;

        }
    }
    var_dump($ret);
    return $ret;
}
function rejectKey() {
    $params = ['client' => 'wheel', 'fun' => 'key.reject', 'match' => $nodeName];
    $data = postRequest($params);

    $ret = false;
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $ret = $result->return[0]->data->success;

        }
    }
    var_dump($ret);
    return $ret;
}
function remoteNoArgExecution($tgt, $fun) {
    //Execute commands without parameters
    $params = ['client' => 'local', 'tgt' => $tgt, 'fun' => $fun, 'expr_form' => 'list'];
    $data = postRequest($params);

    $ret = [];
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $ret = $result->return[0]->tgt;
        }
    }
    var_dump($ret);
    return $ret;
}

function remoteNoArgExecutionNoTgt ($tgt, $fun) {
    //Execute commands without parameters
    $params = ['client' => 'local', 'tgt' => $tgt, 'fun' => $fun, 'expr_form' => 'list'];
    $data = postRequest($params);

    $ret = [];
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $ret = $result->return[0];
        }
    }
    var_dump($ret);
    return $ret;
}

//def remote_execution(self,tgt,fun,arg):
//''' Command execution with parameters '''
    //params = {'client': 'local', 'tgt': tgt, 'fun': fun, 'arg': arg, 'expr_form': 'list'}
function remoteExecution ($tgt, $fun, $arg) {
        //''' Command execution with parameters '''
    $params = ['client' => 'local', 'tgt' => $tgt, 'fun' => $fun, 'arg' => $arg, 'expr_form' => 'list'];
    $data = postRequest($params);

    $ret = [];
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $ret = $result->return[0]->tgt;
        }
    }
    var_dump($ret);
    return $ret;
}
    //def remote_execution_notgt(self,tgt,fun,arg):
        //''' Command execution with parameters '''
        //params = {'client': 'local', 'tgt': tgt, 'fun': fun, 'arg': arg, 'expr_form': 'list'}
        //obj = urllib.urlencode(params)
        //content = self.postRequest(obj)
        //try:
            //ret = content['return'][0]
        //except Exception as e:
            //pass
        //return ret
function remoteExecutionNoTgt ($tgt, $fun, $arg) {
        //''' Command execution with parameters '''
    $params = ['client' => 'local', 'tgt' => $tgt, 'fun' => $fun, 'arg' => $arg, 'expr_form' => 'list'];
    $data = postRequest($params);
    $ret = [];
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $ret = $result->return[0];
        }
    }
    var_dump($ret);
    return $ret;

}
    //def shell_remote_execution(self,tgt,arg):
        //''' Shell command execution with parameters '''
        //params = {'client': 'local', 'tgt': tgt, 'fun': 'cmd.run', 'arg': arg, 'expr_form': 'list'}
function shellRemoteExecution ($tgt, $arg) {
    //Shell command execution with parameters
    $params = ['client' => 'local', 'tgt' => $tgt, 'fun' => 'cmd.run', 'arg' => $arg, 'expr_form' => 'list'];
    $data = postRequest($params);

    $ret = [];
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $ret = $result->return[0];
        }
    }
    var_dump($ret);
    return $ret;

}

//def grains(self,tgt,arg):
        //''' Grains.item '''
        //params = {'client': 'local', 'tgt': tgt, 'fun': 'grains.item', 'arg': arg}
        //content = self.postRequest(obj)
        //ret = content['return'][0]
        //return ret
function grains ($tgt, $arg) {
    //Grains.item
    $params = ['client' => 'local', 'tgt' => $tgt, 'fun' => 'grains.items', 'arg' => $arg];
    $data = postRequest($params);

    $ret = [];
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $ret = $result->return[0];
        }
    }
    var_dump($ret);
    return $ret;
}

    //def target_remote_execution(self,tgt,fun,arg):
        //''' Use targeting for remote execution '''
        //params = {'client': 'local', 'tgt': tgt, 'fun': fun, 'arg': arg, 'expr_form': 'nodegroup'}
        //jid = content['return'][0]['jid']
//根据salt－master对客户端分组，执行命令
function targetRemoteExecution ($tgt, $fun, $arg) {
    //Use targeting for remote execution
    $params = ['client' => 'local', 'tgt' => $tgt, 'fun' => $fun, 'arg' => $arg, 'expr_form' => 'nodegroup'];
    $data = postRequest($params);

    $ret = [];
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $ret = $result->return[0]->jid;
        }
    }
    var_dump($ret);
    return $ret;
}
    //def jobs_list(self):
        //''' Get Cache Jobs Defaut 24h '''
        //url = self.__url + '/jobs/'
        //headers = {'X-Auth-Token': self.__token_id}
        //req = urllib2.Request(url, headers=headers)
        //opener = urllib2.urlopen(req)
        //content = json.loads(opener.read())
        //jid = content['return'][0]
        //return jid
function jobsList () {
    //Get Cache Jobs Defaut 24h
    $params = [];
    $prefix = '/jobs/';
    $data = postRequest($params);
  //string(165) "{"clients": ["_is_master_running", "local", "local_async", "local_batch", "runner", "runner_async", "ssh", "ssh_async", "wheel", "wheel_async"], "return": "Welcome"}"
    var_dump($data);
    $ret = [];
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $ret = $result->return;
        }
    }
    var_dump($ret);
    return $ret;
}
    //def runner_status(self,arg):
        //''' Return minion status '''
        //params = {'client': 'runner', 'fun': 'manage.' + arg }
        //jid = content['return'][0]
function runnerStatus ($arg) {
    //Return minion status
    $params = ['client' => 'runner', 'fun' => 'manage.' . $arg];
    $data = postRequest($params);

    $ret = [];
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $ret = $result->return[0];
        }
    }
    var_dump($ret);
    return $ret;
}
    //def runner(self,arg):
        //''' Return minion status '''
        //params = {'client': 'runner', 'fun': arg }
        //obj = urllib.urlencode(params)
        //content = self.postRequest(obj)
        //jid = content['return'][0]
        //return jid
function runner ($arg) {
    //Return minion status
    $params = ['client' => 'runner', 'fun' => $arg];
    $data = postRequest($params);

    $ret = [];
    if ($data['http_code'] == '200') {
        if ($data['errno'] == '0') {
            $result = json_decode($data['data']);
            $ret = $result->return[0];
        }
    }
    var_dump($ret);
    return $ret;
}

function main() {
   //auth(); 
   //listAllKey();
    
    acceptKey('salt-slave0121');

  //返回值包括tgt (没有找到例子)
  //remoteNoArgExecution('salt-slave01', 'grains.items'); 
  //返回值不包括tgt
  //remoteNoArgExecutionNoTgt('salt-slave01', 'grains.items'); 

  //执行函数 返回值包括tgt (没有找到例子)
  //remoteExecution ('salt-slave01', 'cmd.run', 'date');
  //执行函数 返回值不包括tgt
  //remoteExecutionNoTgt ('salt-slave01', 'cmd.run', 'echo \'123我的\'');
  //remoteExecutionNoTgt ('salt-slave01,salt-slave02', 'cmd.run', 'date');
    
  //执行shell
  //shellRemoteExecution('salt-slave01', 'date'); 

  //执行grains
  //grains('salt-slave01', 'cpu'); 
    
  //joblist 
  //jobsList();


   //runnerStatus ('status'); 
   //runner('manage.up'); 
}

main();
