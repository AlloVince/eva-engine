package com.roytanck.wpcumulus
{
	import flash.geom.ColorTransform;
	import flash.filters.*;
	import flash.display.*;
    import flash.events.*;
    import flash.net.*;
    import flash.text.*;
	import flash.geom.ColorTransform;
	import flash.ui.ContextMenu;
	import flash.ui.ContextMenuItem;
	import com.roytanck.wpcumulus.Tag;

	public class TagCloud extends MovieClip	{
		// private vars
		private var radius:Number;
		private var mcList:Array;
		private var dtr:Number;
		private var d:Number;
		private var sa:Number;
		private var ca:Number;
		private var sb:Number;
		private var cb:Number;
		private var sc:Number;
		private var cc:Number;
		private var originx:Number;
		private var originy:Number;
		private var tcolor:Number;
		private var hicolor:Number;
		private var tcolor2:Number;
		private var tspeed:Number;
		private var distr:Boolean;
		private var lasta:Number;
		private var lastb:Number;
		private var holder:MovieClip;
		private var active:Boolean;
		private var myXML:XML;
		
		public function TagCloud(){
			// settings
			var swfStage:Stage = this.stage;
			swfStage.scaleMode = StageScaleMode.NO_SCALE;
			swfStage.align = StageAlign.TOP_LEFT;
			var myContextMenu:ContextMenu = new ContextMenu();
			myContextMenu.hideBuiltInItems();
			var item:ContextMenuItem = new ContextMenuItem("WP-Cumulus 中文支持 by Wizzer.cn");
			myContextMenu.customItems.push(item);
			this.contextMenu = myContextMenu;
			item.addEventListener(ContextMenuEvent.MENU_ITEM_SELECT, menuItemSelectHandler);
			tcolor = ( this.loaderInfo.parameters.tcolor == null ) ? 0x333333 : Number(this.loaderInfo.parameters.tcolor);
			tcolor2 = ( this.loaderInfo.parameters.tcolor2 == null ) ? 0x995500 : Number(this.loaderInfo.parameters.tcolor2);
			hicolor = ( this.loaderInfo.parameters.hicolor == null ) ? 0x000000 : Number(this.loaderInfo.parameters.hicolor);
			tspeed = ( this.loaderInfo.parameters.tspeed == null ) ? 1 : Number(this.loaderInfo.parameters.tspeed)/100;
			distr = ( this.loaderInfo.parameters.distr == "true" );
			
			myXML = new XML();
			if( this.loaderInfo.parameters.mode == null )	{
				var a:Array = this.loaderInfo.url.split("/");
				a.pop();
				var baseURL:String = a.join("/") + "/";
				var XMLPath = ( this.loaderInfo.parameters.xmlpath == null ) ? baseURL + "tagcloud.xml" : this.loaderInfo.parameters.xmlpath;
				var myXMLReq:URLRequest = new URLRequest( XMLPath );
				var myLoader:URLLoader = new URLLoader(myXMLReq);
				myLoader.addEventListener("complete", xmlLoaded);
				function xmlLoaded(event:Event):void {
						myXML = XML(myLoader.data); 
						init( myXML );
				}
			} else {
				switch( this.loaderInfo.parameters.mode ){
					case "tags":
						myXML = new XML( this.loaderInfo.parameters.tagcloud );
						break;
					case "cats":
						myXML = new XML("<tags></tags>");
						addCategories( this.loaderInfo.parameters.categories );
						break;
					default:
						myXML = new XML( this.loaderInfo.parameters.tagcloud );
						addCategories( this.loaderInfo.parameters.categories );
						break;
				}
				init( myXML );
			}
		}
		
		private function addCategories( cats:String ){
			cats = unescape(cats).replace(/\+/g, " ");
			var cArray:Array = cats.split("<br />");
			var smallest:Number = 9999;
			var largest:Number = 0;
			var pattern:RegExp = /\d/g;
			for( var i:Number=0; i<cArray.length-1; i++ ){
				var parts:Array = cArray[i].split( "</a>" );
				var nr:Number = Number( parts[1].match(pattern).join("") );
				largest = Math.max( largest, nr );
				smallest = Math.min( smallest, nr );
			}
			var scalefactor:Number = ( smallest == largest )? 7/largest : 14 / largest;
			for( i=0; i<cArray.length-1; i++ ){
				parts = cArray[i].split( "</a>" );
				nr = Number( parts[1].match(pattern).join("") );
				var node:String = "<a style=\"" + ((nr*scalefactor)+8) + "\"" + parts[0].substr( parts[0].indexOf("<a")+2 ) + "</a>";
				myXML.appendChild( node );
			}
		}
		
		private function init( o:XML ):void {
			radius = 150;
			dtr = Math.PI/180;
			d=300;
			sineCosine( 0,0,0 );
			mcList = [];
			active = false;
			lasta = 1;
			lastb = 1;
			holder = new MovieClip();
			addChild(holder);
			resizeHolder();
			var largest:Number = 0;
			var smallest:Number = 9999;
			for each( var node:XML in o.a ){
				var nr:Number = getNumberFromString( node["@style"] );
				largest = Math.max( largest, nr );
				smallest = Math.min( smallest, nr );
			}
			for each( var node2:XML in o.a ){
				var nr2:Number = getNumberFromString( node2["@style"] );
				var perc:Number = ( smallest == largest ) ? 1 : (nr2-smallest) / (largest-smallest);
				var col:Number = ( node2["@color"] == undefined ) ? getColorFromGradient( perc ) : Number( node2["@color"] );
				var hicol:Number = ( node2["@hicolor"] == undefined ) ? ( ( hicolor == tcolor ) ? getColorFromGradient( perc ) : hicolor ) : Number( node2["@hicolor"] );
				var mc:Tag = new Tag( node2, col, hicol );
				holder.addChild(mc);
				mcList.push( mc );
			}
			positionAll();
			addEventListener(Event.ENTER_FRAME, updateTags);
			stage.addEventListener(Event.MOUSE_LEAVE, mouseExitHandler);
			stage.addEventListener(MouseEvent.MOUSE_MOVE, mouseMoveHandler);
			stage.addEventListener(Event.RESIZE, resizeHandler);
		}

		private function updateTags( e:Event ):void {
			var a:Number;
			var b:Number;
			if( active ){
				a = (-Math.min( Math.max( holder.mouseY, -250 ), 250 ) / 150 ) * tspeed;
				b = (Math.min( Math.max( holder.mouseX, -250 ), 250 ) /150 ) * tspeed;
			} else {
				a = lasta * 0.98;
				b = lastb * 0.98;
			}
			lasta = a;
			lastb = b;
			if( Math.abs(a) > 0.01 || Math.abs(b) > 0.01 ){
				var c:Number = 0;
				sineCosine( a, b, c );
				for( var j:Number=0; j<mcList.length; j++ ) {
					var rx1:Number = mcList[j].cx;
					var ry1:Number = mcList[j].cy * ca + mcList[j].cz * -sa;
					var rz1:Number = mcList[j].cy * sa + mcList[j].cz * ca;
					var rx2:Number = rx1 * cb + rz1 * sb;
					var ry2:Number = ry1;
					var rz2:Number = rx1 * -sb + rz1 * cb;
					var rx3:Number = rx2 * cc + ry2 * -sc;
					var ry3:Number = rx2 * sc + ry2 * cc;
					var rz3:Number = rz2;
					mcList[j].cx = rx3;
					mcList[j].cy = ry3;
					mcList[j].cz = rz3;
					var per:Number = d / (d+rz3);
					mcList[j].x = rx3 * per;
					mcList[j].y = ry3 * per;
					mcList[j].scaleX = mcList[j].scaleY =  per;
					mcList[j].alpha = per/2;
				}
				depthSort();
			}
		}
		
		private function depthSort():void {
			mcList.sortOn( "cz", Array.DESCENDING | Array.NUMERIC );
			var current:Number = 0;
			for( var i:Number=0; i<mcList.length; i++ ){
				holder.setChildIndex( mcList[i], i );
				if( mcList[i].active == true ){
					current = i;
				}
			}
			holder.setChildIndex( mcList[current], mcList.length-1 );
		}
		
		private function positionAll():void {		
			var phi:Number = 0;
			var theta:Number = 0;
			var max:Number = mcList.length;
			mcList.sort( function(){ return Math.random()<0.5 ? 1 : -1; } );
			for( var i:Number=1; i<max+1; i++){
				if( distr ){
					phi = Math.acos(-1+(2*i-1)/max);
					theta = Math.sqrt(max*Math.PI)*phi;
				}else{
					phi = Math.random()*(Math.PI);
					theta = Math.random()*(2*Math.PI);
				}
				mcList[i-1].cx = radius * Math.cos(theta)*Math.sin(phi);
				mcList[i-1].cy = radius * Math.sin(theta)*Math.sin(phi);
				mcList[i-1].cz = radius * Math.cos(phi);
			}
		}
		
		private function menuItemSelectHandler( e:ContextMenuEvent ):void {
			var request:URLRequest = new URLRequest( "http://www.wizzer.cn" );
			navigateToURL(request);
		}
		
		private function mouseExitHandler( e:Event ):void { active = false; }
		private function mouseMoveHandler( e:MouseEvent ):void {	active = true; }
		private function resizeHandler( e:Event ):void { resizeHolder(); }
		
		private function resizeHolder():void {
			var s:Stage = this.stage;
			holder.x = s.stageWidth/2;
			holder.y = s.stageHeight/2;
			var scale:Number;
			if( s.stageWidth > s.stageHeight ){
				scale = (s.stageHeight/500);
			} else {
				scale = (s.stageWidth/500);
			}
			holder.scaleX = holder.scaleY = scale;
			mousetrap_mc.width = s.stageWidth;
			mousetrap_mc.height = s.stageHeight;
		}
		
		private function sineCosine( a:Number, b:Number, c:Number ):void {
			sa = Math.sin(a * dtr);
			ca = Math.cos(a * dtr);
			sb = Math.sin(b * dtr);
			cb = Math.cos(b * dtr);
			sc = Math.sin(c * dtr);
			cc = Math.cos(c * dtr);
		}
		
		private function getNumberFromString( s:String ):Number {
			return( Number( s.match( /(\d|\.|\,)/g ).join("").split(",").join(".") ) );
		}
		
		private function getColorFromGradient( perc:Number ):Number {
			var r:Number = ( perc * ( tcolor >> 16 ) ) + ( (1-perc) * ( tcolor2 >> 16 ) );
			var g:Number = ( perc * ( (tcolor >> 8) % 256 ) ) + ( (1-perc) * ( (tcolor2 >> 8) % 256 ) );
			var b:Number = ( perc * ( tcolor % 256 ) ) + ( (1-perc) * ( tcolor2 % 256 ) );
			return( (r << 16) | (g << 8) | b );
		}
		
	}

}