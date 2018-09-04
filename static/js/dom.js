export function hasClass (el, className) {
  let reg = new RegExp('(^|\\s)' + className + '(\\s|$)')
  return reg.test(el.className)
}

export function addClass (el, className) {
  if (hasClass(el, className)) {
    return
  }

  let newClass = el.className.split(' ')
  newClass.push(className)
  el.className = newClass.join(' ')
}

export function getData (el, name, val) {
  const prefix = 'data-'
  name = prefix + name
  if (val) {
    return el.setAttribute(name, val)
  } else {
    return el.getAttribute(name)
  }
}

let elementStyle = document.createElement('div').style

let vendor = (() => {
  let transformNames = {
    webkit: 'webkitTransform',
    Moz: 'MozTransform',
    o: 'oTransform',
    ms: 'msTransform',
    standard: 'Transform'
  }
  for (let key in transformNames) {
    if (elementStyle[transformNames[key]] !== undefined) {
      return key
    }
  }
  return false
})()

export function prefixStyle (style) {
  if (vendor === false) {
    return false
  }
  if (vendor === 'standard') {
    return style
  }
  return vendor + style.charAt(0).toUpperCase() + style.substr(1)
}

function getStyle (obj, attr) {
  let val
  if (obj.currentStyle) {
    val = obj.currentStyle[attr]
  } else {
    val = document.defaultView.getComputedStyle(obj, null)[attr]
  }
  return parseInt(val)
}

export function calHei (parent, child, ...args) {
  let pH = parent.offsetHeight || parent.$el.offsetHeight
  let moveH = 0
  for (let arg of args) {
    let el = arg.$el || arg
    let thisH = el.offsetHeight + getStyle(el, 'marginTop') + getStyle(el, 'marginBottom')
    moveH += thisH
  };
  let c = child.$el || child
  let childM = getStyle(c, 'marginTop') + getStyle(c, 'marginBottom')
  let childH = pH - childM - moveH
  c.style.height = childH + 'px'
}
