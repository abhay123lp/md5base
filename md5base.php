<?php
session_start();
if(isset($_COOKIE['remember'])) {$try = new LoginPage();$try->AutoLogin();}

class HelpPage
{
	private $db;
	public function __construct()
	{
		$this->db = new MD5DB();
	}
	public function getHelpCategories() {
		$ret=array();
		$categories=$this->db->query("SELECT * FROM `help-categories` ORDER BY `id` ASC");
		$i = 0;
		foreach ($categories as $cat)
		{
			$ret[$i]['id'] = $cat['id'];$ret[$i]['name'] = $cat['name'];
			$i++;
		}
		return $ret;	
	}
	public function getHelpContents() {
		if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {$id = $this->db->clean($_GET['id']);} else {$id = 0;}
		$ret=array();
		$categories=$this->db->query("SELECT `help-contents`.*,`help-categories`.name FROM `help-contents` LEFT JOIN `help-categories` ON `help-contents`.category=`help-categories`.id WHERE `help-contents`.category='$id' ORDER BY id ASC");
		$i = 0;
		if($categories) {
		foreach ($categories as $cat)
		{
			$ret[$i]['name'] = $cat['name'];$ret[$i]['topic'] = $cat['topic'];$ret[$i]['content'] = $cat['content'];
			$i++;
		}
		return $ret;
		}
		else {
			$ret[0]['name'] = "Help content not found";$ret[0]['topic'] = "Error: Content not found";$ret[$i]['content'] = "Make sure you selected the correct topic and try again!";
			return $ret;
		}
	}
}


class BrowsePage
{
	private $db;
	public function __construct()
	{
		$this->db = new MD5DB();
	}
	
	private function getNumEntries()
	{
		$num_entries=$this->db->query("SELECT COUNT(hashes.hashvalue) FROM hashes");
		return $num_entries[0][0];
	}
	private function cleanGetValues($max=20)
	{
	$num_entries=$this->getNumEntries();
	$output = array();
	$alloworder=array("timestamp","hashvalue","filename","filesize");
	$output['alloworder']=$alloworder;
	$allowsort=array("ASC","DESC");
	$output['allowsort']=$allowsort;
	$num_pages=ceil($num_entries/$max);
	$output['num_pages']=$num_pages;
	if(isset($_GET['orderby']) && in_array($_GET['orderby'],$alloworder)) {$output['orderby']=$_GET['orderby'];} else {$output['orderby']="timestamp";}
	if(isset($_GET['sortby']) && in_array($_GET['sortby'],$allowsort)) {$output['sortby']=$_GET['sortby'];} else {$output['sortby']="ASC";}
	if(isset($_GET['pagenum']) && is_numeric($_GET['pagenum']) && $_GET['pagenum'] > 0 && $_GET['pagenum'] <= $num_pages) {$output['limit']=($_GET['pagenum']-1)*$max;$output['uplimit']=$max;$output['selected']=$_GET['pagenum'];} else {$output['limit']=0;$output['uplimit']=$max;$output['selected']=1;}
	return $output;
	}
	
	public function BrowseValues() {
	$params = $this->cleanGetValues();
	$result=$this->db->query("SELECT hashes.*, users.name, TIME(hashes.timestamp), DATE(hashes.timestamp) FROM hashes LEFT JOIN users ON hashes.addedby=users.id ORDER BY ".$params['orderby']." ".$params['sortby']." LIMIT ". $params['limit'].",".$params['uplimit']);
	$ret=array();
		foreach ($result as $hash)
		{
			$ret[] = new Hash($hash['hashvalue'], $hash['filename'], $hash['filesize'],
				$hash['comment'], $hash['TIME(hashes.timestamp)'],
				$hash['DATE(hashes.timestamp)'],$hash['user']);
		}
	return $ret;
	}
	
	
	public function getPageNumbers($which,$stack=3,$allow=15) {
	$params = $this->cleanGetValues();
	$html = '';
	if($params['num_pages'] > $allow) {
		if($which == 'first') {

			for($i=1;$i<=$stack;$i++) {
			if($i==$params['selected']) {$html .= '<a href="?pagenum='.$i.'&amp;orderby='.$params['orderby'].'&amp;sortby='.$params['sortby'].'" class="selected">'.$i.'</a> ';} else {$html .= '<a href="?pagenum='.$i.'&amp;orderby='.$params['orderby'].'&amp;sortby='.$params['sortby'].'">'.$i.'</a> ';}
			}
		}
		elseif($which == 'last') {
			for($i=$stack-1;$i>=0;$i--) {
			if($params['num_pages']-$i==$params['selected']) {$html .= '<a href="?pagenum='.($params['num_pages']-$i).'&amp;orderby='.$params['orderby'].'&amp;sortby='.$params['sortby'].'" class="selected">'.($params['num_pages']-$i).'</a> ';} else {$html .= '<a href="?pagenum='.($params['num_pages']-$i).'&amp;orderby='.$params['orderby'].'&amp;sortby='.$params['sortby'].'">'.($params['num_pages']-$i).'</a> ';}
			}			
		}
		else {
		$html .= ' ... ';
			for($i=-(floor($stack/2));$i<=floor($stack/2);$i++) {
			if(floor($params['num_pages']/2)+$i==$params['selected']) {$html .= '<a href="?pagenum='.(floor($params['num_pages']/2)+$i).'&amp;orderby='.$params['orderby'].'&amp;sortby='.$params['sortby'].'" class="selected">'.(floor($params['num_pages']/2)+$i).'</a> ';} else {$html .= '<a href="?pagenum='.(floor($params['num_pages']/2)+$i).'&amp;orderby='.$params['orderby'].'&amp;sortby='.$params['sortby'].'">'.(floor($params['num_pages']/2)+$i).'</a> ';}
			}
		$html .= ' ... ';	
		}
		}
	else {
		for($i=1;$i<=$params['num_pages'];$i++) {
			if($which == 'middle') {
			if($i==$params['selected']) {$html .= '<a href="?pagenum='.$i.'&amp;orderby='.$params['orderby'].'&amp;sortby='.$params['sortby'].'" class="selected">'.$i.'</a> ';} else {$html .= '<a href="?pagenum='.$i.'&amp;orderby='.$params['orderby'].'&amp;sortby='.$params['sortby'].'">'.$i.'</a> ';}
			}
			}
		}
	return $html;
	}
	
	public function tableSortHeading() {
	$params = $this->cleanGetValues();
	$alloworder_names=array("Added","Hash","File name","Size");
	$alloworder_classes=array("_ts","_hv","_fn","_fs");
	if($params['sortby']==$params['allowsort'][0]){$newsort = $params['allowsort'][1];} else {$newsort = $params['allowsort'][0];}
	$html .= '';
	foreach($params['alloworder'] as $key => $ordervalue) {
		if($params['orderby']==$ordervalue) {$html .= '<td class="browsephp_'.strtolower($newsort).$alloworder_classes[$key].'"><b><a href="?pagenum='.$params['selected'].'&amp;orderby='.$ordervalue.'&amp;sortby='.$newsort.'" class="browsephp">'.$alloworder_names[$key].'</a></b></td>';} else {$html .= '<td class="browsephp'.$alloworder_classes[$key].'"><b><a href="?pagenum='.$params['selected'].'&amp;orderby='.$ordervalue.'&amp;sortby='.$newsort.'" class="browsephp">'.$alloworder_names[$key].'</a></b></td>';}
	}
	return $html;
	}
}

class LoginPage
{
	private $db, $captcha, $message, $errors;
	public function __construct()
	{
		$this->db = new MD5DB();
		$this->captcha = new Captcha();
		if (isset($_POST['action']) && $_POST['action']=='Login')
		{
			if(isset($_POST['user']) && strlen($_POST['user']) > 0 && isset($_POST['pass']) && strlen($_POST['pass']) > 0) {
				
				$login=$this->db->query("SELECT users.name,users.id,users.temp FROM users WHERE users.name='".$this->db->clean($_POST['user'])."' AND users.password=SHA1('".$this->db->clean($_POST['pass'])."') AND users.activation=1");
				if($login) {
					if(isset($_POST['remember'])) {
						//autologin
						$value = $this->db->randomize(time());
						$remember=$this->db->query("UPDATE users SET users.temp=MD5('$value') WHERE users.name='".$this->db->clean($_POST['user'])."'");
						setcookie("remember", md5($value), time()+3600*24*14, "/");
						if(!$remember) {$message = '<div class="warning"><p>Failed to set AutoLogin!</p></div>';}
					}
				$_SESSION['user_name'] = $login[0]['name'];
				$_SESSION['user_id'] = $login[0]['id'];
				$message = '<div class="success"><p>Login succesful. <a href="login.php">Click here</a> to reload this page!</p></div> ';
				}
				else {
				$message = '<div class="warning"><p>Failed to log you in. Possible reasons:</p><ul><li>Did you activate your account yet? Click here to resend the activation code.</li><li>Did you spell your username and password correctly?</li><li>Is your Caps Lock on?</li></ul></div> ';
				}
			}
			else {
				$message = '<div class="warning"><p>Please enter valid data!</p></div> ';
			}
		}		
		else if (isset($_POST['action']) && $_POST['action']=='register')
		{
		
		}
		else if (isset($_POST['action']) && $_POST['action']=='activate')
		{
		
		}
		else if (isset($_GET['action']) && $_GET['action']=='Logout')	{
		$this->LogOut();
		$message = '<div class="success"><p>You have been successfuly logged out!</p></div>'; 
		}
		$this->message=$message;
	}
	public function getMessage() {return $this->message;}
	public function getErrors() {return $this->errors;}
	public function getCaptcha() {return $this->captcha->getCaptcha();}
	public function AutoLogin() {
		$login = $this->db->query("SELECT users.name,users.id FROM users WHERE users.temp='".$this->db->clean($_COOKIE['remember'])."'");
		if($login) {$_SESSION['user_name'] = $login[0]['name'];$_SESSION['user_id'] = $login[0]['id'];}
	return "SELECT users.name,users.id FROM users WHERE temp='".$this->db->clean($_COOKIE['remember'])."'";
	}
	public function LogOut() {
	if(isset($_COOKIE['remember'])) {setcookie("remember","",time()-3600,"/");}
	unset($_SESSION['user_name']);unset($_SESSION['user_id']);
	}
}

require_once('recaptchalib.php');
class Captcha
{
	private $privatekey, $publickey, $isvalid=-1, $error;
	public function __construct()
	{
		$fcontents=file("captcha.txt");
		$this->publickey=trim($fcontents[0]);
		$this->privatekey=trim($fcontents[1]);
	}
	public function getCaptcha()
	{
		return recaptcha_get_html($this->publickey);
	}
	public function Valid($ip, $challenge, $response)
	{
		if ($this->isvalid>=0) return $this->isvalid==1;
		if (isset($response))
		{
			$result=recaptcha_check_answer($this->privatekey, $ip, $challenge, $response);
			$this->isvalid=$result->is_valid==true;
			$this->error=$result->error; 
		}
		else
		{
			$this->isvalue=0;
			$this->error="Incorrect Captcha response.";
		}
		return $this->isvalid==1;
	}
	public function Error() {return $this->error;}
}

class MainPage
{
	private $db;
	public function __construct()
	{
		$this->db = new MD5DB();
	}
	public function getHallOfFame($num)
	{
		$num+=0; // zero nonnumeric input
		$ret=array();
		$topusers=$this->db->query("SELECT * from users ORDER BY nhashes DESC LIMIT $num");
		foreach ($topusers as $user)
		{
			$ret[] = new User($user['name'], $user['id'], $user['nhashes'], $user['email']);
		}
		return $ret;
	}
	public function getMostRecent($num)
	{
		$num+=0;
		$ret=array();
		$result=$this->db->query("SELECT hashes.*, users.name, TIME(hashes.timestamp), DATE(hashes.timestamp) FROM hashes LEFT JOIN users ON hashes.addedby=users.id ORDER BY timestamp LIMIT $num");
		foreach ($result as $hash)
		{
			$ret[] = new Hash($hash['hashvalue'], $hash['filename'], $hash['filesize'],
				$hash['comment'], $hash['TIME(hashes.timestamp)'],
				$hash['DATE(hashes.timestamp)'],$hash['name']);
		}
		return $ret;
	}
}

class User
{
	private $name, $id, $nhashes, $email;
	public function __construct($name, $id, $nhashes, $email)
	{
		$this->name=$name;
		$this->id=$id;
		$this->nhashes=$nhashes;
		$this->email=$email;
	}
	public function getName() {return $this->name;}
	public function getID() {return $this->id;}
	public function getNHashes() {return $this->nhashes;}
	public function getEmail() {return $this->email;}
}

class Hash
{
	private $value, $filename, $filesize, $comment, $time, $date, $user;
	public function __construct($value, $filename, $filesize, $comment, $time, $date, $user)
	{
		$this->value=$value;
		$this->filename=$filename;
		$this->filesize=$filesize;
		$this->comment=$comment;
		$this->time=$time;
		$this->date=$date;
		$this->user=$user;
	}
	public function getValue() {return $this->value;}
	public function getFilename() {return $this->filename;}
	public function getFilesize($arg) {
		if(isset($arg) && $arg=="human") {
		$size = $this->filesize;
   		$size_names = array('B','KB','MB','GB','TB','PB');
    		for ($i=0;$size>1024 && isset($size_names[$i+1]);$i++) {$size /= 1024;}
		return round($size,2).' '.$size_names[$i];
		}
		else {
		return $this->filesize;
		}
	}
	public function getComment() {return $this->comment;}
	public function getTime() {return $this->time;}
	public function getDate() {return $this->date;}
	public function getUser() {return $this->user;}
	public function getShortFilename($length)
	{
		if ($length>=strlen($this->filename)) return $this->getFilename();
		return substr($this->filename,0,($length-3)/2+0.6).
		"...".
		substr($this->filename, -($length-3)/2, strlen($this->filename));
	}
}

class MD5DB
{
	private $db;
	public function __construct()
	{
		$fcontents = file("login.txt");
		for ($i=0; $i<count($fcontents); $i++)
		{
			$fcontents[$i]=trim($fcontents[$i]);
		}
		$this->db = new Database($fcontents[0], $fcontents[1], $fcontents[2], $fcontents[3]);
	}
	public function query($str)
	{
		return $this->db->query($str);
	}
	public function clean($str) {
		return mysql_real_escape_string($str);
	}
	public function randomize($str) {
		$str_arr = str_split($str);
		shuffle($str_arr);
		$nstr = '';
		foreach ($str_arr as $char) {
			$nstr .= $char;
		}
		return $nstr;
	}
}

class Database
{
	private $db_link=false;

	public function __construct($host, $dbuser, $dbpass, $dbname)
	{
		$this->db_link=mysql_connect($host, $dbuser, $dbpass) or die(mysql_error());
		mysql_select_db($dbname) or die(mysql_error());
	}

	public function query($str)
	{
		$query_result=mysql_query($str, $this->db_link);
		if (is_bool($query_result))
		{
			return $query_result;
		}
		$row_array=array();
		while ($row=mysql_fetch_array($query_result))
		{
			$row_array[]=$row;
		}
		return $row_array;
	}
}
?>
