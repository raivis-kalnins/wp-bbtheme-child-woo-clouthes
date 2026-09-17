/* WP BBTheme Child Woo Clothes 3.8.11.45 - live structure runtime. */
(function(W,D){
  'use strict';
  var CFG=W.wpbbSuiteV145||{};
  var ROOT=D.documentElement;
  var VERSION='145';

  function q(sel,root){try{return(root||D).querySelector(sel);}catch(e){return null;}}
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function kids(el){return el?Array.prototype.slice.call(el.children||[]):[];}
  function unique(arr){return(arr||[]).filter(function(x,i,a){return x&&a.indexOf(x)===i;});}
  function topLevel(arr){arr=unique(arr);return arr.filter(function(item){return !arr.some(function(other){return other!==item&&other.contains&&other.contains(item);});});}
  function txt(el){return String(el&&el.textContent||'').replace(/\s+/g,' ').trim();}
  function home(){return !!(D.body&&D.body.classList.contains('wpbb-v145-home'));}
  function main(){return q('#wp-theme-main');}
  function desktop(){return W.matchMedia('(min-width:1200px)').matches;}

  function directChildUnder(host,node){
    if(!host||!node||!host.contains(node))return null;
    var n=node;
    while(n&&n.parentElement!==host)n=n.parentElement;
    return n&&n.parentElement===host?n:null;
  }

  function bestHost(scope,cards){
    cards=topLevel(cards||[]);if(!scope||cards.length<2)return null;
    var candidates=[];
    cards.forEach(function(card){
      var node=card;
      for(var depth=0;node&&depth<8;depth++,node=node.parentElement){
        if(node===scope)break;
        if(node.matches&&node.matches('.row,.wpbb-row,.wp-block-post-template,.products,.wpbb-catalogue-row'))candidates.push(node);
      }
    });
    candidates=unique(candidates);
    var best=null;
    candidates.forEach(function(host){
      var items=unique(cards.map(function(card){return directChildUnder(host,card);})).filter(Boolean);
      if(items.length<2)return;
      if(!best||items.length>best.items.length)best={host:host,items:items};
    });
    if(best)return best;
    var direct=unique(cards.map(function(card){return directChildUnder(scope,card);})).filter(Boolean);
    if(direct.length>=2)return{host:scope,items:direct};
    return null;
  }

  function clearPresentationClasses(node){
    if(!node||!node.classList)return;
    Array.prototype.slice.call(node.classList).forEach(function(c){
      if(/^wpbb-v1(?:3[4-9]|4[0-4])-/.test(c))node.classList.remove(c);
    });
  }

  function markGrid(host,items,cols,extra){
    if(!host||!items||items.length<2)return;
    clearPresentationClasses(host);
    host.classList.add('wpbb-v145-grid','wpbb-v145-cols-'+Math.max(2,Math.min(4,cols||items.length)));
    if(extra)host.classList.add(extra);
    items.forEach(function(item){
      clearPresentationClasses(item);
      item.classList.add('wpbb-v145-grid-cell');
    });
  }

  function markSection(scopeSelector,cardSelector,cols,extra){
    qa('#wp-theme-main '+scopeSelector).forEach(function(scope){
      var cards=topLevel(qa(cardSelector,scope));
      var pick=bestHost(scope,cards);
      if(pick)markGrid(pick.host,pick.items,cols,extra);
    });
  }

  function fixValues(){
    var section=q('#wp-theme-main .clothes-values');if(!section)return;
    clearPresentationClasses(section);
    section.classList.add('wpbb-v145-values-section');
    var wanted=['natural materials','considered fit','easy returns'];
    var cards=topLevel(qa('.wp-theme-sector-card',section).filter(function(card){
      var h=q('h1,h2,h3,h4,h5,h6',card);return h&&wanted.indexOf(txt(h).toLowerCase())!==-1;
    }));
    if(cards.length<3)cards=topLevel(qa('.wp-theme-sector-card',section));
    var pick=bestHost(section,cards);
    if(pick)markGrid(pick.host,pick.items,3,'wpbb-v145-values-grid');
  }

  function markHomepageGrids(){
    fixValues();
    markSection('.clothes-category-section','.clothes-category-tile',4,'wpbb-v145-category-grid');
    markSection('.wp-theme-services-section','.wp-theme-sector-card',3,'wpbb-v145-services-grid');
    markSection('.wp-theme-industries-section','.wp-theme-sector-card',4,'wpbb-v145-industries-grid');
    markSection('.wp-theme-home-stats','.wpbb-fun-fact,.wp-theme-sector-proof__item',4,'wpbb-v145-stats-grid');
    markSection('.wp-theme-cases-section,.wp-theme-case-studies-section','.wp-theme-case-card',3,'wpbb-v145-case-grid');
    markSection('.wp-theme-process-section','.wp-theme-sector-card,.wp-theme-process-card',3,'wpbb-v145-process-grid');
    qa('#wp-theme-main .wp-theme-insights-section .wp-block-post-template').forEach(function(host){
      var items=kids(host).filter(function(item){return txt(item)||q('article,img,a',item);});
      if(items.length>=2)markGrid(host,items,3,'wpbb-v145-blog-grid');
    });
  }

  function isCell(n){return !!(n&&n.matches&&n.matches('.wpbb-column,.wp-block-wpbb-column,[class^="col-"],[class*=" col-"]'));}
  function closestCell(card,section){var n=card;while(n&&n!==section){if(isCell(n))return n;n=n.parentElement;}return card&&card.parentElement;}
  function closestRow(cell,section){var n=cell&&cell.parentElement;while(n&&n!==section){if(n.matches&&n.matches('.row,.wpbb-row'))return n;n=n.parentElement;}return n===section&&section.matches&&section.matches('.row,.wpbb-row')?section:null;}

  function repairCatalogue(section){
    if(!section)return;
    section.classList.add('wpbb-v145-catalogue');
    qa('.wpbb-v145-catalogue-grid',section).forEach(function(n){n.classList.remove('wpbb-v145-catalogue-grid');});
    qa('.wpbb-v145-catalogue-cell',section).forEach(function(n){n.classList.remove('wpbb-v145-catalogue-cell');});
    var cards=qa('.wpbb-catalogue-card',section);if(!cards.length)return;
    var groups=[];
    cards.forEach(function(card){
      clearPresentationClasses(card);card.classList.add('wpbb-v145-product-card');
      var cell=closestCell(card,section),row=closestRow(cell,section);
      if(!cell||!row||!section.contains(row))return;
      var group=groups.find(function(g){return g.row===row;});
      if(!group){group={row:row,cells:[]};groups.push(group);}
      if(group.cells.indexOf(cell)<0)group.cells.push(cell);
    });
    groups.forEach(function(group){
      if(group.cells.length<2)return;
      clearPresentationClasses(group.row);group.row.classList.add('wpbb-v145-catalogue-grid');
      group.cells.forEach(function(cell){clearPresentationClasses(cell);cell.classList.add('wpbb-v145-catalogue-cell');});
    });
    qa('h1:empty,h2:empty,h3:empty,h4:empty',section).forEach(function(h){
      var cell=closestCell(h,section);if(cell&&!q('.wpbb-catalogue-card,img,form,button,input,select',cell))cell.classList.add('wpbb-v145-empty-catalogue-heading');
    });
  }

  function candidateFromSrcset(value){value=String(value||'').trim();if(!value)return'';var first=value.split(',')[0]||'';return first.trim().split(/\s+/)[0]||'';}
  function placeholder(src){return !src||/^data:image\//i.test(src)||/transparent|placeholder|spacer|blank\.(?:gif|png|svg)/i.test(src);}
  function promoteSource(source){
    if(!source)return;
    if(!String(source.getAttribute('srcset')||'').trim()){
      ['data-srcset','data-lazy-srcset','data-original-srcset'].some(function(attr){var v=String(source.getAttribute(attr)||'').trim();if(!v)return false;source.setAttribute('srcset',v);return true;});
    }
  }
  function promoteImage(img){
    if(!img)return;
    var src=String(img.getAttribute('src')||'').trim();
    if(placeholder(src)){
      var promoted='';
      ['data-src','data-lazy-src','data-original','data-orig-file','data-lazy','data-full-url'].some(function(attr){var v=String(img.getAttribute(attr)||'').trim();if(!v)return false;promoted=v;return true;});
      if(!promoted)promoted=candidateFromSrcset(img.getAttribute('data-srcset')||img.getAttribute('data-lazy-srcset')||img.getAttribute('srcset'));
      if(promoted)img.setAttribute('src',promoted);
    }
    if(!String(img.getAttribute('srcset')||'').trim()){
      ['data-srcset','data-lazy-srcset','data-original-srcset'].some(function(attr){var v=String(img.getAttribute(attr)||'').trim();if(!v)return false;img.setAttribute('srcset',v);return true;});
    }
    var picture=img.closest&&img.closest('picture');if(picture)qa('source',picture).forEach(promoteSource);
    img.setAttribute('loading','eager');img.setAttribute('fetchpriority',img.closest&&img.closest('.wpbb-swiper--hero')?'high':'auto');img.setAttribute('decoding','async');
    img.classList.remove('lazy','lazyload','lazyloading','lazyloaded','entered','exited');
    img.classList.add('wpbb-v145-media-ready');
    img.style.removeProperty('display');img.style.removeProperty('visibility');img.style.removeProperty('opacity');
    if(!img.dataset.wpbbV145ErrorBound){
      img.dataset.wpbbV145ErrorBound='1';
      img.addEventListener('error',function(){
        var fallback=candidateFromSrcset(img.getAttribute('data-srcset')||img.getAttribute('data-lazy-srcset')||img.getAttribute('srcset'));
        if(fallback&&fallback!==img.currentSrc&&fallback!==img.src)img.src=fallback;
      });
    }
  }
  function promoteHomepageMedia(){
    var scope=main();if(!scope)return;
    qa('img',scope).forEach(promoteImage);
  }
  function repairCatalogueMedia(){
    qa('#wp-theme-main .wp-theme-home-product-catalogue').forEach(function(section){repairCatalogue(section);qa('.wpbb-catalogue-card img',section).forEach(promoteImage);});
  }

  function heroBlocks(){return qa('#wp-theme-main .wpbb-swiper--hero');}
  function swiperEl(block){return block&&(block.matches&&block.matches('.swiper')?block:q('.swiper',block));}
  function liveSwiper(block,el){return(el&&el.swiper)||(block&&block.swiper)||null;}
  function uniqueSlides(el){
    if(!el)return[];var slides=qa('.swiper-wrapper > .swiper-slide',el);if(!slides.length)slides=qa('.swiper-slide',el);
    var seen={},out=[];slides.forEach(function(slide,index){if(slide.classList.contains('swiper-slide-duplicate'))return;var raw=slide.getAttribute('data-swiper-slide-index'),key=(raw===null||raw==='')?'dom-'+index:String(raw);if(seen[key])return;seen[key]=1;out.push(slide);});
    return out.length?out:slides;
  }
  function activeIndex(el,sw,count){
    if(!count)return 0;var n=sw&&typeof sw.realIndex==='number'?sw.realIndex:null;
    if(n===null){var active=q('.swiper-slide-active',el);if(active){var raw=active.getAttribute('data-swiper-slide-index');n=(raw!==null&&raw!=='')?parseInt(raw,10):uniqueSlides(el).indexOf(active);}}
    if(n===null||!isFinite(n))n=sw&&typeof sw.activeIndex==='number'?sw.activeIndex:0;
    n=parseInt(n,10);if(!isFinite(n)||n<0)n=0;return n%count;
  }
  function paintPager(pager,el,sw,count){
    var active=activeIndex(el,sw,count);
    qa('.wpbb-v145-hero-pagination__bullet',pager).forEach(function(button,index){var on=index===active;button.classList.toggle('is-active',on);button.setAttribute('aria-label','Hero slide '+(index+1)+' of '+count);if(on)button.setAttribute('aria-current','true');else button.removeAttribute('aria-current');});
  }
  function clearOldPagers(block){
    qa('[class*="wpbb-v14"][class*="hero-pagination"],[class*="wpbb-v13"][class*="hero-pagination"]',block).forEach(function(n){if(!n.classList.contains('wpbb-v145-hero-pagination')&&n.parentNode)n.parentNode.removeChild(n);});
  }
  function ensureHeroPager(block){
    var el=swiperEl(block);if(!el)return;clearOldPagers(block);
    qa('.swiper-button-prev,.swiper-button-next,.wpbb-swiper-button-prev,.wpbb-swiper-button-next',block).forEach(function(n){n.setAttribute('aria-hidden','true');n.setAttribute('tabindex','-1');});
    var slides=uniqueSlides(el),count=slides.length,pager=q(':scope > .wpbb-v145-hero-pagination',el);
    if(count<2){if(pager&&pager.parentNode)pager.parentNode.removeChild(pager);return;}
    if(!pager){pager=D.createElement('div');pager.className='wpbb-v145-hero-pagination';pager.setAttribute('role','group');pager.setAttribute('aria-label','Hero slides');el.appendChild(pager);}
    if(pager.children.length!==count){
      pager.innerHTML='';for(var i=0;i<count;i++){var b=D.createElement('button');b.type='button';b.className='wpbb-v145-hero-pagination__bullet';b.setAttribute('data-wpbb-v145-slide',String(i));b.innerHTML='<span class="screen-reader-text">Slide '+(i+1)+'</span>';pager.appendChild(b);}
    }
    if(!pager.dataset.wpbbV145Bound){
      pager.dataset.wpbbV145Bound='1';pager.addEventListener('click',function(event){
        var b=event.target&&event.target.closest?event.target.closest('[data-wpbb-v145-slide]'):null;if(!b)return;
        var index=parseInt(b.getAttribute('data-wpbb-v145-slide'),10)||0,sw=liveSwiper(block,swiperEl(block));
        if(sw){try{if(typeof sw.slideToLoop==='function')sw.slideToLoop(index);else if(typeof sw.slideTo==='function')sw.slideTo(index);}catch(e){}}
        else{var native=qa('.swiper-pagination-bullet',el);if(native[index]&&typeof native[index].click==='function')native[index].click();}
        paintPager(pager,el,sw,count);
      });
    }
    var sw=liveSwiper(block,el);
    if(sw&&pager._wpbbV145Swiper!==sw){
      pager._wpbbV145Swiper=sw;if(typeof sw.on==='function'){var update=function(){paintPager(pager,el,sw,uniqueSlides(el).length||count);};try{sw.on('slideChange',update);sw.on('realIndexChange',update);sw.on('transitionEnd',update);}catch(e){}}
    }
    paintPager(pager,el,sw,count);
  }

  function ensureHeroFinder(){
    if(!home())return;var hero=heroBlocks()[0];if(!hero)return;
    var existing=qa('#wp-theme-main .wpbb-v97-hero-finder');var owner=existing.find(function(f){return f.classList.contains('wpbb-v145-hero-finder')&&hero.contains(f);})||null;
    existing.forEach(function(f){if(f!==owner&&f.parentNode)f.parentNode.removeChild(f);});
    if(owner)return;
    var html=String(CFG.finderHtml||'').trim();if(!html)return;
    var wrap=D.createElement('div');wrap.innerHTML=html;owner=q('.wpbb-v97-hero-finder',wrap);if(!owner)return;
    owner.className=String(owner.className||'').replace(/\bwpbb-v1(?:3[4-9]|4[0-4])-hero-finder\b/g,'').trim();owner.classList.add('wpbb-v145-hero-finder');hero.appendChild(owner);
  }

  function normalizeHero(){
    heroBlocks().forEach(function(block){
      clearPresentationClasses(block);block.classList.add('wpbb-v145-hero');
      qa('.wpbb-swiper-slide--hero',block).forEach(function(slide){slide.classList.add('wpbb-v145-hero-slide');qa('img',slide).forEach(promoteImage);});
      ensureHeroPager(block);
    });
  }

  function markNativeMediaText(){
    qa('#wp-theme-main .wp-block-media-text.wp-theme-sector-media-text').forEach(function(block){block.classList.add('wpbb-v145-native-media-text');qa('img',block).forEach(promoteImage);});
  }

  function menuTrigger(menu){var li=menu&&menu.parentElement;if(!li)return null;try{return li.querySelector(':scope > a,:scope > button,:scope > .wp-theme-nav-link')||li;}catch(e){return li.querySelector('a,button')||li;}}
  function positionMega(menu){
    if(!menu)return;if(!desktop()){menu.style.removeProperty('top');return;}
    var header=q('.wp-theme-site-header')||q('.wp-theme-header-main'),trigger=menuTrigger(menu);if(!trigger||!trigger.getBoundingClientRect)return;
    var hb=header&&header.getBoundingClientRect?header.getBoundingClientRect():null,r=trigger.getBoundingClientRect(),li=menu.parentElement,lr=li&&li.getBoundingClientRect?li.getBoundingClientRect():r;
    var bottom=Math.ceil(hb?hb.bottom:Math.max(r.bottom,lr.bottom));if(bottom>0){menu.style.setProperty('top',bottom+'px','important');ROOT.style.setProperty('--wpbb-v145-mega-top',bottom+'px');}
  }
  function bindMegas(){
    qa('.wp-theme-primary-menu>li>.wp-theme-mega-menu').forEach(function(menu){
      if(!menu.dataset.wpbbV145Bound){menu.dataset.wpbbV145Bound='1';var li=menu.parentElement;if(li){li.addEventListener('pointerenter',function(){positionMega(menu);},{passive:true});li.addEventListener('focusin',function(){positionMega(menu);});}}
      positionMega(menu);
    });
  }

  function run(){
    if(!home())return;
    if(D.body){D.body.classList.add('wpbb-v145','wpbb-v145-home');['wpbb-v141-home','wpbb-v142-home','wpbb-v143-home','wpbb-v144-home'].forEach(function(c){D.body.classList.remove(c);});}
    ensureHeroFinder();normalizeHero();markNativeMediaText();fixValues();markHomepageGrids();repairCatalogueMedia();promoteHomepageMedia();bindMegas();
  }

  var timer=0;function schedule(delay){W.clearTimeout(timer);timer=W.setTimeout(run,delay||40);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',function(){schedule(10);},{once:true});else schedule(10);
  W.addEventListener('load',function(){run();[120,420,900,1800,3200].forEach(function(ms){W.setTimeout(run,ms);});});
  W.addEventListener('resize',function(){schedule(100);},{passive:true});
  W.addEventListener('pageshow',function(){schedule(20);});
  if(W.MutationObserver){
    var observer=new MutationObserver(function(ms){if(ms.some(function(m){return m.addedNodes&&m.addedNodes.length;}))schedule(60);});
    var observeRoot=main()||D.documentElement;observer.observe(observeRoot,{childList:true,subtree:true});W.setTimeout(function(){observer.disconnect();},9000);
  }
})(window,document);
