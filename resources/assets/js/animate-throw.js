{
    "use strict";
  
    const pipe = (...functions) => arg => functions.reduce((a, b) => b(a), arg);
  
    const define = (object, property, value) =>
      Object.defineProperty(object, property, {
        value,
        writable: true,
        configurable: true,
        enumerable: true
      });
  
    const first = list => list[0];
  
    const last = list => list[list.length - 1];
  
    const getRandomInt = (min, max) =>
      Math.floor(Math.random() * (max - min)) + min;
  
    const interval = (callback, delay) => {
      const tick = now => {
        if (now - start >= delay) {
          start = now;
          callback();
        }
        requestAnimationFrame(tick);
      };
      let start = performance.now();
      requestAnimationFrame(tick);
    };
  
    const easeOutQuart = (t, b, c, d) =>
      -c * ((t=t/d-1) * Math.pow(t, 3) - 1) + b;
    

    jQuery(function($) {
        $.get('/tags', data => {
            const programmingLanguages = document.getElementById("programming-languages");
        
            var languages = data;
        
            const getRandomLanguage = () =>
              languages[getRandomInt(0, languages.length)];
        
            const getRandomY = (x, min, max) => {
              if (Math.abs(x) > min) return	getRandomInt(-max, max);
              const minY = Math.sqrt(Math.pow(min, 2) - Math.pow(x, 2));
              const randomSign = Math.round(Math.random()) * 2 - 1;
              return randomSign * getRandomInt(minY, max);
            };
        
            const createIcon = language => {
              const icon = document.createElement("div");
              icon.innerText = language;
              icon.classList.add('throw');
              programmingLanguages.appendChild(icon);
              icon.setAttribute('aria-hidden', true);
              return icon;
            };
        
            const animateIcon = icon => {
              const time = { total: 12000 };
              const maxDistance = 120;
              const maxRotation = 800;
              const transform = {};
              define(transform, "translateX", getRandomInt(-maxDistance, maxDistance));
              define(transform, "translateY", getRandomY(transform.translateX, 60, maxDistance));
              //define(transform, "rotate", getRandomInt(-maxRotation, maxRotation));
        
              const tick = now => {
                if (time.start == null) define(time, "start", now);
                define(time, "elapsed", now - time.start);
                const progress = easeOutQuart(time.elapsed, 0, 1, time.total);
        
                icon.style.opacity = Math.abs(1 - progress);
                icon.style.transform = Object.keys(transform).map(key => {
                  const value = transform[key] * progress;
                 // const unit = /rotate/.test(key) ? "deg" : "px";
                 const unit =  "px";
                  return `${key}(${value}${unit})`;
                }).join(" ");
        
                time.elapsed < time.total
                ? requestAnimationFrame(tick)
                : programmingLanguages.removeChild(icon);
              };
        
              requestAnimationFrame(tick);
            };
        
            interval(pipe(getRandomLanguage, createIcon, animateIcon), 500);
          });
    });
 
    // {
    //   const programmingLanguages = document.getElementById("programming-languages");
  
    //   var languages = data;
  
    //   const getRandomLanguage = () =>
    //     languages[getRandomInt(0, languages.length)];
  
    //   const getRandomY = (x, min, max) => {
    //     if (Math.abs(x) > min) return	getRandomInt(-max, max);
    //     const minY = Math.sqrt(Math.pow(min, 2) - Math.pow(x, 2));
    //     const randomSign = Math.round(Math.random()) * 2 - 1;
    //     return randomSign * getRandomInt(minY, max);
    //   };
  
    //   const createIcon = language => {
    //     const icon = document.createElement("div");
    //     icon.innerText = language;
    //     icon.classList.add('throw');
    //     programmingLanguages.appendChild(icon);
    //     icon.setAttribute('aria-hidden', true);
    //     return icon;
    //   };
  
    //   const animateIcon = icon => {
    //     const time = { total: 12000 };
    //     const maxDistance = 120;
    //     const maxRotation = 800;
    //     const transform = {};
    //     define(transform, "translateX", getRandomInt(-maxDistance, maxDistance));
    //     define(transform, "translateY", getRandomY(transform.translateX, 60, maxDistance));
    //     //define(transform, "rotate", getRandomInt(-maxRotation, maxRotation));
  
    //     const tick = now => {
    //       if (time.start == null) define(time, "start", now);
    //       define(time, "elapsed", now - time.start);
    //       const progress = easeOutQuart(time.elapsed, 0, 1, time.total);
  
    //       icon.style.opacity = Math.abs(1 - progress);
    //       icon.style.transform = Object.keys(transform).map(key => {
    //         const value = transform[key] * progress;
    //        // const unit = /rotate/.test(key) ? "deg" : "px";
    //        const unit =  "px";
    //         return `${key}(${value}${unit})`;
    //       }).join(" ");
  
    //       time.elapsed < time.total
    //       ? requestAnimationFrame(tick)
    //       : programmingLanguages.removeChild(icon);
    //     };
  
    //     requestAnimationFrame(tick);
    //   };
  
    //   interval(pipe(getRandomLanguage, createIcon, animateIcon), 500);
    // }
}