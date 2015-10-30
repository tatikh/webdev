var HSA_scrollAreas = new Array();

var HSA_default_imagesPath = "images";
var HSA_default_btnLeftImage = "button-left.gif";
var HSA_default_btnRightImage = "button-right.gif";
var HSA_default_scrollStep = 5;
var HSA_default_wheelSensitivity = 10;
var HSA_default_scrollbarPosition = 'bottom'; //'top';//'bottom';
var HSA_default_scrollButtonWidth = 9;
var HSA_default_scrollbarHeight = 11;

var HSA_resizeTimer = 200;

if (window.addEventListener)
	window.addEventListener("load", HSA_initScrollbars, false);
else if (window.attachEvent)
	window.attachEvent("onload", HSA_initScrollbars);

function HSA_initScrollbars()
{
	var scrollElements = HSA_getElements("scrollable", "DIV", document, "class");
	for (var i=0; i<scrollElements.length; i++)
	{
		HSA_scrollAreas[i] = new ScrollArea(i, scrollElements[i]);
	}
}

function ScrollArea(index, elem) //constructor
{
	this.index = index;
	this.element = elem;

	var attr = this.element.getAttribute("imagesPath");
	this.imagesPath = attr ? attr : HSA_default_imagesPath;

	attr = this.element.getAttribute("btnLeftImage");
	this.btnLeftImage = attr ? attr : HSA_default_btnLeftImage;

	attr = this.element.getAttribute("btnRightImage");
	this.btnRightImage = attr ? attr : HSA_default_btnRightImage;

	attr = Number(this.element.getAttribute("scrollStep"));
	this.scrollStep = attr ? attr : HSA_default_scrollStep;

	attr = Number(this.element.getAttribute("wheelSensitivity"));
	this.wheelSensitivity = attr ? attr : HSA_default_wheelSensitivity;

	attr = this.element.getAttribute("scrollbarPosition");
	this.scrollbarPosition = attr ? attr : HSA_default_scrollbarPosition;

	attr = this.element.getAttribute("scrollButtonWidth");
	this.scrollButtonWidth = attr ? attr : HSA_default_scrollButtonWidth;

	attr = this.element.getAttribute("scrollbarHeight");
	this.scrollbarHeight = attr ? attr : HSA_default_scrollbarHeight;

	this.scrolling = false;

	this.iOffsetX = 0;
	this.scrollWidth = 0;
	this.scrollContent = null;
	this.scrollbar = null;
	this.scrollleft = null;
	this.scrollright = null;
	this.scrollslider = null;
	this.scroll = null;
	this.enableScrollbar = false;
	this.scrollFactor = 1;
	this.scrollingLimit = 0;
	this.leftPosition = 0;

	//functions declaration
	this.init = HSA_init;
	this.scrollLeft = HSA_scrollLeft;
	this.scrollRight = HSA_scrollRight;
	this.createScrollBar = HSA_createScrollBar;
	this.scrollIt = HSA_scrollIt;

	this.init();

}


function HSA_init()
{


	this.scrollContent = document.createElement("DIV");
	this.scrollContent.style.position = "absolute";
	this.scrollContent.style.width = this.element.offsetWidth + "px";
	this.scrollContent.style.height = this.element.offsetHeight + "px";
	this.scrollContent.innerHTML = this.element.innerHTML;
	this.scrollContent.style.overflow = "hidden";

	this.element.innerHTML = "";

	this.element.style.overflow = "hidden";
	this.element.style.display = "block";
	this.element.style.visibility = "visible";
	this.element.style.position = "relative";
	this.element.appendChild(this.scrollContent);

	this.scrollContent.className = 'scroll-content';

	this.element.index = this.index;
	this.element.over = false;

	var _this = this;
	this.element.onmouseover = function(){
        _this.element.over = true;
    };
    this.element.onmouseout = function(){
        _this.element.over = false;
    }

	if (document.all)
	{
		this.element.onscroll = HSA_handleOnScroll;
		this.element.onresize = HSA_handleResize;
	}
	else
	{
		window.onresize = HSA_handleResize;
	}

	this.createScrollBar();
	if (window.addEventListener)
        /** DOMMouseScroll is for mozilla. */
        this.element.addEventListener('DOMMouseScroll', HSA_handleMouseWheel, false);
	/** IE/Opera. */
	this.element.onmousewheel = document.onmousewheel = HSA_handleMouseWheel;

}

function HSA_createScrollBar()
{

	if (this.scrollbar != null)
	{
		this.element.removeChild(this.scrollbar);
		this.scrollbar = null;
	}


	if (this.scrollContent.scrollWidth <= this.scrollContent.offsetWidth)
		this.enableScrollbar = false;
	else if (this.element.offsetWidth > 2*this.scrollButtonWidth)
		this.enableScrollbar = true;
	else
		this.enableScrollbar = false;

	if (this.scrollContent.scrollWidth - Math.abs(this.scrollContent.scrollLeft) < this.element.offsetWidth)
		this.scrollContent.style.left = 0;

	if (this.enableScrollbar)
	{

		this.scrollbar = document.createElement("DIV");
		this.element.appendChild(this.scrollbar);
		this.scrollbar.style.position = "absolute";
		this.scrollbar.style.left = "0px";
		this.scrollbar.style.width = this.element.offsetWidth+"px";
		this.scrollbar.style.height = this.scrollbarHeight + "px";

		this.scrollbar.className = 'hscroll-bar';

		if(this.scrollbarHeight != this.scrollbar.offsetHeight)
		{
			this.scrollbarHeight = this.scrollbar.offsetWidth;
		}

		this.scrollbarHeight = this.scrollbar.offsetHeight;

		if(this.scrollbarPosition == 'top')
		{
			this.scrollContent.style.top = this.scrollbarHeight + 5 + "px";
			this.scrollContent.style.height = this.element.offsetHeight - this.scrollbarHeight - 5 + "px";
		}
		else if (this.scrollbarPosition == 'bottom')
		{
			this.scrollbar.style.top = this.element.offsetHeight - this.scrollbarHeight  + "px";
			this.scrollContent.style.height = this.element.offsetHeight - this.scrollbarHeight - 5 + "px";
		}


		//create scroll up button
		this.scrollleft = document.createElement("DIV");
		this.scrollleft.index = this.index;
		this.scrollleft.onmousedown = HSA_handleBtnLeftMouseRight;
		this.scrollleft.onmouseup = HSA_handleBtnLeftMouseLeft;
		this.scrollleft.onmouseout = HSA_handleBtnLeftMouseOut;
		this.scrollleft.style.position = "absolute";
		this.scrollleft.style.left = "0px";
		this.scrollleft.style.width = this.scrollButtonWidth + "px";
		this.scrollleft.style.height = this.scrollbarHeight + "px";

		this.scrollleft.innerHTML = '<img src="' + this.imagesPath + '/' + this.btnLeftImage + '" border="0"/>';
		this.scrollbar.appendChild(this.scrollleft);

		this.scrollleft.className = 'hscroll-left';

		if(this.scrollButtonWidth != this.scrollleft.offsetWidth)
		{
			this.scrollButtonWidth = this.scrollleft.offsetWidth;
		}

		//create scroll down button
		this.scrollright = document.createElement("DIV");
		this.scrollright.index = this.index;
		this.scrollright.onmousedown = HSA_handleBtnRightMouseRight;
		this.scrollright.onmouseup = HSA_handleBtnRightMouseLeft;
		this.scrollright.onmouseout = HSA_handleBtnRightMouseOut;
		this.scrollright.style.position = "absolute";
		this.scrollright.style.left =  this.scrollbar.offsetWidth - this.scrollButtonWidth + "px";
		this.scrollright.style.height = this.scrollbarHeight + "px";
		this.scrollright.innerHTML = '<img src="' + this.imagesPath + '/' + this.btnRightImage + '" border="0"/>';
		this.scrollbar.appendChild(this.scrollright);

		this.scrollright.className = 'hscroll-right';


		//create scroll
		this.scroll = document.createElement("DIV");
		this.scroll.index = this.index;
		this.scroll.style.position = "absolute";
		this.scroll.style.zIndex = 0;
		this.scroll.style.textAlign = "center";
		this.scroll.style.left = this.scrollButtonWidth + "px";
		this.scroll.style.height = this.scrollbarHeight + "px";

		var h = this.scrollbar.offsetWidth - 2*this.scrollButtonWidth;
		this.scroll.style.width = ((h > 0) ? h : 0) + "px";

		this.scroll.innerHTML = '';
		this.scroll.onclick = HSA_handleScrollbarClick;
		this.scrollbar.appendChild(this.scroll);
		this.scroll.style.overflow = "hidden";

		this.scroll.className = "hscroll-line";

		//create slider
		this.scrollslider = document.createElement("DIV");
		this.scrollslider.index = this.index;
		this.scrollslider.style.position = "absolute";
		this.scrollslider.style.zIndex = 1000;
		this.scrollslider.style.textAlign = "center";
		this.scrollslider.innerHTML = '<div id="scrollslider' + this.index + '" style="padding:0;margin:0;"><div class="scroll-bar-left"></div><div class="scroll-bar-right"></div></div>';
		this.scrollbar.appendChild(this.scrollslider);

		this.subscrollslider = document.getElementById("scrollslider"+this.index);
		this.subscrollslider.style.width = Math.round((this.scrollContent.offsetWidth/this.scrollContent.scrollWidth)*(this.scrollbar.offsetWidth - 2*this.scrollButtonWidth)) + "px";

		this.scrollslider.className = "hscroll-slider";

		this.scrollWidth = this.scrollbar.offsetWidth - 2*this.scrollButtonWidth - this.scrollslider.offsetWidth;
		this.scrollFactor = (this.scrollContent.scrollWidth - this.scrollContent.offsetWidth)/this.scrollWidth;
		this.leftPosition = getRealLeft(this.scrollbar) + this.scrollButtonWidth;
		/* this.scrollbarWidth = this.scrollbar.offsetWidth - 2*this.scrollButtonWidth - this.scrollslider.offsetWidth; */

		this.scrollslider.style.left = /* 1 / this.scrollFactor * Math.abs(this.scrollContent.offsetTop) +*/ this.scrollButtonWidth + "px";
		this.scrollslider.style.height = "100%";
		this.scrollslider.onmousedown = HSA_handleSliderMouseDown;
		if (document.all)
			this.scrollslider.onmouseup = HSA_handleSliderMouseLeft;
	}
	else
		this.scrollContent.style.height = this.element.offsetHeight + "px";
}

function HSA_handleBtnLeftMouseRight()
{
	var sa = HSA_scrollAreas[this.index];
	sa.scrolling = true;
	sa.scrollLeft();
}

function HSA_handleBtnLeftMouseLeft()
{
	HSA_scrollAreas[this.index].scrolling = false;
}

function HSA_handleBtnLeftMouseOut()
{
	HSA_scrollAreas[this.index].scrolling = false;
}

function HSA_handleBtnRightMouseRight()
{
	var sa = HSA_scrollAreas[this.index];
	sa.scrolling = true;
	sa.scrollRight();
}

function HSA_handleBtnRightMouseLeft()
{
	HSA_scrollAreas[this.index].scrolling = false;
}

function HSA_handleBtnRightMouseOut()
{
	HSA_scrollAreas[this.index].scrolling = false;
}

function HSA_scrollIt()
{
	this.scrollContent.scrollLeft = this.scrollFactor * ((this.scrollslider.offsetLeft + this.scrollslider.offsetWidth/2) - this.scrollButtonWidth - this.scrollslider.offsetWidth/2);
}

function HSA_scrollLeft()
{
	if (this.scrollingLimit > 0)
	{
		this.scrollingLimit--;
		if (this.scrollingLimit == 0) this.scrolling = false;
	}
	if (!this.scrolling) return;
	if ( this.scrollContent.scrollLeft - this.scrollStep > 0)
	{
		this.scrollContent.scrollLeft -= this.scrollStep;
		this.scrollslider.style.left = 1 / this.scrollFactor * Math.abs(this.scrollContent.scrollLeft) + this.scrollButtonWidth + "px";
	}
	else
	{
		this.scrollContent.scrollLeft = "0";
		this.scrollslider.style.left = this.scrollButtonWidth + "px";
		return;
	}
	setTimeout("HSA_Ext_scrollLeft(" + this.index + ")", 30);
}

function HSA_Ext_scrollLeft(index)
{
	HSA_scrollAreas[index].scrollLeft();
}

function HSA_scrollRight()
{
	if (this.scrollingLimit > 0)
	{
		this.scrollingLimit--;
		if (this.scrollingLimit == 0) this.scrolling = false;
	}
	if (!this.scrolling) return;


	this.scrollContent.scrollLeft += this.scrollStep;
	this.scrollslider.style.left =  1 / this.scrollFactor * Math.abs(this.scrollContent.scrollLeft) + this.scrollButtonWidth + "px";

	if (this.scrollContent.scrollLeft >= (this.scrollContent.scrollWidth - this.scrollContent.offsetWidth))
	{
		this.scrollContent.scrollLeft = (this.scrollContent.scrollWidth - this.scrollContent.offsetWidth);
		this.scrollslider.style.left = this.scrollbar.offsetWidth - this.scrollButtonWidth - this.scrollslider.offsetWidth + "px";
		return;
	}

	setTimeout("HSA_Ext_scrollRight(" + this.index + ")", 30);
}

function HSA_Ext_scrollRight(index)
{
	HSA_scrollAreas[index].scrollRight();
}

function HSA_handleMouseMove(evt)
{
	var sa = HSA_scrollAreas[((document.all && !window.opera) ? this.index : document.documentElement.scrollAreaIndex)];
	var posy = 0;
	if (!evt) var evt = window.event;

	if (evt.pageX)
		posy = evt.pageX;
	else if (evt.clientX)
		posy = evt.clientX;

		if (document.all && !window.opera)
		{
			posy += document.documentElement.scrollLeft;
		}

	var iNewY = posy - sa.iOffsetX - sa.leftPosition;
		iNewY += sa.scrollButtonWidth;

	if (iNewY < sa.scrollButtonWidth)
		iNewY = sa.scrollButtonWidth;
	if (iNewY > (sa.scrollbar.offsetWidth - sa.scrollButtonWidth) - sa.scrollslider.offsetWidth)
		iNewY = (sa.scrollbar.offsetWidth - sa.scrollButtonWidth) - sa.scrollslider.offsetWidth;

	sa.scrollslider.style.left = iNewY + "px";

	sa.scrollIt();
}

function HSA_handleSliderMouseDown(evt)
{
	if (!(document.uniqueID && document.compatMode && !window.XMLHttpRequest))
	{
		document.onselectstart = function() { return false; }
		document.onmousedown = function() { return false; }
	}
	var sa = HSA_scrollAreas[this.index];
	if (document.all && !window.opera)
	{
		sa.scrollslider.setCapture()
		sa.iOffsetX = event.offsetX;
		sa.scrollslider.onmousemove = HSA_handleMouseMove;
	}
	else
	{
		if(window.opera)
		{
			sa.iOffsetX = event.offsetX;
		}
		else
		{
			sa.iOffsetX = evt.layerX;
		}
		document.documentElement.scrollAreaIndex = sa.index;
		document.documentElement.addEventListener("mousemove", HSA_handleMouseMove, true);
		document.documentElement.addEventListener("mouseup", HSA_handleSliderMouseLeft, true);
	}
}

function HSA_handleSliderMouseLeft()
{
	if (!(document.uniqueID && document.compatMode && !window.XMLHttpRequest))
	{
		document.onmousedown = null;
		document.onselectstart = null;
	}
	if (document.all && !window.opera)
	{
		var sa = HSA_scrollAreas[this.index];
		sa.scrollslider.onmousemove = null;
		sa.scrollslider.releaseCapture();
		sa.scrollIt();
	}
	else
	{
		var sa = HSA_scrollAreas[document.documentElement.scrollAreaIndex];
		document.documentElement.removeEventListener("mousemove", HSA_handleMouseMove, true);
		document.documentElement.removeEventListener("mouseup", HSA_handleSliderMouseLeft, true);
		sa.scrollIt();
	}
}

function HSA_handleResize()
{
	if (HSA_resizeTimer)
	{
		clearTimeout(HSA_resizeTimer);
		HSA_resizeTimer = 0;
	}
	HSA_resizeTimer = setTimeout("HSA_performResizeEvent()", 100);
}

function HSA_performResizeEvent()
{
	for (var i=0; i<HSA_scrollAreas.length; i++)
		HSA_scrollAreas[i].createScrollBar();
}

function HSA_handleMouseWheel(event)
{
	if (this.index != null) {
		var sa = HSA_scrollAreas[this.index];
		if (sa.scrollbar == null) return;
		sa.scrolling = true;
		sa.scrollingLimit = sa.wheelSensitivity;

		var delta = 0;
		if (!event) /* For IE. */
				event = window.event;
		if (event.wheelDelta) { /* IE/Opera. */
				delta = event.wheelDelta/120;

				/*if (window.opera)
						delta = -delta;*/
		} else if (event.detail) { /** Mozilla case. */
				delta = -event.detail/3;
		}
		if (delta && sa.element.over) {
				if (delta > 0)
					sa.scrollLeft();
				else
					sa.scrollRight();

				if (event.preventDefault)
						event.preventDefault();
				event.returnValue = false;
		}
	}
}

function HSA_handleSelectStart()
{
	event.returnValue = false;
}

function HSA_handleScrollbarClick(evt)
{
	var sa = HSA_scrollAreas[this.index];
	var offsetX = (document.all ? event.offsetX : evt.layerX);

	if (offsetX < (sa.scrollButtonWidth + sa.scrollslider.offsetWidth/2))
		sa.scrollslider.style.left = sa.scrollButtonWidth + "px";
	else if (offsetX > (sa.scrollbar.offsetWidth - sa.scrollButtonWidth - sa.scrollslider.offsetWidth))
		sa.scrollslider.style.left = sa.scrollbar.offsetWidth - sa.scrollButtonWidth - sa.scrollslider.offsetWidth + "px";
	else
	{
		sa.scrollslider.style.left = offsetX + sa.scrollButtonWidth - sa.scrollslider.offsetWidth/2 + "px";
	}
	sa.scrollIt();
}

function HSA_handleOnScroll()
{
	event.srcElement.doScroll("pageLeft");
}

//--- common functions ----

function HSA_getElements(attrValue, tagName, ownerNode, attrName) //get Elements By Attribute Name
{
	if (!tagName) tagName = "*";
	if (!ownerNode) ownerNode = document;
	if (!attrName) attrName = "name";
	var result = [];
	var nl = ownerNode.getElementsByTagName(tagName);
	for (var i=0; i<nl.length; i++)
	{
	//	if (nl.item(i).getAttribute(attrName) == attrValue)
//		result.push(nl.item(i));
		if (nl.item(i).className.indexOf(attrValue) != -1)
		result.push(nl.item(i));
	}
	return result;
}

function getRealLeft(elem)
{
	var nLeft = 0;
	if(elem)
	{
		do
		{
			nLeft += elem.offsetLeft - elem.scrollLeft;
			elem = elem.offsetParent;
		}
		while(elem)
	}
	return nLeft;
}