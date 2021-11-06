<?php

function distance($lat_1, $lon_1, $lat_2, $lon_2) {

    $radius_earth = 6371; // Радиус Земли

    $lat_1 = deg2rad($lat_1);
    $lon_1 = deg2rad($lon_1);
    $lat_2 = deg2rad($lat_2);
    $lon_2 = deg2rad($lon_2);

    $d = 2 * $radius_earth * asin(sqrt(sin(($lat_2 - $lat_1) / 2) ** 2 + cos($lat_1) * cos($lat_2) * sin(($lon_2 - $lon_1) / 2) ** 2));
//
    return number_format($d, 2, '.', '');
}

function count_peoples($avl_id,$lng,$lat) {
    
    $colPeoplesSportObject = 0;
    
    // узнаём радиус в километрах
    $r = db_query("SELECT km FROM mos_availability WHERE id=".$avl_id." LIMIT 1");
    
    if ($r == false)
      return $colPeoplesSportObject;
    
    // достаём все дома с координатами
    $houses = db_query("SELECT 
    id,
    area_residential,
    lng, 
    lat 
    FROM mos_realty 
    WHERE lng!=0 
    AND area_residential!=0");
    
    foreach($houses as $val) {
            
       $distance = distance($lat, $lng, $val['lat'], $val['lng']);
            
       if ($distance <= $r[0]['km']) {
         $colPeoplesHouse = $val['area_residential'] / 18.5;
         $colPeoplesSportObject += $colPeoplesHouse;
       }
    }
    
    $colPeoplesSportObject = round($colPeoplesSportObject);
    return $colPeoplesSportObject;
    
}

function target_peoples($popular,$peoples) {
    $target = $popular * $peoples;
    $target = round($target / 100000);
    return $target;
}


function mb_ucfirst($str, $encoding='UTF-8') {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
}

function rus2translit($string) {

    $converter = array(

        'а' => 'a',   'б' => 'b',   'в' => 'v',

        'г' => 'g',   'д' => 'd',   'е' => 'e',

        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',

        'и' => 'i',   'й' => 'y',   'к' => 'k',

        'л' => 'l',   'м' => 'm',   'н' => 'n',

        'о' => 'o',   'п' => 'p',   'р' => 'r',

        'с' => 's',   'т' => 't',   'у' => 'u',

        'ф' => 'f',   'х' => 'h',   'ц' => 'c',

        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',

        'ь' => '',    'ы' => 'y',   'ъ' => '',

        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        
        'А' => 'a',   'Б' => 'b',   'В' => 'v',

        'Г' => 'g',   'Д' => 'd',   'Е' => 'e',

        'Ё' => 'e',   'Ж' => 'zh',  'З' => 'z',

        'И' => 'i',   'Й' => 'y',   'К' => 'k',

        'Л' => 'l',   'М' => 'm',   'Н' => 'n',

        'О' => 'o',   'П' => 'p',   'Р' => 'r',

        'С' => 's',   'Т' => 't',   'У' => 'u',

        'Ф' => 'f',   'Х' => 'h',   'Ц' => 'c',

        'Ч' => 'ch',  'Ш' => 'sh',  'Щ' => 'sch',

        'Ь' => '',    'Ы' => 'y',   'Ъ' => '',

        'Э' => 'e',   'Ю' => 'yu',  'Я' => 'ya'

    );

    return strtr($string, $converter);

}

function save_document($filename, $tmpname, $name, $uploaddir) {
    $blacklist = array(
        ".php",
        ".phtml",
        ".php3",
        ".php4",
        ".html",
        ".htm",
        ".js",
        ".inc",
        ".sql",
        ".exe");
    foreach ($blacklist as $item) {
        if (preg_match("/$item\$/i", $filename))
            return 'Недопустимый формат документа';
    }

    $uploadfile = $_SERVER['DOCUMENT_ROOT'] . "/" . $uploaddir . $name;
    move_uploaded_file($tmpname, $uploadfile);

    if (!file_exists($uploadfile))
        return 'Не удалось загрузить файл на сервер';

    return true;
}

function get_filename($address) {
    $filename = $address;
    $filename = mb_strtolower($filename);  
    $filename = preg_replace("/\,/","-",$filename);
    $filename = preg_replace("/[^А-Яа-яA-Za-zЁё0-9\s]/"," ",$filename);
    $filename = preg_replace('/\s+/',' ',$filename);
    $filename = rus2translit($filename);
    //$filename = strtr($filename, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
    $filename = str_replace(" ","-",trim( $filename ) );
    $filename = preg_replace('#(-|-\s){2,}#','\1', $filename); 
    
    return $filename;    
}

function vk_msg_send($peer_id,$text){
    
    global $xc;
    
	$request_params = array(
		'message' => $text, 
		'peer_id' => $peer_id, 
		'access_token' => $xc['bot_key_vk'],
		'v' => '5.131' 
	);
	$get_params = http_build_query($request_params); 
	file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);
}

function transformDate($date,$type,$timezone=null) {
    
    $result = false;
    
    if ($type == 'datetime') {
        
        $d = explode(' ',$date);
        $t = explode(':',$d[1]);
        
        $result = date('d.m.Y',strtotime($d[0])).' в '.$t[0].':'.$t[1];
    }
    
    if ($type == 'date') {
        $d = explode(' ',$date);
        $result = date('d.m.Y',strtotime($d[0]));
    }
    
    return $result;
}

function geocoder($address)
{    
    $addr = urlencode($address);
    
    $res = file_get_contents('https://geocode-maps.yandex.ru/1.x/?geocode=' . $addr.'&apikey=4155716f-40bb-470c-8e92-260c288e704e');
    $parse = simplexml_load_string($res);
    $pos = $parse->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
    if (!empty($pos)) {
        $lt = explode(' ', $pos);
        $lng = $lt[0];
        $lat = $lt[1];
        return array($lng, $lat);
    }

    return false;
}





function clearData($data, $type = 'str')
{
    
    global $xc;

    switch ($type) {

        case 'str':
            return mysqli_real_escape_string( $xc['db'], trim(stripslashes(strip_tags($data))));
            break;

        case 'list':
            return preg_replace('/[^0-9\|]/', '', $data);
            break;

        case 'int':
            return intval($data);
            break;

        case 'get':
            return preg_replace('/[^a-zA-Z0-9-_]/', '', $data);
            break;

        case 'guid':
            return preg_replace('/[^a-z0-9-]/', '', $data);
            break;

        case 'str2':
            return trim(stripslashes(strip_tags($data)));
            break;

        case 'tiny':
            return stripslashes($data);
            break;

        case 'area':
            return preg_replace('/[^0-9\.]/', '', str_replace(',', '.', $data));
            break;

        case 'areaDoc':
            $a = preg_replace('/[^0-9\.]/', '', str_replace(',', '.', $data));
            $a = round($a, 1);
            $a = str_replace('.', ',', $a);
            return $a;
            break;

        case 'date':
            return preg_replace('/[^0-9-]/', '', $data);
            break;

        case 'domain':
            return preg_replace('/[^a-z0-9-\.]/', '', $data);
            break;

        case 'date2':
            if (strlen(preg_replace('/[^0-9\.]/', '', $data)) == 10) {
                $t = explode('.', $data);
                return array(
                    $t[2] . '-' . $t[1] . '-' . $t[0],
                    $t[2],
                    intval($t[1]));
            }
            return false;
            break;

        case 'phone':
            return preg_replace('/[^0-9]/', '', $data);
            break;
    }
}

function compress($buffer)
{
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    $buffer = preg_replace('/\/\/.*\r\n/', '', $buffer);
    $buffer = str_replace(array(
        "\r\n",
        "\r",
        "\n",
        "\t",
        '  ',
        '    ',
        '    '), '', $buffer);
    return $buffer;
}

function combine($format, $ver, $name, $adm, $domain)
{
    //if ($format == 'js')
        //$j = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $format . '/jquery.' .$format);
    $s = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $domain . $adm . '/' . $format .'/main.' . $format);
    //if (empty($s)) return false;
    if ($handle = opendir($_SERVER['DOCUMENT_ROOT'] . $domain . '/modules')) {
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $f = $_SERVER['DOCUMENT_ROOT'] . $domain . '/modules/' . $file . $adm . '/' . $format .
                    '/' . $name . '.' . $format;
                if (file_exists($f) && @filesize($f) > 0)
                    $s .= file_get_contents($f);

                if ($h = @opendir($_SERVER['DOCUMENT_ROOT'] . $domain . '/modules/' . $file . $adm .
                    '/components')) {
                    while (false !== ($com = readdir($h))) {
                        if ($com != '.' && $com != '..') {
                            $y = $_SERVER['DOCUMENT_ROOT'] . $domain . '/modules/' . $file . $adm .
                                '/components/' . $com . '/' . $format . '/' . $name . '.' . $format;
                            if (file_exists($y) && @filesize($y) > 0)
                                $s .= file_get_contents($y);
                        }
                    }
                    closedir($h);
                }
            }
        }
        closedir($handle);
    }

    $s = compress($s);
    
    $s = mb_convert_encoding($s, 'UTF-8', 'auto');
    
    if ($format == 'js')
        $s = $j . '$(document).ready(function(){' . $s . '});';
        
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . $domain . $adm . '/' . $format .'/' . $name . $ver . '.' . $format, $s);
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . $domain . $adm . '/' . $format . '/' . $format . '.txt', $ver);
    return $name . $ver . '.' . $format;
}

function get_file($format, $ver, $update, $admin = false, $domain = null)
{
    if ($format == 'css')
        $name = 'style';
    else
        $name = 'scripts';

    $adm = null;
    if ($admin == true)
        $adm = '/admin';

    $v = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $domain . $adm . '/' . $format .
        '/' . $format . '.txt');

    if (($ver == 0 && $v == 0) || $update == true) {
        $s = combine($format, $ver, $name, $adm, $domain);
        return $s;
    }

    if ($update == false) {
        if ($ver != $v) {
            $s = combine($format, $ver, $name, $adm, $domain);
            if ($s !== false) {
                @unlink($_SERVER['DOCUMENT_ROOT'] . $adm . '/' . $format . '/' . $name . $v . '.' . $format);
                return $s;
            }
        }
    }

    return $name . $v . '.' . $format;
}

function encrypt_pass($pass)
{
    if ($pass != '') {
        $pass = sha1(md5(trim($pass)));
        $pass = md5($pass . 'r435r54f4r3ffsassssef');
        return $pass;
    }
    
    return false;
}

function is_mobile($agent)
{
    $result = false;
    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',
        $agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
        substr($agent, 0, 4)))
        $result = true;

    return $result;
}


function get_hash($login)
{
    $a = sha1(md5(time() . $login . mt_rand(1, 100000)));
    return $a;
}

function popup_window($html, $width = 400, $height = 200, $zIndex = 5500)
{

    if (empty($html))
        return false;

    // определяем есть ли в переменной html тэги
    $h = strip_tags($html);

    // если тэгов нет, то нужно задать стили текста
    if ($h == $html) {

        if (MOBILE == true)
            $html = '<div class="regSuccessMessageDiv">' . $html . '</div>';

        else
            $html = '<div class="regSuccessMessageDiv">' . $html . '</div>';
    }

    $zIndex = intval($zIndex);

    $arr = array(
        0 => 'popup',
        1 => $html,
        2 => $width,
        3 => $height,
        4 => $zIndex);

    $arr = json_encode($arr);

    return $arr;
}

function check_email($email)
{

    $email = clearData($email);
    $email = mb_strtolower($email, 'utf-8');
    $result = $email;

    if (!preg_match("/^[a-z0-9\.\-_]+@[a-z0-9\-]{2,20}\.([a-z0-9\-]+\.)*?[a-z]{2,25}$/is",$email))
        $result = false;

    return $result;
}

function format_phone($phone = '', $convert = false, $trim = true)
{
    // If we have not entered a phone number just return empty
    if (empty($phone)) {
        return '';
    }

    // Strip out any extra characters that we do not need only keep letters and numbers
    $phone = preg_replace("/[^0-9A-Za-z]/", "", $phone);

    // Do we want to convert phone numbers with letters to their number equivalent?
    // Samples are: 1-800-TERMINIX, 1-800-FLOWERS, 1-800-Petmeds
    if ($convert == true) {
        $replace = array(
            '2' => array(
                'a',
                'b',
                'c'),
            '3' => array(
                'd',
                'e',
                'f'),
            '4' => array(
                'g',
                'h',
                'i'),
            '5' => array(
                'j',
                'k',
                'l'),
            '6' => array(
                'm',
                'n',
                'o'),
            '7' => array(
                'p',
                'q',
                'r',
                's'),
            '8' => array(
                't',
                'u',
                'v'),
            '9' => array(
                'w',
                'x',
                'y',
                'z'));

        // Replace each letter with a number
        // Notice this is case insensitive with the str_ireplace instead of str_replace
        foreach ($replace as $digit => $letters) {
            $phone = str_ireplace($letters, $digit, $phone);
        }
    }

    // If we have a number longer than 11 digits cut the string down to only 11
    // This is also only ran if we want to limit only to 11 characters
    if ($trim == true && strlen($phone) > 11) {
        $phone = substr($phone, 0, 11);
    }

    // Perform phone number formatting here
    if (strlen($phone) == 7) {
        return preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "$1-$2", $phone);
    } elseif (strlen($phone) == 10) {
        return preg_replace("/([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "($1) $2-$3",
            $phone);
    } elseif (strlen($phone) == 11) {
        return preg_replace("/([0-9a-zA-Z]{1})([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/",
            "$1 ($2) $3-$4", $phone);
    }

    // Return original phone if not 7, 10 or 11 digits long
    return $phone;
}

function formatNumber($tmp)
{
    $lasttmp = $tmp[strlen($tmp) - 1];
    while ($lasttmp == '0') {
        $tmp = substr($tmp, 0, strlen($tmp) - 1);
        $lasttmp = $tmp[strlen($tmp) - 1];
    }
    if (($lasttmp == '.') || ($lasttmp == ','))
        $tmp = substr($tmp, 0, strlen($tmp) - 1);
    return $tmp;
}

function generatePass($login, $col)
{
    $login = $login . mt_rand(1, 1000000) . time();
    $pass = sha1(md5(md5($login)));
    $pass = substr($pass, 0, 10);
    $pass = md5($pass);
    $pass = substr($pass, 3, $col);
    return $pass;
}


function send_email($email, $subject, $message)
{
    require_once($_SERVER['DOCUMENT_ROOT'].'/modules/phpmailer/PHPMailerAutoload.php');
    
    $name_from = 'NadoCRM.ru';
	$email_from = 'mail@nadocrm.ru';                          
	$name_to = $email;

	$html = TRUE;
	$mail = new PHPMailer;
    $mail->isSMTP(); 
	$mail->Host = 'smtp.yandex.ru';
	$mail->SMTPAuth = true;
	$mail->SMTPAutoTLS = false;                                          
    $mail->Username = 'mail@nadocrm.ru';
	$mail->Password = 'nadocrm123456'; 
	$mail->SMTPSecure = 'ssl';
	$mail->Port = 465;
	$mail->SMTPDebug = 3;  
	$mail->From = $email_from;
	$mail->FromName = $name_from;
	$mail->addAddress($email, $name_to);
	$mail->isHTML(true);
	$mail->Subject = $subject;
	$mail->Body = $body;        

    if($mail->send()) {
     
	} else {
        $headers = null;
        $headers .= "Content-Type: text/html; charset=windows-1251\r\n";
        $headers .= "From: " . $name_from . " <" . $email_from . ">\r\n";
        $headers .= "Reply-To: <" . $email . ">\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    
        mail($email, $subject, $message, $headers);	   
	}
    

}

function monthFormatPrint($month, $return = 1)
{
    $month_a = array(
        "01",
        "02",
        "03",
        "04",
        "05",
        "06",
        "07",
        "08",
        "09",
        "10",
        "11",
        "12");
    $month_names_sh_a = array(
        "янв",
        "фев",
        "мар",
        "апр",
        "май",
        "июн",
        "июл",
        "авг",
        "сен",
        "окт",
        "ноя",
        "дек");
    $month_names_full_a = array(
        "января",
        "февраля",
        "марта",
        "апреля",
        "мая",
        "июня",
        "июля",
        "августа",
        "сентября",
        "октября",
        "ноября",
        "декабря");
    if ($return == 1)
        return $month_names_sh_a[array_search($month, $month_a)];
    elseif ($return == 2)
        return $month_names_full_a[array_search($month, $month_a)];
}

function dateFormatPrint($datetime, $return = 1)
{

    $day = date('d', strtotime($datetime));
    $month = date('m', strtotime($datetime));
    $year = date('Y', strtotime($datetime));

    if ($return == 1) {
        $disp_date = $day . " " . monthFormatPrint($month) . " " . $year . " в " . date('H:i',
            strtotime($datetime));

    } elseif ($return == 2) {
        $disp_date = $day . "." . $month . "." . $year . ", " . date('H:i', strtotime($datetime));

    } elseif ($return == 3) {
        $disp_date = $day . " " . monthFormatPrint($month, 2) . " " . $year;

    } elseif ($return == 4) {
        $disp_date = monthFormatPrint($month, 1) . " " . $year;

    }

    return $disp_date;
}

function save_img($oldname, $tmpname, $name, $format, $uploaddir, $width, $preview = null)
{

    if (!preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)|(gif)|(GIF)|(png)|(PNG)$/', $oldname))
        return 'Допускаются только файлы формата jpg, gif и png';

    $filename = $oldname;
    $uploadfile = $_SERVER['DOCUMENT_ROOT'] . "/img/time-pictures/" . $filename;

    move_uploaded_file($tmpname, $uploadfile);

    if (!file_exists($uploadfile))
        return 'Не удалось загрузить файл на сервер';

    $size = getimagesize($uploadfile);
    $new_name = $name . "." . $format;

    if ($size['mime'] == 'image/png')
        $im = imagecreatefrompng($uploadfile);

    if ($size['mime'] == 'image/jpeg')
        $im = imagecreatefromjpeg($uploadfile);

    if ($size['mime'] == 'image/gif')
        $im = imagecreatefromgif($uploadfile);

    if ($size[0] > $width)
        $height = floor($size[1] * ($width / $size[0]));

    else {
        $width = $size[0];
        $height = $size[1];
    }

    $new_image = imagecreatetruecolor($width, $height);
    imagealphablending($new_image, false);
    imagesavealpha($new_image, true);
    imagecopyresampled($new_image, $im, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);

    if ($format == 'jpg')
        imagejpeg($new_image, $_SERVER['DOCUMENT_ROOT'] . "/" . $uploaddir . $new_name);

    else
        imagepng($new_image, $_SERVER['DOCUMENT_ROOT'] . "/" . $uploaddir . $new_name);

    // создание preview картинки
    if (!empty($preview)) {
        $width = intval($preview);
        $height = floor($size[1] * ($width / $size[0]));

        $new_image = imagecreatetruecolor($width, $height);
        imagealphablending($new_image, false);
        imagesavealpha($new_image, true);
        imagecopyresampled($new_image, $im, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
        imagejpeg($new_image, $_SERVER['DOCUMENT_ROOT'] . "/" . $uploaddir . 'preview-' .
            $name . '.jpg');

    }
    // --------------------------------------------------------------------------------
    unlink($uploadfile);

    return true;
}

function callbackFunction($arr) {
    $arr = json_encode($arr);
    return $arr;
}

// постраничная навигация
function pagination($num, $col, $page, $form)
{   
    $nav = null;
    $total = (($col - 1) / $num) + 1;
    $total = intval($total);
    
    if ($page > $total)
      $page = $total;

    $thisPage = '<li class="paginate_button page-item active">
    <a href="#" aria-controls="datatable-buttons" data-page="'.$page.'" tabindex="0" class="page-link">'.$page.'</a>
    </li>';

    if ($page >= 2) {
        
        $pervpage = '<li class="paginate_button page-item previous" id="datatable-buttons_previous">
        <a href="#" aria-controls="datatable-buttons" data-page="'.($page - 1).'" data-form="'.$form.'" tabindex="0" class="page-link jsAjaxNav">Назад</a>
        </li>
        <li class="paginate_button page-item">
        <a href="#" aria-controls="datatable-buttons" data-page="1" data-form="'.$form.'" tabindex="0" class="page-link jsAjaxNav">1</a>
        </li>
        ';
        
    } else {
        $pervpage = '<li class="paginate_button page-item previous disabled" id="datatable-buttons_previous">
        <a href="#" aria-controls="datatable-buttons" data-page="1" tabindex="0" class="page-link">Назад</a>
        </li>
        <li class="paginate_button page-item active">
        <a href="#" aria-controls="datatable-buttons" data-page="1" tabindex="0" class="page-link">1</a>
        </li>';
        $thisPage = '';
    }
        
        
    if ($page != $total) {
        
        $nextpage = '<li class="paginate_button page-item">
        <a href="#" aria-controls="datatable-buttons" data-page="'.$total.'" data-form="'.$form.'" tabindex="0" class="page-link jsAjaxNav">'.$total.'</a>
        </li>
        <li class="paginate_button page-item next" id="datatable-buttons_next">
        <a href="#" aria-controls="datatable-buttons" data-page="'.($page + 1).'" data-form="'.$form.'" tabindex="0" class="page-link jsAjaxNav">Далее</a>
        </li>';
        
    }
            
    else {
        
      $nextpage = '<li class="paginate_button page-item active">
      <a href="#" aria-controls="datatable-buttons" data-page="'.$total.'" tabindex="0" class="page-link">'.$total.'</a>
      </li>
      <li class="paginate_button page-item next disabled" id="datatable-buttons_next">
      <a href="#" aria-controls="datatable-buttons" data-page="'.($page + 1).'" tabindex="0" class="page-link">Далее</a>
      </li>';  
        
      $thisPage = '';
    }

    for ($i = 1; $i <= 3; $i++) {
      if ($page - $i > 1) {
        $pageNav[$i] = '<li class="paginate_button page-item">
        <a href="#" aria-controls="datatable-buttons" data-page="'.($page - $i).'" data-form="'.$form.'" tabindex="0" class="page-link jsAjaxNav">'.($page - $i).'</a>
        </li>';
      }
      
      if ($page + $i < $total) {
        $pageNav2[$i] = '<li class="paginate_button page-item ">
        <a href="#" aria-controls="datatable-buttons" data-page="'.($page + $i).'" data-form="'.$form.'" tabindex="0" class="page-link jsAjaxNav">'.($page + $i).'</a>
        </li>';
      }
                
    }

    if ($total > 1) {
        Error_Reporting(E_ALL & ~ E_NOTICE);
        $list = $pervpage . $pageNav[3] . $pageNav[2] . $pageNav[1] . $thisPage . $pageNav2[1] .$pageNav2[2] . $pageNav2[3] . $nextpage;
        
        ob_start();
        require $_SERVER['DOCUMENT_ROOT'].'/modules/data/components/component/includes/pagination.inc.php';
        $nav = ob_get_clean();
    }

    return $nav;
}

function lang_function($num, $word, $result = 1) {
    $num_1_a = array(1);
    $num_2_a = array(
        2,
        3,
        4);
    $words_a = array(
        'заявка' => array(
            'заявка',
            'заявки',
            'заявок'),
        'реферал' => array(
            'реферал',
            'реферала',
            'рефералов'),
        'балл' => array(
            'балл',
            'балла',
            'баллов'),
        'участник' => array(
            'участник',
            'участника',
            'участников'),
        'клиент' => array(
            'клиент',
            'клиента',
            'клиентов'),
        'сотрудник' => array(
            'сотрудник',
            'сотрудника',
            'сотрудников'),
        'день' => array(
            'день',
            'дня',
            'дней'),
        'найден' => array(
            'найден',
            'найдено',
            'найдено'),
        'год' => array(
            'год',
            'года',
            'лет'));
    if ($result == 1) {
        if (substr($num, -2, 2) > 10 and substr($num, -2, 2) < 20) {
            return $num . ' ' . $words_a[$word][2];
        } else {
            if (in_array(substr($num, -1), $num_1_a))
                return $num . ' ' . $words_a[$word][0];
            elseif (in_array(substr($num, -1), $num_2_a))
                return $num . ' ' . $words_a[$word][1];
            else
                return $num . ' ' . $words_a[$word][2];
        }
    } elseif ($result == 2) {
        if (substr($num, -2, 2) > 10 and substr($num, -2, 2) < 20) {
            return $words_a[$word][2];
        } else {
            if (in_array(substr($num, -1), $num_1_a))
                return $words_a[$word][0];
            elseif (in_array(substr($num, -1), $num_2_a))
                return $words_a[$word][1];
            else
                return $words_a[$word][2];
        }
    }
}