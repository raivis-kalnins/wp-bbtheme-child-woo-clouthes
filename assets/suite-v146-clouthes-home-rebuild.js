/* WP BBTheme Child Woo Clothes 3.8.11.46 - targeted homepage runtime. */
(function(W,D){
  'use strict';
  var CFG=W.wpbbSuiteV146||{};
  function q(sel,root){try{return(root||D).querySelector(sel);}catch(e){return null;}}
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function txt(el){return String(el&&el.textContent||'').replace(/\s+/g,' ').trim();}
  function home(){return !!(D.body&&D.body.classList.contains('wpbb-v146-home'));}
  function norm(s){return String(s||'').toLowerCase().replace(/&amp;/g,'and').replace(/[^a-z0-9]+/g,' ').trim();}
  function slugFromUrl(url){try{var p=new URL(url,W.location.href).pathname.replace(/\/+$/,'').split('/').filter(Boolean);return p[p.length-1]||'';}catch(e){return'';}}

  function forceImage(img,url,hero){
    if(!img)return;
    if(url){img.setAttribute('src',url);img.removeAttribute('srcset');img.removeAttribute('sizes');}
    else{
      var cur=String(img.getAttribute('src')||'').trim();
      if(!cur||/^data:image\//i.test(cur)||/transparent|placeholder|spacer|blank\.(?:gif|png|svg)/i.test(cur)){
        var promoted='';
        ['data-src','data-lazy-src','data-original','data-orig-file','data-full-url'].some(function(a){var v=String(img.getAttribute(a)||'').trim();if(!v)return false;promoted=v;return true;});
        if(promoted)img.setAttribute('src',promoted);
      }
    }
    img.setAttribute('loading','eager');
    img.setAttribute('decoding','async');
    if(hero)img.setAttribute('fetchpriority','high');
    img.style.setProperty('display','block','important');
    img.style.setProperty('visibility','visible','important');
    img.style.setProperty('opacity','1','important');
  }

  function heroSwiper(){var hero=q('#wp-theme-main .wpbb-swiper--hero');return hero&&(hero.matches('.swiper')?hero:q('.swiper',hero));}
  function heroSlides(sw){if(!sw)return[];var seen={};return qa('.swiper-wrapper > .swiper-slide',sw).filter(function(slide,i){if(slide.classList.contains('swiper-slide-duplicate'))return false;var key=slide.getAttribute('data-swiper-slide-index')||('dom-'+i);if(seen[key])return false;seen[key]=1;return true;});}
  function getSwiper(sw){return sw&&sw.swiper?sw.swiper:null;}
  function heroIndex(sw){var api=getSwiper(sw);if(api&&typeof api.realIndex==='number')return api.realIndex;var active=q('.swiper-slide-active',sw),slides=heroSlides(sw);return Math.max(0,slides.indexOf(active));}

  function repairHeroFinder(){
    var sw=heroSwiper();if(!sw)return;
    var existing=qa('#wp-theme-main .wpbb-v97-hero-finder');
    var owner=existing.shift()||null;
    existing.forEach(function(n){if(n.parentNode)n.parentNode.removeChild(n);});
    if(!owner){
      var html=String(CFG.finderHtml||'').trim();if(!html)return;
      var box=D.createElement('div');box.innerHTML=html;owner=q('.wpbb-v97-hero-finder',box);if(!owner)return;
    }
    if(owner.parentNode!==sw)sw.appendChild(owner);
    owner.classList.add('wpbb-v146-hero-finder');
    owner.classList.remove('wpbb-v140-hero-finder','wpbb-v145-hero-finder');
  }

  function repairHeroPager(){
    var sw=heroSwiper();if(!sw)return;
    qa('.wpbb-v145-hero-pagination,.wpbb-v144-hero-pagination,.wpbb-v143-hero-pagination',sw).forEach(function(n){n.remove();});
    var slides=heroSlides(sw),count=slides.length;if(count<2)return;
    var pager=q(':scope > .wpbb-v146-hero-pager',sw);
    if(!pager){pager=D.createElement('div');pager.className='wpbb-v146-hero-pager';pager.setAttribute('role','group');pager.setAttribute('aria-label','Hero slides');sw.appendChild(pager);}
    if(pager.children.length!==count){
      pager.innerHTML='';slides.forEach(function(_,i){var b=D.createElement('button');b.type='button';b.className='wpbb-v146-hero-pager__button';b.setAttribute('data-slide',String(i));b.innerHTML='<span>'+String(i+1).padStart(2,'0')+'</span>';pager.appendChild(b);});
    }
    function paint(){var i=heroIndex(sw);qa('button',pager).forEach(function(b,n){b.classList.toggle('is-active',n===i);if(n===i)b.setAttribute('aria-current','true');else b.removeAttribute('aria-current');});}
    if(!pager.dataset.bound){
      pager.dataset.bound='1';pager.addEventListener('click',function(e){var b=e.target.closest&&e.target.closest('button[data-slide]');if(!b)return;var i=parseInt(b.getAttribute('data-slide'),10)||0,api=getSwiper(sw);if(api){try{if(typeof api.slideToLoop==='function')api.slideToLoop(i);else if(typeof api.slideTo==='function')api.slideTo(i);}catch(err){}}else{var native=qa('.swiper-pagination-bullet',sw);if(native[i]&&native[i].click)native[i].click();}paint();});
    }
    var api=getSwiper(sw);if(api&&pager._api!==api){pager._api=api;if(typeof api.on==='function'){try{api.on('slideChange',paint);api.on('realIndexChange',paint);api.on('transitionEnd',paint);}catch(e){}}}
    paint();
  }

  function repairHero(){
    var sw=heroSwiper();if(!sw)return;
    qa('.wpbb-swiper-slide__media img',sw).forEach(function(img){forceImage(img,'',true);});
    repairHeroFinder();repairHeroPager();
  }

  function repairValues(){
    var section=q('#wp-theme-main .clothes-values');if(!section)return;
    var grid=q(':scope > .wpbb-v146-values-grid',section);if(grid&&qa(':scope > .wp-theme-sector-card',grid).length===3)return;
    var wanted=['natural materials','considered fit','easy returns'];
    var cards=qa('.wp-theme-sector-card',section).filter(function(card){var h=q('h1,h2,h3,h4,h5,h6',card);return h&&wanted.indexOf(norm(txt(h)))!==-1;});
    if(cards.length!==3)return;
    grid=D.createElement('div');grid.className='wpbb-v146-values-grid';
    cards.forEach(function(card){grid.appendChild(card);});
    while(section.firstChild)section.removeChild(section.firstChild);
    section.appendChild(grid);
    section.classList.add('wpbb-v146-values-ready');
  }

  function productMap(){
    var map={};(Array.isArray(CFG.products)?CFG.products:[]).forEach(function(p){if(!p||!p.url)return;map[norm(p.title)]=p.url;if(p.slug)map[String(p.slug).toLowerCase()]=p.url;map[String(p.id||'')]=p.url;});return map;
  }
  var PRODUCTS=productMap();
  function imageForCard(card){
    var title=q('.card-title,.wpbb-catalogue-card__title,h1,h2,h3,h4,h5,h6',card),key=norm(txt(title));
    if(PRODUCTS[key])return PRODUCTS[key];
    var link=q('a[href*="/product/"]',card),slug=link?slugFromUrl(link.href).toLowerCase():'';
    return PRODUCTS[slug]||'';
  }
  function repairCatalogue(){
    var section=q('#wp-theme-main .wp-theme-home-product-catalogue');if(!section)return;
    var cards=qa('.wpbb-catalogue-card',section);if(!cards.length)return;
    var row=null,cells=[];
    cards.forEach(function(card){
      var url=imageForCard(card),img=q('img',card);
      if(!img&&url){
        img=D.createElement('img');img.className='card-img-top wpbb-v146-product-image';img.alt=txt(q('.card-title,h1,h2,h3,h4,h5,h6',card));
        var firstLink=q(':scope > a[href]',card);if(firstLink)firstLink.insertBefore(img,firstLink.firstChild);else card.insertBefore(img,card.firstChild);
      }
      if(img)forceImage(img,url,false);
      card.classList.add('wpbb-v146-product-card');
      var cell=card.closest('.wpbb-column,.wp-block-wpbb-column,[class*="col-"]');
      if(cell&&cell.parentElement&&/\b(row|wpbb-row)\b/.test(cell.parentElement.className||'')){if(!row)row=cell.parentElement;if(cell.parentElement===row)cells.push(cell);}
    });
    if(row&&cells.length>=4){row.classList.add('wpbb-v146-catalogue-grid');cells.forEach(function(c){c.classList.add('wpbb-v146-catalogue-cell');});}
  }

  function repairSectionMedia(){
    qa('#wp-theme-main :is(.wp-theme-about-section,.wp-theme-gallery-section,.wp-theme-insights-section) img').forEach(function(img){forceImage(img,'',false);});
  }

  function run(){if(!home())return;repairHero();repairValues();repairCatalogue();repairSectionMedia();}
  var timer=0;function schedule(ms){W.clearTimeout(timer);timer=W.setTimeout(run,ms||30);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',function(){schedule(10);},{once:true});else schedule(10);
  W.addEventListener('load',function(){run();[200,600,1400,2800].forEach(function(ms){W.setTimeout(run,ms);});});
  W.addEventListener('resize',function(){schedule(120);},{passive:true});
  if(W.MutationObserver){var obs=new MutationObserver(function(list){if(list.some(function(m){return m.addedNodes&&m.addedNodes.length;}))schedule(70);});obs.observe(q('#wp-theme-main')||D.documentElement,{childList:true,subtree:true});W.setTimeout(function(){obs.disconnect();},7000);}
})(window,document);
