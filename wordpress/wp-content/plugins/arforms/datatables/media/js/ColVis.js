(function($) {

ColVis = function( oDTSettings, oInit )
{
	if(document.getElementById("show_hide_columns"))
		var show_hide_columns = document.getElementById("show_hide_columns").value;
	else
		var show_hide_columns = '';
	
	
	if ( !this.CLASS || this.CLASS != "ColVis" )
	{
		alert( "Warning: ColVis must be initialised with the keyword 'new'" );
	}
	
	if ( typeof oInit == 'undefined' )
	{
		oInit = {};
	}
	
	
	
	
	
	this.s = {
		
		"dt": null,
		
		
		"oInit": oInit,
		
		
		"fnStateChange": null,
		
		
		"activate": "click",
		
		
		"sAlign": "left",
		
		
		"buttonText": show_hide_columns,
		
		
		"hidden": true,
		
		
		"aiExclude": [],
		
		
		"abOriginal": [],
		
		
		"bShowAll": false,
		
		
		"sShowAll": "Show All",
		
		
		"bRestore": false,
		
		
		"sRestore": "Restore original",
		
		
		"iOverlayFade": 500,
		
		
		"fnLabel": null,
		
		
		"sSize": "auto",
		
		
		"bCssPosition": false
	};
	
	
	
	this.dom = {
		
		"wrapper": null,
		
		
		"button": null,
		
		
		"collection": null,
		
		
		"background": null,
		
		
		"catcher": null,
		
		
		"buttons": [],
		
		
		"restore": null
	};
	
	
	ColVis.aInstances.push( this );
	
	
	this.s.dt = oDTSettings;
	this._fnConstruct();
	return this;
};



ColVis.prototype = {
	
	"fnRebuild": function ()
	{
		
		for ( var i=this.dom.buttons.length-1 ; i>=0 ; i-- )
		{
			if ( this.dom.buttons[i] !== null )
			{
				this.dom.collection.removeChild( this.dom.buttons[i] );
			}
		}
		this.dom.buttons.splice( 0, this.dom.buttons.length );
		
		if ( this.dom.restore )
		{
			this.dom.restore.parentNode( this.dom.restore );
		}
		
		
		this._fnAddButtons();
		
		
		this._fnDrawCallback();
	},
	
	
	
	
	"_fnConstruct": function ()
	{
		this._fnApplyCustomisation();
		
		var that = this;
		var i, iLen;
		this.dom.wrapper = document.createElement('div');
		this.dom.wrapper.className = "ColVis TableTools";
		
		this.dom.button = this._fnDomBaseButton( this.s.buttonText );
		this.dom.button.className += " ColVis_MasterButton";
		this.dom.wrapper.appendChild( this.dom.button );
		
		this.dom.catcher = this._fnDomCatcher();
		this.dom.collection = this._fnDomCollection();
		this.dom.background = this._fnDomBackground();
		
		this._fnAddButtons();
		
		
		for ( i=0, iLen=this.s.dt.aoColumns.length ; i<iLen ; i++ )
		{
			this.s.abOriginal.push( this.s.dt.aoColumns[i].bVisible );
		}
		
		
		this.s.dt.aoDrawCallback.push( {
			"fn": function () {
				that._fnDrawCallback.call( that );
			},
			"sName": "ColVis"
		} );

		
		$(this.s.dt.oInstance).bind( 'column-reorder', function ( e, oSettings, oReorder ) {
			for ( i=0, iLen=that.s.aiExclude.length ; i<iLen ; i++ ) {
				that.s.aiExclude[i] = oReorder.aiInvertMapping[ that.s.aiExclude[i] ];
			}

			var mStore = that.s.abOriginal.splice( oReorder.iFrom, 1 )[0];
			that.s.abOriginal.splice( oReorder.iTo, 0, mStore );
			
			that.fnRebuild();
		} );
	},
	
	
	
	"_fnApplyCustomisation": function ()
	{
		var oConfig = this.s.oInit;
		
		if ( typeof oConfig.activate != 'undefined' )
		{
			this.s.activate = oConfig.activate;
		}
		
		if ( typeof oConfig.buttonText != 'undefined' )
		{
			this.s.buttonText = oConfig.buttonText;
		}
		
		if ( typeof oConfig.aiExclude != 'undefined' )
		{
			this.s.aiExclude = oConfig.aiExclude;
		}
		
		if ( typeof oConfig.bRestore != 'undefined' )
		{
			this.s.bRestore = oConfig.bRestore;
		}
		
		if ( typeof oConfig.sRestore != 'undefined' )
		{
			this.s.sRestore = oConfig.sRestore;
		}
		
		if ( typeof oConfig.bShowAll != 'undefined' )
		{
			this.s.bShowAll = oConfig.bShowAll;
		}
		
		if ( typeof oConfig.sShowAll != 'undefined' )
		{
			this.s.sShowAll = oConfig.sShowAll;
		}
		
		if ( typeof oConfig.sAlign != 'undefined' )
		{
			this.s.sAlign = oConfig.sAlign;
		}
		
		if ( typeof oConfig.fnStateChange != 'undefined' )
		{
			this.s.fnStateChange = oConfig.fnStateChange;
		}
		
		if ( typeof oConfig.iOverlayFade != 'undefined' )
		{
			this.s.iOverlayFade = oConfig.iOverlayFade;
		}
		
		if ( typeof oConfig.fnLabel != 'undefined' )
		{
			this.s.fnLabel = oConfig.fnLabel;
		}
		
		if ( typeof oConfig.sSize != 'undefined' )
		{
			this.s.sSize = oConfig.sSize;
		}

		if ( typeof oConfig.bCssPosition != 'undefined' )
		{
			this.s.bCssPosition = oConfig.bCssPosition;
		}
	},
	
	
	
	"_fnDrawCallback": function ()
	{
		var aoColumns = this.s.dt.aoColumns;
		
		for ( var i=0, iLen=aoColumns.length ; i<iLen ; i++ )
		{
			if ( this.dom.buttons[i] !== null )
			{
				if ( aoColumns[i].bVisible )
				{
					$('input', this.dom.buttons[i]).attr('checked','checked');
				}
				else
				{
					$('input', this.dom.buttons[i]).removeAttr('checked');
				}
			}
		}
	},
	
	
	
	"_fnAddButtons": function ()
	{
		var
			nButton,
			sExclude = ","+this.s.aiExclude.join(',')+",";
		
		for ( var i=0, iLen=this.s.dt.aoColumns.length ; i<iLen ; i++ )
		{
			if ( sExclude.indexOf( ","+i+"," ) == -1 )
			{
				nButton = this._fnDomColumnButton( i );
				this.dom.buttons.push( nButton );
				this.dom.collection.appendChild( nButton );
			}
			else
			{
				this.dom.buttons.push( null );
			}
		}
		
		if ( this.s.bRestore )
		{
			nButton = this._fnDomRestoreButton();
			nButton.className += " ColVis_Restore";
			this.dom.buttons.push( nButton );
			this.dom.collection.appendChild( nButton );
		}
		
		if ( this.s.bShowAll )
		{
			nButton = this._fnDomShowAllButton();
			nButton.className += " ColVis_ShowAll";
			this.dom.buttons.push( nButton );
			this.dom.collection.appendChild( nButton );
		}
	},
	
	
	
	"_fnDomRestoreButton": function ()
	{
		var
			that = this,
			nButton = document.createElement('button'),
			nSpan = document.createElement('span');
		
		nButton.className = !this.s.dt.bJUI ? "ColVis_Button TableTools_Button" :
			"ColVis_Button TableTools_Button ui-button ui-state-default";
		nButton.appendChild( nSpan );
		$(nSpan).html( '<span class="ColVis_title">'+this.s.sRestore+'</span>' );
		
		$(nButton).click( function (e) {
			for ( var i=0, iLen=that.s.abOriginal.length ; i<iLen ; i++ )
			{
				that.s.dt.oInstance.fnSetColumnVis( i, that.s.abOriginal[i], false );
			}
			that._fnAdjustOpenRows();
			that.s.dt.oInstance.fnAdjustColumnSizing( false );
			that.s.dt.oInstance.fnDraw( false );
		} );
		
		return nButton;
	},
	
	
	
	"_fnDomShowAllButton": function ()
	{
		var
			that = this,
			nButton = document.createElement('button'),
			nSpan = document.createElement('span');
		
		nButton.className = !this.s.dt.bJUI ? "ColVis_Button TableTools_Button" :
			"ColVis_Button TableTools_Button ui-button ui-state-default";
		nButton.appendChild( nSpan );
		$(nSpan).html( '<span class="ColVis_title">'+this.s.sShowAll+'</span>' );
		
		$(nButton).click( function (e) {
			for ( var i=0, iLen=that.s.abOriginal.length ; i<iLen ; i++ )
			{
				if (that.s.aiExclude.indexOf(i) === -1)
				{
					that.s.dt.oInstance.fnSetColumnVis( i, true, false );
				}
			}
			that._fnAdjustOpenRows();
			that.s.dt.oInstance.fnAdjustColumnSizing( false );
			that.s.dt.oInstance.fnDraw( false );
		} );
		
		return nButton;
	},
	
	
	
	"_fnDomColumnButton": function ( i )
	{
		var
			that = this,
			oColumn = this.s.dt.aoColumns[i],
			nButton = document.createElement('button'),
			nSpan = document.createElement('span'),
			dt = this.s.dt;
		
		nButton.className = !dt.bJUI ? "ColVis_Button TableTools_Button" :
			"ColVis_Button TableTools_Button ui-button ui-state-default";
		nButton.appendChild( nSpan );
		var sTitle = this.s.fnLabel===null ? oColumn.sTitle : this.s.fnLabel( i, oColumn.sTitle, oColumn.nTh );
		$(nSpan).html(
			'<span class="ColVis_radio"><input type="checkbox"/></span>'+
			'<span class="ColVis_title">'+sTitle+'</span>' );
		
		$(nButton).click( function (e) {
			var showHide = !$('input', this).is(":checked");
			if ( e.target.nodeName.toLowerCase() == "input" )
			{
				showHide = $('input', this).is(":checked");
			}
			
			
			var oldIndex = $.fn.dataTableExt.iApiIndex;
			$.fn.dataTableExt.iApiIndex = that._fnDataTablesApiIndex.call(that);


			if ( dt.oFeatures.bServerSide && (dt.oScroll.sX !== "" || dt.oScroll.sY !== "" ) )
			{
				that.s.dt.oInstance.fnSetColumnVis( i, showHide, false );
				that.s.dt.oInstance.fnAdjustColumnSizing( false );
				that.s.dt.oInstance.oApi._fnScrollDraw( that.s.dt );
				that._fnDrawCallback();
			}
			else
			{
				that.s.dt.oInstance.fnSetColumnVis( i, showHide );
			}

			$.fn.dataTableExt.iApiIndex = oldIndex; 
			
			if ( that.s.fnStateChange !== null )
			{
				that.s.fnStateChange.call( that, i, showHide );
			}
		} );
		
		return nButton;
	},
	
	
	
	"_fnDataTablesApiIndex": function ()
	{
		for ( var i=0, iLen=this.s.dt.oInstance.length ; i<iLen ; i++ )
		{
			if ( this.s.dt.oInstance[i] == this.s.dt.nTable )
			{
				return i;
			}
		}
		return 0;
	},
	
	
	
	"_fnDomBaseButton": function ( text )
	{
		var
			that = this,
			nButton = document.createElement('button'),
			nSpan = document.createElement('span'),
			sEvent = this.s.activate=="mouseover" ? "mouseover" : "click";
		
		nButton.className = !this.s.dt.bJUI ? "ColVis_Button TableTools_Button" :
			"ColVis_Button TableTools_Button ui-button ui-state-default";
		nButton.appendChild( nSpan );
		nSpan.innerHTML = text;
		
		$(nButton).bind( sEvent, function (e) {
			that._fnCollectionShow();
			e.preventDefault();
		} );
		
		return nButton;
	},
	
	
	
	"_fnDomCollection": function ()
	{
		var that = this;
		var nHidden = document.createElement('div');
		nHidden.style.display = "none";
		nHidden.className = !this.s.dt.bJUI ? "ColVis_collection TableTools_collection" :
			"ColVis_collection TableTools_collection ui-buttonset ui-buttonset-multi";
		
		if ( !this.s.bCssPosition )
		{
			nHidden.style.position = "absolute";
		}
		$(nHidden).css('opacity', 0);
		
		return nHidden;
	},
	
	
	
	"_fnDomCatcher": function ()
	{
		var 
			that = this,
			nCatcher = document.createElement('div');
		nCatcher.className = "ColVis_catcher TableTools_catcher";
		
		$(nCatcher).click( function () {
			that._fnCollectionHide.call( that, null, null );
		} );
		
		return nCatcher;
	},
	
	
	
	"_fnDomBackground": function ()
	{
		var that = this;
		
		var nBackground = document.createElement('div');
		nBackground.style.position = "absolute";
		nBackground.style.left = "0px";
		nBackground.style.top = "0px";
		nBackground.className = "ColVis_collectionBackground TableTools_collectionBackground";
		$(nBackground).css('opacity', 0);
		
		$(nBackground).click( function () {
			that._fnCollectionHide.call( that, null, null );
		} );
		
		
		if ( this.s.activate == "mouseover" )
		{
			$(nBackground).mouseover( function () {
				that.s.overcollection = false;
				that._fnCollectionHide.call( that, null, null );
			} );
		}
		
		return nBackground;
	},
	
	
	
	"_fnCollectionShow": function ()
	{
		var that = this, i, iLen;
		var oPos = $(this.dom.button).offset();
		var nHidden = this.dom.collection;
		var nBackground = this.dom.background;
		var iDivX = parseInt(oPos.left, 10);
		var iDivY = parseInt(oPos.top + $(this.dom.button).outerHeight(), 10);
		
		if ( !this.s.bCssPosition )
		{
			nHidden.style.top = iDivY+"px";
			nHidden.style.left = iDivX+"px";
		}
		nHidden.style.display = "block";
		$(nHidden).css('opacity',0);
		
		var iWinHeight = $(window).height(), iDocHeight = $(document).height(),
		 	iWinWidth = $(window).width(), iDocWidth = $(document).width();
		
		nBackground.style.height = ((iWinHeight>iDocHeight)? iWinHeight : iDocHeight) +"px";
		nBackground.style.width = ((iWinWidth<iDocWidth)? iWinWidth : iDocWidth) +"px";
		
		var oStyle = this.dom.catcher.style;
		oStyle.height = $(this.dom.button).outerHeight()+"px";
		oStyle.width = $(this.dom.button).outerWidth()+"px";
		oStyle.top = oPos.top+"px";
		oStyle.left = iDivX+"px";
		
		document.body.appendChild( nBackground );
		document.body.appendChild( nHidden );
		document.body.appendChild( this.dom.catcher );
		
		
		if ( this.s.sSize == "auto" )
		{
			var aiSizes = [];
			this.dom.collection.style.width = "auto";
			for ( i=0, iLen=this.dom.buttons.length ; i<iLen ; i++ )
			{
				if ( this.dom.buttons[i] !== null )
				{
					this.dom.buttons[i].style.width = "auto";
					aiSizes.push( $(this.dom.buttons[i]).outerWidth() );
				}
			}
			iMax = Math.max.apply(window, aiSizes);
			for ( i=0, iLen=this.dom.buttons.length ; i<iLen ; i++ )
			{
				if ( this.dom.buttons[i] !== null )
				{
					this.dom.buttons[i].style.width = iMax+"px";
				}
			}
			this.dom.collection.style.width = iMax+"px";
		}
		
		
		if ( !this.s.bCssPosition )
		{
			nHidden.style.left = this.s.sAlign=="left" ?
				iDivX+"px" : (iDivX-$(nHidden).outerWidth()+$(this.dom.button).outerWidth())+"px";

			var iDivWidth = $(nHidden).outerWidth();
			var iDivHeight = $(nHidden).outerHeight();
			
			if ( iDivX + iDivWidth > iDocWidth )
			{
				nHidden.style.left = (iDocWidth-iDivWidth)+"px";
			}
		}
		
		
		setTimeout( function () {
			$(nHidden).animate({"opacity": 1}, that.s.iOverlayFade);
			$(nBackground).animate({"opacity": 0.1}, that.s.iOverlayFade, 'linear', function () {
				
				if ( jQuery.browser.msie && jQuery.browser.version == "6.0" )
				{
					that._fnDrawCallback();
				}
			});
		}, 10 );
		
		this.s.hidden = false;
	},
	
	
	
	"_fnCollectionHide": function (  )
	{
		var that = this;
		
		if ( !this.s.hidden && this.dom.collection !== null )
		{
			this.s.hidden = true;
			
			$(this.dom.collection).animate({"opacity": 0}, that.s.iOverlayFade, function (e) {
				this.style.display = "none";
			} );
			
			$(this.dom.background).animate({"opacity": 0}, that.s.iOverlayFade, function (e) {
				document.body.removeChild( that.dom.background );
				document.body.removeChild( that.dom.catcher );
			} );
		}
	},
	
	
	
	"_fnAdjustOpenRows": function ()
	{
		var aoOpen = this.s.dt.aoOpenRows;
		var iVisible = this.s.dt.oApi._fnVisbleColumns( this.s.dt );
		
		for ( var i=0, iLen=aoOpen.length ; i<iLen ; i++ ) {
			aoOpen[i].nTr.getElementsByTagName('td')[0].colSpan = iVisible;
		}
	}
};






ColVis.fnRebuild = function ( oTable )
{
	var nTable = null;
	if ( typeof oTable != 'undefined' )
	{
		nTable = oTable.fnSettings().nTable;
	}
	
	for ( var i=0, iLen=ColVis.aInstances.length ; i<iLen ; i++ )
	{
		if ( typeof oTable == 'undefined' || nTable == ColVis.aInstances[i].s.dt.nTable )
		{
			ColVis.aInstances[i].fnRebuild();
		}
	}
};






ColVis.aInstances = [];






ColVis.prototype.CLASS = "ColVis";



ColVis.VERSION = "1.0.8";
ColVis.prototype.VERSION = ColVis.VERSION;






if ( typeof $.fn.dataTable == "function" &&
     typeof $.fn.dataTableExt.fnVersionCheck == "function" &&
     $.fn.dataTableExt.fnVersionCheck('1.7.0') )
{
	$.fn.dataTableExt.aoFeatures.push( {
		"fnInit": function( oDTSettings ) {
			var init = (typeof oDTSettings.oInit.oColVis == 'undefined') ?
				{} : oDTSettings.oInit.oColVis;
			var oColvis = new ColVis( oDTSettings, init );
			return oColvis.dom.wrapper;
		},
		"cFeature": "C",
		"sFeature": "ColVis"
	} );
}
else
{
	alert( "Warning: ColVis requires DataTables 1.7 or greater - www.datatables.net/download");
}

})(jQuery);
