
package com.roytanck.wpcumulus
{

	import flash.geom.ColorTransform;
	import flash.filters.*;
	import flash.display.*;
        import flash.events.*;
        import flash.net.*;
        import flash.text.*;
	public class Tag extends Sprite {
		
		private var _back:Sprite;
		private var _node:XML;
		private var _cx:Number;
		private var _cy:Number;
		private var _cz:Number;
		private var _color:Number;
		private var _hicolor:Number;
		private var _active:Boolean;
		private var _tf:TextField;
		
		public function Tag( node:XML, color:Number, hicolor:Number ){
			            _node = node;
            _color = color;
            _hicolor = hicolor;
            _active = false;
            _tf = new TextField();
            _tf.autoSize = TextFieldAutoSize.LEFT;
            _tf.selectable = false;
            _tf.antiAliasType = AntiAliasType.ADVANCED;
	    var format:TextFormat = new TextFormat();
            format.font = "微软雅黑, Arial, 黑体";//设置字体
            format.bold = true;
            format.color = color;
            format.size = 16 * getNumberFromString(node["@style"]);
            _tf.defaultTextFormat = format;
	    //_tf.embedFonts = true;  //是否使用字符库，这个肯定要注释掉	
            _tf.text = node;
            var _loc_5:BlurFilter = new BlurFilter(5, 5, 1);
            _tf.filters = [_loc_5];
	    var _loc_6:Bitmap = new Bitmap(null, "auto", true);
            var _loc_7:BitmapData = new BitmapData(_tf.width, _tf.height, true, color);
			
            _loc_7.draw(_tf);
            _loc_6.bitmapData = _loc_7;
            _loc_6.scaleY = 0.15;
            _loc_6.scaleX = 0.15;

            addChild(_loc_6);
            _loc_6.x = (-this.width) / 2;
            _loc_6.y = (-this.height) / 2;
            _back = new Sprite();
            _back.graphics.beginFill(_hicolor, 0);
            _back.graphics.lineStyle(0.6, _hicolor);
            _back.graphics.drawRect(0, 0, _loc_6.width, _loc_6.height);
            _back.graphics.endFill();
            addChildAt(_back, 0);
            _back.x = (-_loc_6.width) / 2;
            _back.y = (-_loc_6.height) / 2;
            _back.visible = false;
			if( _node["@href"].substr(0,4).toLowerCase() == "http" ){
				this.mouseChildren = false;
				this.buttonMode = true;
				this.useHandCursor = true;
				this.addEventListener(MouseEvent.MOUSE_OUT, mouseOutHandler);
				this.addEventListener(MouseEvent.MOUSE_OVER, mouseOverHandler);
				this.addEventListener(MouseEvent.MOUSE_UP, mouseUpHandler);
			}
		}
		
		private function mouseOverHandler( e:MouseEvent ):void {
			_back.visible = true;
			_tf.textColor = _hicolor;
			_active = true;
		}
		
		private function mouseOutHandler( e:MouseEvent ):void {
			_back.visible = false;
			_tf.textColor = _color;
			_active = false;
		}
		
		private function mouseUpHandler( e:MouseEvent ):void {
			var request:URLRequest = new URLRequest( _node["@href"] );
			var target:String = _node["@target"] == undefined ? "_self" : _node["@target"];
			navigateToURL( request, target );
		}

		private function getNumberFromString( s:String ):Number {
			return( Number( s.match( /(\d|\.|\,)/g ).join("").split(",").join(".") ) );
		}
		
		public function set cx( n:Number ){ _cx = n }
		public function get cx():Number { return _cx; }
		public function set cy( n:Number ){ _cy = n }
		public function get cy():Number { return _cy; }
		public function set cz( n:Number ){ _cz = n }
		public function get cz():Number { return _cz; }
		public function get active():Boolean { return _active; }

	}

}
