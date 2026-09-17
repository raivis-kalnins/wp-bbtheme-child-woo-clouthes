/* Woo Clothes 3.8.11.41 - Automotive-parity grid owner, header axis and accessible hero pager. */
(function(W,D){
  'use strict';
  var ROOT=D.documentElement;
  function q(sel,root){try{return(root||D).querySelector(sel);}catch(e){return null;}}
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function kids(el){return el?Array.prototype.slice.call(el.children||[]):[];}
  function txt(el){return String(el&&el.textContent||'').replace(/\s+/g,' ').trim();}
  function uniq(arr){return arr.filter(function(x,i,a){return x&&a.indexOf(x)===i;});}
  function topLevel(arr){return uniq(arr).filter(function(item){return !arr.some(function(other){return other!==item&&other.contains&&other.contains(item);});});}
  function home(){return !!(D.body&&(D.body.classList.contains('home')||D.body.classList.contains('front-page')||D.body.classList.contains('wpbb-v141-home')));}

  function measureAxis(){
    var candidates=[
      q('.wp-theme-header-main > .container'),
      q('.wp-theme-header-main .container'),
      q('.wp-theme-site-header .container'),
      q('.wp-theme-site-footer .container')
    ].filter(Boolean);
    var best=null;
    candidates.some(function(el){
      var r=el.getBoundingClientRect();
      if(r.width>320&&r.width<=W.innerWidth+2){best=r;return true;}
      return false;
    });
    if(!best)return;
    ROOT.style.setProperty('--wpbb-v141-left',Math.max(16,Math.round(best.left))+'px');
    ROOT.style.setProperty('--wpbb-v141-right',Math.max(16,Math.round(W.innerWidth-best.right))+'px');
  }

  function heroBlocks(){return home()?qa('#wp-theme-main .wpbb-swiper--hero'):[];}
  function swiperEl(block){return block&&(block.matches&&block.matches('.swiper')?block:q('.swiper',block));}
  function liveSwiper(block,el){return (el&&el.swiper)||(block&&block.swiper)||null;}
  function uniqueSlides(el){
    if(!el)return[];
    var slides=qa('.swiper-wrapper > .swiper-slide',el);
    if(!slides.length)slides=qa('.swiper-slide',el);
    var seen={},out=[];
    slides.forEach(function(slide,index){
      if(slide.classList.contains('swiper-slide-duplicate'))return;
      var raw=slide.getAttribute('data-swiper-slide-index');
      var key=(raw===null||raw==='')?'dom-'+index:String(raw);
      if(seen[key])return;
      seen[key]=1;out.push(slide);
    });
    return out.length?out:slides;
  }
  function activeIndex(sw,count){
    if(!count)return 0;
    var n=sw&&typeof sw.realIndex==='number'?sw.realIndex:(sw&&typeof sw.activeIndex==='number'?sw.activeIndex:0);
    n=parseInt(n,10);if(!isFinite(n)||n<0)n=0;return n%count;
  }
  function paintPager(pager,sw,count){
    var active=activeIndex(sw,count);
    qa('.wpbb-v141-hero-pagination__bullet',pager).forEach(function(button,index){
      var on=index===active;
      button.classList.toggle('is-active',on);
      if(on)button.setAttribute('aria-current','true');else button.removeAttribute('aria-current');
    });
  }
  function ensurePager(block){
    var el=swiperEl(block);if(!el)return;
    var count=uniqueSlides(el).length;
    var old=q(':scope > .wpbb-v141-hero-pagination',el);
    if(count<2){if(old&&old.parentNode)old.parentNode.removeChild(old);return;}
    var pager=old;
    if(!pager){
      pager=D.createElement('div');pager.className='wpbb-v141-hero-pagination';pager.setAttribute('role','group');pager.setAttribute('aria-label','Hero slides');el.appendChild(pager);
    }
    if(pager.children.length!==count){
      pager.innerHTML='';
      for(var i=0;i<count;i++){
        var b=D.createElement('button');b.type='button';b.className='wpbb-v141-hero-pagination__bullet';b.setAttribute('data-wpbb-v141-slide',String(i));b.setAttribute('aria-label','Go to slide '+(i+1));pager.appendChild(b);
      }
    }
    if(!pager.dataset.wpbbV141Bound){
      pager.dataset.wpbbV141Bound='1';
      pager.addEventListener('click',function(e){
        var button=e.target.closest&&e.target.closest('.wpbb-v141-hero-pagination__bullet');if(!button)return;
        var index=parseInt(button.getAttribute('data-wpbb-v141-slide'),10)||0;
        var sw=liveSwiper(block,swiperEl(block));
        if(sw){try{if(typeof sw.slideToLoop==='function')sw.slideToLoop(index);else if(typeof sw.slideTo==='function')sw.slideTo(index);}catch(err){}}
        paintPager(pager,sw,count);
      });
    }
    var sw=liveSwiper(block,el);
    if(sw&&pager._wpbbV141Swiper!==sw){
      pager._wpbbV141Swiper=sw;
      if(typeof sw.on==='function'){
        var update=function(){paintPager(pager,sw,uniqueSlides(el).length||count);};
        try{sw.on('slideChange',update);sw.on('realIndexChange',update);sw.on('transitionEnd',update);}catch(err){}
      }
    }
    paintPager(pager,sw,count);
  }
  function markHeroes(){
    var blocks=heroBlocks();
    blocks.forEach(function(block,index){
      block.classList.toggle('wpbb-v141-primary-hero',index===0);
      block.classList.toggle('wpbb-v141-secondary-hero',index===1);
      ensurePager(block);
    });
  }

  function bestHost(scope,cards){
    cards=topLevel(cards||[]);if(!scope||cards.length<2)return null;
    var candidates=uniq(cards.reduce(function(out,card){
      var node=card;
      for(var depth=0;node&&node!==scope&&depth<6;depth++,node=node.parentElement){
        if(node.matches&&node.matches('.row,.wpbb-row,.wpbb-v62-card-grid,.wpbb-sector-grid,.products,.wp-block-post-template,.wpbb-catalogue-grid,.wp-theme-catalogue-grid'))out.push(node);
      }
      return out;
    },[]));
    var best=null;
    candidates.forEach(function(host){
      var direct=kids(host).filter(function(child){return cards.some(function(card){return child===card||child.contains(card);});});
      if(direct.length<2)return;
      if(!best||direct.length>best.items.length)best={host:host,items:direct};
    });
    if(best)return best;
    var parent=cards[0].parentElement;
    if(parent&&cards.every(function(card){return card.parentElement===parent;}))return{host:parent,items:cards.slice()};
    return null;
  }
  function columnsFor(count,preferred){
    if(preferred)return preferred;
    if(count<=2)return 2;
    if(count===3)return 3;
    if(count===5)return 5;
    return 4;
  }
  function markGrid(host,items,preferred,extra){
    if(!host||!items||items.length<2)return;
    var cols=columnsFor(items.length,preferred);
    host.classList.add('wpbb-v141-grid','wpbb-v141-cols-'+cols);
    if(extra)host.classList.add(extra);
    items.forEach(function(item){item.classList.add('wpbb-v141-grid-cell');var card=item.matches&&item.matches('.wp-theme-sector-card,.wp-theme-process-card,.wp-theme-case-card,.wpbb-icon-card,.wpbb-sector-card,.wpbb-catalogue-card,.wpbb-fun-fact,.wp-theme-sector-proof__item,.wpbb-sector-proof-card,.wp-theme-gallery-card,.product,article,.card')?item:q('.wp-theme-sector-card,.wp-theme-process-card,.wp-theme-case-card,.wpbb-icon-card,.wpbb-sector-card,.wpbb-catalogue-card,.wpbb-fun-fact,.wp-theme-sector-proof__item,.wpbb-sector-proof-card,.wp-theme-gallery-card,.product,article,.card',item);if(card)card.classList.add('wpbb-v141-card');});
  }

  function markRow35(){
    var row=D.getElementById('wpbb-row-35');if(!row||!q('#wp-theme-main')||!q('#wp-theme-main').contains(row))return;
    var items=kids(row).filter(function(item){return txt(item)||q('article,.card,.wpbb-icon-card,.wp-theme-sector-card,.wp-theme-process-card,.wpbb-catalogue-card,img,a,button',item);});
    if(items.length>=2)markGrid(row,items,columnsFor(items.length),'wpbb-v141-row35');
  }

  function markSection(section,cardSelector,preferred,extra){
    var cards=topLevel(qa(cardSelector,section));
    var pick=bestHost(section,cards);
    if(pick)markGrid(pick.host,pick.items,preferred,extra);
  }
  function markSections(){
    if(!home())return;
    qa('#wp-theme-main .wp-theme-services-section,#wp-theme-main .wp-theme-sector-services-section').forEach(function(section){markSection(section,'.wp-theme-sector-card,.wpbb-icon-card,.wpbb-sector-card',null,'wpbb-v141-services-grid');});
    qa('#wp-theme-main .wp-theme-industries-section,#wp-theme-main .wp-theme-sector-industries-section').forEach(function(section){markSection(section,'.wp-theme-sector-card,.wpbb-icon-card,.wpbb-sector-card',null,'wpbb-v141-industries-grid');});
    qa('#wp-theme-main .wp-theme-process-section').forEach(function(section){markSection(section,'.wp-theme-process-card,.wp-theme-sector-card,.wpbb-icon-card',3,'wpbb-v141-process-grid');});
    qa('#wp-theme-main .wp-theme-case-studies-section,#wp-theme-main .wp-theme-case-grid,#wp-theme-main .wp-theme-case-studies-grid').forEach(function(section){markSection(section,'.wp-theme-case-card,.wpbb-icon-card,.wp-theme-sector-card',3,'wpbb-v141-case-grid');});
    qa('#wp-theme-main .wp-theme-home-stats,#wp-theme-main .wp-theme-sector-proof').forEach(function(section){markSection(section,'.wpbb-fun-fact,.wp-theme-sector-proof__item',4,'wpbb-v141-stats-grid');});
    qa('#wp-theme-main .wpbb-sector-proof-band').forEach(function(section){markSection(section,'.wpbb-sector-proof-card,.wpbb-icon-card,.wp-theme-sector-card',3,'wpbb-v141-proof-grid');});
    qa('#wp-theme-main .wp-theme-home-product-catalogue').forEach(function(section){
      var cards=topLevel(qa('.wpbb-catalogue-card,.product.type-product,.type-product.product,.product-card,.iws-product-card,li.product',section));
      var pick=bestHost(section,cards);
      if(pick){markGrid(pick.host,pick.items,4,'wpbb-v141-product-grid');cards.forEach(function(card){card.classList.add('wpbb-v141-product-card');});}
      qa('h1,h2,h3,h4,h5,h6',section).forEach(function(h){if(!txt(h)&&!q('img,svg,button,a,input,select',h))h.style.display='none';});
    });
    qa('#wp-theme-main .wp-theme-insights-section .wp-block-post-template,#wp-theme-main .wp-theme-blog-preview-section .wp-block-post-template').forEach(function(host){var items=kids(host).filter(function(item){return txt(item)||q('article,img,a',item);});if(items.length>=2)markGrid(host,items,3,'wpbb-v141-blog-grid');});
    qa('#wp-theme-main .wp-theme-gallery-section').forEach(function(section){if(q('.swiper,.wpbb-swiper--gallery',section))return;markSection(section,'.wp-theme-gallery-card',4,'wpbb-v141-gallery-grid');});
    markRow35();
  }

  function run(){
    if(D.body){D.body.classList.add('wpbb-v141');if(home())D.body.classList.add('wpbb-v141-home');}
    measureAxis();markHeroes();markSections();
  }
  var timer=0;
  function schedule(){W.clearTimeout(timer);timer=W.setTimeout(run,40);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();[180,650,1500].forEach(function(ms){W.setTimeout(run,ms);});});
  W.addEventListener('resize',function(){W.clearTimeout(timer);timer=W.setTimeout(run,100);},{passive:true});
  var main=q('#wp-theme-main');
  if(main&&W.MutationObserver){
    var stopTimer;
    var observer=new MutationObserver(function(ms){if(!ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))return;schedule();W.clearTimeout(stopTimer);stopTimer=W.setTimeout(function(){observer.disconnect();},4500);});
    observer.observe(main,{childList:true,subtree:true});
    stopTimer=W.setTimeout(function(){observer.disconnect();},5200);
  }
})(window,document);
