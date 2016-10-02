<?php
error_reporting(0);
require('config.php'); //sua file ket noi databesrle cua ban tai day
date_default_timezone_set('Asia/Ho_Chi_Minh');
$pin = $_POST['pin'];
$seri = $_POST['seri'];
$type = $_POST['type'];
$azkey = 'a67bcfb186e21ef327e826a7eee26900'; //ma AZKey do gatepay cap . sau khi tich hop hay edit ma nay de tien dc ve tai khoan cua ban tai gatepay.vn
$str_data='azkey='.$azkey.'&seri='.$seri.'&pin='.$pin.'&type='.$type;
$getCode=execPostRequest('http://api.gatepay.vn/card/explode/', $str_data);
function execPostRequest($url,$data){
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($ch);
curl_close($ch);
return $result;
}
$lay= str_replace('-','', $getCode);
if($lay == 4){
$hienthi = 'Sai AZKey';
}elseif($lay == 1){
$hienthi = 'Thông tin thẻ nạp sai';
}elseif($lay == 2){
$hienthi = 'Chưa nhập Pin hoặc Seri';
}elseif($lay >= 10000){
$menhgia = $lay; //so tien menh gia the nap
$loaithe = ($type == VTT ? 'VIETTE	ONE':NULL).($type == VMS ? 'MOBIFONE':NULL).($type == VNM ? 'Vietnamobile':NULL).($type == FTP ? 'Gate':NULL).($type == VTC ? 'Vicoin':NULL); //loai the
$hienthi = 'Nạp thành công:
Loại thẻ : '.$loaithe.'
Mệnh giá: '.$menhgia.' VNĐ';
//----cong tien cho user----//
@mysql_query("UPDATE users SET `balans`=`balans`+'$menhgia' WHERE `id`='{$user_id}'"); //su thanh thong tin cong tien cua ban vao sql tai day
}elseif($lay == 3){
$hienthi = 'Thẻ nạp đã sử dụng'; }
echo '<div class="bmenu">Nạp Card</div><form action="?" method="post" >'.(!empty($_POST['pin']) ? '<div class="head">Tin trả về:</div>
<div class="list2">'.trim(nl2br($hienthi)).'</div>':NULL).'<div class="list1">
<br>Mã Thẻ: <br><input type="text" name="pin" size="22" value="">
<br>Seri: <br><input type="text" name="seri" size="22" value=""><br>Loại Thẻ:<br><select name="type"><option value="VTT">Viettel</option><option value="VNP">Vinaphone</option><option value="VMS">Mobifone</option><option value="VNM">Vietnamobile</option><option value="FPT">FPT Gate</option><option value="VTC">VTC Vcoin</option></select><br><input type="submit" value="Nạp"></form></div>';
?>
