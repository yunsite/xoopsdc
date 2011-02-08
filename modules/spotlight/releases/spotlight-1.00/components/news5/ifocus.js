function $$(id) { return document.getElementById(id);}
function addLoadEvent(func){
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	} else {
		window.onload = function(){
			oldonload();
			func();
		}
	}
}
function moveElement(elementID,final_x,final_y,interval) {
  if (!document.getElementById) return false;
  if (!document.getElementById(elementID)) return false;
  var elem = document.getElementById(elementID);
  if (elem.movement) {
    clearTimeout(elem.movement);
  }
  if (!elem.style.left) {
    elem.style.left = "0px";
  }
  if (!elem.style.top) {
    elem.style.top = "0px";
  }
  var xpos = parseInt(elem.style.left);
  var ypos = parseInt(elem.style.top);
  if (xpos == final_x && ypos == final_y) {
		return true;
  }
  if (xpos < final_x) {
    var dist = Math.ceil((final_x - xpos)/10);
    xpos = xpos + dist;
  }
  if (xpos > final_x) {
    var dist = Math.ceil((xpos - final_x)/10);
    xpos = xpos - dist;
  }
  if (ypos < final_y) {
    var dist = Math.ceil((final_y - ypos)/10);
    ypos = ypos + dist;
  }
  if (ypos > final_y) {
    var dist = Math.ceil((ypos - final_y)/10);
    ypos = ypos - dist;
  }
  elem.style.left = xpos + "px";
  elem.style.top = ypos + "px";
  var repeat = "moveElement('"+elementID+"',"+final_x+","+final_y+","+interval+")";
  elem.movement = setTimeout(repeat,interval);
}




function iFocusChange() {
	if(!$$('ifocus')) return false;
	$$('ifocus').onmouseover = function(){atuokey = true};
	$$('ifocus').onmouseout = function(){atuokey = false};
	var iFocusBtns = $$('ifocus_btn').getElementsByTagName('li');
	var listLength = iFocusBtns.length;
	for(var i=0;i<listLength;i++){
			iFocusBtns[i].index=i;
			iFocusBtns[i].onmouseover = function() {
			moveElement('ifocus_piclist',0,-(this.index*277),5);
			classCurrent('ifocus_btn',this.index);
			}
		}
}
setInterval('autoiFocus()',5000);
var atuokey = false;
function autoiFocus() {
	if(!$$('ifocus')) return false;
	if(atuokey) return false;
	var focusBtnList = $$('ifocus_btn').getElementsByTagName('li');
	var listLength = focusBtnList.length;

	for(var i=0; i<listLength; i++) {
		if (focusBtnList[i].className == 'current') var currentNum = i;
	}
	if (currentNum!=listLength-1){
		moveElement('ifocus_piclist',0,-((currentNum+1-0)*277),5);
		classCurrent('ifocus_btn',currentNum+1-0);
	}
	else{
		moveElement('ifocus_piclist',0,0,5);
		classCurrent('ifocus_btn',0);
	}
	
}

function classCurrent(iFocusBtnID,n){
	var iFocusBtns= $$(iFocusBtnID).getElementsByTagName('li');	
	for(var i=0; i<iFocusBtns.length; i++) {
		if (i==n)
			iFocusBtns[n].className='current';
		
		else
			iFocusBtns[i].className='normal';		
	}
}
addLoadEvent(iFocusChange);
