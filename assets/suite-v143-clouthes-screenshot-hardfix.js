/* Woo Clothes 3.8.11.43 - screenshot-led final homepage runtime. */
(function(W,D){
  'use strict';
  function q(sel,root){try{return(root||D).querySelector(sel);}catch(e){return null;}}
  function qa(sel,root){try{return Array.prototype.slice.call((root||D).querySelectorAll(sel));}catch(e){return[];}}
  function kids(el){return el?Array.prototype.slice.call(el.children||[]):[];}
  function txt(el){return String(el&&el.textContent||'').replace(/\s+/g,' ').trim();}
  function norm(v){return String(v||'').toLowerCase().replace(/[^a-z0-9]+/g,' ').trim();}
  function uniq(arr){return (arr||[]).filter(function(v,i,a){return v&&a.indexOf(v)===i;});}
  function topLevel(arr){arr=uniq(arr);return arr.filter(function(item){return !arr.some(function(other){return other!==item&&other.contains&&other.contains(item);});});}
  function home(){return !!(D.body&&(D.body.classList.contains('home')||D.body.classList.contains('front-page')||D.body.classList.contains('wpbb-v143-home')));}
  function main(){return q('#wp-theme-main');}

  function directChildUnder(host,node){
    if(!host||!node||!host.contains(node))return null;
    var current=node;
    while(current&&current.parentElement!==host)current=current.parentElement;
    return current&&current.parentElement===host?current:null;
  }
  function lowestCommonHost(scope,nodes){
    nodes=topLevel(nodes||[]);
    if(!scope||nodes.length<2)return null;
    var node=nodes[0].parentElement;
    while(node){
      if(nodes.every(function(child){return node.contains(child);})){ 
        var items=uniq(nodes.map(function(child){return directChildUnder(node,child);})).filter(Boolean);
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
    host.classList.add('wpbb-v142-grid','wpbb-v142-cols-'+columnsFor(items.length,preferred),'wpbb-v143-grid');
    if(extra)host.classList.add(extra);
    items.forEach(function(item){
      item.classList.add('wpbb-v142-grid-cell','wpbb-v143-grid-cell');
      var card=cardInside(item);if(card)card.classList.add('wpbb-v142-card','wpbb-v143-card');
    });
  }
  function markSection(section,selector,preferred,extra){
    if(!section)return;
    var cards=topLevel(qa(selector,section));
    var pick=lowestCommonHost(section,cards);
    if(pick)markGrid(pick.host,pick.items,preferred,extra);
  }

  /* The live screenshot identifies #wpbb-row-35. Do not rely on the row's
     imported card classes; locate the three semantic headings and their cells. */
  function fixValues(){
    var root=main();if(!root)return;
    var row=q('#wpbb-row-35',root)||q('.clothes-values',root);
    var wanted=['natural materials','considered fit','easy returns'];
    var headings=qa('h1,h2,h3,h4,h5,h6',row||root).filter(function(h){return wanted.indexOf(norm(txt(h)))!==-1;});
    var cells=[];
    headings.forEach(function(h){
      var cell=h.closest&&h.closest('.wpbb-column,.wp-block-column,[class~="col"],[class*="col-"]');
      if(!cell)cell=h.closest&&h.closest('.wp-theme-sector-card,.wpbb-icon-card,.wpbb-sector-card,.card');
      if(cell)cells.push(cell);
    });
    cells=topLevel(cells);

    var pick=null;
    if(cells.length>=3){
      var scope=row||root;
      pick=lowestCommonHost(scope,cells);
      if(!pick&&cells.every(function(c){return c.parentElement===cells[0].parentElement;}))pick={host:cells[0].parentElement,items:cells};
    }
    if(!pick&&row){
      var direct=kids(row).filter(function(item){return txt(item)||q('.wp-theme-sector-card,h3',item);});
      if(direct.length>=3)pick={host:row,items:direct};
      else{
        var inner=q(':scope > .row',row)||q('.row',row);
        if(inner){
          var innerKids=kids(inner).filter(function(item){return txt(item)||q('.wp-theme-sector-card,h3',item);});
          if(innerKids.length>=3)pick={host:inner,items:innerKids};
        }
      }
    }
    if(!pick)return;
    pick.host.classList.add('wpbb-v143-values-grid','wpbb-v142-values-grid');
    pick.items.forEach(function(item){item.classList.add('wpbb-v143-grid-cell','wpbb-v142-grid-cell');});
    if(row)row.classList.add('wpbb-v143-values-section');
  }

  function markHomepageGrids(){
    if(!home())return;
    var root=main();if(!root)return;
    fixValues();
    qa('.clothes-category-section',root).forEach(function(s){markSection(s,'.clothes-category-tile',4,'wpbb-v142-category-grid');});
    qa('.wp-theme-services-section,.wp-theme-sector-services-section',root).forEach(function(s){markSection(s,'.wp-theme-sector-card,.wpbb-icon-card,.wpbb-sector-card',null,'wpbb-v142-services-grid');});
    qa('.wp-theme-industries-section,.wp-theme-sector-industries-section',root).forEach(function(s){markSection(s,'.wp-theme-sector-card,.wpbb-icon-card,.wpbb-sector-card',null,'wpbb-v142-industries-grid');});
    qa('.wp-theme-process-section',root).forEach(function(s){markSection(s,'.wp-theme-process-card,.wp-theme-sector-card,.wpbb-icon-card',3,'wpbb-v142-process-grid');});
    qa('.wp-theme-case-studies-section,.wp-theme-case-grid,.wp-theme-case-studies-grid',root).forEach(function(s){markSection(s,'.wp-theme-case-card,.wpbb-icon-card,.wp-theme-sector-card',3,'wpbb-v142-case-grid');});
    qa('.wp-theme-home-stats,.wp-theme-sector-proof',root).forEach(function(s){markSection(s,'.wpbb-fun-fact,.wp-theme-sector-proof__item',4,'wpbb-v142-stats-grid');});
    qa('.wpbb-sector-proof-band',root).forEach(function(s){markSection(s,'.wpbb-sector-proof-card,.wpbb-icon-card,.wp-theme-sector-card',3,'wpbb-v142-proof-grid');});

    qa('.wp-theme-home-product-catalogue,.wp-theme-shop-section,.wp-theme-products-section',root).forEach(function(section){
      var cards=topLevel(qa('.wpbb-catalogue-card,.product.type-product,.type-product.product,.product-card,.iws-product-card,li.product',section));
      var pick=lowestCommonHost(section,cards);
      if(pick)markGrid(pick.host,pick.items,4,'wpbb-v142-product-grid');
      cards.forEach(function(card){card.classList.add('wpbb-v142-product-card','wpbb-v143-product-card');});
      qa('h1,h2,h3,h4,h5,h6',section).forEach(function(h){if(!txt(h)&&!q('img,svg,button,a,input,select',h))h.style.display='none';});
    });

    qa('.wp-theme-insights-section .wp-block-post-template,.wp-theme-blog-preview-section .wp-block-post-template',root).forEach(function(host){
      var items=kids(host).filter(function(item){return txt(item)||q('article,img,a',item);});
      if(items.length>=2)markGrid(host,items,3,'wpbb-v142-blog-grid');
    });

    qa('.wp-theme-gallery-section',root).forEach(function(section){
      if(!q('.swiper,.wpbb-swiper--gallery',section))markSection(section,'.wp-theme-gallery-card',4,'wpbb-v142-gallery-grid');
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
  function activeIndex(el,sw,count){
    if(!count)return 0;
    var n=sw&&typeof sw.realIndex==='number'?sw.realIndex:null;
    if(n===null){
      var active=q('.swiper-slide-active',el);
      if(active){
        var raw=active.getAttribute('data-swiper-slide-index');
        n=(raw!==null&&raw!=='')?parseInt(raw,10):uniqueSlides(el).indexOf(active);
      }
    }
    if(n===null||!isFinite(n))n=sw&&typeof sw.activeIndex==='number'?sw.activeIndex:0;
    n=parseInt(n,10);if(!isFinite(n)||n<0)n=0;return n%count;
  }
  function paintPager(pager,el,sw,count){
    var active=activeIndex(el,sw,count);
    qa('.wpbb-v143-hero-pagination__button',pager).forEach(function(button,index){
      var on=index===active;button.classList.toggle('is-active',on);
      if(on)button.setAttribute('aria-current','true');else button.removeAttribute('aria-current');
    });
  }
  function clearOldHeroPagers(block){
    qa('.wpbb-v141-hero-pagination,.wpbb-v142-hero-pagination,.wpbb-v138-hero-pagination,.wpbb-v120-pagination,.wpbb-v121-pagination,.wpbb-v123-hero-pagination,.wpbb-v124-hero-pagination,.wpbb-v126-hero-pagination,.wpbb-v127-hero-pagination,.wpbb-v128-hero-pagination,.wpbb-v133-hero-pagination,.wpbb-v134-hero-pagination,.wpbb-v135-hero-pagination',block).forEach(function(node){
      if(node.parentNode)node.parentNode.removeChild(node);
    });
  }
  function ensurePager(block){
    var el=swiperElement(block);if(!el)return;
    clearOldHeroPagers(block);
    var slides=uniqueSlides(el),count=slides.length;
    var pager=q(':scope > .wpbb-v143-hero-pagination',block);
    if(count<2){if(pager&&pager.parentNode)pager.parentNode.removeChild(pager);return;}
    if(!pager){
      pager=D.createElement('div');pager.className='wpbb-v143-hero-pagination';pager.setAttribute('role','group');pager.setAttribute('aria-label','Hero slides');block.appendChild(pager);
    }
    if(pager.children.length!==count){
      pager.innerHTML='';
      for(var i=0;i<count;i++){
        var button=D.createElement('button');button.type='button';button.className='wpbb-v143-hero-pagination__button';
        button.setAttribute('data-wpbb-v143-slide',String(i));button.setAttribute('aria-label','Go to hero slide '+(i+1)+' of '+count);
        button.textContent=String(i+1).padStart(2,'0');pager.appendChild(button);
      }
    }
    if(!pager.dataset.wpbbV143Bound){
      pager.dataset.wpbbV143Bound='1';
      pager.addEventListener('click',function(event){
        var button=event.target&&event.target.closest?event.target.closest('[data-wpbb-v143-slide]'):null;if(!button)return;
        var index=parseInt(button.getAttribute('data-wpbb-v143-slide'),10)||0;
        var currentEl=swiperElement(block),sw=liveSwiper(block,currentEl);
        if(sw){
          try{if(typeof sw.slideToLoop==='function')sw.slideToLoop(index);else if(typeof sw.slideTo==='function')sw.slideTo(index);}catch(e){}
        }else{
          var native=qa('.swiper-pagination-bullet',currentEl);if(native[index]&&typeof native[index].click==='function')native[index].click();
        }
        paintPager(pager,currentEl,sw,count);
      });
    }
    var sw=liveSwiper(block,el);
    if(sw&&pager._wpbbV143Swiper!==sw){
      pager._wpbbV143Swiper=sw;
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
      block.classList.toggle('wpbb-v143-primary-hero',index===0);
      block.classList.toggle('wpbb-v143-secondary-hero',index===1);
      var shell=block.closest&&block.closest('.wp-theme-sector-hero');if(shell)shell.classList.add('wpbb-v143-hero-shell');
      ensurePager(block);
    });
  }

  function cleanFinders(){
    if(!home())return;
    var finders=qa('#wp-theme-main .wpbb-v97-hero-finder');
    finders.forEach(function(f,index){
      if(index===0){f.classList.add('wpbb-v140-hero-finder');return;}
      if(f.parentNode)f.parentNode.removeChild(f);
    });
  }

  function promoteSource(source){
    if(!source)return;
    var srcset=String(source.getAttribute('srcset')||'').trim();
    if(!srcset){
      ['data-srcset','data-lazy-srcset'].some(function(attr){var value=String(source.getAttribute(attr)||'').trim();if(!value)return false;source.setAttribute('srcset',value);return true;});
    }
  }
  function promoteImage(img){
    if(!img)return;
    var src=String(img.getAttribute('src')||'').trim();
    var placeholder=!src||/^data:image\/(?:gif|svg\+xml)/i.test(src)||/transparent|placeholder|spacer/i.test(src);
    if(placeholder){
      ['data-src','data-lazy-src','data-original','data-lazy','data-orig-file'].some(function(attr){
        var value=String(img.getAttribute(attr)||'').trim();if(!value)return false;img.setAttribute('src',value);return true;
      });
    }
    if(!img.getAttribute('srcset')){
      ['data-srcset','data-lazy-srcset'].some(function(attr){var value=String(img.getAttribute(attr)||'').trim();if(!value)return false;img.setAttribute('srcset',value);return true;});
    }
    var picture=img.closest&&img.closest('picture');if(picture)qa('source',picture).forEach(promoteSource);
    img.setAttribute('loading','eager');img.setAttribute('decoding','async');
    img.style.setProperty('opacity','1','important');img.style.setProperty('visibility','visible','important');img.style.setProperty('display','block','important');
    img.classList.remove('lazy','lazyload','lazyloading');img.classList.add('wpbb-v143-media-ready');
  }
  function promoteHomepageImages(){
    if(!home())return;
    var root=main();if(!root)return;
    /* This page is intentionally image-led; load all main-content imagery so
       fast scroll/full-page capture cannot strand product/gallery placeholders. */
    qa('img',root).forEach(promoteImage);
  }

  function markEditorialBand(){
    if(!home())return;
    var root=main();if(!root)return;
    qa('.clothes-editorial-band',root).forEach(function(band){band.classList.add('wpbb-v143-editorial-band');});
  }

  function run(){
    if(D.body){D.body.classList.add('wpbb-v143');if(home())D.body.classList.add('wpbb-v143-home');}
    cleanFinders();markHeroes();fixValues();markHomepageGrids();markEditorialBand();promoteHomepageImages();
  }
  var timer=0;
  function schedule(){W.clearTimeout(timer);timer=W.setTimeout(run,45);}
  if(D.readyState==='loading')D.addEventListener('DOMContentLoaded',schedule,{once:true});else schedule();
  W.addEventListener('load',function(){run();[140,420,900,1800,3200].forEach(function(ms){W.setTimeout(run,ms);});});
  W.addEventListener('resize',function(){W.clearTimeout(timer);timer=W.setTimeout(run,120);},{passive:true});
  var root=main();
  if(root&&W.MutationObserver){
    var stopTimer;
    var observer=new MutationObserver(function(mutations){
      if(!mutations.some(function(m){return (m.addedNodes&&m.addedNodes.length)||(m.type==='attributes'&&['class','src','data-src','srcset','data-srcset'].indexOf(m.attributeName)!==-1);}))return;
      schedule();W.clearTimeout(stopTimer);stopTimer=W.setTimeout(function(){observer.disconnect();},7600);
    });
    observer.observe(root,{childList:true,subtree:true,attributes:true,attributeFilter:['class','src','data-src','srcset','data-srcset']});
    stopTimer=W.setTimeout(function(){observer.disconnect();},8200);
  }
})(window,document);
