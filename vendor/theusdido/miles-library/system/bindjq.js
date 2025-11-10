/* bindjq.js
   Dependência: jQuery
   Minimal two-way binding using Proxy + simple template parsing.
   API: const vm = BindJQ(rootElement, model);
*/

(function(global, $){
  if(!$) throw new Error('jQuery required');

  function BindJQ(root, model){
    this.$root = $(root || document.body);
    this.subscribers = []; // {el, expr, type}
    this.repeatTemplates = []; // {container, itemName, listName, templateNode}
    this.model = this._makeReactive(model || {});
    this._compile(this.$root);
    // initial render
    this._notifyAll();
    return this.model; // return reactive model for caller
  }

  // helpers
  BindJQ.prototype._get = function(path, scope){
    // path like "a.b" or "item.codigo"
    try {
      if(!path) return undefined;
      const parts = path.split('.');
      let cur = scope || this.model;
      for(let p of parts){
        if(cur === undefined || cur === null) return undefined;
        cur = cur[p];
      }
      return cur;
    } catch(e){ return undefined; }
  };

  BindJQ.prototype._set = function(path, value, scope){
    const parts = path.split('.');
    let cur = scope || this.model;
    for(let i=0;i<parts.length-1;i++){
      const p = parts[i];
      if(!(p in cur)) cur[p] = {};
      cur = cur[p];
    }
    cur[parts[parts.length-1]] = value;
  };

  // create reactive object using Proxy (handles nested objects & arrays)
  BindJQ.prototype._makeReactive = function(obj, pathPrefix){
    const self = this;
    if(obj && obj.__isProxy) return obj;
    if(Array.isArray(obj)){
      // wrap each element
      for(let i=0;i<obj.length;i++) obj[i] = this._makeReactive(obj[i], (pathPrefix?pathPrefix+'.':'')+i);
    } else if(obj && typeof obj === 'object'){
      Object.keys(obj).forEach(k => {
        obj[k] = this._makeReactive(obj[k], pathPrefix? (pathPrefix + '.' + k) : k);
      });
    }
    const handler = {
      set(target, prop, value){
        const fullPath = pathPrefix ? (pathPrefix + '.' + prop.toString()) : prop.toString();
        const newVal = self._makeReactive(value, fullPath);
        target[prop] = newVal;
        // notify
        self._notifyAll();
        return true;
      },
      deleteProperty(target, prop){
        delete target[prop];
        self._notifyAll();
        return true;
      }
    };
    const proxy = new Proxy(obj, handler);
    Object.defineProperty(proxy, '__isProxy', {value:true, enumerable:false});
    return proxy;
  };

  // compile DOM: text nodes with {{ }}, inputs with data-model, repeat templates
  BindJQ.prototype._compile = function($root){
    const self = this;

    // process repeats: elements with data-repeat="item in list"
    $root.find('[data-repeat]').each(function(){
      const $el = $(this);
      const expr = $el.attr('data-repeat').trim(); // "item in list"
      const m = expr.match(/^\s*([$\w]+)\s+in\s+([$\.\w]+)\s*$/);
      if(!m) return;
      const itemName = m[1], listName = m[2];
      // keep template: clone and remove original content from DOM (use comment placeholder)
      const $template = $el.clone();
      const $container = $el;
      $container.empty();
      self.repeatTemplates.push({
        container: $container,
        itemName,
        listName,
        templateNode: $template
      });
    });

    // process text nodes with {{ expr }}
    const walker = document.createTreeWalker($root[0], NodeFilter.SHOW_TEXT, null, false);
    const textNodes = [];
    while(walker.nextNode()){
      const node = walker.currentNode;
      if(node.nodeValue && node.nodeValue.indexOf('{{') !== -1){
        textNodes.push(node);
      }
    }
    textNodes.forEach(node => {
      const txt = node.nodeValue;
      // surrounding span to host dynamic text
      const $span = $('<span></span>');
      const $node = $(node);
      $node.replaceWith($span);
      // parse multiple expressions in same text
      const parts = txt.split(/(\{\{[\s\S]+?\}\})/g).filter(Boolean);
      parts.forEach(p => {
        const m = p.match(/^\{\{\s*([\s\S]+?)\s*\}\}$/);
        if(m){
          const expr = m[1].trim();
          const dyn = $('<span data-bind-text></span>');
          dyn.attr('data-bind-expr', expr);
          $span.append(dyn);
          self.subscribers.push({el: dyn, expr, type: 'text'});
        } else {
          $span.append(document.createTextNode(p));
        }
      });
    });

    // inputs with data-model
    $root.find('[data-model]').each(function(){
      const $el = $(this);
      const expr = $el.attr('data-model').trim();
      self.subscribers.push({el: $el, expr, type: 'model'});
      // listen for changes to update model
      const event = $el.is('input[type=checkbox], input[type=radio]') ? 'change' : 'input';
      $el.on(event, function(){
        let val;
        if($el.is(':checkbox')) val = $el.prop('checked');
        else val = $el.val();
        self._set(expr, val);
      });
    });
  };

  // re-render repeats and update subscribers
  BindJQ.prototype._notifyAll = function(){
    // first handle repeats
    this.repeatTemplates.forEach(rt => {
      const list = this._get(rt.listName);
      const $container = rt.container;
      $container.empty();
      if(!Array.isArray(list)) return;
      for(let i=0;i<list.length;i++){
        // clone template node content
        const $clone = rt.templateNode.clone();
        // for cloned subtree, we need to bind expressions that reference itemName
        // simple approach: replace {{ item.prop }} inside text and data-model attributes
        // and support nested data-repeat as well by instantiating a temporary binder scope.
        const html = $clone.html();
        // create a temp DOM to perform simple replacements for expressions referencing itemName
        const temp = $('<div></div>').html(html);
        // replace text nodes with {{ itemName.x }}
        const walker = document.createTreeWalker(temp[0], NodeFilter.SHOW_TEXT, null, false);
        const nodes = [];
        while(walker.nextNode()) nodes.push(walker.currentNode);
        nodes.forEach(node => {
          const txt = node.nodeValue;
          if(!txt || txt.indexOf('{{')===-1) return;
          const parts = txt.split(/(\{\{[\s\S]+?\}\})/g).filter(Boolean);
          const out = parts.map(p => {
            const m = p.match(/^\{\{\s*([\s\S]+?)\s*\}\}$/);
            if(m){
              const expr = m[1].trim();
              if(expr.indexOf(rt.itemName + '.') === 0){
                const path = expr.replace(rt.itemName + '.', '');
                return self._get(path, list[i]) ?? '';
              } else {
                return self._get(expr) ?? '';
              }
            } else return p;
          }).join('');
          $(node).replaceWith(document.createTextNode(out));
        });
        // handle inputs with data-model inside template
        temp.find('[data-model]').each(function(){
          const $el = $(this);
          const expr = $el.attr('data-model').trim();
          if(expr.indexOf(rt.itemName + '.')===0){
            const path = expr.replace(rt.itemName + '.', '');
            const val = self._get(path, list[i]);
            if($el.is(':checkbox')) $el.prop('checked', !!val);
            else $el.val(val);
            // attach input event to update the array item
            const event = $el.is('input[type=checkbox]') ? 'change' : 'input';
            $el.on(event, function(){
              let v = $el.is(':checkbox') ? $el.prop('checked') : $el.val();
              // update the actual array item (proxied)
              self._set(`${rt.listName}.${i}.${path}`, v);
            });
          } else {
            // model that references root model; set value from root
            const val = self._get(expr);
            if($el.is(':checkbox')) $el.prop('checked', !!val);
            else $el.val(val);
            $el.on('input change', function(){
              const v = $el.is(':checkbox') ? $el.prop('checked') : $el.val();
              self._set(expr, v);
            });
          }
        });

        $container.append(temp.contents());
      }
    });

    // update simple subscribers (text & inputs outside repeats)
    this.subscribers.forEach(sub => {
      if(sub.type === 'text'){
        const val = this._safeEval(sub.expr);
        sub.el.text(val === undefined || val === null ? '' : val);
      } else if(sub.type === 'model'){
        const val = this._get(sub.expr);
        if(sub.el.is(':checkbox')) sub.el.prop('checked', !!val);
        else sub.el.val(val === undefined || val === null ? '' : val);
      }
    });
  };

  BindJQ.prototype._safeEval = function(expr){
    // support simple dotted paths only
    // If expression contains + or function calls, ignore for now
    expr = expr.trim();
    // allow literal strings or numbers? We'll handle dotted paths:
    return this._get(expr);
  };

  // public factory
  global.BindJQ = function(root, model){
    return new BindJQ(root, model);
  };

})(window, window.jQuery);
