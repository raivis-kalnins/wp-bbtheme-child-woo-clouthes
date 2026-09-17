/* WP BBTheme Child Woo Clothes 3.8.11.47 - Events-parity homepage runtime. */
(function(W,D){
  'use strict';
  var CFG=W.wpbbSuiteV147||{};
  function q(sel,root){try{return(root||D).querySelector(sel);}catch(e){return null;}}
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function home(){return !!(D.body&&D.body.classList.contains('wpbb-v147-home'));}
  function closest(el,sel){try{return el&&el.closest?el.closest(sel):null;}catch(e){return null;}}

  function promoteImage(img,hero){
    if(!img)return;
    var src=String(img.getAttribute('src')||'').trim();
    if(!src||/^data:image\//i.test(src)||/placeholder|transparent|spacer|blank\.(?:gif|png|svg)/i.test(src)){
      ['data-src','data-lazy-src','data-original','data-full-url','data-orig-file'].some(function(a){var v=String(img.getAttribute(a)||'').trim();if(!v)return false;img.setAttribute('src',v);return true;});
    }
    var ss=String(img.getAttribute('srcset')||'').trim();
    if(!ss){['data-srcset','data-lazy-srcset'].some(function(a){var v=String(img.getAttribute(a)||'').trim();if(!v)return false;img.setAttribute('srcset',v);return true;});}
    img.setAttribute('loading','eager');
    img.setAttribute('decoding','async');
    if(hero)img.setAttribute('fetchpriority','high');
    img.style.setProperty('opacity','1','important');
    img.style.setProperty('visibility','visible','important');
  }

  function heroRoot(){return q('#wp-theme-main .wp-theme-sector-hero .wpbb-swiper--hero')||q('#wp-theme-main .wp-theme-sector-hero .wpbb-swiper');}
  function heroSwiper(root){return root&&(root.matches('.swiper')?root:q('.swiper',root));}
  function uniqueSlides(sw){
    var seen={};
    return qa('.swiper-wrapper > .swiper-slide',sw).filter(function(slide,i){
      if(slide.classList.contains('swiper-slide-duplicate'))return false;
      var k=slide.getAttribute('data-swiper-slide-index')||('dom-'+i);
      if(seen[k])return false;seen[k]=1;return true;
    });
  }
  function swiperIndex(sw){var api=sw&&sw.swiper;if(api&&typeof api.realIndex==='number')return api.realIndex;var slides=uniqueSlides(sw),active=q('.swiper-slide-active',sw);return Math.max(0,slides.indexOf(active));}

  function ownHeroFinder(root,sw){
    qa('#wp-theme-main .wpbb-v97-hero-finder').forEach(function(n){n.remove();});
    var html=String(CFG.finderHtml||'').trim();if(!html)return;
    var tmp=D.createElement('div');tmp.innerHTML=html;
    var finder=q('.wpbb-v97-hero-finder',tmp);if(!finder)return;
    finder.classList.remove('wpbb-v140-hero-finder');finder.classList.add('wpbb-v147-hero-finder');
    var first=uniqueSlides(sw)[0];
    var content=first&&q('.wpbb-swiper-slide__content',first);
    if(content)content.appendChild(finder);else root.appendChild(finder);
  }

  function ownHeroPager(root,sw){
    qa('.wpbb-v146-hero-pager,.wpbb-v145-hero-pagination,.wpbb-v144-hero-pagination,.wpbb-v143-hero-pagination,.wpbb-v142-hero-pagination,.wpbb-v141-hero-pagination',root).forEach(function(n){n.remove();});
    var slides=uniqueSlides(sw);if(slides.length<2)return;
    var pager=q('.wpbb-v147-hero-pager',root);
    if(!pager){pager=D.createElement('div');pager.className='wpbb-v147-hero-pager';pager.setAttribute('role','group');pager.setAttribute('aria-label','Hero slides');root.appendChild(pager);}
    if(pager.children.length!==slides.length){
      pager.innerHTML='';slides.forEach(function(_,i){var b=D.createElement('button');b.type='button';b.className='wpbb-v147-hero-pager__bullet';b.setAttribute('aria-label','Show slide '+(i+1));b.dataset.slide=String(i);pager.appendChild(b);});
    }
    function paint(){var i=swiperIndex(sw);qa('button',pager).forEach(function(b,n){b.classList.toggle('is-active',n===i);if(n===i)b.setAttribute('aria-current','true');else b.removeAttribute('aria-current');});}
    if(!pager.dataset.bound){
      pager.dataset.bound='1';pager.addEventListener('click',function(e){var b=closest(e.target,'button[data-slide]');if(!b)return;var i=parseInt(b.dataset.slide,10)||0,api=sw.swiper;if(api){try{if(typeof api.slideToLoop==='function')api.slideToLoop(i);else if(typeof api.slideTo==='function')api.slideTo(i);}catch(err){}}paint();});
    }
    var api=sw.swiper;if(api&&pager._api!==api){pager._api=api;if(typeof api.on==='function'){try{api.on('slideChange',paint);api.on('realIndexChange',paint);api.on('transitionEnd',paint);}catch(e){}}}
    paint();
  }

  function repairHero(){
    var root=heroRoot(),sw=heroSwiper(root);if(!root||!sw)return;
    qa('.wpbb-swiper-slide__media img',root).forEach(function(img){promoteImage(img,true);});
    ownHeroFinder(root,sw);ownHeroPager(root,sw);
  }

  function repairPartners(){
    var section=q('#wp-theme-main .wp-theme-partners-section');if(!section)return;
    var sw=q('.swiper',section);if(!sw)return;
    section.classList.add('wpbb-v147-partners-ready');
    var api=sw.swiper;if(api&&api.autoplay&&typeof api.autoplay.stop==='function'){try{api.autoplay.stop();}catch(e){}}
    qa('img',section).forEach(function(img){promoteImage(img,false);});
  }

  function directCells(row){
    if(!row)return[];
    return Array.prototype.slice.call(row.children||[]).filter(function(el){
      var c=' '+String(el.className||'')+' ';
      return /\bwpbb-column\b|\bwp-block-wpbb-column\b|\bcol(?:-\w+)?-\d+\b/.test(c);
    });
  }
  function markGrid(row,cols){
    if(!row)return;
    var cells=directCells(row);if(cells.length<2)return;
    row.classList.add('wpbb-v147-grid','wpbb-v147-cols-'+cols);
    cells.forEach(function(c){c.classList.add('wpbb-v147-grid-cell');});
  }
  function rowContaining(section,selector){
    var item=q(selector,section);return item?closest(item,'.wpbb-row,.row'):null;
  }
  function markKnownGrids(){
    markGrid(q('#wp-theme-main .clothes-values'),3);
    var categories=q('#wp-theme-main .clothes-category-section');markGrid(rowContaining(categories,'.clothes-category-tile'),4);
    markGrid(q('#wp-theme-main .wp-theme-services-section .wp-theme-sector-services'),3);
    markGrid(q('#wp-theme-main .wp-theme-industries-section .wp-theme-sector-industries'),4);
    markGrid(q('#wp-theme-main .wp-theme-home-stats .wp-theme-sector-proof'),4);
    markGrid(q('#wp-theme-main .wp-theme-cases-section .wp-theme-case-grid'),3);
    markGrid(q('#wp-theme-main .wp-theme-process-section .wp-theme-sector-process-grid'),3);
  }

  function promoteHomepageMedia(){
    qa('#wp-theme-main img').forEach(function(img){promoteImage(img,false);});
  }

  function run(){if(!home())return;repairHero();repairPartners();markKnownGrids();promoteHomepageMedia();}
  var timer=0;function schedule(ms){W.clearTimeout(timer);timer=W.setTimeout(run,ms||30);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',function(){schedule(1);},{once:true});else schedule(1);
  W.addEventListener('load',function(){run();[160,500,1200,2500].forEach(function(ms){W.setTimeout(run,ms);});},{once:true});
  W.addEventListener('resize',function(){schedule(100);},{passive:true});
  if(W.MutationObserver){var root=q('#wp-theme-main');if(root){var obs=new MutationObserver(function(list){if(list.some(function(m){return m.addedNodes&&m.addedNodes.length;}))schedule(50);});obs.observe(root,{childList:true,subtree:true});W.setTimeout(function(){obs.disconnect();},6000);}}
})(window,document);
