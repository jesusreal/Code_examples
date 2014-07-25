//-- Global object initialization
this.cishaTask = this.cishaTask || {};





//-- Variables initialization

cishaTask.initVariables = function () {
	
	defaultStartPosition = [
			['r', 'n', 'b', 'q', 'k', 'b', 'n', 'r'],
			['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p'],
			['-', '-', '-', '-', '-', '-', '-', '-'],
			['-', '-', '-', '-', '-', '-', '-', '-'],
			['-', '-', '-', '-', '-', '-', '-', '-'],
			['-', '-', '-', '-', '-', '-', '-', '-'],
			['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P'],
			['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R']
	];

	unicodeChessSymbols = {
			r		: '&#9820;'
		,   n		: '&#9822;'
		,   b		: '&#9821;'
		,   q		: '&#9819;'
		,   k		: '&#9818;'
		,   p		: '&#9823;'
		,   R		: '&#9814;'
		,   N		: '&#9816;'
		,   B		: '&#9815;'
		,   Q		: '&#9813;'
		,   K		: '&#9812;'
		,   P		: '&#9817;'
	};
	
	pieceMoves = {
			r		: {}
		,   n		: {}
		,   b		: {}
		,   q		: {}
		,   k		: {}
		,   p		: {}
		,   R		: {}
		,   N		: {}
		,   B		: {}
		,   Q		: {}
		,   K		: {}
		,   P		: {}
	};

};





//-- Chess board initialization

cishaTask.initChessBoard = function () {

	// Create chessBoardView instance (initializes chess board view)
	chessBoardView = new cishaTask.chessBoardViewConstructor('#demo', defaultStartPosition);
	
	// Configure User interface
	chessBoardView.configureUI();
	
	// Moves: 2-7 squares forward, for both black and white towers of the left side of the view
	// White rock
	chessBoardView.movePiece(0,0,2,0);
	chessBoardView.movePiece(0,0,3,0);
	chessBoardView.movePiece(0,0,4,0);
	chessBoardView.movePiece(0,0,5,0);
	chessBoardView.movePiece(0,0,6,0);
	chessBoardView.movePiece(0,0,7,0);
	// Black rock
	chessBoardView.movePiece(7,0,5,0);
	chessBoardView.movePiece(7,0,4,0);
	chessBoardView.movePiece(7,0,3,0);
	chessBoardView.movePiece(7,0,2,0);
	chessBoardView.movePiece(7,0,1,0);
	chessBoardView.movePiece(7,0,0,0);
	// invalid move, since there is no piece in this square
	chessBoardView.movePiece(2,0,2,4); 
};





//-- Chess Board View Constructor

cishaTask.chessBoardViewConstructor = function (container, startPosition) { 
	
	// Constructor
	this.constructor = function() {
		// Variables initialization (constructor)
		var html = '';
		
		// Squares loop
		for (var i=0; i<8; i++) {
			for (var j=0; j<8; j++) {
				// Variables initialization (squares loop)
				var squareColorClass = '';
				var piece = '';
				var currentPiece = startPosition[i][j];
				// Square id according to the task description (field in bottom left corner assigned to coord. y=0, x=0)
				var squareId = (7-i).toString()+j.toString();
				
				// Manage square CSS class
				if ( (i+j)%2 !== 0 ) {
					squareColorClass += 'dark';
				}
				
				// Check if square is not empty
				if (currentPiece!=='-') {
					var pieceColor = 'white';
					if (currentPiece.toLowerCase() === currentPiece) {
						pieceColor = 'black';
					}
					// Create piece
					piece = '<div id="'+currentPiece+'" class="piece '+pieceColor+'">'+unicodeChessSymbols[currentPiece]+'</div>';
				}
				
				// Add square
				html += '<div id="'+squareId+'" class="square '+squareColorClass+'">'+piece+'</div>';	
			}
		}
		// Add all the squares to the board
		$(html).appendTo(container);
	}();
	
	
	// Configure User Interface
	this.configureUI = function () {
		// Make empty squares droppable
		$('.square').each( function(index) {
			var squareId = $(this).attr("id");
			$(this).droppable({accept: '.'+squareId});	        
	    });

		// Make pieces draggable
		$('.piece').draggable({
			revert : function (droppable) {
				return !droppable && $(this).addClass('ui-draggable-reverted');
			}
		   ,	revertDuration: 0
		   ,	containment: '#demo'
		   , 	cursor: 'move'
//		   ,	grid: [80, 80]
		   , 	stop : function (event, ui) {
		   		// call to the onDragStop method
				chessBoardView.onDragStop(ui);
		   }
		});

		// Disable dragging on black pieces
		$('.piece.black').draggable('disable');
		// Show default text in dialog
		$('#menu #turn').html('<span>White</span> pieces start the game');
	};
	
	
	// Move piece method
	this.movePiece = function (y1, x1, y2, x2) {	
		// Obtain id of the origin element and destiny squares
   		var fromSquareId = y1.toString()+x1.toString();
   		var toSquareId = y2.toString()+x2.toString();		
		if ($('#'+fromSquareId+':parent').length>0) {
			var pieceName = $('#'+fromSquareId+' .piece').attr('id');
			if (!(pieceMoves[pieceName].hasOwnProperty(fromSquareId))){
				pieceMoves[pieceName][fromSquareId] = new Array();
			}
			if ($.inArray(toSquareId,pieceMoves[pieceName][fromSquareId])==-1) {
				pieceMoves[pieceName][fromSquareId].push(toSquareId);
				$("#"+fromSquareId+' .piece').addClass(toSquareId);
			}
		}
	};
	
	
	// On drag stop method
	this.onDragStop = function (ui) {
		// 'else' statement executed when performing a valid move
		 if (ui.helper.hasClass('ui-draggable-reverted')) {
			 ui.helper.removeClass('ui-draggable-reverted');
		 } 
		 else {
			var fromSquareId = ui.helper.parent().attr('id');
			// Center piece when moved, to avoid display errors with draggable 'grid' option
			var centerPiece = function (ui) {	
				squareWidth = ui.helper.width();
				var horizontal = Math.round(ui.position.left / squareWidth);
				var vertical = Math.round(ui.position.top / squareWidth);
				// center piece on square
				ui.helper.attr('style','left:'+(horizontal*squareWidth)+'px; top:'+(vertical*squareWidth)+'px');
				// Return destiny square id
				return (fromSquareId[0]-vertical).toString() + (parseInt(fromSquareId[1])+horizontal).toString();
			 };
				
		   	// Center piece in the square where it has been moved
			var toSquareId = centerPiece(ui);
			
			// Elimination of pieces
			if ($('#'+toSquareId+':parent').length>0){
				var fromElemPieceColor = cishaTask.getColor($('#'+fromSquareId+' .piece'));
				var toElemPieceColor = cishaTask.getColor($('#'+toSquareId+' .piece'));
				if (fromElemPieceColor!=toElemPieceColor) {
					$('#'+toSquareId+' .piece').remove();
				}	
			}
   		
			// Activate restart button
			$('#menu #restart').addClass('active');
	
			// Draggable squares and dialog content changes
			if ($('.piece.black').draggable( 'option', 'disabled' )) {
				$('.piece.black').draggable('enable');
				$('.piece.white').draggable('disable');	
				$('#menu #turn').html('Turn for <span>black</span> pieces');
			}
			else {
				$('.piece.white').draggable('enable');
				$('.piece.black').draggable('disable');
				$('#menu #turn').html('Turn for <span>white</span> pieces');
			}
		 }
	};
	
	
	// Reset Chess Board View
	this.resetChessBoardView = function () {		
		
		// Deactivate restart button
		$('#menu #restart').removeClass('active');
		// Remove current pieces
		$('.piece').remove();
		
		// Squares loop
		for (var i=0; i<8; i++) {
			for (var j=0; j<8; j++) {
				var currentPiece = startPosition[i][j];				
				// Check if square is not empty
				if (currentPiece!=='-') {
					// Variables initialization
					var piece = '';
					var pieceColor = 'white';
					// Square id according to the task description (field in bottom left corner assigned to coord. y=0, x=0)
					var squareId = (7-i).toString()+j.toString();
					var movePieceClasses = "";
					if (pieceMoves[currentPiece].hasOwnProperty(squareId)) {
						$.each(pieceMoves[currentPiece][squareId], function(index, value) {
							movePieceClasses += value + ' ';
						});
					}
					if (currentPiece.toLowerCase() === currentPiece) {
						pieceColor = 'black';
					}
					// Create piece
					piece = '<div class="piece '+pieceColor+' '+movePieceClasses+'">'+unicodeChessSymbols[currentPiece]+'</div>';
					// Add piece
					$(piece).appendTo($('#'+squareId));
				}
			}
		}
	};

};





//-- Click event management on menu buttons 

$(document).on('click','#menu #restart',function(){ 
	// Reset pieces position
	chessBoardView.resetChessBoardView();
	// Configure User interface
	chessBoardView.configureUI();
});





//-- Get the color of a piece
cishaTask.getColor = function (piece) {
	if (piece.hasClass('black')) {
		return 'black';
	}
	else { 
		return 'white';
	}
};













