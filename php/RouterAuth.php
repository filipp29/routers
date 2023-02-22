<?php




$_SERVER['DOCUMENT_ROOT']='/var/htdocs/wotom.net'; //Указываем корневую папку (нужно, только если работаем с консольным скриптом
require_once ($_SERVER['DOCUMENT_ROOT'].'/_lib/libObjEngine.php'); //Подключаем библиотеку для работы с БД
require_once $_SERVER['DOCUMENT_ROOT'].'/_modules/routers/php/RouterBase.php';



class RouterAuth {
    
    
    
    
    /*--------------------------------------------------*/
    
   
    static private function getLast(
            $obj
    ){
        $result = [];
        $max = 0;
        foreach($obj as $key => $value){
            $buf = explode('_', $key);
            if ((int)$buf[1] >= $max){
                $result[$buf[0]] = $value;
                $max = (int)$buf[1];
            }
        }
        return $result;
    }
    
    
    
    
    
    /*--------------------------------------------------*/
    
    
    static private function getFirst(
            $obj
    ){
        $result = [];
        $min = 9647343143;
        foreach($obj as $key => $value){
            $buf = explode('_', $key);
            if ((int)$buf[1] <= $min){
                $result[$buf[0]] = $value;
                $min = (int)$buf[1];
            }
        }
        return $result;
    }
    
    /*--------------------------------------------------*/
    
    
    static public function getLastByMac(
            $mac
    ){
        $buf = \RouterBase::getValidMac($mac);
        if (strlen($buf) != 12){
            throw new Exception("Wrong mac {$buf}");
        }
        $mac = "";
        for ($i = 0; $i < 12; $i++){
            if (($i % 2 == 0) && ($i > 0)){
                $mac .= ":";
            }
            $mac .= $buf[$i];
        }
        $path = "/authlog/byMAC/".$mac. "/";
        $br = array_keys(objLoadBranch($path, true, false));
        if (count($br) == 0){
            throw new Exception("Auth not found");
        }
        rsort($br);
        $obj = objLoad($path. $br[0]);
        return self::getLast($obj);
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    static public function getFirstByMac(
            $mac
    ){
        $buf = \RouterBase::getValidMac($mac);
        if (strlen($buf) != 12){
            throw new Exception("Wrong mac {$buf}");
        }
        $mac = "";
        for ($i = 0; $i < 12; $i++){
            if (($i % 2 == 0) && ($i > 0)){
                $mac .= ":";
            }
            $mac .= $buf[$i];
        }
        $path = "/authlog/byMAC/".$mac. "/";
        $br = array_keys(objLoadBranch($path, true, false));
        if (count($br) == 0){
            throw new Exception("Auth not found");
        }
        sort($br);
        $obj = objLoad($path. $br[0]);
        return self::getFirst($obj);
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    static public function getLastByDnum(
            $dnum
    ){
        $path = "/authlog/byLogin/".$dnum. "/";
        $br = array_keys(objLoadBranch($path, true, false));
        if (count($br) == 0){
            throw new Exception("Auth {$dnum} not found");
        }
        rsort($br);
        $obj = objLoad($path. $br[0]);
        return self::getLast($obj);
    }
    
    
    /*--------------------------------------------------*/
    
    
    static public function getFirstByDnum(
            $dnum
    ){
        $path = "/authlog/byLogin/".$dnum. "/";
        $br = array_keys(objLoadBranch($path, true, false));
        if (count($br) == 0){
            throw new Exception("Auth {$dnum} not found");
        }
        sort($br);
        $obj = objLoad($path. $br[0]);
        return self::getFirst($obj);
    }
    
    
    /*--------------------------------------------------*/
    
    
    static public function getLastByBoth(
            $mac,
            $dnum
    ){
        $buf = \RouterBase::getValidMac($mac);
        if (strlen($buf) != 12){
            throw new Exception("Wrong mac {$buf}");
        }
        $mac = "";
        for ($i = 0; $i < 12; $i++){
            if (($i % 2 == 0) && ($i > 0)){
                $mac .= ":";
            }
            $mac .= $buf[$i];
        }
        $mac = strtoupper($mac);
        $path = "/authlog/byLogin/".$dnum. "/";
        $br = array_keys(objLoadBranch($path, true, false));
        if (count($br) == 0){
            throw new Exception("Auth {$dnum} not found");
        }
        rsort($br);
        $max = -1;
        for ($i = 0; $i < count($br); $i++){
            $obj = objLoad($path. $br[$i]);
            $result = [];
            
            foreach($obj as $key => $value){
                $buf = explode("_", $key);
                $result[$buf[1]][$buf[0]] = $value;
                if (($buf[0] == "mac") &&($value == $mac)){
                    $max = (int)$buf[1];
                }
            }
            
            if ($max >= 0 ){
                return $result[$max];
            }
        }
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    
    static public function getFirstByBoth(
            $mac,
            $dnum
    ){
        $buf = \RouterBase::getValidMac($mac);
        if (strlen($buf) != 12){
            throw new Exception("Wrong mac {$buf}");
        }
        $mac = "";
        for ($i = 0; $i < 12; $i++){
            if (($i % 2 == 0) && ($i > 0)){
                $mac .= ":";
            }
            $mac .= $buf[$i];
        }
        $mac = strtoupper($mac);
        $path = "/authlog/byLogin/".$dnum. "/";
        $br = array_keys(objLoadBranch($path, true, false));
        if (count($br) == 0){
            throw new Exception("Auth {$dnum} not found");
        }
        sort($br);
        $min = -1;
        for ($i = 0; $i < count($br); $i++){
            $obj = objLoad($path. $br[$i]);
            $result = [];
            
            foreach($obj as $key => $value){
                $buf = explode("_", $key);
                $result[$buf[1]][$buf[0]] = $value;
                if (($buf[0] == "mac") &&($value == $mac)&&(((int)$buf[1] < $min)) || ($min < 0)){
                    $min = (int)$buf[1];
                }
            }
            
            if ($min >= 0 ){
                return $result[$min];
            }
        }
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    static private function srt(
            $a, 
            $b
    ){
        if ((int)$a["time"] == (int)$b["time"]) {
            return 0;
        }
        return ((int)$a["time"] > (int)$b["time"]) ? -1 : 1;
    }
    
    /*--------------------------------------------------*/
    
    static public function getAllByMac(
            $mac,
            $typeFlag = true
    ){
        $buf = \RouterBase::getValidMac($mac);
        if (strlen($buf) != 12){
            throw new Exception("Wrong mac {$buf}");
        }
        $mac = "";
        for ($i = 0; $i < 12; $i++){
            if (($i % 2 == 0) && ($i > 0)){
                $mac .= ":";
            }
            $mac .= $buf[$i];
        }
//        echo microtime(). " 3\n";
        $path = "/authlog/byMAC/".$mac. "/";
        $br = array_keys(objLoadBranch($path, true, false));
        if (count($br) == 0){
            throw new Exception("Auth not found");
        }
        $time = microtime(true);
        echo " [". count($br). "] files count\n";
        $result = [];
        $count = 0;
        
        /*--------------------------------------------------*/
        
        $objList = [];
        
        foreach($br as $v){
            
            $objList[] = objLoad($path. $v);
        }    
        unset($br);
        foreach($objList as $k => $obj){
            foreach($obj as $key => $value){
                unset($buf);
                if ($key == "#e"){
                    continue;
                }
                $count++;
                $buf = explode("_", $key);
                $result[(int)$buf[1]][$buf[0]] = $value;
                
            }
            unset($obj,$buf,$key,$value,$objList[$k]);
        }
        
        
        
        /*--------------------------------------------------*/
        unset($br, $v, $objList);
        echo " [". $count. "] auth count\n";
        echo (microtime(true) - $time). " - time\n";
        echo (memory_get_peak_usage() / 1024). " - peak memory\n";
        echo (memory_get_usage() / 1024). " - memory\n";
        $keys = [];
        usort($result, 'RouterAuth::srt');
        $result = array_values($result);
        
        for ($i = 1; $i < (count($result) - 1); $i++){
            $dCur = $result[$i]["login"];
            $dBef = $result[$i-1]["login"];
            $dNext = $result[$i+1]["login"];
            $tCur = $result[$i]["authtype"];
            $tBef = $result[$i-1]["authtype"];
            $tNext = $result[$i+1]["authtype"];
//            echo $tCur. " ". $tBef. " ". $tNext. "\n";
            if (($dCur == null) || ($result[$i]["time"] == null)){
                $keys[] = $i;
            } else
            if ((($dCur == $dBef)&&($dCur == $dNext) && ((($tCur == $tBef) && ($tNext == $tCur)) || ($typeFlag))) ){
                
                $keys[] = $i;
            }
    
        }
        unset($i,$dCur,$dNext,$dBef,$tBef,$tCur,$tNext);
        
        if (($result[count($result) - 1]["login"] == null) || ($result[count($result) - 1]["time"] == null)){
            $keys[] = $i;
        }
        
        
        foreach($keys as $key){
            unset($result[$key]);
        }
//        echo microtime(). " 6\n";
        
        return  array_values($result);
        
        
    }
    
    
    
    
    /*--------------------------------------------------*/
    
    
    static private function removeOne(
            $ar
    ){
        $n = count($ar);
        for ($i = 1; $i < $n-1; $i++){
            $dCur = $ar[$i]["login"];
            $dBef = $ar[$i-1]["login"];
            $dNext = $ar[$i+1]["login"];
            if (!$dCur){
                $keys[] = $i;
            }
            else if (($dCur != $dBef)&&($dCur != $dNext)){
                $keys[] = $i;
            }
        }
        foreach($keys as $key){
            unset($ar[$key]);
        }
        
        return  array_values($ar);
    }
    
    /*--------------------------------------------------*/
    
    static private function removeShort(
            $ar
    ){
        $flag = false;
        $n = count($ar);
        for ($i = 2; $i < $n-2; $i++){
            $dCur = $ar[$i]["login"];
            $dNext = $ar[$i+1]["login"];
            if ((abs((int)$dCur["time"] - (int)$dNext["time"]) < (60*60*24))&&($dCur == $dNext)){
                $keys[] = $i;
                $keys[] = $i+1;
                $flag = true;
            }
        }
        foreach($keys as $key){
            unset($ar[$key]);
        }
        
        return array_values($ar);
    }
    /*--------------------------------------------------*/
    static private function removeSame(
            $ar
    ){
        for ($i = 1; $i < (count($R) - 1); $i++){
            $dCur = $ar[$i]["login"];
            $dBef = $ar[$i-1]["login"];
            $dNext = $ar[$i+1]["login"];
            
            if (($dCur == $dBef)&&($dCur == $dNext)){
//                echo $dBef. " ". $dCur. " ". $dNext. " \n";
                $keys[] = $i;
            }
        }
        
        foreach($keys as $key){
            unset($ar[$key]);
        }
        
        return array_values($ar);
    }
    /*--------------------------------------------------*/
    static public function getIntstall(
            $mac
    ){
//        echo microtime(). " 2\n";
        $auth = self::getAllByMac($mac);
        $keys = [];
        foreach($auth as $key => $value){
            if (!$value["time"]){
                $keys[]  = $key;
            }
        }
        foreach($keys as $key){
            unset($auth[$key]);
        }
        $auth = array_values($auth);
        unset($keys);
//        print_r($auth);
        $auth = array_reverse($auth);
//        echo microtime(). " 4\n";
//        echo "----1--------------------------------------------------\n";
//        print_r($auth);
        $auth = self::removeOne($auth);
//        echo microtime(). " 5\n";
//        echo "----2--------------------------------------------------\n";
//        print_r($auth);
        $auth = self::removeSame($auth);
//        echo microtime(). " 6\n";
//        echo "----3--------------------------------------------------\n";
//        print_r($auth);
        $auth = self::removeShort($auth);
//        echo "----4--------------------------------------------------\n";
//        print_r($auth);
//        echo microtime(). " 7\n";
        $result = [];
        $dCur = $auth[0];
        $buf = [];
        $buf["timeStamp"] = $dCur["time"];
        $buf["state"] = "installed";
        $buf["dnum"] = $dCur["login"];
        $buf["author"] = "SYSTEM";
        $result[] = $buf;
        
        for ($i = 1; $i < count($auth); $i++){
            $dCur = $auth[$i];
            $dNext = $auth[$i+1];
            if ($dCur["login"] == $dNext["login"]){
                
                $buf = [];
                $buf["timeStamp"] = $dCur["time"]-10;
                $buf["state"] = "store";
                $buf["store"] = "store";
                $buf["storeMan"] = "storeMan";
                $buf["author"] = "SYSTEM";
                $result[] = $buf;
                
                $buf = [];
                $buf["timeStamp"] = $dCur["time"];
                $buf["state"] = "installed";
                $buf["dnum"] = $dCur["login"];
                $buf["author"] = "SYSTEM";
                $result[] = $buf;
//                if ($i+1 < count($auth) - 1){
//                    $buf = [];
//                    $buf["timeStamp"] = $dCur["time"];
//                    $buf["state"] = "store";
//                    $buf["store"] = "store";
//                    $buf["storeMan"] = "storeMan";
//                    $buf["author"] = "SYSTEM";
//                    $result[] = $buf;
//                }
            }
        }
//        print_r($result);
//        echo microtime(). " 8\n";
        return $result;
    }
    
    
    /*--------------------------------------------------*/
    
    
    
    static public function getAllByDnum(
            $dnum,
            $typeFlag = true
    ){
        
        $path = "/authlog/byLogin/".$dnum. "/";
        $br = array_keys(objLoadBranch($path, true, false));
        if (count($br) == 0){
            throw new Exception("Auth not found");
        }
        $result = [];
        foreach($br as $value){
            $obj = objLoad($path. $value);
            
            foreach($obj as $key => $value){
                if ($key == "#e"){
                    continue;
                }
                $buf = explode("_", $key);
                $result[(int)$buf[1]][$buf[0]] = $value;
            }
        }
        $keys = [];
        usort($result, 'RouterAuth::srt');
        for ($i = 1; $i < (count($result) - 1); $i++){
            $dCur = $result[$i]["mac"];
            $dBef = $result[$i-1]["mac"];
            $dNext = $result[$i+1]["mac"];
            $tCur = $result[$i]["authtype"];
            $tBef = $result[$i-1]["authtype"];
            $tNext = $result[$i+1]["authtype"];
//            echo $tCur. " ". $tBef. " ". $tNext. "\n";
            if (($dCur == $dBef)&&($dCur == $dNext) && ((($tCur == $tBef) && ($tNext == $tCur)) || ($typeFlag))){
                $keys[] = $i;
            }
        }
        foreach($keys as $key){
            unset($result[$key]);
        }
        
        return array_values($result);
        
    }
    
    
    
    /*--------------------------------------------------*/
    
    
    
    static public function getByDnum(
            $dnum,
            $count
    ){
        
        $path = "/authlog/byLogin/".$dnum. "/";
        $br = array_keys(objLoadBranch($path, true, false));
        if (count($br) == 0){
            throw new Exception("Auth not found");
        }
        $result = [];
        rsort($br);
        $days = 0;
        foreach($br as $value){
            $obj = objLoad($path. $value);
            $days++;
            if ($days >= $count){
                break;
            }
            foreach($obj as $key => $value){
                if ($key == "#e"){
                    continue;
                }
                $buf = explode("_", $key);
                $result[(int)$buf[1]][$buf[0]] = $value;
            }
        }
        $keys = [];
        usort($result, 'RouterAuth::srt');
        for ($i = 1; $i < (count($result) - 1); $i++){
            $dCur = $result[$i]["mac"];
            $dBef = $result[$i-1]["mac"];
            $dNext = $result[$i+1]["mac"];
            
//            echo $tCur. " ". $tBef. " ". $tNext. "\n";
            if (($dCur == $dBef)&&($dCur == $dNext)){
                $keys[] = $i;
            }
        }
        foreach($keys as $key){
            unset($result[$key]);
        }
        
        return array_values($result);
        
    }
    
    
    
    
    
    
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    /*--------------------------------------------------*/
    
    
    
    
    
    
    
    
    static public function getSomeByMac(
            $mac,
            $timeStamp,
            $typeFlag = true
    ){
        $buf = \RouterBase::getValidMac($mac);
        if (strlen($buf) != 12){
            throw new Exception("Wrong mac {$buf}");
        }
        $mac = "";
        for ($i = 0; $i < 12; $i++){
            if (($i % 2 == 0) && ($i > 0)){
                $mac .= ":";
            }
            $mac .= $buf[$i];
        }
//        echo microtime(). " 3\n";
        $path = "/authlog/byMAC/".$mac. "/";
        $br = array_keys(objLoadBranch($path, true, false));
        if (count($br) == 0){
            throw new Exception("Auth not found");
        }
        $time = microtime(true);
        echo " [". count($br). "] files count\n";
        $result = [];
        $count = 0;
        
        /*--------------------------------------------------*/
        
        $objList = [];
        
        foreach($br as $v){
            
            $buf = objLoad($path. $v);
            if ((int)$buf[time] < (int)$timeStamp){
                $objList[] = $buf;
            }
        }    
        foreach($objList as $obj){
            foreach($obj as $key => $value){
                if ($key == "#e"){
                    continue;
                }
                $count++;
                $buf = explode("_", $key);
                $result[(int)$buf[1]][$buf[0]] = $value;
            }
            unset($obj,$buf,$key,$value);
        }
        
        
        
        /*--------------------------------------------------*/
        unset($br, $v, $objList);
        echo " [". $count. "] auth count\n";
        echo (microtime(true) - $time). " - time\n";
        echo (memory_get_peak_usage() / 1024). " - peak memory\n";
        echo (memory_get_usage() / 1024). " - memory\n";
        $keys = [];
        usort($result, 'RouterAuth::srt');
        $result = array_values($result);
        
        for ($i = 1; $i < (count($result) - 1); $i++){
            $dCur = $result[$i]["login"];
            $dBef = $result[$i-1]["login"];
            $dNext = $result[$i+1]["login"];
            $tCur = $result[$i]["authtype"];
            $tBef = $result[$i-1]["authtype"];
            $tNext = $result[$i+1]["authtype"];
//            echo $tCur. " ". $tBef. " ". $tNext. "\n";
            if (($dCur == null) || ($result[$i]["time"] == null)){
                $keys[] = $i;
            } else
            if ((($dCur == $dBef)&&($dCur == $dNext) && ((($tCur == $tBef) && ($tNext == $tCur)) || ($typeFlag))) ){
                
                $keys[] = $i;
            }
    
        }
        unset($i,$dCur,$dNext,$dBef,$tBef,$tCur,$tNext);
        
        if (($result[count($result) - 1]["login"] == null) || ($result[count($result) - 1]["time"] == null)){
            $keys[] = $i;
        }
        
        
        foreach($keys as $key){
            unset($result[$key]);
        }
//        echo microtime(). " 6\n";
        
        return  array_values($result);
        
        
    }
    
    
    
    
    
    /*--------------------------------------------------*/
    
    
    
    static public function getSomeIntstall(
            $mac,
            $timeStamp
    ){
//        echo microtime(). " 2\n";
        $auth = self::getAllByMac($mac,$timeStamp);
        $keys = [];
        foreach($auth as $key => $value){
            if (!$value["time"]){
                $keys[]  = $key;
            }
        }
        foreach($keys as $key){
            unset($auth[$key]);
        }
        $auth = array_values($auth);
        unset($keys);
//        print_r($auth);
        $auth = array_reverse($auth);
//        echo microtime(). " 4\n";
//        echo "----1--------------------------------------------------\n";
//        print_r($auth);
        $auth = self::removeOne($auth);
//        echo microtime(). " 5\n";
//        echo "----2--------------------------------------------------\n";
//        print_r($auth);
        $auth = self::removeSame($auth);
//        echo microtime(). " 6\n";
//        echo "----3--------------------------------------------------\n";
//        print_r($auth);
        $auth = self::removeShort($auth);
//        echo "----4--------------------------------------------------\n";
//        print_r($auth);
//        echo microtime(). " 7\n";
        $result = [];
        $dCur = $auth[0];
        $buf = [];
        $buf["timeStamp"] = $dCur["time"];
        $buf["state"] = "installed";
        $buf["dnum"] = $dCur["login"];
        $buf["author"] = "SYSTEM";
        $result[] = $buf;
        
        for ($i = 1; $i < count($auth); $i++){
            $dCur = $auth[$i];
            $dNext = $auth[$i+1];
            if ($dCur["login"] == $dNext["login"]){
                
                $buf = [];
                $buf["timeStamp"] = $dCur["time"]-10;
                $buf["state"] = "store";
                $buf["store"] = "store";
                $buf["storeMan"] = "storeMan";
                $buf["author"] = "SYSTEM";
                $result[] = $buf;
                
                $buf = [];
                $buf["timeStamp"] = $dCur["time"];
                $buf["state"] = "installed";
                $buf["dnum"] = $dCur["login"];
                $buf["author"] = "SYSTEM";
                $result[] = $buf;
//                if ($i+1 < count($auth) - 1){
//                    $buf = [];
//                    $buf["timeStamp"] = $dCur["time"];
//                    $buf["state"] = "store";
//                    $buf["store"] = "store";
//                    $buf["storeMan"] = "storeMan";
//                    $buf["author"] = "SYSTEM";
//                    $result[] = $buf;
//                }
            }
        }
//        print_r($result);
//        echo microtime(). " 8\n";
        return $result;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}


//$result = "none";
//$type = readline("Type: ");
//if ($type == "mac"){
//    $mac = readline("MAC: ");
//    $result = \RouterAuth::getLastByMac($mac);
//}
//else if ($type == "dnum"){
//    $dnum = readline("Dnum: ");
//    $result = \RouterAuth::getLastByDnum($dnum);
//}
//else if ($type == "both"){
//    $dnum = readline("Dnum: ");
//    $mac = readline("MAC: ");
//    $result = \RouterAuth::getLastByBoth($mac, $dnum);
//}
//print_r($result);
