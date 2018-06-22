var x3History = jQuery.noConflict();
x3History(function($) {
	var undoManager = new UndoManager();

	pdcUndoManage = {
		canvasHistoies : [],
		historyId : 0,
		historyUpdateNumber : 0,
		historySizeJson : 0,
		undoRedoClick : 0,
		defaultHistory : {},
		duringUpdateText : 1,
		firstSelect : 0,
		objAdded : function()
		{
			if(pdcUndoManage.undoRedoClick !== 1 && pdcUndoManage.firstSelect == 1)
			{
				var currentCanvans = pdc.getCurrentCanvas();
				pdcUndoManage.historyId++;
				var customAttrs = ['name', 'isrc', 'price', 'object_type', 'selectable', 'scale', 'evented'];
				var canvasJson = currentCanvans.toJSON(customAttrs);
				var attrs = {
					id : pdcUndoManage.historyId,
					json : canvasJson
				}
				pdcUndoManage.addHistories(attrs,2);
				undoManager.setCallback(pdcUndoManage.undoManagerUI());
			}
			if(pdcUndoManage.undoRedoClick == 1)
			{
				pdcUndoManage.historyUpdateNumber++;
				if(pdcUndoManage.historyUpdateNumber == pdcUndoManage.historySizeJson)
				{
					pdcUndoManage.undoRedoClick = 0;
				}
			}
		},
		objModified : function()
		{
			var currentCanvans = pdc.getCurrentCanvas();
			pdcUndoManage.historyId++;
			var customAttrs = ['name', 'isrc', 'price', 'object_type', 'selectable', 'scale', 'evented'];
			var canvasJson = currentCanvans.toJSON(customAttrs);
			var attrs = {
				id : pdcUndoManage.historyId,
				json : canvasJson
			}
			pdcUndoManage.addHistories(attrs,2);
			undoManager.setCallback(pdcUndoManage.undoManagerUI());
		},
		removeObjecHistory : function(id)
		{
			var i = 0, index = -1;
			for (i = 0; i < this.canvasHistoies.length; i += 1) {
				if (this.canvasHistoies[i].id === id) {
					index = i;
				}
			}
			if (index !== -1) {
				this.canvasHistoies.splice(index, 1);
			}
			this.undoResoCanvas();
		},
		//add history for canvas
		addHistories : function(attrs,restoreCanvan)
		{
			var self = this;
			this.canvasHistoies.push(attrs);
			var resCanvan = restoreCanvan || 1;
			if(resCanvan == 1)
			{
				this.undoResoCanvas();
			}
			undoManager.add({
				undo: function () {
					self.removeObjecHistory(attrs.id);
				},
				redo: function () {
					self.addHistories(attrs,1);
				}
			});
		},
		undoResoCanvas : function()
		{
			var currentHistoryJson = null;
			//console.log(this.canvasHistoies);
			if(this.canvasHistoies.length > 0)
			{
				for(var i = 0; i < this.canvasHistoies.length; i++)
				{
					currentHistoryJson = this.canvasHistoies[i].json;
				}
				this.historySizeJson = currentHistoryJson.objects.length;
				this.historyUpdateNumber = 0;
				currentHistoryJson = JSON.stringify(currentHistoryJson);
				//console.log(currentHistoryJson);
				if(currentHistoryJson)
				{
					var canvas = pdc.getCurrentCanvas();
					canvas.loadFromJSON(currentHistoryJson, canvas.renderAll.bind(canvas), function(o, object) {});
				}
			}
			else
			{
				var currentHistoryJson = JSON.stringify(defaultHistory);
				var canvas = pdc.getCurrentCanvas();
					canvas.loadFromJSON(currentHistoryJson, canvas.renderAll.bind(canvas), function(o, object) {});
			}
			
		},
		undoManagerUI : function()
		{
			if(undoManager.hasUndo())
			{
				$('#pdc-x3-undo').addClass('pdc-x3-undo-redo-active');
				$('#pdc-x3-undo').removeClass('pdc-x3-undo-redo-inactive');
			}
			else
			{
				$('#pdc-x3-undo').removeClass('pdc-x3-undo-redo-active');
				$('#pdc-x3-undo').addClass('pdc-x3-undo-redo-inactive');
			}
			if(undoManager.hasRedo())
			{
				$('#pdc-x3-redo').addClass('pdc-x3-undo-redo-active');
				$('#pdc-x3-redo').removeClass('pdc-x3-undo-redo-inactive');
			}
			else
			{
				$('#pdc-x3-redo').removeClass('pdc-x3-undo-redo-active');
				$('#pdc-x3-redo').addClass('pdc-x3-undo-redo-inactive');
			}
		},
		updateTextHistory : function()
		{
			var self = this;
			var currentCanvans = pdc.getCurrentCanvas();
			var customAttrs = ['name', 'isrc', 'price', 'object_type', 'selectable', 'scale', 'evented'];
			canvasJson = currentCanvans.toJSON(customAttrs);
				var attrs = {
				id : pdcUndoManage.historyId,
				json : canvasJson
			}
			self.addHistories(attrs,2);
			undoManager.setCallback(self.undoManagerUI());
		},
		objSelected : function ()
		{
			//make default json
			if(pdcUndoManage.firstSelect == 0)
			{
				var currentCanvans = pdc.getCurrentCanvas();
				var customAttrs = ['name', 'isrc', 'price', 'object_type', 'selectable', 'scale', 'evented'];
				defaultHistory = currentCanvans.toJSON(customAttrs);
				pdcUndoManage.firstSelect = 1;
			}
		},
		init : function()
		{
			var self = this;
			undoManager.setLimit(10);
			$('#pdc-x3-undo').click(function(){
				self.undoRedoClick = 1;
				undoManager.undo();
				self.undoManagerUI();
			})
			$('#pdc-x3-redo').click(function(){
				self.undoRedoClick = 1;
				undoManager.redo();
				self.undoManagerUI();
			});
			$('#text_edit_form').change(function(){
				self.duringUpdateText = 1;
				var currentCanvans = pdc.getCurrentCanvas();
				var customAttrs = ['name', 'isrc', 'price', 'object_type', 'selectable', 'scale', 'evented'];
				canvasJson = currentCanvans.toJSON(customAttrs);
					var attrs = {
					id : pdcUndoManage.historyId,
					json : canvasJson
				}
				self.addHistories(attrs,2);
				undoManager.setCallback(self.undoManagerUI());
			});
			$('#text_edit_form').keydown(function(){
				self.duringUpdateText = 0;
			})
			/* var currentCanvans = pdc.getCurrentCanvas();
			var customAttrs = ['name', 'isrc', 'price', 'object_type', 'selectable', 'scale', 'evented'];
			defaultHistory = currentCanvans.toJSON(customAttrs); */
			//switch side
			$(document).on('click', '[pdc-action="SWITCH_SIDE"]', function() {
				self.undoRedoClick = 1;
				self.historyId = 0;
				var currentCanvans = pdc.getCurrentCanvas();
				self.canvasHistoies = [];
				var customAttrs = ['name', 'isrc', 'price', 'object_type', 'selectable', 'scale', 'evented'];
				defaultHistory = currentCanvans.toJSON(customAttrs);  
				undoManager.clear();
				self.undoManagerUI();
			});
			$(document).on('click', '[pdc-data="pdp-templates"]', function() {
				self.undoRedoClick = 1;
				self.historyId = 0;
				var currentCanvans = pdc.getCurrentCanvas();
				self.canvasHistoies = [];
				var customAttrs = ['name', 'isrc', 'price', 'object_type', 'selectable', 'scale', 'evented'];
				defaultHistory = currentCanvans.toJSON(customAttrs);  
				undoManager.clear();
				self.undoManagerUI();
			});
			//reset history when clicking to zoom
			$('.btn-zoom').on("click",function(){
				self.undoRedoClick = 1;
				self.historyId = 0;
				var currentCanvans = pdc.getCurrentCanvas();
				self.canvasHistoies = [];
				var customAttrs = ['name', 'isrc', 'price', 'object_type', 'selectable', 'scale', 'evented'];
				defaultHistory = currentCanvans.toJSON(customAttrs);  
				undoManager.clear();
				self.undoManagerUI();
			});
			$(document).on('click', '[pdc-data="SAVE_CUSTOMER_DESIGN"]', function() {
				self.undoRedoClick = 1;
				self.historyId = 0;
				var currentCanvans = pdc.getCurrentCanvas();
				self.canvasHistoies = [];
				var customAttrs = ['name', 'isrc', 'price', 'object_type', 'selectable', 'scale', 'evented'];
				defaultHistory = currentCanvans.toJSON(customAttrs);  
				undoManager.clear();
				self.undoManagerUI();
			});
			$(document).on('click', '.reset-btn', function() {
				self.undoRedoClick = 1;
				self.historyId = 0;
				var currentCanvans = pdc.getCurrentCanvas();
				self.canvasHistoies = [];
				var customAttrs = ['name', 'isrc', 'price', 'object_type', 'selectable', 'scale', 'evented'];
				defaultHistory = currentCanvans.toJSON(customAttrs);  
				undoManager.clear();
				self.undoManagerUI();
			});
			$(document).on("click", '.pdc-tabs', function() {
				if(self.firstSelect == 0)
				{
					var currentCanvans = pdc.getCurrentCanvas();
					var customAttrs = ['name', 'isrc', 'price', 'object_type', 'selectable', 'scale', 'evented'];
					defaultHistory = currentCanvans.toJSON(customAttrs);
					self.firstSelect = 1;
				}
			})
		}
	}
	pdcUndoManage.init();
	$.each(pdc.allCanvas, function(i, canvas) {
		canvas.observe('object:added', pdcUndoManage.objAdded);
		canvas.observe('object:modified', pdcUndoManage.objModified);
		canvas.observe('object:selected', pdcUndoManage.objSelected);
	});
})
