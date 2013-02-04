/*! jQuery popup plugin from : http://www.maxmert.com/ */
;(function ( $, window, document, undefined ) {

	var _name = 'kit'
	,	_defaults = {
			enabled: true
		,	animation: null
		,	animationDuration: 0
		,	theme: 'dark'
		}

	$.kit = function(element, options) {
		this.name = _name;
		this.state = 'uninitialized'; // closed in opened out
		
		this.element = element;
		this.options = $.extend({}, _defaults, options);
		this.El = $(this.options.template);

		// this._setOptions( options );
	}

	$.kit.prototype._setOptions = function( options_ ) {
		var me  = this;
		var $me = $(me.element);

		$.each( options_, function( key_, value_ ) {
			me._setOption( key_, value_ );
			me.__setOption( key_, value_ );

			var currentOption = { };
				currentOption[ key_ ] = value_;

			if( $.isFunction( value_ ) ) {
				me._on( $me, currentOption );
			}

		});
	}

	$.kit.prototype._setOption = function( key_, value_ ) {
		var me  = this;
		var $me = $(me.element);

		switch( key_ ) {
			case 'theme':
				me.El.removeClass( '-' + me.options.theme + '-' );
				me.El.addClass( '-' + value_ + '-' )
			break;

			case 'enabled':
				value_ === true ? me.El.removeClass( '-disabled-' ) : me.El.addClass( '-disabled-' );
			break;
		}
	}

	$.kit.prototype.__setOption = function ( key_, value_ ) {
		this.options[ key_ ] = value_;
	}

	$.kit.prototype._on = function( element_, handlers_ ) {
		var me = this;

		$.each( handlers_, function( event, handler ) {
			element_.bind( event + '.' + me.name, handler );
		});
	}

	$.kit.prototype.enable = function() {
		var me = this;

		me._setOption( 'enabled', true )
	}

	$.kit.prototype.disable = function() {
		var me = this;

		me._setOption( 'enabled', false )
	}

	$.kit.prototype.getState = function() {
		return this.state;
	}

	$.kit.prototype._getOtherInstanses = function( instanses_ ) {
		var me = this;
		
		return $.grep( instanses_ , function( el ) {
			return $.data($(el[0]), 'kit-' + me.name) !== me;
		});
		
	}

	$.kit.prototype._removeInstanse = function( instanses_ ) {
		var me = this;
		
		var what
		,	a = arguments.splice(0,1)
		,	L = a.length
		,	ax;

		while( L && instanses_.length ) {
			what = a[ --L ];
			
			while( (ax = instanses_.indexOf( what ) ) != -1 ){
				instanses_.splice( ax, 1 );
			}
		}

		return me;
	}

})( jQuery, window, document );


;(function ( $, window, document, undefined ) {

	var _name = 'popup'
	,	_defaults = {
			placement: 'top'
		,	offset: [0, 0]
		,	autoOpen: false
		,	template: '<div class="js-content"></div>'
		,	onlyOne: false
		,	content: null
		,	header: null
		,	trigger: 'click'
		,	delay: 0

		,	beforeOpen: $.noop()
		,	open: $.noop()
		,	ifOpenedOrNot: $.noop()
		,	ifNotOpened: $.noop()
		,	beforeClose: $.noop()
		,	close: $.noop()
		,	ifClosedOrNot: $.noop()
		,	ifNotClosed: $.noop()
		}

	Popup = function( element_, options_ ) {

		this.element = element_;
		this.name = _name;
		this.state = 'closed';
		this.options = $.extend( {}, this.options, _defaults, options_ );
		
		// Creating popup element by template
		// Eva Fix 1 : when EL is a selector, use it directly but not copy and create DOM
		this.options.template.charAt(0) === '.' || this.options.template.charAt(0) === '#' ?
				this.El = $(this.options.template) :
				this.El = $( $(this.options.template).html() );
			
		// Eva Fix 2 : not copy again
		$('body').append( this.El );
			// CSS manupulations just in case
			this.El
				.css({position: 'absolute', display: 'none'})
				.find('.arrow')
					.css({opacity: 0});

		this._setOptions( this.options );

		// Create collection for other instances
		if( typeof $.popup === 'undefined' )
			$.popup = []
		// Put this instance to the collection
		if( this.element !== undefined )
			$.popup.push( this.element );
		
		// For delay before open
		this.timeout = null;

		this.init();
	}

	Popup.prototype = new $.kit();
	Popup.prototype.constructor = Popup;

	Popup.prototype.__setOption = function ( key_, value_ ) {
		var me  = this;
		var $me = $(me.element);
		switch( key_ ) {

			case 'trigger':
				var _events = value_.split(/[ ,]+/);
					
				
				// me.options[key_]['in'] – for IE < 8
				if( typeof me.options[key_]['in'] !== undefined )
					$me.off( 'mouseenter.' + me.name, 'click.' + me.name );

				if( typeof me.options[key_]['out'] !== undefined )
					$me.off( 'mouseleave.' + me.name, 'click.' + me.name );

				me.options[key_] = {
					'in': _events[0] 
				,   'out': (_events[1] == undefined || _events[1] == '') ? _events[0] : _events[1]
				};
				
				switch( me.options[key_]['in'] ) {
					case 'hover':
						$me.on('mouseenter.' + me.name, function( event ) {
							if( me.state === 'closed' )
								me.open();
						});
					break;
					
					default:
						$me.on( me.options[key_]['in'] + '.' + me.name, function() {
							if( me.state === 'closed' )
								me.open();
						});
				}

				switch( me.options[key_].out ) {
					case 'hover':
						$me.on('mouseleave.' + me.name, function( event ) {
							me.close();
						});
					break;

					default:
						$me.on( me.options[key_].out + '.' + me.name, function() {
							if( me.state == 'opened' )
								me.close();
						});
				}
			break;

			case 'content':
				if( value_ !== null )
					me.El.find('.js-content').html(value_);
				else
					me.El.find('.js-content').html( $me.data('content') );
			break;

			case 'header':
				if( value_ !== null )
					me.El.find('.js-header').html(value_);
				else
					me.El.find('.js-header').html( $me.data('header') );
			break;

			case 'placement':
				//Eva fix 3: remove prefix
				me.El.removeClass(me.options.placement)
				me.El.addClass(value_)
			break;

			case 'animation':
				if( $.easing === undefined || !(value_ in $.easing) )
					switch( value_ ) {
						case 'scaleIn':
							me.El.addClass('-mx-scaleIn');
						break;

						case 'growUp':
							me.El.addClass('-mx-growUp');
						break;

						case 'rotateIn':
							me.El.addClass('-mx-rotateIn');
						break;

						case 'dropIn':
							me.El.addClass('-mx-dropIn');
						break;
					}

			break;
				
		}

		if( key_ !== 'trigger' )
			me.options[ key_ ] = value_;
	}

	Popup.prototype.init = function() {
		var me  = this;

		if( me.options.autoOpen )
			me.open()
	}

	Popup.prototype.open = function() {
		var me  = this;
		var $me = $(me.element);
		
		me.timeout = setTimeout(function(){
			if( me.options.enabled === true && me.state !== 'opened' ) {
				
				me.state = 'in';

				if( me.options.beforeOpen !== undefined && (typeof me.options.beforeOpen === 'object' || typeof me.options.beforeOpen === 'function' )) {
					
					try {
						var deferred = me.options.beforeOpen.call( $me );
						deferred
							.done(function(){
								me._open();
							})
							.fail(function(){
								me.state = 'closed';
								$me.trigger('ifNotOpened.' + me.name);
								$me.trigger('ifOpenedOrNot.' + me.name);
							})
					} catch( e ) {
						me._open();
					}
					
				}
				else {
					me._open();
				}
			}
		}, me.options.delay)
	}

	Popup.prototype._open = function() {
		var me  = this;
		var $me = $(me.element);

		if( me.state === 'in' ) {
			
			if( me.options.onlyOne )
				
				$.each( me._getOtherInstanses( $.popup ), function() {
					if( $.data( this, 'kit-' + me.name ).getState() === 'opened' )
						$.data( this, 'kit-' + me.name ).close();
				});

			me._setPosition();
			
			if( me.options.animation !== null && me.options.animation !== false )
			{	
				me._openAnimation();
			}
			else
			{
				//Eva fix : replace all .-arrow to .arrow
				me.El.find('.arrow').css({opacity: 1});
				me.El.show();
			}
			
			me.state = 'opened';
			$me.trigger('open.' + me.name);
		}

		$me.trigger('ifOpenedOrNot.' + me.name);
	}

	Popup.prototype._openAnimation = function() {
		var me  = this;
		var $me = $(me.element);

		if( $.easing !== undefined && (me.options.animation.split(/[ ,]+/)[1] in $.easing || me.options.animation.split(/[ ,]+/)[0] in $.easing) ) {
			me.El.animate({opacity:1},50).slideDown({
				duration: me.options.animationDuration,
				easing: me.options.animation.split(/[ ,]+/)[0],
				complete: function(){
					me.El.find('.arrow').animate({opacity: 1});
				}
			});
		}
		else {
			me.El.show().animate({opacity:1},50);
			this.El.find('.arrow').css({opacity: 1});
			if( Modernizr && Modernizr.csstransitions && Modernizr.csstransforms3d && Modernizr.cssanimations )
				me.El.addClass('-mx-start');
		}
	}

	Popup.prototype.close = function() {
		var me  = this;
		var $me = $(me.element);
		
		clearTimeout( me.timeout );

		if( me.options.enabled === true && me.state !== 'closed' ) {

			me.state = 'out';

			if( me.options.beforeClose != undefined && (typeof me.options.beforeClose === 'object' || typeof me.options.beforeClose === 'function' ))
			{
				
				try {
					var deferred = me.options.beforeClose.call( $me );
					deferred
						.done(function(){
							me._close();
						})
						.fail(function(){
							$me.trigger('ifNotClosed.' + me.name);
							$me.trigger('ifClosedOrNot.' + me.name);
							me.state = 'opened';
						})
				} catch( e ) {
					me._close();
				}
				
			}
			else {
				me._close();
			}
		}
	}

	Popup.prototype._close = function() {
		var me  = this;
		var $me = $(me.element);

		if( me.state === 'out' ) {
			
			if( me.options.animation === null )
				me.El.hide();
			else {
				me._closeAnimation();
			}
			me.state = 'closed';

			$me.trigger('close');	
		}
		
		$me.trigger('ifClosedOrNot.' + me.name);
	}

	Popup.prototype._closeAnimation = function() {
		var me  = this;
		var $me = $(me.element);

		if( $.easing !== undefined && (me.options.animation.split(/[ ,]+/)[1] in $.easing || me.options.animation.split(/[ ,]+/)[0] in $.easing) ) {
			me.El.animate({opacity:0},200).slideUp({
				duration: me.options.animationDuration,
				easing: me.options.animation.split(/[ ,]+/)[1] !== undefined ? me.options.animation.split(/[ ,]+/)[1] : me.options.animation,
				complete: function(){
					me.El.find('.arrow').animate({opacity: 0});
				}
			});
		}
		else {
			if( Modernizr && Modernizr.csstransitions && Modernizr.csstransforms3d && Modernizr.cssanimations ) {
				me.El.animate({opacity:0},200).removeClass('-mx-start');
				setTimeout(function(){
					if ( me.state === 'closed' )
						me.El.hide()
				},1000)
			}
			else
				me.El.animate({opacity:0},200).hide();
		}
	}

	Popup.prototype._setPosition = function() {
		var me  = this;
		var $me = $(me.element);

		var actualWidth = $me.outerWidth() ;
		var actualHeight = $me.outerHeight() ;
		var actualPosition = $me.offset();
		var arrowSize = 8;
// console.log(actualPosition, $me.height(), actualHeight, parseInt($me.css('paddingTop')), parseInt($me.css('paddingBottom')));
		
		var _zIndex = 1
		,	_position = 'absolute';
		$.each( $me.parents(), function( index_, item_ ) {
			if( $(item_).css('z-index') !== 'auto' && parseInt( $(item_).css('z-index')) > _zIndex )
				_zIndex = $(item_).css('z-index') + 1;
			if( $(item_).css('position') === 'fixed' )
				_position = 'fixed';
		});
		if( _position === 'fixed' ) {
			actualPosition.top = actualPosition.top - $(document).scrollTop();
		}
		me.El.css({'z-index': _zIndex, 'position': _position});

		var pos = {}

		switch( me.options.placement ) {
			case 'top':
				pos = { top: Math.round( actualPosition.top - me.El.outerHeight() - arrowSize + me.options.offset[0] ), left: Math.round( actualPosition.left + actualWidth / 2 - me.El.outerWidth() / 2 + me.options.offset[1] ) }
			break;

			case 'bottom':
				pos = { top: Math.round( actualPosition.top + actualHeight + arrowSize + me.options.offset[0] ), left: Math.round( actualPosition.left + actualWidth / 2 - me.El.outerWidth() / 2 + me.options.offset[1] ) };
			break;

			case 'left':
				pos = { top: Math.round( actualPosition.top + actualHeight / 2 - me.El.outerHeight() / 2 ), left: Math.round(actualPosition.left - me.El.outerWidth() - arrowSize + me.options.offset[1]) }
			break;

			case 'right':
				pos = { top: Math.round(actualPosition.top + actualHeight / 2 - me.El.outerHeight() / 2), left: Math.round(actualPosition.left + actualWidth + arrowSize + me.options.offset[1])}
			break;
		}

		me.El.css(pos);
	}

	$.fn[_name] = function( options_ ) {
		return this.each(function() {
			if( ! $.data( this, 'kit-' + _name ) ) {
				$.data( this, 'kit-' + _name, new Popup( this, options_ ) );
			}
			else {
				typeof options_ === 'object' ? $.data( this, 'kit-' + _name )._setOptions( options_ ) :
					typeof options_ === 'string' && options_.charAt(0) !== '_' ? $.data( this, 'kit-' + _name )[ options_ ] : $.error( 'What do you want to do?' );
			}
		});
	}
	
	

})( jQuery, window, document );

