/* Woo Clothes 3.8.11.42 - robust homepage grids, explicit pager and media recovery. */
(function(W,D){
  'use strict';
  function q(sel,root){try{return(root||D).querySelector(sel);}catch(e){return null;}}
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function kids(el){return el?Array.prototype.slice.call(el.children||[]):[];}
  function text(el){return String(el&&el.textContent||'').replace(/\s+/g,' ').trim();}
  function norm(value){return String(value||'').toLowerCase().replace(/[^a-z0-9]+/g,' ').trim();}
  function uniq(arr){return arr.filter(function(item,index,list){return item&&list.indexOf(item)===index;});}
  function topLevel(arr){arr=uniq(arr);return arr.filter(function(item){return !arr.some(function(other){return other!==item&&other.contains&&other.contains(item);});});}
  function home(){return !!(D.body&&(D.body.classList.contains('home')||D.body.classList.contains('front-page')||D.body.classList.contains('wpbb-v142-home')));}

  function directChildUnder(host,node){
    if(!host||!node||!host.contains(node))return null;
    var current=node;
    while(current&&current.parentElement!==host)current=current.parentElement;
    return current&&current.parentElement===host?current:null;
  }
  function lowestCommonHost(scope,cards){
    cards=topLevel(cards||[]);
    if(!scope||cards.length<2)return null;
    var node=cards[0].parentElement;
    while(node){
      if(cards.every(function(card){return node.contains(card);})){
        var items=uniq(cards.map(function(card){return directChildUnder(node,card);})).filter(Boolean);
        if(items.length>=2)return{host:node,items:items};
      }
      if(node===scope)break;
      node=node.parentElement;
    }
    return null;
  }
  function columnsFor(count,preferred){
    if(preferred)return preferred;
    if(count<=2)return 2;
    if(count===3)return 3;
    if(count===5)return 5;
    return 4;
  }
  function cardInside(item){
    var selector='.wp-theme-sector-card,.wp-theme-process-card,.wp-theme-case-card,.wpbb-icon-card,.wpbb-sector-card,.wpbb-catalogue-card,.wpbb-fun-fact,.wp-theme-sector-proof__item,.wpbb-sector-proof-card,.wp-theme-gallery-card,.wp-theme-blog-card,.product,article,.card';
    return item&&item.matches&&item.matches(selector)?item:q(selector,item);
  }
  function markGrid(host,items,preferred,extra){
    items=uniq(items||[]).filter(Boolean);
    if(!host||items.length<2)return;
    host.classList.add('wpbb-v142-grid','wpbb-v142-cols-'+columnsFor(items.length,preferred));
    if(extra)host.classList.add(extra);
    items.forEach(function(item){
      item.classList.add('wpbb-v142-grid-cell');
      var card=cardInside(item);if(card)card.classList.add('wpbb-v142-card');
    });
  }
  function markSection(section,selector,preferred,extra){
    if(!section)return;
    var cards=topLevel(qa(selector,section));
    var pick=lowestCommonHost(section,cards);
    if(pick)markGrid(pick.host,pick.items,preferred,extra);
  }

  function markValues(){
    var main=q('#wp-theme-main');if(!main)return;
    var row=q('.clothes-values',main);
    if(row){
      var direct=kids(row).filter(function(item){return text(item)||q('.wp-theme-sector-card,h3',item);});
      if(direct.length>=2){
        row.classList.add('wpbb-v142-values-grid');
        direct.forEach(function(item){item.classList.add('wpbb-v142-grid-cell');});
        return;
      }
    }
    var wanted=['natural materials','considered fit','easy returns'];
    var cards=[];
    qa('h1,h2,h3,h4,h5,h6',main).forEach(function(h){
      if(wanted.indexOf(norm(text(h)))===-1)return;
      var card=h.closest&&h.closest('.wp-theme-sector-card,.wpbb-icon-card,.wpbb-sector-card,.card');
      if(card)cards.push(card);
    });
    cards=topLevel(cards);
    if(cards.length<3)return;
    var scope=cards[0].closest&&cards[0].closest('.wp-theme-section-shell,.wpbb-v67-section-shell,#wpbb-row-35');
    scope=scope||main;
    var pick=lowestCommonHost(scope,cards);
    if(!pick)return;
    pick.host.classList.add('wpbb-v142-values-grid');
    pick.items.forEach(function(item){item.classList.add('wpbb-v142-grid-cell');});
  }

  function markHomepageGrids(){
    if(!home())return;
    var main=q('#wp-theme-main');if(!main)return;
    markValues();

    qa('.clothes-category-section',main).forEach(function(section){markSection(section,'.clothes-category-tile',4,'wpbb-v142-category-grid');});
    qa('.wp-theme-services-section,.wp-theme-sector-services-section',main).forEach(function(section){markSection(section,'.wp-theme-sector-card,.wpbb-icon-card,.wpbb-sector-card',null,'wpbb-v142-services-grid');});
    qa('.wp-theme-industries-section,.wp-theme-sector-industries-section',main).forEach(function(section){markSection(section,'.wp-theme-sector-card,.wpbb-icon-card,.wpbb-sector-card',null,'wpbb-v142-industries-grid');});
    qa('.wp-theme-process-section',main).forEach(function(section){markSection(section,'.wp-theme-process-card,.wp-theme-sector-card,.wpbb-icon-card',3,'wpbb-v142-process-grid');});
    qa('.wp-theme-case-studies-section,.wp-theme-case-grid,.wp-theme-case-studies-grid',main).forEach(function(section){markSection(section,'.wp-theme-case-card,.wpbb-icon-card,.wp-theme-sector-card',3,'wpbb-v142-case-grid');});
    qa('.wp-theme-home-stats,.wp-theme-sector-proof',main).forEach(function(section){markSection(section,'.wpbb-fun-fact,.wp-theme-sector-proof__item',4,'wpbb-v142-stats-grid');});
    qa('.wpbb-sector-proof-band',main).forEach(function(section){markSection(section,'.wpbb-sector-proof-card,.wpbb-icon-card,.wp-theme-sector-card',3,'wpbb-v142-proof-grid');});

    qa('.wp-theme-home-product-catalogue',main).forEach(function(section){
      var cards=topLevel(qa('.wpbb-catalogue-card,.product.type-product,.type-product.product,.product-card,.iws-product-card,li.product',section));
      var pick=lowestCommonHost(section,cards);
      if(pick){markGrid(pick.host,pick.items,4,'wpbb-v142-product-grid');cards.forEach(function(card){card.classList.add('wpbb-v142-product-card');});}
      qa('h1,h2,h3,h4,h5,h6',section).forEach(function(h){if(!text(h)&&!q('img,svg,button,a,input,select',h))h.style.display='none';});
    });

    qa('.wp-theme-insights-section .wp-block-post-template,.wp-theme-blog-preview-section .wp-block-post-template',main).forEach(function(host){
      var items=kids(host).filter(function(item){return text(item)||q('article,img,a',item);});
      if(items.length>=2)markGrid(host,items,3,'wpbb-v142-blog-grid');
    });

    qa('.wp-theme-gallery-section',main).forEach(function(section){
      if(q('.swiper,.wpbb-swiper--gallery',section))return;
      markSection(section,'.wp-theme-gallery-card',4,'wpbb-v142-gallery-grid');
    });
  }

  function swiperElement(block){return block&&(block.matches&&block.matches('.swiper')?block:q('.swiper',block));}
  function liveSwiper(block,el){return (el&&el.swiper)||(block&&block.swiper)||null;}
  function uniqueSlides(el){
    if(!el)return[];
    var slides=qa('.swiper-wrapper > .swiper-slide',el);
    if(!slides.length)slides=qa('.swiper-wrapper > .wpbb-swiper-slide',el);
    if(!slides.length)slides=qa('.swiper-slide,.wpbb-swiper-slide--hero',el);
    var seen={},out=[];
    slides.forEach(function(slide,index){
      if(slide.classList.contains('swiper-slide-duplicate'))return;
      var raw=slide.getAttribute('data-swiper-slide-index');
      var key=(raw===null||raw==='')?'dom-'+index:String(raw);
      if(seen[key])return;seen[key]=1;out.push(slide);
    });
    return out;
  }
  function activeSlideIndex(el,sw,count){
    if(!count)return 0;
    var n=sw&&typeof sw.realIndex==='number'?sw.realIndex:null;
    if(n===null){
      var active=q('.swiper-slide-active',el);
      if(active){
        var raw=active.getAttribute('data-swiper-slide-index');
        if(raw!==null&&raw!=='')n=parseInt(raw,10);
        else n=uniqueSlides(el).indexOf(active);
      }
    }
    if(n===null||!isFinite(n))n=sw&&typeof sw.activeIndex==='number'?sw.activeIndex:0;
    n=parseInt(n,10);if(!isFinite(n)||n<0)n=0;return n%count;
  }
  function paintPager(pager,el,sw,count){
    var active=activeSlideIndex(el,sw,count);
    qa('.wpbb-v142-hero-pagination__button',pager).forEach(function(button,index){
      var on=index===active;button.classList.toggle('is-active',on);
      if(on)button.setAttribute('aria-current','true');else button.removeAttribute('aria-current');
    });
  }
  function ensurePager(block){
    var el=swiperElement(block);if(!el)return;
    var slides=uniqueSlides(el),count=slides.length;
    var pager=q(':scope > .wpbb-v142-hero-pagination',el);
    if(count<2){if(pager&&pager.parentNode)pager.parentNode.removeChild(pager);return;}
    if(!pager){
      pager=D.createElement('div');pager.className='wpbb-v142-hero-pagination';pager.setAttribute('role','group');pager.setAttribute('aria-label','Hero slides');el.appendChild(pager);
    }
    if(pager.children.length!==count){
      pager.innerHTML='';
      for(var i=0;i<count;i++){
        var button=D.createElement('button');button.type='button';button.className='wpbb-v142-hero-pagination__button';
        button.setAttribute('data-wpbb-v142-slide',String(i));button.setAttribute('aria-label','Go to hero slide '+(i+1)+' of '+count);
        button.textContent=String(i+1).padStart(2,'0');pager.appendChild(button);
      }
    }
    if(!pager.dataset.wpbbV142Bound){
      pager.dataset.wpbbV142Bound='1';
      pager.addEventListener('click',function(event){
        var button=event.target&&event.target.closest?event.target.closest('[data-wpbb-v142-slide]'):null;if(!button)return;
        var index=parseInt(button.getAttribute('data-wpbb-v142-slide'),10)||0;
        var currentEl=swiperElement(block),sw=liveSwiper(block,currentEl);
        if(sw){
          try{if(typeof sw.slideToLoop==='function')sw.slideToLoop(index);else if(typeof sw.slideTo==='function')sw.slideTo(index);}catch(e){}
        }else{
          var nativeBullets=qa('.swiper-pagination-bullet',currentEl);if(nativeBullets[index]&&typeof nativeBullets[index].click==='function')nativeBullets[index].click();
        }
        paintPager(pager,currentEl,sw,count);
      });
    }
    var sw=liveSwiper(block,el);
    if(sw&&pager._wpbbV142Swiper!==sw){
      pager._wpbbV142Swiper=sw;
      if(typeof sw.on==='function'){
        var update=function(){paintPager(pager,el,sw,uniqueSlides(el).length||count);};
        try{sw.on('slideChange',update);sw.on('realIndexChange',update);sw.on('transitionEnd',update);}catch(e){}
      }
    }
    paintPager(pager,el,sw,count);
  }
  function markHeroes(){
    if(!home())return;
    qa('#wp-theme-main .wpbb-swiper--hero').forEach(function(block,index){
      block.classList.toggle('wpbb-v142-primary-hero',index===0);
      block.classList.toggle('wpbb-v142-secondary-hero',index===1);
      ensurePager(block);
    });
  }

  function promoteImage(img){
    if(!img)return;
    var src=String(img.getAttribute('src')||'').trim();
    var placeholder=!src||/^data:image\/(?:gif|svg\+xml)/i.test(src)||/transparent|placeholder/i.test(src);
    if(placeholder){
      ['data-src','data-lazy-src','data-original','data-lazy'].some(function(attr){
        var value=String(img.getAttribute(attr)||'').trim();if(!value)return false;img.setAttribute('src',value);return true;
      });
    }
    if(!img.getAttribute('srcset')){
      ['data-srcset','data-lazy-srcset'].some(function(attr){var value=String(img.getAttribute(attr)||'').trim();if(!value)return false;img.setAttribute('srcset',value);return true;});
    }
    img.setAttribute('loading','eager');
    img.setAttribute('decoding','async');
  }
  function promoteHomepageImages(){
    if(!home())return;
    qa('#wp-theme-main .wp-theme-home-product-catalogue img,#wp-theme-main .wp-theme-gallery-section img,#wp-theme-main .wp-theme-insights-section img,#wp-theme-main .wp-theme-blog-preview-section img').forEach(promoteImage);
  }

  function run(){
    if(D.body){D.body.classList.add('wpbb-v142');if(home())D.body.classList.add('wpbb-v142-home');}
    markHeroes();markHomepageGrids();promoteHomepageImages();
  }
  var timer=0;
  function schedule(){W.clearTimeout(timer);timer=W.setTimeout(run,40);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();[160,520,1200,2400].forEach(function(ms){W.setTimeout(run,ms);});});
  W.addEventListener('resize',function(){W.clearTimeout(timer);timer=W.setTimeout(run,120);},{passive:true});
  var main=q('#wp-theme-main');
  if(main&&W.MutationObserver){
    var stopTimer;
    var observer=new MutationObserver(function(mutations){
      if(!mutations.some(function(m){return (m.addedNodes&&m.addedNodes.length)||(m.type==='attributes'&&m.attributeName==='class');}))return;
      schedule();W.clearTimeout(stopTimer);stopTimer=W.setTimeout(function(){observer.disconnect();},6500);
    });
    observer.observe(main,{childList:true,subtree:true,attributes:true,attributeFilter:['class','src','data-src']});
    stopTimer=W.setTimeout(function(){observer.disconnect();},7200);
  }
})(window,document);
