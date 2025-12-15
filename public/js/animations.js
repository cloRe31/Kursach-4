/**
 * Harbor - Animations
 */
document.addEventListener('DOMContentLoaded', () => {
  gsap.registerPlugin(ScrollTrigger);

  const isMobile = window.innerWidth < 768;
  const isTouch = 'ontouchstart' in window;

  // === LENIS ===
  const lenis = new Lenis({ lerp: 0.08, smoothWheel: true });
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add(t => lenis.raf(t * 1000));

  // === HEADER ===
  const header = document.querySelector('.header');
  if (header) {
    let lastY = 0;
    ScrollTrigger.create({
      start: 'top -80',
      onUpdate: self => {
        const y = self.scroll();
        header.classList.toggle('header--scrolled', y > 80);
        header.classList.toggle('header--hidden', y > 400 && y > lastY);
        lastY = y;
      }
    });
  }

  // === HERO ===
  const hero = document.querySelector('.hero');
  if (hero) {
    gsap.from('.hero__badge', { opacity: 0, y: 30, duration: 0.7, delay: 0.2 });
    gsap.from('.hero__title', { opacity: 0, y: 50, duration: 0.8, delay: 0.4 });
    gsap.from('.hero__subtitle', { opacity: 0, y: 30, duration: 0.7, delay: 0.6 });
    
    // Кнопки hero - с очисткой стилей после анимации
    document.querySelectorAll('.hero__actions > *').forEach((btn, i) => {
      gsap.from(btn, { 
        opacity: 0, 
        y: 30, 
        duration: 0.6, 
        delay: 0.8 + i * 0.15,
        onComplete: () => btn.removeAttribute('style')
      });
    });
    
    gsap.from('.hero__stat', { opacity: 0, scale: 0.5, stagger: 0.1, duration: 0.5, delay: 1, ease: 'back.out(1.7)' });
    gsap.from('.hero__scroll', { opacity: 0, duration: 0.5, delay: 1.3 });

    gsap.to('.hero__scroll', { y: 12, duration: 1.2, ease: 'sine.inOut', repeat: -1, yoyo: true, delay: 1.5 });

    if (!isMobile) {
      gsap.to('.hero__bg', {
        yPercent: 30,
        ease: 'none',
        scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true }
      });
    }
  }

  // === PAGE HERO ===
  if (document.querySelector('.page-hero')) {
    gsap.from('.page-hero__label', { opacity: 0, x: -30, duration: 0.6, delay: 0.1 });
    gsap.from('.page-hero__title', { opacity: 0, y: 40, duration: 0.7, delay: 0.2 });
    gsap.from('.page-hero__subtitle', { opacity: 0, y: 20, duration: 0.6, delay: 0.4 });
  }

  // === CAR HERO ===
  if (document.querySelector('.car-hero')) {
    gsap.from('.breadcrumbs', { opacity: 0, x: -20, duration: 0.5, delay: 0.1 });
    gsap.from('.car-hero__main', { opacity: 0, scale: 0.95, duration: 0.7, delay: 0.2 });
    gsap.from('.car-hero__colors', { opacity: 0, y: 20, duration: 0.5, delay: 0.4 });
    gsap.from('.car-hero__brand', { opacity: 0, y: 15, duration: 0.4, delay: 0.3 });
    gsap.from('.car-hero__title', { opacity: 0, y: 25, duration: 0.5, delay: 0.4 });
    gsap.from('.car-hero__price', { opacity: 0, y: 20, duration: 0.5, delay: 0.5 });
    gsap.from('.car-hero__spec', { opacity: 0, y: 15, stagger: 0.08, duration: 0.4, delay: 0.6 });
    
    // Кнопки car-hero - с очисткой стилей
    document.querySelectorAll('.car-hero__actions > *').forEach((btn, i) => {
      gsap.from(btn, { 
        opacity: 0, 
        y: 25, 
        duration: 0.5, 
        delay: 0.7 + i * 0.1,
        onComplete: () => btn.removeAttribute('style')
      });
    });
    
    gsap.from('.car-hero__benefit', { opacity: 0, x: -15, stagger: 0.08, duration: 0.4, delay: 0.9 });
  }

  // === SCROLL ANIMATIONS ===
  
  // Section headers
  gsap.utils.toArray('.section__header').forEach(el => {
    gsap.from(el.children, {
      opacity: 0,
      y: 30,
      stagger: 0.1,
      duration: 0.6,
      scrollTrigger: { trigger: el, start: 'top 85%' }
    });
  });

  // Benefits
  gsap.utils.toArray('.benefit').forEach((el, i) => {
    gsap.from(el, {
      opacity: 0,
      y: 50,
      duration: 0.6,
      delay: i * 0.1,
      scrollTrigger: { trigger: el, start: 'top 88%' }
    });
  });

  // Car cards
  gsap.utils.toArray('.car-card').forEach((el, i) => {
    gsap.from(el, {
      opacity: 0,
      y: 60,
      duration: 0.7,
      delay: (i % 3) * 0.1,
      scrollTrigger: { trigger: el, start: 'top 90%' }
    });
  });

  // Offers
  gsap.utils.toArray('.offer').forEach((el, i) => {
    const x = i === 0 ? -50 : i === 2 ? 50 : 0;
    gsap.from(el, {
      opacity: 0,
      x: x,
      y: x === 0 ? 50 : 0,
      duration: 0.7,
      scrollTrigger: { trigger: el, start: 'top 88%' }
    });
  });

  // Steps
  gsap.utils.toArray('.step').forEach((el, i) => {
    gsap.from(el, {
      opacity: 0,
      x: i % 2 === 0 ? -30 : 30,
      duration: 0.5,
      delay: i * 0.1,
      scrollTrigger: { trigger: el, start: 'top 85%' }
    });
  });

  // Contact
  if (document.querySelector('.contact')) {
    gsap.from('.contact__info', {
      opacity: 0, x: -50, duration: 0.7,
      scrollTrigger: { trigger: '.contact', start: 'top 80%' }
    });
    gsap.from('.contact__form', {
      opacity: 0, x: 50, duration: 0.7,
      scrollTrigger: { trigger: '.contact', start: 'top 80%' }
    });
  }

  // Calculator
  if (document.querySelector('.calculator')) {
    gsap.from('.calculator__form', {
      opacity: 0, x: -50, duration: 0.7,
      scrollTrigger: { trigger: '.calculator', start: 'top 80%' }
    });
    gsap.from('.calculator__result', {
      opacity: 0, x: 50, duration: 0.7,
      scrollTrigger: { trigger: '.calculator', start: 'top 80%' }
    });
  }

  // Trim cards
  gsap.utils.toArray('.trim-card').forEach((el, i) => {
    gsap.from(el, {
      opacity: 0,
      y: 50,
      duration: 0.6,
      delay: i * 0.1,
      scrollTrigger: { trigger: el, start: 'top 88%' }
    });
  });

  // Specs
  gsap.utils.toArray('.specs-row').forEach((el, i) => {
    gsap.from(el, {
      opacity: 0,
      x: -20,
      duration: 0.4,
      delay: i * 0.05,
      scrollTrigger: { trigger: el, start: 'top 95%' }
    });
  });

  // Banks
  gsap.utils.toArray('.bank-card').forEach((el, i) => {
    gsap.from(el, {
      opacity: 0,
      scale: 0.8,
      duration: 0.4,
      delay: i * 0.05,
      scrollTrigger: { trigger: el, start: 'top 92%' }
    });
  });

  // About
  if (document.querySelector('.about__grid')) {
    gsap.from('.about__content', {
      opacity: 0, x: -50, duration: 0.7,
      scrollTrigger: { trigger: '.about__grid', start: 'top 80%' }
    });
    gsap.from('.about__image', {
      opacity: 0, x: 50, duration: 0.7,
      scrollTrigger: { trigger: '.about__grid', start: 'top 80%' }
    });
  }

  gsap.utils.toArray('.about__stat').forEach((el, i) => {
    gsap.from(el, {
      opacity: 0,
      y: 30,
      scale: 0.9,
      duration: 0.5,
      delay: i * 0.1,
      scrollTrigger: { trigger: el, start: 'top 90%' }
    });
  });

  // Prefooter
  if (document.querySelector('.prefooter__inner')) {
    gsap.from('.prefooter__inner', {
      opacity: 0, y: 40, duration: 0.6,
      scrollTrigger: { trigger: '.prefooter__inner', start: 'top 92%' }
    });
  }

  // Footer
  if (document.querySelector('.footer__grid')) {
    gsap.from('.footer__grid > *', {
      opacity: 0, y: 30, stagger: 0.1, duration: 0.5,
      scrollTrigger: { trigger: '.footer__grid', start: 'top 95%' }
    });
  }

  // CTA
  if (document.querySelector('.cta')) {
    gsap.from('.cta__content > *', {
      opacity: 0, y: 30, stagger: 0.1, duration: 0.5,
      scrollTrigger: { trigger: '.cta', start: 'top 85%' }
    });
  }

  // Map
  if (document.querySelector('.map')) {
    gsap.from('.map', {
      opacity: 0, y: 40, duration: 0.6,
      scrollTrigger: { trigger: '.map', start: 'top 88%' }
    });
  }

  // === HOVER EFFECTS ===
  if (!isTouch) {
    // Car cards hover
    document.querySelectorAll('.car-card').forEach(card => {
      card.addEventListener('mouseenter', () => gsap.to(card, { y: -10, duration: 0.3 }));
      card.addEventListener('mouseleave', () => gsap.to(card, { y: 0, duration: 0.3 }));
    });

    // Benefits 3D
    document.querySelectorAll('.benefit').forEach(el => {
      el.addEventListener('mousemove', e => {
        const rect = el.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width - 0.5;
        const y = (e.clientY - rect.top) / rect.height - 0.5;
        gsap.to(el, { rotateY: x * 10, rotateX: -y * 10, duration: 0.3 });
      });
      el.addEventListener('mouseleave', () => {
        gsap.to(el, { rotateY: 0, rotateX: 0, duration: 0.4 });
      });
    });

    // Magnetic buttons
    document.querySelectorAll('.btn--primary').forEach(btn => {
      btn.addEventListener('mousemove', e => {
        const rect = btn.getBoundingClientRect();
        const x = (e.clientX - rect.left - rect.width / 2) * 0.15;
        const y = (e.clientY - rect.top - rect.height / 2) * 0.15;
        gsap.to(btn, { x, y, duration: 0.2 });
      });
      btn.addEventListener('mouseleave', () => {
        gsap.to(btn, { x: 0, y: 0, duration: 0.4, ease: 'elastic.out(1, 0.5)' });
      });
    });
  }

  // === MODAL ===
  const modal = document.getElementById('callbackModal');
  if (modal) {
    const observer = new MutationObserver(mutations => {
      mutations.forEach(m => {
        if (m.attributeName === 'class' && modal.classList.contains('active')) {
          gsap.from('.modal__content', { opacity: 0, scale: 0.9, y: 30, duration: 0.35, ease: 'back.out(1.7)' });
        }
      });
    });
    observer.observe(modal, { attributes: true });
  }

  // === COLOR CHANGE ===
  document.querySelectorAll('.color-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const img = document.getElementById('mainImage');
      if (img) {
        gsap.to(img, {
          opacity: 0,
          duration: 0.15,
          onComplete: () => {
            img.src = btn.dataset.image;
            gsap.to(img, { opacity: 1, duration: 0.2 });
          }
        });
      }
    });
  });

  // === CUSTOM CURSOR ===
  // if (!isTouch && !isMobile) {
  //   const cursor = document.createElement('div');
  //   cursor.className = 'custom-cursor';
  //   cursor.innerHTML = '<div class="cursor-dot"></div><div class="cursor-ring"></div>';
  //   document.body.appendChild(cursor);
    
  //   let mx = 0, my = 0;
  //   document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
    
  //   gsap.ticker.add(() => {
  //     gsap.set('.cursor-dot', { x: mx, y: my });
  //     gsap.to('.cursor-ring', { x: mx, y: my, duration: 0.15 });
  //   });

  //   document.querySelectorAll('a, button').forEach(el => {
  //     el.addEventListener('mouseenter', () => gsap.to('.cursor-ring', { scale: 1.8, opacity: 0.5, duration: 0.3 }));
  //     el.addEventListener('mouseleave', () => gsap.to('.cursor-ring', { scale: 1, opacity: 1, duration: 0.3 }));
  //   });
  // }

  window.addEventListener('load', () => ScrollTrigger.refresh());
});
