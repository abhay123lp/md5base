<!-- //

var msg_loginphp=["Input box is empty","Your input is valid","Your input is invalid","Your input is too long! Use up to 16 characters","Your input is too long! Use up to 16 characters long password!","Your input is too short! Use at least 8 characters long password!","Passwords do not match!","Passwords match!","Unknown error"];

var msg_submitphp=["Maximum number of input boxes achieved! Use a text-file upload for more than 10 entries at a time!","MD5 Hash:","File name:","File size (bytes):","Optional comment:","(128 characters max)","Be careful! You will use any data stored in the last set of input boxes that will be removed!"];

var SetImg = function (img_id, what,saywhat) {
		var newsrc = 'images/icons/button_'+what+'.png';
		document.getElementById(img_id).src = newsrc;
		document.getElementById(img_id).title = saywhat;
};

var myInput = function (input_id) {
	this.myid = document.getElementById(input_id);
	this.myvalue = document.getElementById(input_id).value;
	this.mylength = document.getElementById(input_id).value.length;
};

var CheckVal = function () {
		var what = arguments[0];
		if(what == 'username') {
		var input_id1 = arguments[1];
		var img1 = arguments[2];
		var Input1 = new myInput(input_id1);
			if(Input1.mylength == 0) {
				SetImg(img1,'blank',msg_loginphp[0]);
			}
			else if((Input1.mylength > 0) && (Input1.mylength < 17)) {
				SetImg(img1,'ok',msg_loginphp[1]);
			} 
			else if((Input1.mylength > 16)) {
				SetImg(img1,'bad',msg_loginphp[3]);
			}
			else {
				SetImg(img1,'blank',msg_loginphp[2]);
			}
		}
		if(what == 'password') {
		var img1 = arguments[3];
		var img2 = arguments[4];
		var input_id1 = arguments[1];
		var input_id2 = arguments[2];
		var Input1 = new myInput(input_id1);
		var Input2 = new myInput(input_id2);
			if(Input1.mylength == 0) {
				SetImg(img1,'blank',msg_loginphp[0]);
			}
			else if((Input1.mylength > 7) && (Input1.mylength < 17)) {
				SetImg(img1,'ok',msg_loginphp[1]);
			}
			else if((Input1.mylength > 16)) {
				SetImg(img1,'bad',msg_loginphp[4]);
			}
			else if((Input1.mylength < 8 )) {
				SetImg(img1,'bad',msg_loginphp[5]);
			}
			else {
				SetImg(img1,'bad',msg_loginphp[2]);
			}

			if(Input2.mylength == 0) {
				SetImg(img2,'blank',msg_loginphp[0]);
			}
			else if((Input2.mylength > 7) && (Input2.mylength < 17)) {
				SetImg(img2,'ok',msg_loginphp[1]);
			}
			else if((Input2.mylength > 16)) {
				SetImg(img2,'bad',msg_loginphp[4]);
			}
			else if((Input2.mylength < 8 )) {
				SetImg(img2,'bad',msg_loginphp[5]);
			}
			else {
				SetImg(img2,'bad',msg_loginphp[2]);
			}
			if((Input1.mylength > 7) && (Input1.mylength < 17) && (Input2.mylength > 7) && (Input2.mylength < 17)) {
				if(Input1.myvalue != Input2.myvalue) {
				SetImg(img1,'bad',msg_loginphp[6]);SetImg(img2,'bad',msg_loginphp[6]);
				}
				else if(Input1.myvalue == Input2.myvalue) {
				SetImg(img1,'ok',msg_loginphp[7]);SetImg(img2,'ok',msg_loginphp[7]);
				}
				else {
				SetImg(img1,'bad',msg_loginphp[8]);SetImg(img2,'bad',msg_loginphp[8]);
				}
			}
		
		}
		if(what == 'mail') {
				var input_id1 = arguments[1];
				var img1 = arguments[2];
				var Input1 = new myInput(input_id1);
				if(Input1.mylength == 0) {
					SetImg(img1,'blank',msg_loginphp[0]);
				}
				else if(Input1.mylength < 6) {
					SetImg(img1,'bad',msg_loginphp[2]);
				}
				else if((Input1.myvalue.indexOf('.') != -1 && Input1.myvalue.indexOf('@') != -1) && Input1.mylength > 5) {
					SetImg(img1,'ok',msg_loginphp[1]);
				}
				else {
					SetImg(img1,'bad',msg_loginphp[2]);
				}

		}
};

var RecaptchaOptions = {
	theme : 'clean'
};

function addRowToTable(table_id)
{
  var tbl = document.getElementById(table_id);
  var lastRow = tbl.rows.length;
  var iteration = (lastRow+1)/5+1;

  if(lastRow > 49) {
  alert(msg_submitphp[0]);
  }
  else {
  //new row
  var row = tbl.insertRow(lastRow); 
  // left cell
  var cellLeft = row.insertCell(0);
  cellLeft.colSpan=2;
  var el = document.createElement('hr');
  cellLeft.appendChild(el);

  //new row
  var row = tbl.insertRow(lastRow+1); 
  // left cell
  var cellLeft = row.insertCell(0);
  var bold = document.createElement('b');
  cellLeft.appendChild(bold);
  var textNode = document.createTextNode(msg_submitphp[1]);
  bold.appendChild(textNode);
  // right cell
  var cellRight = row.insertCell(1);
  var el = document.createElement('input');
  el.type = 'text';
  el.name = 'hash' + iteration;
  el.className = 'input2';
  el.maxLength = 32;
  cellRight.appendChild(el);

  //new row
  var row = tbl.insertRow(lastRow+2);
  // left cell
  var cellLeft = row.insertCell(0);
  var bold = document.createElement('b');
  cellLeft.appendChild(bold);
  var textNode = document.createTextNode(msg_submitphp[2]);
  bold.appendChild(textNode);
  // right cell
  var cellRight = row.insertCell(1);
  var el = document.createElement('input');
  el.type = 'text';
  el.name = 'name' + iteration;
  el.className = 'input2';
  el.maxLength = 255;
  cellRight.appendChild(el);

  //new row
  var row = tbl.insertRow(lastRow+3);
  // left cell
  var cellLeft = row.insertCell(0);
  var bold = document.createElement('b');
  cellLeft.appendChild(bold);
  var textNode = document.createTextNode(msg_submitphp[3]);
  bold.appendChild(textNode);
  // right cell
  var cellRight = row.insertCell(1);
  var el = document.createElement('input');
  el.type = 'text';
  el.name = 'size' + iteration;
  el.className = 'input2';
  el.maxLength = 16;
  cellRight.appendChild(el);

  //new row
  var row = tbl.insertRow(lastRow+4);
  // left cell
  var cellLeft = row.insertCell(0);
  var bold = document.createElement('b');
  cellLeft.appendChild(bold);
  var textNode = document.createTextNode(msg_submitphp[4]);
  bold.appendChild(textNode);
  var linebreak = document.createElement('br');
  cellLeft.appendChild(linebreak);
  var newnode = document.createTextNode(msg_submitphp[5]);
  cellLeft.appendChild(newnode);
  // right cell
  var cellRight = row.insertCell(1);
  var el = document.createElement('textarea');
  el.name = 'comment' + iteration;
  el.className = 'textarea1';
  el.maxLength = 128;
  cellRight.appendChild(el);
  }
}

function removeRowFromTable(table_id)
{
  var tbl = document.getElementById(table_id);
  var lastRow = tbl.rows.length;
  if(lastRow > 5) {
  var question = confirm (msg_submitphp[6]);
  if(question) {
    for(i=1;i<6;i++) {
	tbl.deleteRow(lastRow - i);
      }
    }
  }
}
// -->


